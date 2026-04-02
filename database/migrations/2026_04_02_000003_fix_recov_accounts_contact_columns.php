<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Primary_contact and Sec_contact were defined as INT, which overflows
 * for 11-digit Pakistani numbers (e.g. 03344-3332222 → 33443332222 > INT max).
 * Change both to VARCHAR(20) so any number format is stored safely.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recov_accounts', function (Blueprint $table) {
            $table->string('Primary_contact', 20)->default('0')->change();
            $table->string('Sec_contact', 20)->default('0')->change();
        });
    }

    public function down(): void
    {
        Schema::table('recov_accounts', function (Blueprint $table) {
            $table->integer('Primary_contact', false, true)->length(20)->change();
            $table->integer('Sec_contact', false, true)->length(20)->change();
        });
    }
};
