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
        $this->generateReferralCodes();
    }

    protected function generateReferralCodes()
    {
        $users = \App\Models\User::whereNull('referral_code')->get();

        foreach ($users as $user) {
            $user->update([
                'referral_code' => ReferralUtils::getReferralId()
            ]);
        }

        // Finally make the column non-nullable
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code', 6)->nullable(false)->change();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // If we want to roll back the changes, we can make the referral_code column nullable again
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code', 6)->nullable()->change();
        });
    }
};
