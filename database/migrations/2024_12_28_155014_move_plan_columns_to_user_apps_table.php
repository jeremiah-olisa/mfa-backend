<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_apps', function (Blueprint $table) {
            $table->string('plan')->nullable()->after('app');
            $table->timestamp('plan_started_at')->nullable()->after('plan');
            $table->timestamp('plan_expires_at')->nullable()->after('plan_started_at');
        });

        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn(['plan', 'plan_duration', 'plan_started_at', 'plan_expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('plan')->nullable();
            $table->integer('plan_duration')->nullable()->after('plan'); // Duration in months or days
            $table->timestamp('plan_started_at')->nullable()->after('plan_duration');
            $table->timestamp('plan_expires_at')->nullable()->after('plan_started_at');
        });

        Schema::table('user_apps', function (Blueprint $table) {
            $table->dropColumn(['plan', 'plan_duration', 'plan_started_at', 'plan_expires_at']);
        });
    }
};
