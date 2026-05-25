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
        if (!Schema::connection('mysql_user')->hasColumn('employees', 'procurement_role')) {
            Schema::connection('mysql_user')->table('employees', function (Blueprint $table) {
                $table->enum('procurement_role', ['user', 'manager', 'ict', 'cao', 'procurement', 'admin', 'executive'])->default('user')->after('role');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::connection('mysql_user')->hasColumn('employees', 'procurement_role')) {
            Schema::connection('mysql_user')->table('employees', function (Blueprint $table) {
                $table->dropColumn('procurement_role');
            });
        }
    }
};
