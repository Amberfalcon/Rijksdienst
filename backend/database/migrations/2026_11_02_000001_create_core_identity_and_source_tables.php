<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('source_type', 40);
            $table->string('publisher_name');
            $table->text('base_url')->nullable();
            $table->string('license_code')->nullable();
            $table->string('update_frequency', 80)->nullable();
            $table->string('trust_level', 30)->default('unassessed');
            $table->boolean('is_enabled')->default(false);
            $table->jsonb('configuration')->default('{}');
            $table->timestampsTz();

            $table->index(['is_enabled', 'source_type']);
        });

        Schema::create('entities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('entity_type', 60);
            $table->string('lifecycle_status', 30)->default('draft');
            $table->timestampTz('valid_from')->nullable();
            $table->timestampTz('valid_until')->nullable();
            $table->timestampsTz();

            $table->index(['entity_type', 'lifecycle_status']);
            $table->index('updated_at');
        });

        Schema::create('organizations', function (Blueprint $table) {
            $table->uuid('entity_id')->primary();
            $table->text('official_name');
            $table->text('display_name')->nullable();
            $table->string('organization_type', 100)->nullable();
            $table->text('description')->nullable();
            $table->text('website_url')->nullable();
            $table->date('established_on')->nullable();
            $table->date('ended_on')->nullable();
            $table->timestampsTz();

            $table->foreign('entity_id')
                ->references('id')->on('entities')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->index('official_name');
        });

        Schema::create('source_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('source_id');
            $table->text('external_record_id')->nullable();
            $table->string('record_type', 80);
            $table->string('record_status', 30)->default('active');
            $table->timestampTz('first_seen_at');
            $table->timestampTz('last_seen_at');
            $table->timestampsTz();

            $table->foreign('source_id')
                ->references('id')->on('sources')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->unique(['source_id', 'external_record_id'], 'source_records_source_external_unique');
            $table->index(['source_id', 'record_status']);
        });

        Schema::create('source_record_versions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('source_record_id');
            $table->unsignedInteger('version_number');
            $table->jsonb('payload');
            $table->string('payload_hash', 128);
            $table->timestampTz('received_at');
            $table->string('processing_status', 30)->default('received');
            $table->text('processing_error')->nullable();
            $table->timestampsTz();

            $table->foreign('source_record_id')
                ->references('id')->on('source_records')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->unique(['source_record_id', 'version_number'], 'source_record_versions_number_unique');
            $table->unique(['source_record_id', 'payload_hash'], 'source_record_versions_hash_unique');
            $table->index(['processing_status', 'received_at']);
        });

        Schema::create('external_identifiers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('entity_id');
            $table->uuid('source_id');
            $table->string('identifier_scheme', 100);
            $table->text('identifier_value');
            $table->boolean('is_primary')->default(false);
            $table->timestampTz('valid_from')->nullable();
            $table->timestampTz('valid_until')->nullable();
            $table->timestampsTz();

            $table->foreign('entity_id')
                ->references('id')->on('entities')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('source_id')
                ->references('id')->on('sources')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->unique(
                ['source_id', 'identifier_scheme', 'identifier_value'],
                'external_identifiers_source_scheme_value_unique'
            );
            $table->index(['entity_id', 'is_primary']);
        });

        Schema::create('entity_source_links', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('entity_id');
            $table->uuid('source_record_id');
            $table->string('link_type', 30)->default('corroborating');
            $table->string('status', 30)->default('active');
            $table->timestampTz('first_linked_at');
            $table->timestampTz('last_verified_at')->nullable();
            $table->timestampsTz();

            $table->foreign('entity_id')
                ->references('id')->on('entities')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('source_record_id')
                ->references('id')->on('source_records')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->unique(
                ['entity_id', 'source_record_id', 'link_type'],
                'entity_source_links_entity_record_type_unique'
            );
            $table->index(['entity_id', 'status']);
        });

        DB::statement("ALTER TABLE entities ADD CONSTRAINT entities_validity_check CHECK (valid_until IS NULL OR valid_from IS NULL OR valid_until >= valid_from)");
        DB::statement("ALTER TABLE organizations ADD CONSTRAINT organizations_dates_check CHECK (ended_on IS NULL OR established_on IS NULL OR ended_on >= established_on)");
        DB::statement("ALTER TABLE source_records ADD CONSTRAINT source_records_seen_check CHECK (last_seen_at >= first_seen_at)");
        DB::statement("ALTER TABLE external_identifiers ADD CONSTRAINT external_identifiers_validity_check CHECK (valid_until IS NULL OR valid_from IS NULL OR valid_until >= valid_from)");
    }

    public function down(): void
    {
        Schema::dropIfExists('entity_source_links');
        Schema::dropIfExists('external_identifiers');
        Schema::dropIfExists('source_record_versions');
        Schema::dropIfExists('source_records');
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('entities');
        Schema::dropIfExists('sources');
    }
};
