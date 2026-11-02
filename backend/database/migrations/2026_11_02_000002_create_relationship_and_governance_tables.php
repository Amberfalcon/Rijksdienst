<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('relation_type_rules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('predicate', 100);
            $table->string('subject_type', 60);
            $table->string('object_type', 60);
            $table->text('description');
            $table->boolean('requires_evidence')->default(true);
            $table->boolean('auto_publish_allowed')->default(false);
            $table->decimal('minimum_confidence', 5, 4)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestampsTz();

            $table->unique(
                ['predicate', 'subject_type', 'object_type'],
                'relation_type_rules_combination_unique'
            );
            $table->index(['is_active', 'predicate']);
        });

        Schema::create('entity_relations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('subject_entity_id');
            $table->string('predicate', 100);
            $table->uuid('object_entity_id');
            $table->string('status', 30)->default('proposed');
            $table->decimal('confidence_score', 5, 4)->nullable();
            $table->timestampTz('valid_from')->nullable();
            $table->timestampTz('valid_until')->nullable();
            $table->string('assertion_method', 50)->default('source_explicit');
            $table->timestampsTz();

            $table->foreign('subject_entity_id')
                ->references('id')->on('entities')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('object_entity_id')
                ->references('id')->on('entities')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->index(['subject_entity_id', 'predicate', 'status'], 'entity_relations_subject_idx');
            $table->index(['object_entity_id', 'predicate', 'status'], 'entity_relations_object_idx');
            $table->index(['predicate', 'status']);
        });

        Schema::create('relation_evidence', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('relation_id');
            $table->uuid('source_record_id')->nullable();
            $table->string('evidence_type', 50);
            $table->text('evidence_location')->nullable();
            $table->string('assessment_status', 30)->default('pending');
            $table->uuid('assessed_by')->nullable();
            $table->timestampTz('assessed_at')->nullable();
            $table->text('assessment_notes')->nullable();
            $table->timestampsTz();

            $table->foreign('relation_id')
                ->references('id')->on('entity_relations')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('source_record_id')
                ->references('id')->on('source_records')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->index(['relation_id', 'assessment_status']);
        });

        Schema::create('ingestion_runs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('source_id');
            $table->string('status', 30)->default('queued');
            $table->timestampTz('started_at')->nullable();
            $table->timestampTz('finished_at')->nullable();
            $table->unsignedInteger('records_seen')->default(0);
            $table->unsignedInteger('records_created')->default(0);
            $table->unsignedInteger('records_updated')->default(0);
            $table->unsignedInteger('records_unchanged')->default(0);
            $table->unsignedInteger('records_failed')->default(0);
            $table->text('error_summary')->nullable();
            $table->jsonb('metadata')->default('{}');
            $table->timestampsTz();

            $table->foreign('source_id')
                ->references('id')->on('sources')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->index(['source_id', 'status', 'created_at']);
        });

        Schema::create('ingestion_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ingestion_run_id');
            $table->uuid('source_record_id')->nullable();
            $table->string('external_record_id')->nullable();
            $table->string('outcome', 30)->default('pending');
            $table->text('error_message')->nullable();
            $table->jsonb('diagnostics')->default('{}');
            $table->timestampTz('processed_at')->nullable();
            $table->timestampsTz();

            $table->foreign('ingestion_run_id')
                ->references('id')->on('ingestion_runs')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('source_record_id')
                ->references('id')->on('source_records')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->index(['ingestion_run_id', 'outcome']);
        });

        Schema::create('quality_assessments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('entity_id')->nullable();
            $table->uuid('source_record_id')->nullable();
            $table->uuid('ingestion_run_id')->nullable();
            $table->string('rule_code', 100);
            $table->string('severity', 20);
            $table->string('result', 20);
            $table->text('message')->nullable();
            $table->jsonb('details')->default('{}');
            $table->timestampTz('assessed_at');
            $table->timestampsTz();

            $table->foreign('entity_id')
                ->references('id')->on('entities')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('source_record_id')
                ->references('id')->on('source_records')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('ingestion_run_id')
                ->references('id')->on('ingestion_runs')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->index(['rule_code', 'result', 'severity']);
            $table->index(['entity_id', 'assessed_at']);
        });

        Schema::create('review_tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('entity_id')->nullable();
            $table->uuid('source_record_id')->nullable();
            $table->uuid('ingestion_item_id')->nullable();
            $table->string('task_type', 60);
            $table->string('status', 30)->default('open');
            $table->string('priority', 20)->default('normal');
            $table->text('reason');
            $table->uuid('assigned_to')->nullable();
            $table->uuid('resolved_by')->nullable();
            $table->timestampTz('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestampsTz();

            $table->foreign('entity_id')
                ->references('id')->on('entities')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('source_record_id')
                ->references('id')->on('source_records')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('ingestion_item_id')
                ->references('id')->on('ingestion_items')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->index(['status', 'priority', 'created_at']);
        });

        Schema::create('entity_versions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('entity_id');
            $table->unsignedInteger('version_number');
            $table->jsonb('snapshot');
            $table->string('change_reason', 100)->nullable();
            $table->timestampTz('valid_from')->nullable();
            $table->timestampTz('valid_until')->nullable();
            $table->timestampTz('recorded_at');
            $table->timestampsTz();

            $table->foreign('entity_id')
                ->references('id')->on('entities')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->unique(['entity_id', 'version_number'], 'entity_versions_number_unique');
            $table->index(['entity_id', 'recorded_at']);
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('actor_id')->nullable();
            $table->string('action', 100);
            $table->string('subject_type', 100)->nullable();
            $table->uuid('subject_id')->nullable();
            $table->jsonb('context')->default('{}');
            $table->timestampTz('occurred_at');
            $table->index(['subject_type', 'subject_id', 'occurred_at']);
            $table->index(['action', 'occurred_at']);
        });

        DB::statement("ALTER TABLE entity_relations ADD CONSTRAINT entity_relations_not_self_check CHECK (subject_entity_id <> object_entity_id)");
        DB::statement("ALTER TABLE entity_relations ADD CONSTRAINT entity_relations_confidence_check CHECK (confidence_score IS NULL OR (confidence_score >= 0 AND confidence_score <= 1))");
        DB::statement("ALTER TABLE entity_relations ADD CONSTRAINT entity_relations_validity_check CHECK (valid_until IS NULL OR valid_from IS NULL OR valid_until >= valid_from)");
        DB::statement("ALTER TABLE relation_type_rules ADD CONSTRAINT relation_type_rules_confidence_check CHECK (minimum_confidence IS NULL OR (minimum_confidence >= 0 AND minimum_confidence <= 1))");
        DB::statement("ALTER TABLE ingestion_runs ADD CONSTRAINT ingestion_runs_finished_check CHECK (finished_at IS NULL OR started_at IS NULL OR finished_at >= started_at)");
        DB::statement("ALTER TABLE quality_assessments ADD CONSTRAINT quality_assessments_subject_check CHECK (entity_id IS NOT NULL OR source_record_id IS NOT NULL OR ingestion_run_id IS NOT NULL)");
        DB::statement("ALTER TABLE review_tasks ADD CONSTRAINT review_tasks_subject_check CHECK (entity_id IS NOT NULL OR source_record_id IS NOT NULL OR ingestion_item_id IS NOT NULL)");
        DB::statement("ALTER TABLE entity_versions ADD CONSTRAINT entity_versions_validity_check CHECK (valid_until IS NULL OR valid_from IS NULL OR valid_until >= valid_from)");
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('entity_versions');
        Schema::dropIfExists('review_tasks');
        Schema::dropIfExists('quality_assessments');
        Schema::dropIfExists('ingestion_items');
        Schema::dropIfExists('ingestion_runs');
        Schema::dropIfExists('relation_evidence');
        Schema::dropIfExists('entity_relations');
        Schema::dropIfExists('relation_type_rules');
    }
};
