<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');          // ชื่อหมวดหมู่ (ภาษาไทย+อังกฤษ)
            $table->string('slug')->unique(); // code key เช่น hardware, software
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#6366f1'); // HEX color สำหรับแสดงผล
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Seed default categories (ค่าเดิม 5 รายการ)
        DB::table('categories')->insert([
            [
                'name' => 'Hardware (คอมพิวเตอร์/เซิร์ฟเวอร์)',
                'slug' => 'hardware',
                'description' => 'อุปกรณ์ฮาร์ดแวร์ คอมพิวเตอร์ เซิร์ฟเวอร์ และอุปกรณ์ต่อพ่วง',
                'color' => '#6366f1',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Software (ลิขสิทธิ์ซอฟต์แวร์)',
                'slug' => 'software',
                'description' => 'ซอฟต์แวร์ลิขสิทธิ์ ระบบปฏิบัติการ และแอปพลิเคชัน',
                'color' => '#8b5cf6',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Network (อุปกรณ์เครือข่าย)',
                'slug' => 'network',
                'description' => 'อุปกรณ์เครือข่าย สวิตช์ เราเตอร์ ไฟร์วอลล์ และระบบเครือข่าย',
                'color' => '#0ea5e9',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Service (บริการดูแลรักษา/คลาวด์)',
                'slug' => 'service',
                'description' => 'บริการดูแลรักษาระบบ คลาวด์ SaaS และบริการ IT ต่างๆ',
                'color' => '#10b981',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'อื่นๆ (Other)',
                'slug' => 'other',
                'description' => 'หมวดหมู่อื่นๆ ที่ไม่ได้จัดอยู่ในหมวดข้างต้น',
                'color' => '#64748b',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 3. Change category column in procurement_requests from enum to string
        // Drop the enum constraint and change to string to support dynamic categories
        Schema::table('procurement_requests', function (Blueprint $table) {
            $table->string('category', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
