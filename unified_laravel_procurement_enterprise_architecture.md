# Laravel Enterprise Procurement Architecture
## Unified System Design + Modular Architecture + Enterprise Workflow

---

# Project Overview

ระบบ Enterprise Procurement & ICT Operations Platform
ออกแบบสำหรับองค์กรขนาดกลางถึงขนาดใหญ่
รองรับ:

- Procurement Workflow
- Approval Flow
- PR / PO Tracking
- Budget Management
- Vendor Management
- License Management
- Notification System
- Dashboard & Analytics
- File Attachments
- Audit Trail
- Export PDF / Excel
- API Integration
- Enterprise RBAC

แนวคิดหลัก:

> Modular + Service + Repository Pattern

ได้รับแรงบันดาลใจจาก:

- SAP Procurement
- ServiceNow ITSM
- Odoo ERP
- Jira Service Management
- Monday Enterprise

---

# Recommended Tech Stack

## Frontend

- Blade Template
- Tailwind CSS
- Bootstrap 5
- Alpine.js
- Chart.js

## Backend

- Laravel 12
- Laravel Breeze / Jetstream
- Laravel Sanctum
- Spatie Permission
- Laravel Queue
- Laravel Notification
- Laravel DOMPDF
- Laravel Excel

## Database

- MySQL 8
- Redis

## Infrastructure

```text
Nginx
PHP 8.3+
MySQL 8
Redis
Supervisor
Docker
GitHub Actions
```

---

# System Architecture

```text
Frontend
   ↓
Controller
   ↓
Request Validation
   ↓
Service Layer
   ↓
Repository Layer
   ↓
Database
```

---

# Enterprise Modular Structure

```text
app/
├── Modules/
│   ├── Authentication/
│   ├── UserManagement/
│   ├── Procurement/
│   ├── Approval/
│   ├── Vendor/
│   ├── Budget/
│   ├── License/
│   ├── Dashboard/
│   ├── Reports/
│   └── Settings/
│
├── Services/
├── Repositories/
├── DTOs/
├── Events/
├── Jobs/
├── Notifications/
├── Policies/
├── Models/
└── Http/
```

---

# Example Procurement Module Structure

```text
Modules/
└── Procurement/
    ├── Controllers/
    ├── Requests/
    ├── Services/
    ├── Repositories/
    ├── Models/
    ├── Routes/
    ├── Views/
    ├── Policies/
    ├── Notifications/
    ├── Jobs/
    ├── PDF/
    └── Database/
```

---

# Frontend Structure

```text
resources/
├── views/
├── js/
├── css/
└── components/
```

---

# Core Database ER Structure

```text
USERS
 ├── DEPARTMENTS
 ├── ROLES
 ├── PROCUREMENT_REQUESTS
 │     ├── PROCUREMENT_APPROVALS
 │     ├── PROCUREMENT_ITEMS
 │     ├── PROCUREMENT_FILES
 │     ├── PROCUREMENT_LOGS
 │     ├── PURCHASE_REQUISITIONS
 │     ├── PURCHASE_ORDERS
 │     └── COMMENTS
 │
 ├── VENDORS
 │     ├── PURCHASE_ORDERS
 │     └── LICENSES
 │
 ├── LICENSES
 │     └── LICENSE_ASSIGNMENTS
 │
 ├── BUDGETS
 │     └── BUDGET_TRANSACTIONS
 │
 └── NOTIFICATIONS
```

---

# Main Modules

## 1. User & Permission Module

### users

| Field | Type |
|---|---|
| id | BIGINT |
| employee_code | VARCHAR |
| name | VARCHAR |
| email | VARCHAR |
| password | VARCHAR |
| department_id | FK |
| role_id | FK |
| status | ENUM |

---

### roles

| Field | Type |
|---|---|
| id | BIGINT |
| role_name | VARCHAR |

---

### departments

| Field | Type |
|---|---|
| id | BIGINT |
| department_name | VARCHAR |
| department_code | VARCHAR |

---

## 2. Procurement Module

### procurement_requests

| Field | Type |
|---|---|
| id | BIGINT |
| request_no | VARCHAR |
| requester_id | FK |
| department_id | FK |
| title | VARCHAR |
| description | TEXT |
| category | ENUM |
| priority | ENUM |
| estimated_budget | DECIMAL |
| approved_budget | DECIMAL |
| current_step | VARCHAR |
| status | ENUM |
| expected_date | DATE |
| completed_date | DATE |

---

### procurement_items

| Field | Type |
|---|---|
| id | BIGINT |
| request_id | FK |
| item_name | VARCHAR |
| specification | TEXT |
| quantity | INT |
| unit_price | DECIMAL |
| total_price | DECIMAL |
| vendor_id | FK |

---

### procurement_approvals

| Field | Type |
|---|---|
| id | BIGINT |
| request_id | FK |
| approver_id | FK |
| approval_step | VARCHAR |
| status | ENUM |
| comment | TEXT |
| approved_at | TIMESTAMP |

---

### procurement_files

| Field | Type |
|---|---|
| id | BIGINT |
| request_id | FK |
| file_name | VARCHAR |
| file_path | VARCHAR |
| file_type | VARCHAR |
| uploaded_by | FK |

---

### procurement_logs

| Field | Type |
|---|---|
| id | BIGINT |
| request_id | FK |
| action | VARCHAR |
| user_id | FK |
| old_value | JSON |
| new_value | JSON |

---

## 3. PR / PO Module

### purchase_requisitions

| Field | Type |
|---|---|
| id | BIGINT |
| request_id | FK |
| pr_no | VARCHAR |
| pr_date | DATE |
| created_by | FK |
| status | VARCHAR |

---

### purchase_orders

| Field | Type |
|---|---|
| id | BIGINT |
| request_id | FK |
| po_no | VARCHAR |
| vendor_id | FK |
| po_date | DATE |
| total_amount | DECIMAL |
| delivery_date | DATE |
| status | ENUM |

---

## 4. Vendor Module

### vendors

| Field | Type |
|---|---|
| id | BIGINT |
| vendor_name | VARCHAR |
| contact_name | VARCHAR |
| phone | VARCHAR |
| email | VARCHAR |
| tax_id | VARCHAR |
| address | TEXT |
| rating | DECIMAL |
| status | ENUM |

---

## 5. Budget Module

### budgets

| Field | Type |
|---|---|
| id | BIGINT |
| fiscal_year | YEAR |
| department_id | FK |
| allocated_budget | DECIMAL |
| used_budget | DECIMAL |
| remaining_budget | DECIMAL |

---

### budget_transactions

| Field | Type |
|---|---|
| id | BIGINT |
| budget_id | FK |
| request_id | FK |
| transaction_type | ENUM |
| amount | DECIMAL |

---

## 6. License Management Module

### licenses

| Field | Type |
|---|---|
| id | BIGINT |
| software_name | VARCHAR |
| license_key | TEXT |
| license_type | VARCHAR |
| total_license | INT |
| used_license | INT |
| purchase_date | DATE |
| expire_date | DATE |
| annual_cost | DECIMAL |
| vendor_id | FK |
| status | ENUM |

---

### license_assignments

| Field | Type |
|---|---|
| id | BIGINT |
| license_id | FK |
| user_id | FK |
| assigned_date | DATE |
| returned_date | DATE |
| status | ENUM |

---

## 7. Notification Module

### notifications

| Field | Type |
|---|---|
| id | BIGINT |
| user_id | FK |
| title | VARCHAR |
| message | TEXT |
| type | VARCHAR |
| is_read | BOOLEAN |

---

## 8. Comment System

### comments

| Field | Type |
|---|---|
| id | BIGINT |
| request_id | FK |
| user_id | FK |
| comment | TEXT |

---

# Workflow Process

```text
Draft
↓
Submitted
↓
Manager Approved
↓
ICT Approved
↓
CAO Approved
↓
PR Created
↓
PO Created
↓
Vendor Processing
↓
Delivered
↓
Completed
```

---

# User Roles

| Role | Description |
|---|---|
| User | สร้างคำขอจัดซื้อ |
| Manager | อนุมัติระดับแผนก |
| ICT | ตรวจสอบ Technical |
| CAO | อนุมัติงบประมาณ |
| Procurement | จัดทำ PR / PO |
| Admin | จัดการระบบ |
| Executive | ดู Dashboard และ Analytics |

---

# Service Pattern Example

## Controller

```php
public function store(StorePurchaseRequest $request)
{
    return $this->service->store(
        $request->validated()
    );
}
```

---

## Service

```php
class PurchaseService
{
    public function store(array $data)
    {
        return $this->repository->create($data);
    }
}
```

---

## Repository

```php
class PurchaseRepository
{
    public function create(array $data)
    {
        return Purchase::create($data);
    }
}
```

---

# Queue System

ใช้สำหรับ:

- Send Email
- Export PDF
- Notifications
- Reports
- Heavy Background Processing

## Example

```bash
php artisan make:job GenerateReportJob
```

---

# Permission System

## Install

```bash
composer require spatie/laravel-permission
```

## Example

```php
$user->assignRole('admin');
```

---

# Sanctum API

## Install

```bash
composer require laravel/sanctum
```

เหมาะสำหรับ:

- SPA
- Mobile App
- API

---

# PDF Export

## Install DOMPDF

```bash
composer require barryvdh/laravel-dompdf
```

## Example

```php
use Barryvdh\DomPDF\Facade\Pdf;

$pdf = Pdf::loadView('pdf.purchase', $data);

return $pdf->download('purchase.pdf');
```

---

# Dashboard Features

- Procurement Summary
- KPI Charts
- Budget Analytics
- Approval Status
- Notifications
- Vendor Performance
- License Expiration Tracking
- SLA Monitoring

---

# Recommended Packages

| Package | Purpose |
|---|---|
| Laravel Breeze / Jetstream | Authentication |
| Laravel Sanctum | API Authentication |
| Spatie Permission | Role & Permission |
| Laravel DOMPDF | Export PDF |
| Laravel Excel | Import/Export Excel |
| Spatie Activitylog | Audit Log |

---

# Recommended Enterprise Features

## Optional Tables

- assets
- contracts
- quotations
- invoice_payments
- approval_templates
- workflow_configs
- digital_signatures
- risk_assessments

---

# Best Practices

## DO

- Thin Controller
- Business Logic in Service
- Query Logic in Repository
- Use Queue
- Use Events
- Use Notifications
- Use Policies
- Use Validation
- Use DTOs
- Use Soft Delete
- Use Audit Log
- Use File Versioning

---

## DON'T

- Fat Controller
- SQL Everywhere
- Hardcode Status
- Business Logic in Blade
- Copy Paste Code

---

# Final Vision

ระบบนี้ถูกออกแบบให้เป็น:

> Enterprise Procurement Intelligence Platform

จุดเด่น:

- Enterprise Scalability
- Premium UX/UI
- Real-time Workflow Tracking
- Enterprise Security
- Modular Architecture
- API Ready
- Audit Ready
- Future AI Integration

