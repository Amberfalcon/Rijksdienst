# Rijksdienst backend foundation

The main repository currently contains the Vue/Vite frontend. This directory holds the initial Laravel/PostgreSQL migration blueprint and is not yet a complete Laravel application.

## Before running migrations

1. Scaffold a supported Laravel application in this directory (or initialize Laravel here without overwriting the migration files).
2. Configure `DB_CONNECTION=pgsql` and the PostgreSQL connection values in the local `.env`.
3. Ensure the PHP PostgreSQL PDO extension is installed and enabled.
4. Review the migration assumptions in [the data foundation documentation](../../docs/data-architecture/postgresql-data-foundation.md).
5. Run the migrations against a disposable development database first.
6. Add Laravel models, factories, services, API routes and automated tests before importing real source data.

## Initial migration files

- `database/migrations/2026_11_02_000001_create_core_identity_and_source_tables.php`
- `database/migrations/2026_11_02_000002_create_relationship_and_governance_tables.php`

These migrations define the first database structure. They do not themselves implement the import worker, identity resolution, relation engine, access controls or public API.

## Safety

Do not point initial migration experiments at a production database. Do not commit secrets, real personal data or raw source payloads that are not permitted to be stored in the repository.