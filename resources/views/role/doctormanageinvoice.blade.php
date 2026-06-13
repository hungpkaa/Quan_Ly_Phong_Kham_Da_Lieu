@extends('layouts.admin_layout')

@section('title', 'Quản lý Hóa Đơn (Bác sĩ)')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1" style="color: #0056b3; font-weight: 600;">Quản lý Hóa Đơn</h3>
                <p class="text-secondary mb-0">Theo dõi doanh thu và lập hóa đơn khám chữa bệnh cho khách hàng.</p>
            </div>
            <div>
                <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-medium" data-bs-toggle="modal" data-bs-target="#addInvoiceModal">
                    <i class="bi bi-plus-lg me-1"></i> Lập Hóa Đơn
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <strong>Thành công!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Lỗi!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Danh sách Hóa Đơn -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">
                <i class="bi bi-list-columns me-2"></i> Danh Sách Hóa Đơn Đã Lập
            </h6>
        </div>
        
        <div class="card-body p-0">
            @if(isset($invoices) && $invoices->isEmpty())
                <div class="text-center py-5">
                    <div class="text-secondary opacity-50 mb-3"><i class="bi bi-receipt fs-1"></i></div>
                    <p class="mb-0 text-secondary">Bạn chưa lập hóa đơn nào.</p>
                </div>
            @elseif(isset($invoices))
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3 text-secondary fw-medium small text-center" style="width: 60px;">#</th>
                                <th class="border-0 py-3 text-secondary fw-medium small">Khách hàng</th>
                                <th class="border-0 py-3 text-secondary fw-medium small text-center">Ngày lập</th>
                                <th class="border-0 py-3 text-secondary fw-medium small">Chi tiết (Dịch vụ + Thuốc)</th>
                                <th class="border-0 py-3 text-secondary fw-medium small text-end">Tổng tiền</th>
                                <th class="border-0 py-3 text-secondary fw-medium small text-center">Trạng thái</th>
                                <th class="border-0 py-3 text-secondary fw-medium small text-center" style="width: 100px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                            <tr>
                                <td class="py-3 text-center text-secondary">{{ $loop->iteration }}</td>
                                <td class="py-3">
                                    <div class="fw-semibold text-dark">{{ optional(optional($invoice->medicalRecord)->user)->name }}</div>
                                    <div class="text-secondary small">SĐT: {{ optional(optional($invoice->medicalRecord)->user)->phone }} | HS: #{{ $invoice->medical_record_id }}</div>
                                </td>
                                <td class="py-3 text-center text-secondary small">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                                <td class="py-3 text-secondary small" style="max-width: 250px;">
                                    <div class="text-truncate" title="{{ $invoice->services_medicines }}">
                                        {{ $invoice->services_medicines ?: 'Không có ghi chú' }}
                                    </div>
                                </td>
                                <td class="py-3 text-end fw-semibold text-danger">{{ number_format($invoice->total_amount, 0) }} đ</td>
                                <td class="py-3 text-center">
                                    @if(!$invoice->isPaid())
                                        <span class="badge bg-warning text-dark bg-opacity-25 border border-warning rounded-pill fw-medium px-2 py-1">Chưa thanh toán</span>
                                    @else
                                        <span class="badge bg-success text-success bg-opacity-10 border border-success rounded-pill fw-medium px-2 py-1"><i class="bi bi-check-circle me-1"></i>Đã thanh toán</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('admindoctor.invoices.print', $invoice->id) }}" class="btn btn-sm btn-light border text-primary rounded-circle" target="_blank" title="In Hóa Đơn">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admindoctor.invoices.destroy', $invoice->id) }}" class="m-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger rounded-circle" onclick="return confirm('Bạn có chắc chắn muốn xóa hóa đơn của {{ optional(optional($invoice->medicalRecord)->user)->name }}?')" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-top">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Thêm Hóa Đơn -->
@php $showModal = isset($medicalRecord); @endphp
<div class="modal fade {{ $showModal ? 'show' : '' }}" id="addInvoiceModal" tabindex="-1" aria-hidden="{{ $showModal ? 'false' : 'true' }}" style="{{ $showModal ? 'display: block; background: rgba(0,0,0,0.5);' : '' }}">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom py-3 px-4 bg-light rounded-top-4">
                <h5 class="modal-title fw-semibold text-primary">
                    <i class="bi bi-receipt me-2"></i>Lập Hóa Đơn
                </h5>
                @if($showModal)
                    <a href="{{ route('admindoctor.invoices.index') }}" class="btn-close"></a>
                @else
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                @endif
            </div>
            
            <form method="POST" action="{{ route('admindoctor.invoices.store') }}">
                @csrf
                <div class="modal-body p-4">
                    @if(isset($medicalRecord))
                        <input type="hidden" name="medical_record_id" value="{{ $medicalRecord->id }}">
                        
                        <div class="row g-3 mb-4 p-3 bg-primary bg-opacity-10 rounded-3 border border-primary border-opacity-25">
                            <h6 class="text-primary fw-semibold mb-2">Thông tin Hồ sơ Bệnh án #{{ $medicalRecord->id }}</h6>
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-medium small mb-1">Tên Bệnh Nhân</label>
                                <input type="text" class="form-control form-control-sm bg-white" value="{{ optional($medicalRecord->user)->name }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-medium small mb-1">Số Điện Thoại</label>
                                <input type="text" class="form-control form-control-sm bg-white" value="{{ optional($medicalRecord->user)->phone }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-medium small mb-1">Ngày Khám</label>
                                <input type="text" class="form-control form-control-sm bg-white" value="{{ \Carbon\Carbon::parse($medicalRecord->exam_date)->format('d/m/Y') }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-medium small mb-1">Chi Phí Gốc (Chưa thuốc)</label>
                                <input type="text" class="form-control form-control-sm bg-white text-danger fw-medium" value="{{ number_format($medicalRecord->cost, 0) }} VNĐ" readonly>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning border-0 small">
                            <i class="bi bi-info-circle me-2"></i>Vui lòng chọn tạo hóa đơn từ danh sách <strong>Hồ Sơ Bệnh Án</strong> để liên kết dữ liệu.
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="invoice_date" class="form-label text-secondary fw-medium small mb-1">Ngày Lập Hóa Đơn <span class="text-danger">*</span></label>
                            <input type="date" name="invoice_date" id="invoice_date" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" value="{{ now()->format('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label text-secondary fw-medium small mb-1">Trạng Thái Thanh Toán <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select bg-light border-0 focus-ring focus-ring-primary py-2" required>
                                <option value="unpaid">Chưa thanh toán</option>
                                <option value="paid">Đã thanh toán</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="total_amount" class="form-label text-secondary fw-medium small mb-1">Tổng Tiền (Dịch vụ + Thuốc) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="any" name="total_amount" id="total_amount" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" value="{{ old('total_amount', $medicalRecord->cost ?? '') }}" required>
                                <span class="input-group-text bg-light border-0 text-secondary fw-medium px-4">VNĐ</span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="services_medicines" class="form-label text-secondary fw-medium small mb-1">Chi tiết Dịch Vụ & Thuốc</label>
                            <textarea name="services_medicines" id="services_medicines" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" rows="3" placeholder="Liệt kê dịch vụ, các loại thuốc, thủ thuật...">{{ old('services_medicines', $servicesMedicines ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-top px-4 py-3 bg-light rounded-bottom-4">
                    @if($showModal)
                        <a href="{{ route('admindoctor.invoices.index') }}" class="btn btn-secondary rounded-pill px-4">Hủy</a>
                    @else
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                    @endif
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-medium" {{ !isset($medicalRecord) ? 'disabled' : '' }}>Lập Hóa Đơn</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush
@endsection
