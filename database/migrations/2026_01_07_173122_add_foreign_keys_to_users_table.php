<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->fk('status_id', 'statuses')->cascadeOnDelete();
            $table->fk('locality_id', 'districts')->cascadeOnDelete();
            $table->fk('language_id', 'languages')->cascadeOnDelete();
            $table->fk('created_by_id', 'users')->cascadeOnDelete();
            $table->fk('updated_by_id', 'users')->cascadeOnDelete();
        });

        Schema::table('security_policies', function (Blueprint $table) {
            $table->uuid('organization_id');
            $table->fk('organization_id', 'structures')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('security_policies', function (Blueprint $table) {
            $table->dropColumn(['organization_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['created_by_id', 'updated_by_id']);
        });
    }
};
