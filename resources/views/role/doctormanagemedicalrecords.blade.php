@extends('layouts.admin_layout')

@section('title', 'Quản lý Hồ Sơ Bệnh Án')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1" style="color: #0056b3; font-weight: 600;">Hồ Sơ Bệnh Án</h3>
                <p class="text-secondary mb-0">Quản lý và lưu trữ thông tin khám chữa bệnh của khách hàng.</p>
            </div>
            <div>
                <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-medium" data-bs-toggle="modal" data-bs-target="#addRecordModal">
                    <i class="bi bi-plus-lg me-1"></i> Thêm Hồ Sơ Mới
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

    <!-- Danh sách Hồ sơ bệnh án -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">
                <i class="bi bi-journal-medical me-2"></i> Danh Sách Bệnh Án
            </h6>
            
            <!-- Form tìm kiếm -->
            <form method="GET" action="{{ route('admindoctor.medicalrecords.index') }}" class="d-flex align-items-center" style="max-width: 400px; width: 100%; margin: 0;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0 rounded-start-pill text-secondary px-3">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control bg-light border-start-0 rounded-end-pill focus-ring focus-ring-light py-2" placeholder="Tìm tên bệnh nhân, SĐT, Email..." value="{{ request('search') }}">
                </div>
            </form>
        </div>
        
        <div class="card-body p-0">
            @if($medicalRecords->isEmpty())
                <div class="text-center py-5">
                    <div class="text-secondary opacity-50 mb-3"><i class="bi bi-folder-x fs-1"></i></div>
                    <p class="mb-0 text-secondary">Không tìm thấy hồ sơ bệnh án nào.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3 text-secondary fw-medium small text-center" style="width: 60px;">#</th>
                                <th class="border-0 py-3 text-secondary fw-medium small">Bệnh Nhân</th>
                                <th class="border-0 py-3 text-secondary fw-medium small">Ngày Khám</th>
                                <th class="border-0 py-3 text-secondary fw-medium small">Dịch Vụ / Chẩn Đoán</th>
                                <th class="border-0 py-3 text-secondary fw-medium small text-center">Trạng Thái</th>
                                <th class="border-0 py-3 text-secondary fw-medium small text-center" style="width: 140px;">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicalRecords as $record)
                            <tr>
                                <td class="py-3 text-center text-secondary">{{ $loop->iteration }}</td>
                                <td class="py-3">
                                    <div class="fw-semibold text-dark">{{ optional($record->user)->name }}</div>
                                    <div class="text-secondary small">SĐT: {{ optional($record->user)->phone }} | Tuổi: {{ optional($record->user)->age }}</div>
                                </td>
                                <td class="py-3 text-secondary small">
                                    <i class="bi bi-calendar-event me-1"></i>{{ \Carbon\Carbon::parse($record->exam_date)->format('d/m/Y') }}
                                </td>
                                <td class="py-3">
                                    <div class="text-dark small fw-medium mb-1"><span class="badge bg-light text-dark border px-2 py-1">{{ $record->service ?: 'Khám tổng quát' }}</span></div>
                                    <div class="text-secondary small text-truncate" style="max-width: 250px;" title="{{ $record->diagnosis }}">{{ $record->diagnosis }}</div>
                                </td>
                                <td class="py-3 text-center">
                                    @if($record->status === 'paid')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill fw-medium px-2 py-1"><i class="bi bi-check-circle me-1"></i>Đã thanh toán</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill fw-medium px-2 py-1">Chưa thanh toán</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button class="btn btn-sm btn-light border text-info rounded-circle view-btn" data-bs-toggle="modal" data-bs-target="#viewRecordModal-{{ $record->id }}" title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <a href="{{ route('admindoctor.invoices.create', ['medical_record_id' => $record->id]) }}" class="btn btn-sm btn-light border text-success rounded-circle" title="Tạo hóa đơn">
                                            <i class="bi bi-receipt"></i>
                                        </a>
                                        <a href="{{ route('admindoctor.medicalrecords.index', ['edit_id' => $record->id]) }}" class="btn btn-sm btn-light border text-primary rounded-circle" title="Chỉnh sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admindoctor.medicalrecords.destroy', $record->id) }}" class="m-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger rounded-circle" onclick="return confirm('Bạn có chắc chắn muốn xóa hồ sơ này?')" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Xem Chi Tiết -->
                            <div class="modal fade" id="viewRecordModal-{{ $record->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <div class="modal-header border-bottom py-3 px-4 bg-light rounded-top-4">
                                            <h5 class="modal-title fw-semibold text-primary">
                                                <i class="bi bi-file-medical me-2"></i>Chi Tiết Hồ Sơ Bệnh Án
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <h6 class="text-secondary fw-semibold border-bottom pb-2 mb-3">Thông tin bệnh nhân</h6>
                                                    <div class="d-flex flex-column gap-2 small">
                                                        <div class="d-flex justify-content-between"><span class="text-muted">Họ tên:</span> <span class="fw-medium text-dark">{{ optional($record->user)->name }}</span></div>
                                                        <div class="d-flex justify-content-between"><span class="text-muted">Tuổi:</span> <span class="fw-medium text-dark">{{ optional($record->user)->age }}</span></div>
                                                        <div class="d-flex justify-content-between"><span class="text-muted">CCCD:</span> <span class="fw-medium text-dark">{{ optional($record->user)->cccd }}</span></div>
                                                        <div class="d-flex justify-content-between"><span class="text-muted">SĐT:</span> <span class="fw-medium text-dark">{{ optional($record->user)->phone }}</span></div>
                                                        <div class="d-flex justify-content-between"><span class="text-muted">Email:</span> <span class="fw-medium text-dark">{{ optional($record->user)->email }}</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-secondary fw-semibold border-bottom pb-2 mb-3">Thông tin khám bệnh</h6>
                                                    <div class="d-flex flex-column gap-2 small">
                                                        <div class="d-flex justify-content-between"><span class="text-muted">Ngày khám:</span> <span class="fw-medium text-dark">{{ \Carbon\Carbon::parse($record->exam_date)->format('d/m/Y') }}</span></div>
                                                        <div class="d-flex justify-content-between"><span class="text-muted">Tái khám:</span> <span class="fw-medium text-danger">{{ $record->follow_up_date ? \Carbon\Carbon::parse($record->follow_up_date)->format('d/m/Y') : 'Không' }}</span></div>
                                                        <div class="d-flex justify-content-between"><span class="text-muted">Dịch vụ:</span> <span class="fw-medium text-dark">{{ $record->service }}</span></div>
                                                        <div class="d-flex justify-content-between"><span class="text-muted">Chi phí:</span> <span class="fw-medium text-danger">{{ number_format($record->cost, 0) }} đ</span></div>
                                                        <div class="d-flex justify-content-between">
                                                            <span class="text-muted">Trạng thái TT:</span> 
                                                            @if($record->status === 'paid')
                                                                <span class="text-success fw-medium">Đã thanh toán</span>
                                                            @else
                                                                <span class="text-warning fw-medium">Chưa thanh toán</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <h6 class="text-secondary fw-semibold border-bottom pb-2 mb-3">Kết quả lâm sàng</h6>
                                                    <div class="mb-3">
                                                        <label class="text-muted small fw-medium mb-1">Chẩn đoán:</label>
                                                        <div class="p-3 bg-light rounded-3 small text-dark border">{{ $record->diagnosis ?: 'Chưa cập nhật' }}</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="text-muted small fw-medium mb-1">Toa thuốc:</label>
                                                        <div class="p-3 bg-light rounded-3 small text-dark border" style="white-space: pre-wrap;">{{ $record->prescription ?: 'Không kê đơn' }}</div>
                                                    </div>
                                                    <div>
                                                        <label class="text-muted small fw-medium mb-1">Ghi chú thêm:</label>
                                                        <div class="p-3 bg-light rounded-3 small text-dark border fst-italic">{{ $record->notes ?: 'Không có ghi chú' }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Thêm/Sửa Hồ Sơ -->
@php 
    $isEditing = isset($editMedicalRecord) && !empty($editMedicalRecord->id); 
    $showModal = isset($editMedicalRecord);
@endphp
<div class="modal fade" id="addRecordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom py-3 px-4 bg-light rounded-top-4">
                <h5 class="modal-title fw-semibold text-primary">
                    <i class="bi bi-journal-medical me-2"></i>{{ $isEditing ? 'Cập Nhật Hồ Sơ Bệnh Án' : 'Lập Hồ Sơ Bệnh Án Mới' }}
                </h5>
                @if($showModal)
                    <a href="{{ route('admindoctor.medicalrecords.index') }}" class="btn-close"></a>
                @else
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                @endif
            </div>
            <form method="POST" action="{{ $isEditing ? route('admindoctor.medicalrecords.update', $editMedicalRecord->id) : route('admindoctor.medicalrecords.store') }}">
                @csrf
                @if($isEditing)
                    @method('PUT')
                @endif
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Thông tin cá nhân -->
                        <div class="col-lg-4">
                            <h6 class="text-secondary fw-semibold border-bottom pb-2 mb-3">Thông tin cá nhân</h6>
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-medium small mb-1">Tên Bệnh Nhân <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" value="{{ old('name', $editMedicalRecord?->user?->name ?? '') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-medium small mb-1">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" value="{{ old('phone', $editMedicalRecord?->user?->phone ?? '') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-medium small mb-1">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" value="{{ old('email', $editMedicalRecord?->user?->email ?? '') }}" required>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label text-secondary fw-medium small mb-1">Tuổi <span class="text-danger">*</span></label>
                                    <input type="number" name="age" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" value="{{ old('age', $editMedicalRecord?->user?->age ?? '') }}" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-secondary fw-medium small mb-1">CCCD <span class="text-danger">*</span></label>
                                    <input type="text" name="cccd" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" value="{{ old('cccd', $editMedicalRecord?->user?->cccd ?? '') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin khám bệnh -->
                        <div class="col-lg-8">
                            <h6 class="text-secondary fw-semibold border-bottom pb-2 mb-3">Thông tin khám & Chẩn đoán</h6>
                            <div class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <label class="form-label text-secondary fw-medium small mb-1">Ngày Khám <span class="text-danger">*</span></label>
                                    <input type="date" name="exam_date" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" value="{{ old('exam_date', $editMedicalRecord->exam_date ?? now()->format('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-secondary fw-medium small mb-1">Tái khám</label>
                                    <input type="date" name="follow_up_date" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" value="{{ old('follow_up_date', $editMedicalRecord->follow_up_date ?? '') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-secondary fw-medium small mb-1">Dịch vụ sử dụng</label>
                                    <input type="text" name="service" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" value="{{ old('service', $editMedicalRecord->service ?? '') }}" placeholder="Vd: Khám da liễu">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-secondary fw-medium small mb-1">Thanh toán</label>
                                    <select name="status" class="form-select bg-light border-0 focus-ring focus-ring-primary py-2">
                                        <option value="unpaid" {{ (isset($editMedicalRecord) && $editMedicalRecord->status == 'unpaid') ? 'selected' : '' }}>Chưa thanh toán</option>
                                        <option value="paid" {{ (isset($editMedicalRecord) && $editMedicalRecord->status == 'paid') ? 'selected' : '' }}>Đã thanh toán</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-medium small mb-1">Chi phí khám / Dịch vụ <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="any" name="cost" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" value="{{ isset($editMedicalRecord) ? $editMedicalRecord->cost / 1000 : old('cost') }}" placeholder="Nhập số tiền..." required>
                                    <span class="input-group-text bg-light border-0 text-secondary px-4 fw-medium">.000 VNĐ</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary fw-medium small mb-1">Chẩn Đoán Lâm Sàng <span class="text-danger">*</span></label>
                                <textarea name="diagnosis" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" rows="2" placeholder="Ghi nhận tình trạng bệnh lý..." required>{{ old('diagnosis', $editMedicalRecord->diagnosis ?? '') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary fw-medium small mb-1">Toa Thuốc Kê Đơn</label>
                                <textarea name="prescription" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" rows="2" placeholder="Tên thuốc, liều lượng, cách dùng...">{{ old('prescription', $editMedicalRecord->prescription ?? '') }}</textarea>
                            </div>

                            <div class="mb-0">
                                <label class="form-label text-secondary fw-medium small mb-1">Ghi Chú Dặn Dò</label>
                                <textarea name="notes" class="form-control bg-light border-0 focus-ring focus-ring-primary py-2" rows="2" placeholder="Dặn dò tái khám, kiêng cữ...">{{ old('notes', $editMedicalRecord->notes ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top px-4 py-3 bg-light rounded-bottom-4">
                    @if($isEditing)
                        <a href="{{ route('admindoctor.medicalrecords.index') }}" class="btn btn-secondary rounded-pill px-4">Hủy Bỏ</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-medium"><i class="bi bi-save me-2"></i>Lưu Thay Đổi</button>
                    @else
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-medium"><i class="bi bi-plus-lg me-2"></i>Tạo Hồ Sơ</button>
                    @endif
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

        @if(isset($showModal) && $showModal)
        var addRecordModal = new bootstrap.Modal(document.getElementById('addRecordModal'), {
            keyboard: false
        });
        addRecordModal.show();
        @endif
    });
</script>
@endpush
@endsection
