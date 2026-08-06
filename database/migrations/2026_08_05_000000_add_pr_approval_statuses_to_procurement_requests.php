<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds 'pr_approved_ict' and 'pr_approved_cao' to the status ENUM
     * on the procurement_requests table, to support the PR sub-approval workflow.
     */
    public function up(): void
    {
        // MySQL ENUM cannot be altered via Blueprint easily; use raw SQL instead.
        DB::statement("ALTER TABLE `procurement_requests` MODIFY COLUMN `status` ENUM(
            'draft',
            'submitted',
            'approved_manager',
            'approved_ict',
            'approved_cao',
            'pr_created',
            'pr_approved_ict',
            'pr_approved_cao',
            'po_created',
            'delivered',
            'completed',
            'rejected'
        ) NOT NULL DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     * Restores the original ENUM without the new PR approval statuses.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `procurement_requests` MODIFY COLUMN `status` ENUM(
            'draft',
            'submitted',
            'approved_manager',
            'approved_ict',
            'approved_cao',
            'pr_created',
            'po_created',
            'delivered',
            'completed',
            'rejected'
        ) NOT NULL DEFAULT 'draft'");
    }
};
