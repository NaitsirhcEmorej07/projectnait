<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('naitnetwork_roles_tbl', 'naitnetwork_roles_select_tbl');
    }

    public function down(): void
    {
        Schema::rename('naitnetwork_roles_select_tbl', 'naitnetwork_roles_tbl');
    }
};