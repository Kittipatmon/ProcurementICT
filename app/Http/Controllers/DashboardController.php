<?php

namespace App\Http\Controllers;

use App\Models\ProcurementRequest;
use App\Models\Budget;
use App\Models\License;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 1. Core counters
        $stats = [
            'total' => 0,
            'pending' => 0,
            'completed' => 0,
            'rejected' => 0,
            'budget_spent' => 0,
        ];

        // Query scopes depending on role
        $query = ProcurementRequest::query();
        if ($user->procurement_role === 'user') {
            $query->where('requester_id', $user->id);
        } elseif ($user->procurement_role === 'manager') {
            $query->where('department_id', $user->dept_id);
        }

        $stats['total'] = (clone $query)->count();
        $stats['pending'] = (clone $query)->whereIn('status', ['submitted', 'approved_manager', 'approved_ict', 'approved_cao', 'pr_created', 'po_created', 'delivered'])->count();
        $stats['completed'] = (clone $query)->where('status', 'completed')->count();
        $stats['rejected'] = (clone $query)->where('status', 'rejected')->count();
        $stats['budget_spent'] = (clone $query)->where('status', 'completed')->sum('approved_budget');

        // 2. Department budget information
        $budget = Budget::where('department_id', $user->dept_id)
            ->where('fiscal_year', 2026)
            ->first();

        // 3. Pending approvals list
        $pendingApprovals = ProcurementRequest::query();
        if ($user->procurement_role === 'manager') {
            $pendingApprovals->where('department_id', $user->dept_id)
                ->where('status', 'submitted');
        } elseif ($user->procurement_role === 'ict') {
            $pendingApprovals->where('status', 'approved_manager');
        } elseif ($user->procurement_role === 'cao') {
            $pendingApprovals->where('status', 'approved_ict');
        } elseif ($user->procurement_role === 'procurement') {
            $pendingApprovals->whereIn('status', ['approved_cao', 'pr_created']);
        } else {
            $pendingApprovals->whereRaw('1 = 0'); // No tasks for user/executive/admin
        }
        $pendingApprovals = $pendingApprovals->with(['requester', 'department'])->latest()->get();

        // 4. Recent activities/requests
        $recentRequests = (clone $query)->with(['requester', 'department'])->latest()->limit(5)->get();

        // 4.1 All tracking requests for timeline table (ordered by status/latest)
        $allRequests = ProcurementRequest::with(['requester', 'department', 'purchaseRequisitions', 'purchaseOrders.vendor'])
            ->latest()
            ->get();

        // 5. Vendor count & Software license alerts (for Admin/Executive/ICT)
        $licenseAlertsCount = 0;
        if (in_array($user->procurement_role, ['admin', 'executive', 'ict'])) {
            $licenseAlertsCount = License::where('expire_date', '<=', now()->addDays(30))
                ->where('status', 'active')
                ->count();
        }

        // 6. Status Tracker (Bottleneck Analysis)
        $statusTracker = [
            'รออนุมัติ Manager' => (clone $query)->where('status', 'submitted')->count(),
            'รอตรวจสอบ ICT' => (clone $query)->where('status', 'approved_manager')->count(),
            'รออนุมัติงบ CAO' => (clone $query)->where('status', 'approved_ict')->count(),
            'รอเปิด PR / PO' => (clone $query)->whereIn('status', ['approved_cao', 'pr_created'])->count(),
            'รอส่งมอบอุปกรณ์' => (clone $query)->where('status', 'po_created')->count(),
            'รอส่งเอกสารให้บัญชี' => (clone $query)->where('status', 'delivered')->count(),
        ];

        return view('dashboard', compact('stats', 'budget', 'pendingApprovals', 'recentRequests', 'allRequests', 'licenseAlertsCount', 'statusTracker'));
    }
}
