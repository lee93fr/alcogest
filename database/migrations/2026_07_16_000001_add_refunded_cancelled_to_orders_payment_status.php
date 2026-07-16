<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_payment_status_check");
        DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_payment_status_check CHECK (payment_status IN ('unpaid', 'partial', 'paid', 'refunded', 'cancelled'))");
    }

    public function down(): void
    {
        // Ramener les valeurs introduites vers 'unpaid' avant de restaurer la contrainte d'origine.
        DB::statement("UPDATE orders SET payment_status = 'unpaid' WHERE payment_status IN ('refunded', 'cancelled')");
        DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_payment_status_check");
        DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_payment_status_check CHECK (payment_status IN ('unpaid', 'partial', 'paid'))");
    }
};
