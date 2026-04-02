<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Fix recov_debt rows that were auto-created by CashierController without
 * a Customer_id (left as 0). We backfill by joining Invoice_no → jobc_invoice
 * → jobcard to get the correct Customer_id, then do the same for recov_cred.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Fix recov_debt rows where Customer_id = 0 but Invoice_no is known
        DB::statement("
            UPDATE recov_debt rd
            JOIN jobc_invoice inv ON inv.Invoice_id = rd.Invoice_no
            JOIN jobcard jc       ON jc.Jobc_id     = inv.Jobc_id
            SET rd.Customer_id = jc.Customer_id
            WHERE rd.Customer_id = 0
              AND jc.Customer_id IS NOT NULL
              AND jc.Customer_id != 0
        ");

        // Fix recov_cred rows where Customer_id = 0 (backfill via recov_debt)
        DB::statement("
            UPDATE recov_cred rc
            JOIN recov_debt rd ON rd.Invoice_no = CAST(rc.dm_invoice AS UNSIGNED)
            SET rc.Customer_id = rd.Customer_id
            WHERE rc.Customer_id = 0
              AND rd.Customer_id != 0
        ");
    }

    public function down(): void
    {
        // Not reversible — we don't want to restore broken data
    }
};
