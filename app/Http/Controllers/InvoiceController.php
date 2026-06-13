<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;

class InvoiceController extends Controller
{
    private function currentDoctor()
    {
        return Auth::user()->doctor;
    }

    private function authorizeMedicalRecord($medicalRecord)
    {
        $doctor = $this->currentDoctor();
        if ($medicalRecord->doctor_id !== $doctor->id) {
            abort(403, 'Bạn không có quyền thao tác trên hồ sơ bệnh án này.');
        }
    }

    private function authorizeInvoice($invoice)
    {
        $doctor = $this->currentDoctor();
        if (!$invoice->medicalRecord || $invoice->medicalRecord->doctor_id !== $doctor->id) {
            abort(403, 'Bạn không có quyền truy cập hóa đơn này vì nó không thuộc hồ sơ bệnh án do bạn phụ trách.');
        }
    }

    // Hiển thị danh sách hóa đơn với chức năng tìm kiếm
    public function index(Request $request)
    {
        $doctor = $this->currentDoctor();
        $query = Invoice::with('medicalRecord')->whereHas('medicalRecord', function($q) use ($doctor) {
            $q->where('doctor_id', $doctor->id);
        });

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                  ->orWhereHas('medicalRecord.user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('phone', 'like', "%$search%");
                  });
            });
        }

        $invoices = $query->latest()->paginate(10);
        return view('role.doctormanageinvoice', compact('invoices'));
    }

    // Hiển thị form tạo hóa đơn, đồng thời hiển thị danh sách hóa đơn nếu có medical_record_id
    public function create(Request $request)
    {
        $medicalRecord = null;
        $invoices = collect();
        $servicesMedicines = '';

        if ($request->has('medical_record_id')) {
            $medicalRecord = MedicalRecord::with('user')->findOrFail($request->input('medical_record_id'));
            $this->authorizeMedicalRecord($medicalRecord);
            $invoices = Invoice::where('medical_record_id', $medicalRecord->id)->latest()->paginate(5); // SỬA LẠI paginate(5)

            // Lấy dữ liệu "Dịch vụ + Thuốc" từ hồ sơ bệnh án
            $servicesMedicines = trim($medicalRecord->service . '; ' . $medicalRecord->prescription, '; ');
        }

        return view('role.doctormanageinvoice', compact('medicalRecord', 'invoices', 'servicesMedicines'));
    }




    public function store(Request $request)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'status' => 'required|string',
            'medical_record_id' => 'required|exists:medical_records,id',
            'services_medicines' => 'nullable|string', // Nhận dữ liệu từ form
        ]);

        $status = Invoice::normalizeStatus($request->status);
        if (!$status) {
            return back()->withInput()->with('error', 'Trạng thái hóa đơn không hợp lệ.');
        }

        $medicalRecord = MedicalRecord::findOrFail($request->input('medical_record_id'));
        $this->authorizeMedicalRecord($medicalRecord);

        // Kiểm tra dữ liệu từ form, nếu trống thì lấy từ hồ sơ bệnh án
        $servicesMedicines = $request->input('services_medicines');
        if (empty($servicesMedicines)) {
            $servicesMedicines = trim(($medicalRecord->service ?? '') . '; ' . ($medicalRecord->prescription ?? ''), '; ');
        }

        // Lưu vào hóa đơn
        $invoice = Invoice::create([
            'medical_record_id' => $medicalRecord->id,
            'invoice_date' => $request->invoice_date,
            'total_amount' => $request->total_amount,
            'status' => $status,
            'services_medicines' => $servicesMedicines, // Lưu thông tin "Dịch vụ + Thuốc"
        ]);

        // Kiểm tra xem dữ liệu có thực sự được lưu hay không
        if (!$invoice->exists) {
            return redirect()->back()->with('error', 'Lỗi khi lưu hóa đơn.');
        }

        return redirect()->route('admindoctor.invoices.create', ['medical_record_id' => $medicalRecord->id])
            ->with('success', 'Hóa đơn đã được tạo thành công!');
        ;
    }


    // Hiển thị form chỉnh sửa hóa đơn
    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $this->authorizeInvoice($invoice);

        return redirect()->route('admindoctor.invoices.index')
            ->with('success', 'Trang hóa đơn bác sĩ hiện hỗ trợ xem, in và xóa hóa đơn. Vui lòng lập hóa đơn mới nếu cần điều chỉnh.');
    }

    public function show($id)
    {
        return $this->print($id);
    }

    // Cập nhật hóa đơn
    public function update(Request $request, $id)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $status = Invoice::normalizeStatus($request->status);
        if (!$status) {
            return back()->withInput()->with('error', 'Trạng thái hóa đơn không hợp lệ.');
        }

        $invoice = Invoice::findOrFail($id);
        $this->authorizeInvoice($invoice);
        $invoice->update([
            'invoice_date' => $request->invoice_date,
            'total_amount' => $request->total_amount,
            'status' => $status,
        ]);

        return redirect()->route('admindoctor.invoices.index')->with('success', 'Hóa đơn đã được cập nhật.');
    }

    // Xóa hóa đơn
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $this->authorizeInvoice($invoice);
        $invoice->delete();

        return redirect()->back();
    }



    public function print($id)
    {
        $invoice = Invoice::findOrFail($id);
        $this->authorizeInvoice($invoice);
        return view('role.printinvoice', compact('invoice'));
    }


}
