# PostgreSQL data foundation

This branch adds a first Laravel migration blueprint for the Rijksdienst data platform. The existing repository is currently a Vite/Vue frontend, so these PHP migrations are prepared backend files; they cannot be executed until a Laravel application has been scaffolded in `backend/` and configured for PostgreSQL.

## Core model

- `entities`: stable internal identity for every managed information object.
- `organizations`: organization-specific attributes; its primary key is also a foreign key to `entities.id`.
- `sources`: registry of approved external sources and their ingestion configuration.
- `source_records`: identity of a record as it appears in a specific source.
- `source_record_versions`: received payload versions, including a hash and processing status.
- `external_identifiers`: identifiers from external systems used to resolve identity.
- `entity_source_links`: provenance links between a canonical entity and source records.
- `relation_type_rules`: allowed subject-type / predicate / object-type combinations.
- `entity_relations`: typed relationships between canonical entities.
- `relation_evidence`: evidence supporting a relationship.
- `ingestion_runs` / `ingestion_items`: run-level and per-record processing history.
- `quality_assessments`: outcomes of quality rules.
- `review_tasks`: human review queue for uncertain or conflicting cases.
- `entity_versions`: historical snapshots of canonical entities.
- `audit_logs`: security- and governance-relevant actions.

## Conditions already represented in database constraints

- Primary keys and foreign keys protect referential integrity.
- Validity intervals cannot end before they start.
- Organization end dates cannot precede establishment dates.
- Source-record last-seen timestamps cannot precede first-seen timestamps.
- A relation cannot point from an entity to itself.
- Confidence scores must be between 0 and 1.
- A review task and a quality assessment must point to at least one subject.
- Source-record versions and entity versions have unique version numbers per parent record/entity.
- Unique indexes prevent duplicate external identifiers within a source/identifier scheme and duplicate provenance links of the same type.

## Rules that must also be enforced in application services

Some business rules depend on more than one table and should not be implemented as ordinary PostgreSQL `CHECK` constraints. Enforce them in Laravel services and tests; consider database triggers only where a hard database guarantee is required.

1. **Subtype correctness:** an `organizations` row must reference an `entities` row whose `entity_type = 'organization'`. The same rule applies to future subtype tables.
2. **Relationship typing:** `entity_relations.predicate` must have an active matching rule in `relation_type_rules` for the subject and object entity types.
3. **Publication gates:** only entities and relationships that meet the relevant validation, source, licensing, privacy and review requirements may be publicly exposed.
4. **Identity resolution:** names alone must not automatically merge entities. Prefer exact external identifiers or explicit references; ambiguous candidates create review tasks.
5. **Idempotency:** repeated imports must not create duplicate canonical entities or duplicate active relationships. Use database uniqueness, transactions and safe retry handling.
6. **Source disappearance:** a missing record in a partial or failed import must not be treated as a deletion. Only a complete, validated snapshot or an explicit source withdrawal can initiate retirement logic.
7. **Version history:** compare the incoming payload with the latest version before inserting a new version. Do not use a permanent uniqueness constraint on payload hash if a source may later revert to a previously seen payload.
8. **Concurrency:** concurrent workers resolving the same external identifier must safely handle unique-key conflicts and retry or re-read the winning row.
9. **Entity publication:** an entity must not be marked publishable until its required subtype and minimum required fields exist.
10. **Allowed status values:** validate status and type values using application enums/value objects and tests. Add PostgreSQL constraints where the set is stable and tightly controlled.

## Recommended import flow

1. Create an `ingestion_runs` row.
2. Fetch and store the raw response or record payload under the source's allowed retention policy.
3. Validate the response schema and record an `ingestion_items` result.
4. Normalize values without discarding the original payload.
5. Resolve external identifiers and explicit references.
6. In a transaction, create or update the canonical entity, subtype, identifier and provenance link.
7. Derive relationship candidates using active `relation_type_rules`; save evidence for each candidate.
8. Automatically publish only relationships whose rules explicitly allow it and whose evidence meets the required threshold.
9. Create `review_tasks` for ambiguous identities, conflicts or high-impact uncertain relations.
10. Update run counters, quality assessments and downstream search/index notifications.

## PostgreSQL setup

In the Laravel backend's environment configuration, use values such as:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=rijksdienst
DB_USERNAME=rijksdienst_app
DB_PASSWORD=replace-with-a-local-secret
```

Never commit real credentials. For Docker, the database host is usually the Compose service name rather than `127.0.0.1`.

Use pgAdmin or DBeaver for visual database management. The frontend should only call the Laravel API and must never connect directly to PostgreSQL.

## ROO integration

Do not guess ROO fields. First inspect the current official data/API schema and record:

- source URL and access method;
- source identifier and record identifier;
- field names, types and requiredness;
- pagination and update behavior;
- terms of use and license;
- whether the export represents a complete snapshot or a partial result.

Then create a dedicated ROO mapping/connector that maps verified source fields to the canonical organization model. Keep source-specific fields in the raw payload or a documented extension structure until their meaning is confirmed.

## Important implementation note

The migrations are a starting foundation, not yet a complete production data platform. Before deployment, scaffold Laravel, add models/services/factories/tests, review the source-specific unique-identifier semantics, implement subtype and relationship-rule enforcement, and test migrations and rollback against a disposable PostgreSQL database.