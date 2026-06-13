<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class AdminInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('medicalRecord.user')
            ->where('total_amount', '>', 0);
        $search = trim((string) $request->input('search', ''));

        if ($search !== '') {
            $recordId = ltrim($search, '#');

            $query->where(function ($invoiceQuery) use ($search, $recordId) {
                $invoiceQuery
                    ->whereHas('medicalRecord.user', function ($userQuery) use ($search) {
                        $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhere('services_medicines', 'like', "%{$search}%");

                if (ctype_digit($recordId)) {
                    $invoiceQuery->orWhere('medical_record_id', (int) $recordId);
                }
            });
        }

        $invoices = $query->latest()->get();

        $totalInvoices = Invoice::where('total_amount', '>', 0)->count();
        $totalMedicalRecords = MedicalRecord::count();
        $totalDoctors = Doctor::count();
        $totalRevenue = Invoice::whereIn('status', Invoice::paidStatusValues())->sum('total_amount');

        $medicalRecords = MedicalRecord::with(['user', 'invoice'])
            ->where(function ($recordQuery) {
                $recordQuery
                    ->doesntHave('invoice')
                    ->orWhereHas('invoice', function ($invoiceQuery) {
                        $invoiceQuery->where('total_amount', '<=', 0);
                    });
            })
            ->latest()
            ->get();

        $unpaidRecords = $medicalRecords;

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

    public function create()
    {
        return redirect()->route('admin.invoices.index')
            ->with('success', 'Bạn có thể lập hóa đơn mới bằng biểu mẫu trên trang quản lý hóa đơn.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'invoice_date' => 'required|date',
            'total_amount' => 'required|numeric|min:1',
            'status' => 'required|string',
        ]);

        $status = Invoice::normalizeStatus($request->status);
        if (!$status) {
            return back()->withInput()->with('error', 'Trạng thái hóa đơn không hợp lệ.');
        }

        $medicalRecord = MedicalRecord::with('invoice')->findOrFail($request->medical_record_id);
        $invoice = $medicalRecord->invoice;

        if ($invoice && !$this->isDraftInvoice($invoice)) {
            return back()
                ->withInput()
                ->with('error', 'Hồ sơ bệnh án này đã có hóa đơn hợp lệ. Vui lòng chỉnh sửa hóa đơn hiện có thay vì lập hóa đơn mới.');
        }

        $invoiceData = [
            'services_medicines' => $this->servicesMedicinesText($medicalRecord),
            'invoice_date' => $request->invoice_date,
            'total_amount' => $request->total_amount,
            'status' => $status,
        ];

        if ($invoice) {
            $invoice->update($invoiceData);
        } else {
            Invoice::create($invoiceData + [
                'medical_record_id' => $medicalRecord->id,
            ]);
        }

        $medicalRecord->update([
            'cost' => $request->total_amount,
            'status' => $status,
        ]);

        return redirect()->route('admin.invoices.index')->with('success', 'Hóa đơn đã được tạo thành công.');
    }

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

    public function update(Request $request, $id)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'services_medicines' => 'nullable|string',
            'total_amount' => 'required|numeric|min:1',
            'status' => 'required|string',
        ]);

        $status = Invoice::normalizeStatus($request->status);
        if (!$status) {
            return back()->withInput()->with('error', 'Trạng thái hóa đơn không hợp lệ.');
        }

        $invoice = Invoice::with('medicalRecord')->findOrFail($id);

        $invoice->update([
            'invoice_date' => $request->invoice_date,
            'services_medicines' => $request->services_medicines,
            'total_amount' => $request->total_amount,
            'status' => $status,
        ]);

        if ($invoice->medicalRecord) {
            $invoice->medicalRecord->update([
                'cost' => $request->total_amount,
                'status' => $status,
            ]);
        }

        return redirect()->route('admin.invoices.index')->with('success', 'Hóa đơn đã được cập nhật.');
    }

    public function destroy($id)
    {
        $invoice = Invoice::with('medicalRecord')->findOrFail($id);

        if ($invoice->medicalRecord) {
            $invoice->medicalRecord->update([
                'cost' => null,
                'status' => 'unpaid',
            ]);
        }

        $invoice->delete();

        return redirect()->route('admin.invoices.index')->with('success', 'Hóa đơn đã bị xóa.');
    }

    private function servicesMedicinesText(MedicalRecord $medicalRecord): string
    {
        return collect([$medicalRecord->service, $medicalRecord->prescription])
            ->filter(fn ($value) => trim((string) $value) !== '')
            ->implode('; ');
    }

    private function isDraftInvoice(Invoice $invoice): bool
    {
        return (float) $invoice->total_amount <= 0;
    }
}
