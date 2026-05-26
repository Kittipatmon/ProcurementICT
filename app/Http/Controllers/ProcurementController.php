<?php

namespace App\Http\Controllers;

use App\Models\ProcurementRequest;
use App\Models\ProcurementItem;
use App\Models\ProcurementApproval;
use App\Models\ProcurementFile;
use App\Models\ProcurementLog;
use App\Models\Budget;
use App\Models\BudgetTransaction;
use App\Models\Category;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcurementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = ProcurementRequest::query()->with(['requester', 'department']);

        // Scope by role (Admin can access all records - Modified: Added bypass for 'admin' role)
        if ($user->procurement_role === 'user') {
            $query->where('requester_id', $user->id);
        } elseif ($user->procurement_role === 'manager') {
            $query->where('department_id', $user->dept_id);
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('request_no', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $requests = $query->latest()->paginate(15);
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('procurements.index', compact('requests', 'categories'));
    }

    public function create()
    {
        $vendors = Vendor::where('status', 'active')->get();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('procurements.create', compact('vendors', 'categories'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|exists:categories,slug',
            'priority' => 'required|in:low,medium,high,urgent',
            'expected_date' => 'nullable|date|after_or_equal:today',
            'next_renewal_date' => 'nullable|date|after_or_equal:today',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.specification' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.vendor_id' => 'nullable|exists:vendors,id',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:10240',
        ]);

        return DB::transaction(function () use ($validated, $user, $request) {
            // Calculate estimated budget
            $estimatedBudget = 0;
            foreach ($validated['items'] as $item) {
                $estimatedBudget += $item['quantity'] * $item['unit_price'];
            }

            // Generate unique request no (PRQ-YYYYMMDD-XXXX)
            $datePrefix = 'PRQ-' . date('Ymd');
            $lastRequest = ProcurementRequest::where('request_no', 'like', "{$datePrefix}%")
                ->orderBy('request_no', 'desc')
                ->first();
            
            $sequence = 1;
            if ($lastRequest) {
                $lastSeq = (int) substr($lastRequest->request_no, -4);
                $sequence = $lastSeq + 1;
            }
            $requestNo = $datePrefix . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

            // Create Request
            $procRequest = ProcurementRequest::create([
                'request_no' => $requestNo,
                'requester_id' => $user->id,
                'department_id' => $user->dept_id,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'category' => $validated['category'],
                'priority' => $validated['priority'],
                'estimated_budget' => $estimatedBudget,
                'current_step' => 'draft',
                'status' => 'draft',
                'expected_date' => $validated['expected_date'],
                'next_renewal_date' => $validated['next_renewal_date'] ?? null,
            ]);

            // Create items
            foreach ($validated['items'] as $itemData) {
                $totalPrice = $itemData['quantity'] * $itemData['unit_price'];
                ProcurementItem::create([
                    'request_id' => $procRequest->id,
                    'item_name' => $itemData['item_name'],
                    'specification' => $itemData['specification'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'total_price' => $totalPrice,
                    'vendor_id' => $itemData['vendor_id'] ?? null,
                ]);
            }

            // Save attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $fileName = $file->getClientOriginalName();
                    $filePath = $file->store('procurements/' . $procRequest->id, 'public');
                    
                    ProcurementFile::create([
                        'request_id' => $procRequest->id,
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'file_type' => $file->getClientOriginalExtension(),
                        'uploaded_by' => $user->id,
                    ]);
                }
            }

            // Write Log
            ProcurementLog::create([
                'request_id' => $procRequest->id,
                'action' => 'created_draft',
                'user_id' => $user->id,
                'new_value' => ['status' => 'draft', 'budget' => $estimatedBudget],
            ]);

            return redirect()->route('procurements.show', $procRequest->id)->with('success', 'สร้างฉบับร่างคำขอสำเร็จ');
        });
    }

    public function show($id)
    {
        $procRequest = ProcurementRequest::with([
            'requester', 'department', 'items.vendor', 'approvals.approver', 
            'files.uploader', 'logs.user', 'comments.user', 
            'purchaseRequisitions.creator', 'purchaseOrders.vendor'
        ])->findOrFail($id);

        $vendors = Vendor::where('status', 'active')->get();
        return view('procurements.show', compact('procRequest', 'vendors'));
    }

    public function submit($id)
    {
        $procRequest = ProcurementRequest::findOrFail($id);
        
        if ($procRequest->status !== 'draft') {
            return back()->with('error', 'คำขอนี้ไม่ได้อยู่ในสถานะฉบับร่าง');
        }

        $procRequest->update([
            'status' => 'submitted',
            'current_step' => 'manager_approval',
        ]);

        // Log action
        ProcurementLog::create([
            'request_id' => $procRequest->id,
            'action' => 'submitted_request',
            'user_id' => Auth::id(),
            'old_value' => ['status' => 'draft'],
            'new_value' => ['status' => 'submitted', 'current_step' => 'manager_approval'],
        ]);

        // Auto create approval track for Department Manager
        ProcurementApproval::create([
            'request_id' => $procRequest->id,
            'approver_id' => $procRequest->department->manager_id ?? Auth::id(),
            'approval_step' => 'manager_approval',
            'status' => 'pending',
        ]);

        return back()->with('success', 'ส่งคำขอจัดซื้อเรียบร้อยแล้ว รอการอนุมัติจากหัวหน้าแผนก');
    }

    public function approve(Request $request, $id)
    {
        $procRequest = ProcurementRequest::findOrFail($id);
        $user = Auth::user();

        $validated = $request->validate([
            'comment' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($procRequest, $user, $validated) {
            $currentStep = $procRequest->current_step;
            $oldStatus = $procRequest->status;

            // Modified: Added admin role bypass in the checks below to allow administrators to approve any workflow step.
            if ($currentStep === 'manager_approval' && ($user->procurement_role === 'manager' || $user->procurement_role === 'admin')) {
                // Update approval step
                ProcurementApproval::where('request_id', $procRequest->id)
                    ->where('approval_step', 'manager_approval')
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'approved',
                        'comment' => $validated['comment'],
                        'approved_at' => now(),
                    ]);

                // Update request
                $procRequest->update([
                    'status' => 'approved_manager',
                    'current_step' => 'ict_approval',
                ]);

                // Create next step approval
                // Find an ICT department employee
                $ictManager = DB::connection('mysql_user')->table('employees')->where('procurement_role', 'ict')->first();
                ProcurementApproval::create([
                    'request_id' => $procRequest->id,
                    'approver_id' => $ictManager ? $ictManager->id : $user->id,
                    'approval_step' => 'ict_approval',
                    'status' => 'pending',
                ]);
            }
            elseif ($currentStep === 'ict_approval' && ($user->procurement_role === 'ict' || $user->procurement_role === 'admin')) {
                ProcurementApproval::where('request_id', $procRequest->id)
                    ->where('approval_step', 'ict_approval')
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'approved',
                        'comment' => $validated['comment'],
                        'approved_at' => now(),
                    ]);

                $procRequest->update([
                    'status' => 'approved_ict',
                    'current_step' => 'cao_approval',
                ]);

                $caoUser = DB::connection('mysql_user')->table('employees')->where('procurement_role', 'cao')->first();
                ProcurementApproval::create([
                    'request_id' => $procRequest->id,
                    'approver_id' => $caoUser ? $caoUser->id : $user->id,
                    'approval_step' => 'cao_approval',
                    'status' => 'pending',
                ]);
            }
            elseif ($currentStep === 'cao_approval' && ($user->procurement_role === 'cao' || $user->procurement_role === 'admin')) {
                // CAO checks budget. Let's verify budget
                $budget = Budget::where('department_id', $procRequest->department_id)
                    ->where('fiscal_year', 2026)
                    ->first();

                if (!$budget || $budget->remaining_budget < $procRequest->estimated_budget) {
                    return back()->with('error', 'งบประมาณคงเหลือในแผนกนี้ไม่เพียงพอสำหรับการจัดซื้อ');
                }

                // Update approval
                ProcurementApproval::where('request_id', $procRequest->id)
                    ->where('approval_step', 'cao_approval')
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'approved',
                        'comment' => $validated['comment'],
                        'approved_at' => now(),
                    ]);

                // Commit Budget (Update Budget statistics)
                $budget->used_budget += $procRequest->estimated_budget;
                $budget->remaining_budget -= $procRequest->estimated_budget;
                $budget->save();

                // Record transaction
                BudgetTransaction::create([
                    'budget_id' => $budget->id,
                    'request_id' => $procRequest->id,
                    'transaction_type' => 'spend',
                    'amount' => $procRequest->estimated_budget,
                ]);

                $procRequest->update([
                    'status' => 'approved_cao',
                    'approved_budget' => $procRequest->estimated_budget,
                    'current_step' => 'pr_creation',
                ]);
            }
            elseif ($currentStep === 'pr_ict_approval' && ($user->procurement_role === 'ict' || $user->procurement_role === 'admin')) {
                ProcurementApproval::where('request_id', $procRequest->id)
                    ->where('approval_step', 'pr_ict_approval')
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'approved',
                        'comment' => $validated['comment'],
                        'approved_at' => now(),
                    ]);

                $procRequest->update([
                    'status' => 'pr_approved_ict',
                    'current_step' => 'pr_cao_approval',
                ]);

                $caoUser = DB::connection('mysql_user')->table('employees')->where('procurement_role', 'cao')->first();
                ProcurementApproval::create([
                    'request_id' => $procRequest->id,
                    'approver_id' => $caoUser ? $caoUser->id : $user->id,
                    'approval_step' => 'pr_cao_approval',
                    'status' => 'pending',
                ]);
            }
            elseif ($currentStep === 'pr_cao_approval' && ($user->procurement_role === 'cao' || $user->procurement_role === 'admin')) {
                ProcurementApproval::where('request_id', $procRequest->id)
                    ->where('approval_step', 'pr_cao_approval')
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'approved',
                        'comment' => $validated['comment'],
                        'approved_at' => now(),
                    ]);

                $procRequest->update([
                    'status' => 'pr_approved_cao',
                    'current_step' => 'po_creation',
                ]);
            } else {
                return back()->with('error', 'คุณไม่มีสิทธิ์ในการอนุมัติขั้นตอนนี้');
            }

            ProcurementLog::create([
                'request_id' => $procRequest->id,
                'action' => 'approved_step_' . $currentStep,
                'user_id' => $user->id,
                'old_value' => ['status' => $oldStatus],
                'new_value' => ['status' => $procRequest->status, 'current_step' => $procRequest->current_step],
            ]);

            return back()->with('success', 'อนุมัติเรียบร้อยแล้ว');
        });
    }

    public function reject(Request $request, $id)
    {
        $procRequest = ProcurementRequest::findOrFail($id);
        $user = Auth::user();

        $validated = $request->validate([
            'comment' => 'required|string',
        ]);

        return DB::transaction(function () use ($procRequest, $user, $validated) {
            $currentStep = $procRequest->current_step;
            $oldStatus = $procRequest->status;

            ProcurementApproval::where('request_id', $procRequest->id)
                ->where('approval_step', $currentStep)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                    'comment' => $validated['comment'],
                    'approved_at' => now(),
                ]);

            $procRequest->update([
                'status' => 'rejected',
                'current_step' => 'rejected',
            ]);

            ProcurementLog::create([
                'request_id' => $procRequest->id,
                'action' => 'rejected_request',
                'user_id' => $user->id,
                'old_value' => ['status' => $oldStatus],
                'new_value' => ['status' => 'rejected', 'comment' => $validated['comment']],
            ]);

            return back()->with('success', 'ปฏิเสธคำขอจัดซื้อเรียบร้อยแล้ว');
        });
    }

    public function createPr(Request $request, $id)
    {
        $procRequest = ProcurementRequest::findOrFail($id);
        $user = Auth::user();

        // Modified: Allowed admin to create PR records
        if ($user->procurement_role !== 'procurement' && $user->procurement_role !== 'admin') {
            return back()->with('error', 'เฉพาะฝ่ายจัดซื้อหรือผู้ดูแลระบบเท่านั้นที่สามารถดำเนินการนี้ได้');
        }

        $validated = $request->validate([
            'pr_no' => 'required|string|unique:purchase_requisitions,pr_no|max:50',
        ]);

        PurchaseRequisition::create([
            'request_id' => $procRequest->id,
            'pr_no' => $validated['pr_no'],
            'pr_date' => now(),
            'created_by' => $user->id,
            'status' => 'created',
        ]);

        $procRequest->update([
            'status' => 'pr_created',
            'current_step' => 'pr_ict_approval',
        ]);

        $ictManager = DB::connection('mysql_user')->table('employees')->where('procurement_role', 'ict')->first();
        ProcurementApproval::create([
            'request_id' => $procRequest->id,
            'approver_id' => $ictManager ? $ictManager->id : $user->id,
            'approval_step' => 'pr_ict_approval',
            'status' => 'pending',
        ]);

        ProcurementLog::create([
            'request_id' => $procRequest->id,
            'action' => 'pr_created',
            'user_id' => $user->id,
            'new_value' => ['pr_no' => $validated['pr_no']],
        ]);

        return back()->with('success', 'บันทึกเลข PR สำเร็จ');
    }

    public function createPo(Request $request, $id)
    {
        $procRequest = ProcurementRequest::findOrFail($id);
        $user = Auth::user();

        // Modified: Allowed admin to create PO records
        if ($user->procurement_role !== 'procurement' && $user->procurement_role !== 'admin') {
            return back()->with('error', 'เฉพาะฝ่ายจัดซื้อหรือผู้ดูแลระบบเท่านั้นที่สามารถดำเนินการนี้ได้');
        }

        $validated = $request->validate([
            'po_no' => 'required|string|unique:purchase_orders,po_no|max:50',
            'vendor_id' => 'required|exists:vendors,id',
            'delivery_date' => 'nullable|date',
        ]);

        PurchaseOrder::create([
            'request_id' => $procRequest->id,
            'po_no' => $validated['po_no'],
            'vendor_id' => $validated['vendor_id'],
            'po_date' => now(),
            'total_amount' => $procRequest->approved_budget ?? $procRequest->estimated_budget,
            'delivery_date' => $validated['delivery_date'],
            'status' => 'pending',
        ]);

        $procRequest->update([
            'status' => 'po_created',
            'current_step' => 'delivery',
        ]);

        ProcurementLog::create([
            'request_id' => $procRequest->id,
            'action' => 'po_created',
            'user_id' => $user->id,
            'new_value' => ['po_no' => $validated['po_no']],
        ]);

        return back()->with('success', 'บันทึกเลข PO สำเร็จ');
    }

    public function updateDelivery(Request $request, $id)
    {
        $procRequest = ProcurementRequest::findOrFail($id);
        
        $po = PurchaseOrder::where('request_id', $procRequest->id)->firstOrFail();
        $po->update(['status' => 'delivered', 'delivery_date' => now()]);

        $procRequest->update([
            'status' => 'delivered',
            'current_step' => 'completion',
        ]);

        ProcurementLog::create([
            'request_id' => $procRequest->id,
            'action' => 'items_delivered',
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'อัปเดตสถานะจัดส่งสินค้าสำเร็จ');
    }

    public function complete($id)
    {
        $procRequest = ProcurementRequest::findOrFail($id);

        $procRequest->update([
            'status' => 'completed',
            'current_step' => 'completed',
            'completed_date' => now(),
        ]);

        ProcurementLog::create([
            'request_id' => $procRequest->id,
            'action' => 'completed_procurement',
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'ปิดงานติดตามการจัดซื้อเสร็จสิ้น');
    }

    public function addComment(Request $request, $id)
    {
        $validated = $request->validate([
            'comment' => 'required|string',
        ]);

        Comment::create([
            'request_id' => $id,
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'โพสต์ความคิดเห็นสำเร็จ');
    }
}
