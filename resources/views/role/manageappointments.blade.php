@extends('layouts.admin_layout')

@section('title', 'Quản lý Lịch Hẹn')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1" style="color: #0056b3; font-weight: 600;">Quản lý Lịch Hẹn</h3>
                <p class="text-secondary mb-0">Duyệt, chỉnh sửa hoặc thêm lịch hẹn mới cho bệnh nhân.</p>
            </div>
            <!-- Nút Thêm Mới -->
            <div>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm" style="font-weight: 500;">
                    <i class="bi bi-calendar-plus me-1"></i> Tạo Lịch Hẹn
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
        <!-- Form Thêm / Sửa -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4">
                    <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">
                        <i class="bi {{ isset($editAppointment) ? 'bi-pencil-square' : 'bi-calendar-plus' }} me-2"></i>
                        {{ isset($editAppointment) ? 'Cập nhật Lịch hẹn' : 'Thêm Lịch hẹn Mới' }}
                    </h6>
                </div>
                <div class="card-body p-4">
                    @if($editAppointment)
                    <form method="POST" action="{{ route('admin.appointments.update', $editAppointment->id) }}">
                        @csrf
                    @else
                    <form method="POST" action="{{ route('admin.appointments.store') }}">
                        @csrf
                    @endif
                        <div class="row g-3">
                            <!-- Chuyên môn / Dịch vụ -->
                            <div class="col-12">
                                <label class="form-label text-secondary fw-medium small mb-1">Chuyên Môn/Dịch Vụ <span class="text-danger">*</span></label>
                                <select name="specialty" id="specialty" class="form-select bg-light border-0 focus-ring focus-ring-primary" required>
                                    <option value="">-- Chọn Dịch Vụ --</option>
                                    @foreach($specialties as $specialty)
                                    <option value="{{ $specialty }}" {{ (isset($editAppointment) && $editAppointment->specialty == $specialty) ? 'selected' : '' }}>
                                        {{ $specialty }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Bác sĩ -->
                            <div class="col-12">
                                <label class="form-label text-secondary fw-medium small mb-1">Bác sĩ phụ trách <span class="text-danger">*</span></label>
                                <select name="doctor_id" id="doctor_id" class="form-select bg-light border-0 focus-ring focus-ring-primary" required>
                                    @if(isset($editAppointment))
                                        <option value="{{ $editAppointment->doctor_id }}">{{ optional(optional($editAppointment->doctor)->user)->name }}</option>
                                    @else
                                        <option value="">-- Chọn mục Dịch Vụ trước --</option>
                                    @endif
                                </select>
                            </div>

                            <hr class="my-3 text-secondary opacity-25">

                            <!-- Bệnh nhân -->
                            <div class="col-12">
                                <label class="form-label text-secondary fw-medium small mb-1">Tên bệnh nhân <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control bg-light border-0 focus-ring focus-ring-primary" value="{{ $editAppointment->name ?? '' }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-medium small mb-1">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="phone" class="form-control bg-light border-0 focus-ring focus-ring-primary" value="{{ $editAppointment->phone ?? '' }}" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-medium small mb-1">Tuổi <span class="text-danger">*</span></label>
                                <input type="number" name="age" id="age" class="form-control bg-light border-0 focus-ring focus-ring-primary" value="{{ $editAppointment->age ?? '' }}" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label text-secondary fw-medium small mb-1">CCCD <span class="text-danger">*</span></label>
                                <input type="text" name="cccd" id="cccd" class="form-control bg-light border-0 focus-ring focus-ring-primary" value="{{ $editAppointment->cccd ?? '' }}" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label text-secondary fw-medium small mb-1">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control bg-light border-0 focus-ring focus-ring-primary" value="{{ $editAppointment->email ?? '' }}" required>
                            </div>

                            <hr class="my-3 text-secondary opacity-25">

                            <!-- Thời gian -->
                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-medium small mb-1">Ngày hẹn <span class="text-danger">*</span></label>
                                <input type="date" name="appointment_date" id="appointment_date" class="form-control bg-light border-0 focus-ring focus-ring-primary" value="{{ $editAppointment->appointment_date ?? '' }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-secondary fw-medium small mb-1">Ca làm việc <span class="text-danger">*</span></label>
                                <select name="shift" id="shift" class="form-select bg-light border-0 focus-ring focus-ring-primary" required>
                                    @if(isset($editAppointment))
                                        <option value="morning" {{ $editAppointment->shift == 'morning' ? 'selected' : '' }}>08:00 - 12:00</option>
                                        <option value="afternoon" {{ $editAppointment->shift == 'afternoon' ? 'selected' : '' }}>14:00 - 18:00</option>
                                    @else
                                        <option value="">-- Chọn ngày trước --</option>
                                    @endif
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label text-secondary fw-medium small mb-1">Mô tả bệnh lý</label>
                                <textarea name="description" id="description" rows="2" class="form-control bg-light border-0 focus-ring focus-ring-primary">{{ $editAppointment->description ?? '' }}</textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn {{ isset($editAppointment) ? 'btn-warning text-dark' : 'btn-primary' }} w-100 rounded-pill fw-medium py-2 shadow-sm">
                                    {{ isset($editAppointment) ? 'Lưu Thay Đổi' : 'Tạo Lịch Hẹn' }}
                                </button>
                                @if(isset($editAppointment))
                                <a href="{{ route('admin.appointments.index') }}" class="btn btn-light w-100 rounded-pill fw-medium py-2 mt-2">Hủy Bỏ</a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bảng Dữ Liệu Lịch Hẹn -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">
                        <i class="bi bi-card-checklist me-2"></i> Danh sách Lịch hẹn
                    </h6>
                    <!-- Form Tìm kiếm -->
                    <form method="GET" action="{{ route('admin.appointments.index') }}" class="m-0" style="width: 250px;">
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
                                    <th class="border-0 py-3 text-secondary fw-medium small">Thời gian</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small text-center">Trạng thái</th>
                                    <th class="border-0 px-4 py-3 text-secondary fw-medium small text-end" style="border-top-right-radius: 8px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $appointment)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="fw-semibold text-dark">{{ optional($appointment->user)->name }}</div>
                                        <div class="text-secondary small"><i class="bi bi-telephone-fill me-1 opacity-50"></i>{{ optional($appointment->user)->phone }}</div>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-dark fw-medium">{{ optional(optional($appointment->doctor)->user)->name ?? '---' }}</div>
                                        <div class="text-secondary small">{{ optional($appointment->doctor)->specialty ?? '' }}</div>
                                    </td>
                                    <td class="py-3 text-dark">
                                        <div class="fw-medium">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</div>
                                        @if($appointment->shift === 'morning')
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle rounded-pill">Sáng (08:00 - 12:00)</span>
                                        @elseif($appointment->shift === 'afternoon')
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info-subtle rounded-pill">Chiều (14:00 - 18:00)</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle rounded-pill">Không XĐ</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-center">
                                        @if($appointment->status === 'pending')
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle rounded-pill px-3 py-2 fw-medium">Chờ duyệt</span>
                                        @elseif($appointment->status === 'approved')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill px-3 py-2 fw-medium">Đã duyệt</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle rounded-pill px-3 py-2 fw-medium">Đã từ chối</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            @if($appointment->status === 'pending')
                                            <!-- Duyệt -->
                                            <form method="POST" action="{{ route('admin.appointments.approve', $appointment->id) }}" class="m-0">
                                                @csrf @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-light border text-success rounded-circle" data-bs-toggle="tooltip" title="Duyệt lịch">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <!-- Từ chối -->
                                            <form method="POST" action="{{ route('admin.appointments.reject', $appointment->id) }}" class="m-0">
                                                @csrf @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-light border text-danger rounded-circle" data-bs-toggle="tooltip" title="Từ chối">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                            @endif
                                            
                                            <!-- Nút Xem nhanh (Modal) -->
                                            <button type="button" class="btn btn-sm btn-light border text-info rounded-circle" data-bs-toggle="modal" data-bs-target="#viewModal{{ $appointment->id }}" title="Xem chi tiết">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            <!-- Sửa -->
                                            <a href="{{ route('admin.appointments.index', ['edit_id' => $appointment->id]) }}" class="btn btn-sm btn-light border text-primary rounded-circle" data-bs-toggle="tooltip" title="Sửa">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <!-- Xóa -->
                                            <form method="POST" action="{{ route('admin.appointments.destroy', $appointment->id) }}" class="m-0" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lịch hẹn này?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border text-danger rounded-circle" data-bs-toggle="tooltip" title="Xóa">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Modal Xem Chi Tiết -->
                                        <div class="modal fade" id="viewModal{{ $appointment->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $appointment->id }}" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow rounded-4 text-start">
                                              <div class="modal-header border-bottom-0 pb-0">
                                                <h5 class="modal-title fw-bold text-primary" id="viewModalLabel{{ $appointment->id }}">Chi tiết Lịch hẹn</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                              </div>
                                              <div class="modal-body pt-3 pb-4">
                                                <div class="p-3 bg-light rounded-3 mb-3">
                                                    <h6 class="fw-bold mb-2">{{ optional($appointment->user)->name }}</h6>
                                                    <div class="row g-2 text-secondary small">
                                                        <div class="col-6"><i class="bi bi-telephone me-1"></i> {{ optional($appointment->user)->phone }}</div>
                                                        <div class="col-6"><i class="bi bi-envelope me-1"></i> {{ optional($appointment->user)->email }}</div>
                                                        <div class="col-6"><i class="bi bi-person-badge me-1"></i> {{ optional($appointment->user)->age }} tuổi</div>
                                                        <div class="col-6"><i class="bi bi-credit-card-2-front me-1"></i> {{ optional($appointment->user)->cccd }}</div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <span class="text-secondary small fw-medium">Bác sĩ:</span>
                                                    <p class="mb-0 text-dark">{{ optional(optional($appointment->doctor)->user)->name ?? '---' }} ({{ optional($appointment->doctor)->specialty ?? '---' }})</p>
                                                </div>
                                                <div class="mb-3">
                                                    <span class="text-secondary small fw-medium">Thời gian:</span>
                                                    <p class="mb-0 text-dark">Ngày {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }} - {{ $appointment->shift == 'morning' ? 'Sáng' : 'Chiều' }}</p>
                                                </div>
                                                <div>
                                                    <span class="text-secondary small fw-medium">Mô tả bệnh lý:</span>
                                                    <p class="mb-0 text-dark">{{ $appointment->description ?: 'Không có' }}</p>
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
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-secondary opacity-50 mb-3"><i class="bi bi-calendar-x fs-1"></i></div>
                                        <p class="mb-0">Không có lịch hẹn nào.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // AJAX cho Bác Sĩ
    $(document).ready(function() {
        $('#specialty').change(function() {
            var specialty = $(this).val();
            $('#doctor_id').html('<option value="">-- Đang tải... --</option>');

            if (specialty) {
                $.ajax({
                    url: '/get-doctors-by-specialty',
                    type: 'GET',
                    data: { specialty: specialty },
                    success: function(data) {
                        $('#doctor_id').html('<option value="">-- Chọn Bác Sĩ --</option>');
                        $.each(data, function(index, doctor) {
                            $('#doctor_id').append('<option value="' + doctor.id + '">' + doctor.name + '</option>');
                        });
                    },
                    error: function() {
                        $('#doctor_id').html('<option value="">-- Lỗi --</option>');
                    }
                });
            } else {
                $('#doctor_id').html('<option value="">-- Chọn mục Dịch Vụ trước --</option>');
            }
        });

        // AJAX cho Ca làm việc
        $('#appointment_date').change(function() {
            var selectedDate = $(this).val();
            var doctorId = $('#doctor_id').val();
            $('#shift').html('<option value="">-- Đang tải... --</option>');

            if (selectedDate && doctorId) {
                $.ajax({
                    url: '/get-working-hours',
                    type: 'GET',
                    data: { doctor_id: doctorId, date: selectedDate },
                    success: function(data) {
                        $('#shift').html('<option value="">-- Chọn Ca --</option>');
                        if (data.morning || data.afternoon) {
                            if (data.morning) $('#shift').append('<option value="morning">08:00 - 12:00</option>');
                            if (data.afternoon) $('#shift').append('<option value="afternoon">14:00 - 18:00</option>');
                        } else {
                            $('#shift').html('<option value="">-- Không có ca --</option>');
                        }
                    },
                    error: function() {
                        $('#shift').html('<option value="">-- Lỗi --</option>');
                    }
                });
            } else {
                $('#shift').html('<option value="">-- Chọn ngày trước --</option>');
            }
        });
    });
</script>
@endpush
@endsection
