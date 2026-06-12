<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE supplier_orders DROP CONSTRAINT IF EXISTS supplier_orders_status_check");
        DB::statement("ALTER TABLE supplier_orders ADD CONSTRAINT supplier_orders_status_check CHECK (status IN ('draft', 'sent', 'confirmed', 'cancelled'))");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE supplier_orders DROP CONSTRAINT IF EXISTS supplier_orders_status_check");
        DB::statement("ALTER TABLE supplier_orders ADD CONSTRAINT supplier_orders_status_check CHECK (status IN ('draft', 'sent', 'confirmed'))");
    }
};
