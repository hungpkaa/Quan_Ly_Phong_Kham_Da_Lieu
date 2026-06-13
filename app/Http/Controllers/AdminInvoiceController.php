<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\MedicalRecord;
use App\Models\Doctor;


class AdminInvoiceController extends Controller
{
    // Hiển thị danh sách hóa đơn với tìm kiếm
    public function index(Request $request)
    {
        $query = Invoice::query();

        // Tìm kiếm hóa đơn nếu có input search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('medicalRecord.user', function($uq) use ($search) {
                $uq->where('name', 'like', "%$search%")
                   ->orWhere('phone', 'like', "%$search%");
            })
            ->orWhere('services_medicines', 'like', "%$search%");
        }

        // Lấy danh sách hóa đơn
        $invoices = $query->latest()->get();

        // Tính toán thống kê
        $totalInvoices = Invoice::count();
        $totalMedicalRecords = MedicalRecord::count();
        $totalDoctors = Doctor::count();
        $totalRevenue = Invoice::whereIn('status', Invoice::paidStatusValues())->sum('total_amount');

        // Lấy danh sách hồ sơ bệnh án để tạo hóa đơn
        $medicalRecords = MedicalRecord::all();

        // Lấy danh sách hồ sơ chưa thanh toán (chờ lập hóa đơn)
        $unpaidRecords = MedicalRecord::where('status', 'unpaid')->latest()->get();

        return view('role.adminmanageinvoices', compact(
            'invoices',
            'totalInvoices',
            'totalMedicalRecords',
            'totalDoctors',
            'totalRevenue',
            'medicalRecords',
            'unpaidRecords'
        ));

    }

    // Hiển thị form tạo hóa đơn
    public function create()
    {
        return redirect()->route('admin.invoices.index')
            ->with('success', 'Bạn có thể lập hóa đơn mới bằng biểu mẫu trên trang quản lý hóa đơn.');
    }

    // Lưu hóa đơn mới vào CSDL
    public function store(Request $request)
    {
        $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'invoice_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|string',
        ]);

        $status = Invoice::normalizeStatus($request->status);
        if (!$status) {
            return back()->withInput()->with('error', 'Trạng thái hóa đơn không hợp lệ.');
        }

        // Lấy thông tin hồ sơ bệnh án
        $medicalRecord = MedicalRecord::findOrFail($request->medical_record_id);

        // Tạo hóa đơn mới
        Invoice::create([
            'medical_record_id' => $medicalRecord->id,
            'services_medicines' => $medicalRecord->service . "; " . $medicalRecord->prescription,
            'invoice_date' => $request->invoice_date,
            'total_amount' => $request->total_amount,
            'status' => $status,
        ]);

        // Đồng bộ lại MedicalRecord
        $medicalRecord->update([
            'cost' => $request->total_amount,
            'status' => $status
        ]);

        return redirect()->route('admin.invoices.index')->with('success', 'Hóa đơn đã được tạo thành công.');
    }


    // Hiển thị form chỉnh sửa hóa đơn
    public function edit($id)
    {
        Invoice::findOrFail($id);

        return redirect()->route('admin.invoices.index', ['edit_id' => $id])
            ->with('success', 'Vui lòng chỉnh sửa hóa đơn trực tiếp trong danh sách.');
    }

    public function show($id)
    {
        $invoice = Invoice::with('medicalRecord.user')->findOrFail($id);

        return view('role.printinvoice', compact('invoice'));
    }

    // Cập nhật hóa đơn
    public function update(Request $request, $id)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'services_medicines' => 'nullable|string',
            'total_amount' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $status = Invoice::normalizeStatus($request->status);
        if (!$status) {
            return back()->withInput()->with('error', 'Trạng thái hóa đơn không hợp lệ.');
        }

        $invoice = Invoice::findOrFail($id);

        $invoice->update([
            'invoice_date' => $request->invoice_date,
            'services_medicines' => $request->services_medicines,
            'total_amount' => $request->total_amount,
            'status' => $status,
        ]);

        // Đồng bộ lại MedicalRecord
        if ($invoice->medicalRecord) {
            $invoice->medicalRecord->update([
                'cost' => $request->total_amount,
                'status' => $status
            ]);
        }

        return redirect()->route('admin.invoices.index')->with('success', 'Hóa đơn đã được cập nhật.');
    }


    // Xóa hóa đơn
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        // Hoàn tác MedicalRecord trước khi xóa hóa đơn
        if ($invoice->medicalRecord) {
            $invoice->medicalRecord->update([
                'cost' => null,
                'status' => 'unpaid'
            ]);
        }
        
        $invoice->delete();

        return redirect()->route('admin.invoices.index')->with('success', 'Hóa đơn đã bị xóa.');
    }
}
