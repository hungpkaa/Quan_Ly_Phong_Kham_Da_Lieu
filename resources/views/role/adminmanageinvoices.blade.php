@extends('layouts.admin_layout')

@section('title', 'Hóa Đơn & Thống Kê')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1" style="color: #0056b3; font-weight: 600;">Hóa Đơn & Thống Kê</h3>
                <p class="text-secondary mb-0">Theo dõi doanh thu và quản lý các hóa đơn khám chữa bệnh.</p>
            </div>
            @if(false)
            <div>
                <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm fw-medium" id="scrollToPendingInvoices">
                    <i class="bi bi-plus-lg me-1"></i> Lập Hóa Đơn
                </button>
            </div>
            @endif
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

    <!-- Phần Thống Kê -->
    <div class="row g-4 mb-4">
        <!-- Tổng Hóa Đơn -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);">
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Tổng Hóa Đơn</div>
                        <div class="bg-white text-info rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                            <i class="bi bi-receipt fs-4"></i>
                        </div>
                    </div>
                    <h2 class="mb-0 fw-bold text-dark">{{ $totalInvoices }}</h2>
                </div>
            </div>
        </div>
        
        <!-- Tổng Hồ Sơ -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #e8eaf6 0%, #c5cae9 100%);">
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Hồ Sơ Bệnh Án</div>
                        <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                            <i class="bi bi-file-earmark-medical fs-4"></i>
                        </div>
                    </div>
                    <h2 class="mb-0 fw-bold text-dark">{{ $totalMedicalRecords }}</h2>
                </div>
            </div>
        </div>

        <!-- Số Bác Sĩ -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);">
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Bác Sĩ Hiện Có</div>
                        <div class="bg-white text-warning rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                            <i class="bi bi-person-badge fs-4"></i>
                        </div>
                    </div>
                    <h2 class="mb-0 fw-bold text-dark">{{ $totalDoctors }}</h2>
                </div>
            </div>
        </div>

        <!-- Tổng Doanh Thu -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);">
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">Tổng Doanh Thu</div>
                        <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                            <i class="bi bi-cash-stack fs-4"></i>
                        </div>
                    </div>
                    <h2 class="mb-0 fw-bold text-dark fs-3">{{ number_format($totalRevenue, 0) }} <span class="fs-6 text-secondary fw-medium">VNĐ</span></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách Hồ Sơ Chờ Lập Hóa Đơn -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 border-warning" id="pendingInvoiceRecords">
        <div class="card-header bg-warning bg-opacity-10 border-bottom py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-warning-emphasis fw-bold">
                <i class="bi bi-clock-history me-2"></i> Hồ Sơ Chờ Lập Hóa Đơn
            </h6>
        </div>
        <div class="card-body p-0">
            @if($unpaidRecords->isEmpty())
                <div class="text-center py-4">
                    <p class="mb-0 text-secondary">Tất cả hồ sơ bệnh án hiện đã có hóa đơn.</p>
                    @if(false)
                    <p class="mb-0 text-secondary">Tuyệt vời! Tất cả hồ sơ đã được thanh toán.</p>
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3 text-secondary fw-medium small text-center" style="width: 60px;">#</th>
                                <th class="border-0 py-3 text-secondary fw-medium small">Khách hàng</th>
                                <th class="border-0 py-3 text-secondary fw-medium small">Ngày khám</th>
                                <th class="border-0 py-3 text-secondary fw-medium small">Dịch vụ / Thuốc</th>
                                <th class="border-0 py-3 text-secondary fw-medium small text-center" style="width: 150px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unpaidRecords as $record)
                            <tr>
                                <td class="py-3 text-center text-secondary">{{ $loop->iteration }}</td>
                                <td class="py-3">
                                    <div class="fw-semibold text-dark">{{ optional($record->user)->name }}</div>
                                    <div class="text-secondary small">SĐT: {{ optional($record->user)->phone }}</div>
                                </td>
                                <td class="py-3 text-secondary small">{{ \Carbon\Carbon::parse($record->exam_date)->format('d/m/Y') }}</td>
                                <td class="py-3 text-secondary small">
                                    <div class="fw-medium text-dark mb-1"><span class="badge bg-light text-dark border px-2 py-1">{{ $record->service ?: 'Khám tổng quát' }}</span></div>
                                    <div class="text-truncate" style="max-width: 250px;" title="{{ $record->prescription }}">{{ $record->prescription ?: 'Không kê đơn' }}</div>
                                </td>
                                <td class="py-3 text-center">
                                    <button class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm create-invoice-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#addInvoiceModal"
                                            data-record-id="{{ $record->id }}"
                                            data-record-cost="{{ $record->cost ?? '' }}">
                                        <i class="bi bi-receipt me-1"></i> Lập hóa đơn
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Danh sách Hóa Đơn -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">
                <i class="bi bi-list-columns me-2"></i> Danh Sách Hóa Đơn
            </h6>
            
            <!-- Form tìm kiếm -->
            <form method="GET" action="{{ route('admin.invoices.index') }}" class="d-flex align-items-center" style="max-width: 400px; width: 100%; m-0">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0 rounded-start-pill text-secondary px-3">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control bg-light border-start-0 rounded-end-pill focus-ring focus-ring-light py-2" placeholder="Tên BN, SĐT, ID hồ sơ..." value="{{ request('search') }}">
                </div>
            </form>
        </div>
        
        <div class="card-body p-0">
            @if($invoices->isEmpty())
                <div class="text-center py-5">
                    <div class="text-secondary opacity-50 mb-3"><i class="bi bi-receipt fs-1"></i></div>
                    <p class="mb-0 text-secondary">Không tìm thấy hóa đơn nào.</p>
                </div>
            @else
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
                            <tr id="invoiceRow-{{ $invoice->id }}">
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
                                        <button class="btn btn-sm btn-light border text-primary rounded-circle edit-btn" data-id="{{ $invoice->id }}" title="Chỉnh sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form method="POST" action="{{ route('admin.invoices.destroy', $invoice->id) }}" class="m-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger rounded-circle" onclick="return confirm('Bạn có chắc chắn muốn xóa hóa đơn của {{ optional(optional($invoice->medicalRecord)->user)->name }}?')" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Form Edit Inline (Ẩn mặc định) -->
                            <tr id="editRow-{{ $invoice->id }}" style="display: none;" class="bg-light border-bottom">
                                <td colspan="7" class="py-4 px-4">
                                    <form method="POST" action="{{ route('admin.invoices.update', $invoice->id) }}" class="m-0">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-2">
                                                <label class="form-label text-secondary small fw-medium">Ngày lập</label>
                                                <input type="date" name="invoice_date" class="form-control form-control-sm bg-white focus-ring focus-ring-primary" value="{{ $invoice->invoice_date }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-secondary small fw-medium">Dịch vụ + Thuốc</label>
                                                <input type="text" name="services_medicines" class="form-control form-control-sm bg-white focus-ring focus-ring-primary" value="{{ $invoice->services_medicines }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label text-secondary small fw-medium">Tổng tiền (VNĐ)</label>
                                                <input type="number" min="1" step="any" name="total_amount" class="form-control form-control-sm bg-white focus-ring focus-ring-primary" value="{{ $invoice->total_amount }}" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label text-secondary small fw-medium">Trạng thái</label>
                                                <select name="status" class="form-select form-select-sm bg-white focus-ring focus-ring-primary">
                                                    <option value="unpaid" {{ $invoice->statusCode() === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                                                    <option value="paid" {{ $invoice->statusCode() === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 d-flex gap-2">
                                                <button type="submit" class="btn btn-sm btn-success w-100 fw-medium shadow-sm"><i class="bi bi-save me-1"></i>Lưu</button>
                                                <button type="button" class="btn btn-sm btn-secondary w-100 fw-medium shadow-sm cancel-edit-btn" data-id="{{ $invoice->id }}">Hủy</button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Thêm Hóa Đơn -->
<div class="modal fade" id="addInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom py-3 px-4 bg-light rounded-top-4">
                <h5 class="modal-title fw-semibold text-primary">
                    <i class="bi bi-receipt me-2"></i>Lập Hóa Đơn Mới
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.invoices.store') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="medical_record_id" class="form-label text-secondary fw-medium small mb-1">Chọn Hồ Sơ Bệnh Án <span class="text-danger">*</span></label>
                            <select name="medical_record_id" id="medical_record_id" class="form-select bg-light border-0 focus-ring focus-ring-primary py-2" required>
                                <option value="">-- Chọn Hồ Sơ --</option>
                                @forelse($medicalRecords as $record)
                                <option value="{{ $record->id }}" data-cost="{{ $record->cost ?? '' }}" label="#{{ $record->id }} - {{ optional($record->user)->name }} | SĐT: {{ optional($record->user)->phone }} | {{ $record->service ?: 'Khám tổng quát' }} | Khám ngày: {{ \Carbon\Carbon::parse($record->exam_date)->format('d/m/Y') }}">
                                    #{{ $record->id }} - {{ optional($record->user)->name }} (Khám ngày: {{ \Carbon\Carbon::parse($record->exam_date)->format('d/m/Y') }})
                                </option>
                                @empty
                                <option value="" disabled>Không còn hồ sơ nào cần lập hóa đơn</option>
                                @endforelse
                            </select>
                        </div>
                        
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
                            <label for="total_amount" class="form-label text-secondary fw-medium small mb-1">Tổng Số Tiền <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" min="1" step="any" name="total_amount" id="total_amount" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" placeholder="Nhập số tiền" required>
                                <span class="input-group-text bg-light border-0 text-secondary fw-medium px-4">VNĐ</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top px-4 py-3 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-medium">Tạo Hóa Đơn</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const scrollToPendingButton = document.getElementById('scrollToPendingInvoices');
        if (scrollToPendingButton) {
            scrollToPendingButton.innerHTML = '<i class="bi bi-arrow-down-circle me-1"></i> Xem Hồ Sơ Chờ';
            scrollToPendingButton.addEventListener('click', function () {
                document.getElementById('pendingInvoiceRecords')?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        }

        // Auto select medical record in Add Invoice Modal
        document.querySelectorAll('.create-invoice-btn').forEach(button => {
            button.addEventListener('click', function() {
                let recordId = this.getAttribute('data-record-id');
                let recordCost = this.getAttribute('data-record-cost');
                let selectElement = document.getElementById('medical_record_id');
                if (selectElement && recordId) {
                    selectElement.value = recordId;
                }
                let amountInput = document.getElementById('total_amount');
                if (amountInput && recordCost && Number(recordCost) > 0) {
                    amountInput.value = recordCost;
                }
            });
        });

        let medicalRecordSelect = document.getElementById('medical_record_id');
        if (medicalRecordSelect) {
            medicalRecordSelect.addEventListener('change', function () {
                let selectedOption = this.options[this.selectedIndex];
                let amountInput = document.getElementById('total_amount');
                let recordCost = selectedOption ? selectedOption.getAttribute('data-cost') : '';

                if (amountInput && recordCost && Number(recordCost) > 0) {
                    amountInput.value = recordCost;
                }
            });
        }

        // Toggle inline edit row
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.getAttribute('data-id');
                let editRow = document.getElementById(`editRow-${id}`);
                
                // Hide all other edit rows
                document.querySelectorAll('tr[id^="editRow-"]').forEach(row => {
                    if (row.id !== `editRow-${id}`) row.style.display = 'none';
                });

                // Toggle current edit row
                if (editRow.style.display === 'none' || editRow.style.display === '') {
                    editRow.style.display = 'table-row';
                } else {
                    editRow.style.display = 'none';
                }
            });
        });

        // Cancel inline edit row
        document.querySelectorAll('.cancel-edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.getAttribute('data-id');
                document.getElementById(`editRow-${id}`).style.display = 'none';
            });
        });

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush
@endsection
