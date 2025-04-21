<?php

use App\Utils\ReferralUtils;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['Student', 'Admin', 'Content Manager', 'Marketer'])
                ->default('Student')
                ->change();

            $table->enum('gender', ['Male', 'Female'])
                ->after('role')
                ->nullable();

            $table->string('referral_code', 6)
                ->after('gender')
                ->unique()
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert role enum
            $table->enum('role', ['Student', 'Admin', 'Content Manager'])
                ->default('Student')
                ->change();

            // Remove added columns
            $table->dropColumn('gender');
            $table->dropColumn('referral_code');
        });
    }
};
