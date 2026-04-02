<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add Customer_id (FK to customer_data) to recov_debt, recov_cred, recov_fallowons.
     * This replaces cust_name as the grouping key so two customers with the
     * same name are never mixed up.
     *
     * Existing rows: backfilled with Customer_id looked up via jobc_invoice → jobcard.
     * Rows where no match is found get Customer_id = 0 (safe fallback — still
     * identified by their unique Invoice_no).
     */
    public function up(): void
    {
        // ── recov_debt ────────────────────────────────────────────────────────
        Schema::table('recov_debt', function (Blueprint $table) {
            $table->unsignedInteger('Customer_id')->default(0)->after('cust_id');
            $table->index('Customer_id');
        });

        // Backfill: join Invoice_no → jobc_invoice → jobcard to get Customer_id
        DB::statement("
            UPDATE recov_debt rd
            LEFT JOIN jobc_invoice inv ON inv.Invoice_id = rd.Invoice_no
            LEFT JOIN jobcard jc        ON jc.Jobc_id    = inv.Jobc_id
            SET rd.Customer_id = COALESCE(jc.Customer_id, 0)
        ");

        // ── recov_cred ────────────────────────────────────────────────────────
        Schema::table('recov_cred', function (Blueprint $table) {
            $table->unsignedInteger('Customer_id')->default(0)->after('cred_id');
            $table->index('Customer_id');
        });

        // Backfill via dm_invoice → recov_debt → Customer_id
        DB::statement("
            UPDATE recov_cred rc
            LEFT JOIN recov_debt rd ON rd.Invoice_no = CAST(rc.dm_invoice AS UNSIGNED)
            SET rc.Customer_id = COALESCE(rd.Customer_id, 0)
        ");

        // ── recov_fallowons ───────────────────────────────────────────────────
        Schema::table('recov_fallowons', function (Blueprint $table) {
            $table->unsignedInteger('Customer_id')->default(0)->after('id');
            $table->index('Customer_id');
        });

        // Backfill via cust_name → recov_debt (first match per name)
        DB::statement("
            UPDATE recov_fallowons rf
            LEFT JOIN (
                SELECT cust_name, MIN(Customer_id) as Customer_id
                FROM recov_debt
                GROUP BY cust_name
            ) rd ON rd.cust_name = rf.cust_name
            SET rf.Customer_id = COALESCE(rd.Customer_id, 0)
        ");
    }

    public function down(): void
    {
        Schema::table('recov_debt',      fn($t) => $t->dropColumn('Customer_id'));
        Schema::table('recov_cred',      fn($t) => $t->dropColumn('Customer_id'));
        Schema::table('recov_fallowons', fn($t) => $t->dropColumn('Customer_id'));
    }
};
