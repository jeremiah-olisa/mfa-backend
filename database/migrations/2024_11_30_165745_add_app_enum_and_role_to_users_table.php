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
            $table->enum('role', ['Student', 'Admin', 'Content Manager']);
        });

        Schema::create('user_apps', function (Blueprint $table) {
            $table->id();
            $table->enum('app', ['WAEC', 'NECO', 'JAMB', 'OYO', 'WEB', 'ADMIN']);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'app'], 'user_id_app_unique_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_apps');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
