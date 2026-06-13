<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    private function currentDoctor()
    {
        return Auth::user()->doctor;
    }

    private function authorizeInvoice(Invoice $invoice): void
    {
        $doctor = $this->currentDoctor();

        if (!$doctor || !$invoice->medicalRecord || $invoice->medicalRecord->doctor_id !== $doctor->id) {
            abort(403, 'Bạn không có quyền truy cập hóa đơn này vì nó không thuộc hồ sơ bệnh án do bạn phụ trách.');
        }
    }

    public function print($id)
    {
        $invoice = Invoice::with('medicalRecord.user')->findOrFail($id);
        $this->authorizeInvoice($invoice);

        return view('role.printinvoice', compact('invoice'));
    }
}
