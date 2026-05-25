<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Budget;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create or Find Departments
        $deptIct = Department::firstOrCreate(['name' => 'ICT Department'], ['manager_id' => null]);
        $deptHr = Department::firstOrCreate(['name' => 'HR Department'], ['manager_id' => null]);
        $deptFinance = Department::firstOrCreate(['name' => 'Finance Department'], ['manager_id' => null]);
        $deptPurchasing = Department::firstOrCreate(['name' => 'Purchasing Department'], ['manager_id' => null]);

        // 2. Create or Find Employees (Users)
        // Admin
        $admin = Employee::firstOrCreate(
            ['username' => 'admin'],
            [
                'emp_code' => 'EMP001',
                'firstname' => 'ระบบ',
                'lastname' => 'ผู้ดูแลระบบ',
                'email' => 'admin@procure.com',
                'password' => 'password', // Model cast handles hashing
                'dept_id' => $deptIct->id,
                'status' => 'active',
                'role' => 'admin',
                'procurement_role' => 'admin'
            ]
        );

        // HR Requester
        $requester = Employee::firstOrCreate(
            ['username' => 'employee1'],
            [
                'emp_code' => 'EMP002',
                'firstname' => 'สมปอง',
                'lastname' => 'ดีใจ',
                'email' => 'employee1@procure.com',
                'password' => 'password',
                'dept_id' => $deptHr->id,
                'status' => 'active',
                'role' => 'staff',
                'procurement_role' => 'user'
            ]
        );

        // HR Manager
        $manager = Employee::firstOrCreate(
            ['username' => 'manager1'],
            [
                'emp_code' => 'EMP003',
                'firstname' => 'สมศักดิ์',
                'lastname' => 'เป็นหัวหน้า',
                'email' => 'manager1@procure.com',
                'password' => 'password',
                'dept_id' => $deptHr->id,
                'status' => 'active',
                'role' => 'staff',
                'procurement_role' => 'manager'
            ]
        );

        // Set HR Manager in Department
        $deptHr->update(['manager_id' => $manager->id]);

        // ICT Reviewer
        $ict = Employee::firstOrCreate(
            ['username' => 'ict1'],
            [
                'emp_code' => 'EMP004',
                'firstname' => 'วิชัย',
                'lastname' => 'ไอที',
                'email' => 'ict1@procure.com',
                'password' => 'password',
                'dept_id' => $deptIct->id,
                'status' => 'active',
                'role' => 'staff',
                'procurement_role' => 'ict'
            ]
        );
        $deptIct->update(['manager_id' => $ict->id]);

        // CAO Budget Approver
        $cao = Employee::firstOrCreate(
            ['username' => 'cao1'],
            [
                'emp_code' => 'EMP005',
                'firstname' => 'อนันต์',
                'lastname' => 'อนุมัติงบ',
                'email' => 'cao1@procure.com',
                'password' => 'password',
                'dept_id' => $deptFinance->id,
                'status' => 'active',
                'role' => 'staff',
                'procurement_role' => 'cao'
            ]
        );
        $deptFinance->update(['manager_id' => $cao->id]);

        // Procurement Staff
        $proc = Employee::firstOrCreate(
            ['username' => 'buyer1'],
            [
                'emp_code' => 'EMP006',
                'firstname' => 'นารี',
                'lastname' => 'จัดซื้อ',
                'email' => 'buyer1@procure.com',
                'password' => 'password',
                'dept_id' => $deptPurchasing->id,
                'status' => 'active',
                'role' => 'staff',
                'procurement_role' => 'procurement'
            ]
        );
        $deptPurchasing->update(['manager_id' => $proc->id]);

        // Executive Observer
        $exec = Employee::firstOrCreate(
            ['username' => 'exec1'],
            [
                'emp_code' => 'EMP007',
                'firstname' => 'ศิริ',
                'lastname' => 'บริหาร',
                'email' => 'exec1@procure.com',
                'password' => 'password',
                'dept_id' => $deptFinance->id,
                'status' => 'active',
                'role' => 'staff',
                'procurement_role' => 'executive'
            ]
        );

        // 3. Budgets
        $year = 2026;
        Budget::firstOrCreate(
            ['fiscal_year' => $year, 'department_id' => $deptHr->id],
            ['allocated_budget' => 500000.00, 'used_budget' => 0.00, 'remaining_budget' => 500000.00]
        );
        Budget::firstOrCreate(
            ['fiscal_year' => $year, 'department_id' => $deptIct->id],
            ['allocated_budget' => 1500000.00, 'used_budget' => 0.00, 'remaining_budget' => 1500000.00]
        );
        Budget::firstOrCreate(
            ['fiscal_year' => $year, 'department_id' => $deptFinance->id],
            ['allocated_budget' => 300000.00, 'used_budget' => 0.00, 'remaining_budget' => 300000.00]
        );
        Budget::firstOrCreate(
            ['fiscal_year' => $year, 'department_id' => $deptPurchasing->id],
            ['allocated_budget' => 200000.00, 'used_budget' => 0.00, 'remaining_budget' => 200000.00]
        );

        // 4. Vendors
        Vendor::firstOrCreate(
            ['vendor_name' => 'บริษัท แอดวานซ์ ไอที โซลูชั่น จำกัด'],
            [
                'contact_name' => 'คุณสมชาย',
                'phone' => '02-123-4567',
                'email' => 'sales@advanceit.co.th',
                'tax_id' => '0105560001234',
                'address' => '123 อาคารไอที ชั้น 5 ถนนรัชดาภิเษก ห้วยขวาง กรุงเทพฯ 10310',
                'rating' => 4.5,
                'status' => 'active'
            ]
        );

        Vendor::firstOrCreate(
            ['vendor_name' => 'บริษัท ซอฟต์แวร์ ดิสทริบิวชั่น (ประเทศไทย) จำกัด'],
            [
                'contact_name' => 'คุณพิมลวรรณ',
                'phone' => '02-987-6543',
                'email' => 'info@softdist.co.th',
                'tax_id' => '0105559005678',
                'address' => '456 ตึกทาวเวอร์ ชั้น 12 ถนนวิภาวดีรังสิต จตุจักร กรุงเทพฯ 10900',
                'rating' => 4.8,
                'status' => 'active'
            ]
        );
    }
}
