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
        // 1. Vendors table
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_name');
            $table->string('contact_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('tax_id')->nullable();
            $table->text('address')->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 2. Budgets table
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->integer('fiscal_year');
            $table->integer('department_id'); // Match type of departments.id (int)
            $table->decimal('allocated_budget', 15, 2);
            $table->decimal('used_budget', 15, 2)->default(0.00);
            $table->decimal('remaining_budget', 15, 2)->default(0.00);
            $table->timestamps();

        });

        // 3. Procurement requests table
        Schema::create('procurement_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_no')->unique();
            $table->integer('requester_id'); // Match type of employees.id (int)
            $table->integer('department_id'); // Match type of departments.id (int)
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('category', ['hardware', 'software', 'network', 'service', 'other']);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->decimal('estimated_budget', 15, 2);
            $table->decimal('approved_budget', 15, 2)->nullable();
            $table->string('current_step')->default('draft');
            $table->enum('status', ['draft', 'submitted', 'approved_manager', 'approved_ict', 'approved_cao', 'pr_created', 'po_created', 'delivered', 'completed', 'rejected'])->default('draft');
            $table->date('expected_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->timestamps();

        });

        // 4. Budget transactions table
        Schema::create('budget_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('budget_id');
            $table->unsignedBigInteger('request_id');
            $table->enum('transaction_type', ['allocate', 'commit', 'spend', 'release']);
            $table->decimal('amount', 15, 2);
            $table->timestamps();

            $table->foreign('budget_id')->references('id')->on('budgets')->onDelete('cascade');
            $table->foreign('request_id')->references('id')->on('procurement_requests')->onDelete('cascade');
        });

        // 5. Procurement items table
        Schema::create('procurement_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->string('item_name');
            $table->text('specification')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_price', 15, 2);
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('procurement_requests')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
        });

        // 6. Procurement approvals table
        Schema::create('procurement_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->integer('approver_id'); // Match type of employees.id (int)
            $table->string('approval_step');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('comment')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('procurement_requests')->onDelete('cascade');
        });

        // 7. Procurement files table
        Schema::create('procurement_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->integer('uploaded_by'); // Match type of employees.id (int)
            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('procurement_requests')->onDelete('cascade');
        });

        // 8. Procurement logs table
        Schema::create('procurement_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->string('action');
            $table->integer('user_id'); // Match type of employees.id (int)
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('procurement_requests')->onDelete('cascade');
        });

        // 9. Purchase requisitions table
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->string('pr_no')->unique();
            $table->date('pr_date');
            $table->integer('created_by'); // Match type of employees.id (int)
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('procurement_requests')->onDelete('cascade');
        });

        // 10. Purchase orders table
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->string('po_no')->unique();
            $table->unsignedBigInteger('vendor_id');
            $table->date('po_date');
            $table->decimal('total_amount', 15, 2);
            $table->date('delivery_date')->nullable();
            $table->enum('status', ['pending', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('procurement_requests')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });

        // 11. Comments table
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->integer('user_id'); // Match type of employees.id (int)
            $table->text('comment');
            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('procurement_requests')->onDelete('cascade');
        });

        // 12. Licenses table
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('software_name');
            $table->text('license_key')->nullable();
            $table->string('license_type')->nullable();
            $table->integer('total_license');
            $table->integer('used_license')->default(0);
            $table->date('purchase_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->decimal('annual_cost', 15, 2)->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->enum('status', ['active', 'expired', 'suspended'])->default('active');
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('set null');
        });

        // 13. License assignments table
        Schema::create('license_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('license_id');
            $table->integer('user_id'); // Match type of employees.id (int)
            $table->date('assigned_date');
            $table->date('returned_date')->nullable();
            $table->enum('status', ['active', 'returned'])->default('active');
            $table->timestamps();

            $table->foreign('license_id')->references('id')->on('licenses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_assignments');
        Schema::dropIfExists('licenses');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('purchase_requisitions');
        Schema::dropIfExists('procurement_logs');
        Schema::dropIfExists('procurement_files');
        Schema::dropIfExists('procurement_approvals');
        Schema::dropIfExists('procurement_items');
        Schema::dropIfExists('budget_transactions');
        Schema::dropIfExists('procurement_requests');
        Schema::dropIfExists('budgets');
        Schema::dropIfExists('vendors');
    }
};
