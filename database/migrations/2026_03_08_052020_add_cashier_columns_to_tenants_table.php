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
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('pm_type')->after('stripe_id')->nullable();
            $table->string('pm_last_four', 4)->after('pm_type')->nullable();
            $table->timestamp('trial_ends_at')->after('pm_last_four')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'pm_type',
                'pm_last_four',
                'trial_ends_at',
            ]);
        });
    }
};
