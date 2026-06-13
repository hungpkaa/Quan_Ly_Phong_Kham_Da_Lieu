@extends('layouts.admin_layout')

@section('title', 'Quản lý Hồ sơ Bệnh án')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1" style="color: #0056b3; font-weight: 600;">Hồ sơ Bệnh án</h3>
                <p class="text-secondary mb-0">Quản lý và cập nhật thông tin khám bệnh, chẩn đoán, toa thuốc của bệnh nhân.</p>
            </div>
            
            <!-- Nút Thêm Mới -->
            <div>
                <a href="{{ route('admin.medicalrecords.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm" style="font-weight: 500;">
                    <i class="bi bi-plus-lg me-1"></i> Thêm Hồ sơ Mới
                </a>
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

    <div class="row g-4">
        <!-- Khu vực Thêm / Sửa Hồ sơ -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4">
                    <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">
                        <i class="bi {{ isset($editMedicalRecord) ? 'bi-pencil-square' : 'bi-file-earmark-plus' }} me-2"></i>
                        {{ isset($editMedicalRecord) ? 'Cập nhật Hồ sơ' : 'Tạo Hồ sơ Mới' }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    @if(isset($editMedicalRecord))
                    <form method="POST" action="{{ route('admin.medicalrecords.update', $editMedicalRecord->id) }}">
                        @csrf
                        @method('PUT')
                    @else
                    <form method="POST" action="{{ route('admin.medicalrecords.store') }}">
                        @csrf
                    @endif
                        <div class="row g-3">
                            <!-- Bác sĩ -->
                            <div class="col-12">
                                <label class="form-label text-secondary fw-medium small mb-1">Bác sĩ phụ trách <span class="text-danger">*</span></label>
                                <select name="doctor_id" class="form-select bg-light border-0 focus-ring focus-ring-primary" required>
                                    <option value="">-- Chọn bác sĩ --</option>
                                    @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ (isset($editMedicalRecord) && $editMedicalRecord->doctor_id == $doctor->id) ? 'selected' : '' }}>
                                        BS. {{ $doctor->user?->name }} ({{ $doctor->specialty }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Bệnh nhân -->
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-medium small mb-1">Tên Bệnh nhân <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control bg-light border-0 focus-ring focus-ring-primary" value="{{ $editMedicalRecord?->user?->name ?? '' }}" placeholder="Nhập họ và tên" required>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label text-secondary fw-medium small mb-1">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control bg-light border-0 focus-ring focus-ring-primary" value="{{ $editMedicalRecord?->user?->phone ?? '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary fw-medium small mb-1">Tuổi <span class="text-danger">*</span></label>
                                    <input type="number" name="age" class="form-control bg-light border-0 focus-ring focus-ring-primary" value="{{ $editMedicalRecord?->user?->age ?? '' }}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-medium small mb-1">Căn cước công dân (CCCD) <span class="text-danger">*</span></label>
                                <input type="text" name="cccd" class="form-control bg-light border-0 focus-ring focus-ring-primary" value="{{ $editMedicalRecord?->user?->cccd ?? '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-medium small mb-1">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control bg-light border-0 focus-ring focus-ring-primary" value="{{ $editMedicalRecord?->user?->email ?? '' }}" required>
                            </div>

                            <!-- Thông tin khám bệnh -->
                            <hr class="my-3 text-secondary opacity-25">

                            <div class="col-12">
                                <label class="form-label text-secondary fw-medium small mb-1">Dịch vụ sử dụng</label>
                                <input type="text" name="service" class="form-control bg-light border-0 focus-ring focus-ring-primary" value="{{ $editMedicalRecord->service ?? '' }}" placeholder="VD: Khám tổng quát...">
                            </div>

                            <div class="col-6">
                                <label class="form-label text-secondary fw-medium small mb-1">Ngày khám <span class="text-danger">*</span></label>
                                <input type="date" name="exam_date" class="form-control bg-light border-0 focus-ring focus-ring-primary" value="{{ $editMedicalRecord->exam_date ?? date('Y-m-d') }}" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label text-secondary fw-medium small mb-1">Tái khám</label>
                                <input type="date" name="follow_up_date" class="form-control bg-light border-0 focus-ring focus-ring-primary" value="{{ $editMedicalRecord->follow_up_date ?? '' }}">
                            </div>

                            <!-- Chẩn đoán & Toa thuốc -->
                            <div class="col-12">
                                <label class="form-label text-secondary fw-medium small mb-1">Chẩn đoán <span class="text-danger">*</span></label>
                                <textarea name="diagnosis" class="form-control bg-light border-0 focus-ring focus-ring-primary" rows="2" required placeholder="Nhập chẩn đoán của bác sĩ...">{{ $editMedicalRecord->diagnosis ?? '' }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-secondary fw-medium small mb-1">Toa thuốc</label>
                                <textarea name="prescription" class="form-control bg-light border-0 focus-ring focus-ring-primary" rows="2" placeholder="Liệt kê các thuốc được kê đơn...">{{ $editMedicalRecord->prescription ?? '' }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-secondary fw-medium small mb-1">Ghi chú thêm</label>
                                <textarea name="notes" class="form-control bg-light border-0 focus-ring focus-ring-primary" rows="2">{{ $editMedicalRecord->notes ?? '' }}</textarea>
                            </div>

                            <!-- Chi phí & Thanh toán -->
                            <hr class="my-3 text-secondary opacity-25">

                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-medium small mb-1">Chi phí (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" step="any" name="cost" class="form-control bg-light border-0 focus-ring focus-ring-primary" value="{{ isset($editMedicalRecord) ? $editMedicalRecord->cost : old('cost') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-medium small mb-1">Thanh toán <span class="text-danger">*</span></label>
                                <select name="status" class="form-select bg-light border-0 focus-ring focus-ring-primary" required>
                                    <option value="unpaid" {{ (isset($editMedicalRecord) && $editMedicalRecord->status == 'unpaid') ? 'selected' : '' }}>Chưa thanh toán</option>
                                    <option value="paid" {{ (isset($editMedicalRecord) && $editMedicalRecord->status == 'paid') ? 'selected' : '' }}>Đã thanh toán</option>
                                </select>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn {{ isset($editMedicalRecord) ? 'btn-warning text-dark' : 'btn-primary' }} w-100 rounded-pill fw-medium py-2 shadow-sm">
                                    {{ isset($editMedicalRecord) ? 'Lưu Thay Đổi' : 'Tạo Hồ Sơ' }}
                                </button>
                                @if(isset($editMedicalRecord))
                                <a href="{{ route('admin.medicalrecords.index') }}" class="btn btn-light w-100 rounded-pill fw-medium py-2 mt-2">Hủy Bỏ</a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Khu vực Danh sách Hồ sơ -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">
                        <i class="bi bi-card-list me-2"></i> Danh sách Hồ sơ
                    </h6>
                    <!-- Form Tìm kiếm trong Card -->
                    <form method="GET" action="{{ route('admin.medicalrecords.index') }}" class="m-0" style="width: 250px;">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-0 rounded-start-pill text-secondary">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control bg-light border-0 rounded-end-pill focus-ring focus-ring-light" placeholder="Tìm tên/SĐT..." value="{{ $search ?? '' }}">
                        </div>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4 py-3 text-secondary fw-medium small" style="border-top-left-radius: 8px;">Thông tin Bệnh nhân</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small">Bác sĩ phụ trách</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small">Ngày khám</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small">Chi phí</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small">Trạng thái</th>
                                    <th class="border-0 px-4 py-3 text-secondary fw-medium small text-end" style="border-top-right-radius: 8px;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($medicalRecords as $record)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-semibold text-dark">{{ optional($record->user)->name }}</div>
                                        <div class="text-secondary small"><i class="bi bi-telephone-fill me-1 opacity-50"></i>{{ optional($record->user)->phone }}</div>
                                        <div class="text-secondary small" style="font-size: 11px;">CCCD: {{ optional($record->user)->cccd }}</div>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-dark fw-medium">{{ optional(optional($record->doctor)->user)->name ?? '---' }}</div>
                                        <div class="text-secondary small">{{ optional($record->doctor)->specialty ?? '' }}</div>
                                    </td>
                                    <td class="py-3 text-dark">
                                        {{ \Carbon\Carbon::parse($record->exam_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 text-dark fw-medium">
                                        {{ number_format($record->cost, 0, ',', '.') }}đ
                                    </td>
                                    <td class="py-3">
                                        @if($record->status === 'unpaid')
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle rounded-pill px-3 py-2 fw-medium">Chưa thanh toán</span>
                                        @else
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill px-3 py-2 fw-medium">Đã thanh toán</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <!-- Nút Sửa -->
                                            <a href="{{ route('admin.medicalrecords.index', ['edit_id' => $record->id]) }}" class="btn btn-sm btn-light border text-primary rounded-circle" data-bs-toggle="tooltip" title="Chỉnh sửa">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <!-- Nút Xem nhanh (Modal) -->
                                            <button type="button" class="btn btn-sm btn-light border text-info rounded-circle" data-bs-toggle="modal" data-bs-target="#viewModal{{ $record->id }}" title="Xem chi tiết">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <!-- Nút Xóa -->
                                            <form method="POST" action="{{ route('admin.medicalrecords.destroy', $record->id) }}" class="m-0" onsubmit="return confirm('Bạn có chắc chắn muốn xóa hồ sơ của {{ optional($record->user)->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border text-danger rounded-circle" data-bs-toggle="tooltip" title="Xóa">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <!-- Modal Xem Chi Tiết -->
                                        <div class="modal fade" id="viewModal{{ $record->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $record->id }}" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow rounded-4 text-start">
                                              <div class="modal-header border-bottom-0 pb-0">
                                                <h5 class="modal-title fw-bold text-primary" id="viewModalLabel{{ $record->id }}">Chi tiết Hồ sơ</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                              </div>
                                              <div class="modal-body pt-3 pb-4">
                                                <div class="p-3 bg-light rounded-3 mb-3">
                                                    <h6 class="fw-bold mb-2">{{ optional($record->user)->name }}</h6>
                                                    <div class="row g-2 text-secondary small">
                                                        <div class="col-6"><i class="bi bi-telephone me-1"></i> {{ optional($record->user)->phone }}</div>
                                                        <div class="col-6"><i class="bi bi-envelope me-1"></i> {{ optional($record->user)->email }}</div>
                                                        <div class="col-6"><i class="bi bi-person-badge me-1"></i> {{ optional($record->user)->age }} tuổi</div>
                                                        <div class="col-6"><i class="bi bi-credit-card-2-front me-1"></i> {{ optional($record->user)->cccd }}</div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <span class="text-secondary small fw-medium">Chẩn đoán:</span>
                                                    <p class="mb-0 text-dark">{{ $record->diagnosis }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <span class="text-secondary small fw-medium">Toa thuốc:</span>
                                                    <p class="mb-0 text-dark">{{ $record->prescription ?: 'Không có' }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <span class="text-secondary small fw-medium">Dịch vụ:</span>
                                                    <p class="mb-0 text-dark">{{ $record->service ?: 'Không có' }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <span class="text-secondary small fw-medium">Tái khám:</span>
                                                    <p class="mb-0 text-danger fw-medium">{{ $record->follow_up_date ? \Carbon\Carbon::parse($record->follow_up_date)->format('d/m/Y') : 'Không hẹn tái khám' }}</p>
                                                </div>
                                                <div>
                                                    <span class="text-secondary small fw-medium">Ghi chú:</span>
                                                    <p class="mb-0 text-dark">{{ $record->notes ?: 'Không có' }}</p>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Modal -->
                                        
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-secondary opacity-50 mb-3"><i class="bi bi-inbox fs-1"></i></div>
                                        <p class="mb-0">Chưa có hồ sơ bệnh án nào.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Phân trang -->
                @if($medicalRecords->hasPages())
                <div class="card-footer bg-white border-top py-3 px-4 rounded-bottom-4">
                    <div class="d-flex justify-content-center pagination-sm">
                        {{ $medicalRecords->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                @endif
                
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // === Validate: Tái khám phải sau Ngày khám ===
    document.addEventListener("DOMContentLoaded", function() {
        const examDateInput = document.querySelector('input[name="exam_date"]');
        const followUpInput = document.querySelector('input[name="follow_up_date"]');

        if (examDateInput && followUpInput) {
            function updateFollowUpMin() {
                const examDate = examDateInput.value;
                if (examDate) {
                    const nextDay = new Date(examDate);
                    nextDay.setDate(nextDay.getDate() + 1);
                    followUpInput.min = nextDay.toISOString().split('T')[0];
                    if (followUpInput.value && followUpInput.value <= examDate) {
                        followUpInput.value = '';
                    }
                }
            }
            examDateInput.addEventListener('change', updateFollowUpMin);
            updateFollowUpMin();

            const form = examDateInput.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (followUpInput.value && examDateInput.value && followUpInput.value <= examDateInput.value) {
                        e.preventDefault();
                        alert('Ngày tái khám phải sau ngày khám!');
                        followUpInput.focus();
                    }
                });
            }
        }
    });
</script>
@endpush
@endsection
