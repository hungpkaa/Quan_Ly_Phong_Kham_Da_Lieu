@extends('layouts.admin_layout')

@section('title', 'Quản lý Bác Sĩ')

@section('content')
<div class="container-fluid py-2">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0" style="color: #0056b3; font-weight: 600;">Quản lý Bác Sĩ</h4>
            <p class="text-secondary mb-0">Thêm mới, cập nhật thông tin và lịch làm việc của bác sĩ.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> Đã xảy ra lỗi:
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row g-4">
        <!-- Form Area (Left) -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">
                        @if(isset($editDoctor)) <i class="bi bi-pencil-square me-2"></i>Sửa thông tin bác sĩ @else <i class="bi bi-plus-circle me-2"></i>Thêm bác sĩ mới @endif
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ isset($editDoctor) ? route('admin.doctors.update', $editDoctor->id) : route('admin.doctors.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-secondary" style="font-size: 14px; font-weight: 500;">Họ và Tên</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $editDoctor?->user?->name ?? '') }}" placeholder="Nhập tên bác sĩ" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary" style="font-size: 14px; font-weight: 500;">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $editDoctor?->user?->email ?? '') }}" placeholder="Nhập email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary" style="font-size: 14px; font-weight: 500;">Chuyên Khoa</label>
                            <input type="text" name="specialty" class="form-control" value="{{ old('specialty', $editDoctor->specialty ?? '') }}" placeholder="Ví dụ: Da liễu, Tim mạch..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary" style="font-size: 14px; font-weight: 500;">Số Điện Thoại</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $editDoctor?->user?->phone ?? '') }}" placeholder="Nhập số điện thoại" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary" style="font-size: 14px; font-weight: 500;">Tiểu sử / Mô tả</label>
                            <textarea name="bio" class="form-control" rows="3" placeholder="Nhập tiểu sử ngắn">{{ old('bio', $editDoctor->bio ?? '') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary" style="font-size: 14px; font-weight: 500;">Mật khẩu {{ isset($editDoctor) ? '(Để trống nếu không đổi)' : '' }}</label>
                            <input type="password" name="password" class="form-control" placeholder="Mật khẩu" {{ !isset($editDoctor) ? 'required' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary" style="font-size: 14px; font-weight: 500;">Ảnh đại diện</label>
                            <input type="file" name="image" class="form-control">
                            @if(isset($editDoctor) && $editDoctor->image)
                            <div class="mt-2">
                                <img src="{{ asset($editDoctor->image) }}" alt="Ảnh" class="img-thumbnail rounded" style="width: 80px; height: 80px; object-fit: cover;">
                            </div>
                            @endif
                        </div>
                        
                        <hr class="my-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label text-secondary mb-0" style="font-size: 14px; font-weight: 500;">Lịch làm việc</label>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addScheduleRow()">
                                <i class="bi bi-plus"></i> Thêm ca
                            </button>
                        </div>

                        <div id="schedule" class="d-flex flex-column gap-2">
                            @php
                            $workingHours = $editDoctor->working_hours ?? (old('working_hours') ?? [['day' => 'Monday', 'shift' => 'morning']]);
                            if (is_string($workingHours)) {
                                $workingHours = json_decode($workingHours, true);
                            }
                            if (empty($workingHours)) {
                                $workingHours = [['day' => 'Monday', 'shift' => 'morning']];
                            }
                            @endphp

                            @foreach ($workingHours as $index => $schedule)
                            <div class="schedule-row d-flex gap-2 align-items-center bg-light p-2 rounded">
                                <select name="working_hours[{{ $index }}][day]" class="form-select form-select-sm">
                                    <option value="Monday" {{ ($schedule['day'] ?? '') == 'Monday' ? 'selected' : '' }}>Thứ Hai</option>
                                    <option value="Tuesday" {{ ($schedule['day'] ?? '') == 'Tuesday' ? 'selected' : '' }}>Thứ Ba</option>
                                    <option value="Wednesday" {{ ($schedule['day'] ?? '') == 'Wednesday' ? 'selected' : '' }}>Thứ Tư</option>
                                    <option value="Thursday" {{ ($schedule['day'] ?? '') == 'Thursday' ? 'selected' : '' }}>Thứ Năm</option>
                                    <option value="Friday" {{ ($schedule['day'] ?? '') == 'Friday' ? 'selected' : '' }}>Thứ Sáu</option>
                                    <option value="Saturday" {{ ($schedule['day'] ?? '') == 'Saturday' ? 'selected' : '' }}>Thứ Bảy</option>
                                    <option value="Sunday" {{ ($schedule['day'] ?? '') == 'Sunday' ? 'selected' : '' }}>Chủ Nhật</option>
                                </select>
                                <select name="working_hours[{{ $index }}][shift]" class="form-select form-select-sm">
                                    <option value="morning" {{ ($schedule['shift'] ?? '') == 'morning' ? 'selected' : '' }}>08:00 - 12:00</option>
                                    <option value="afternoon" {{ ($schedule['shift'] ?? '') == 'afternoon' ? 'selected' : '' }}>14:00 - 18:00</option>
                                </select>
                                @if(count($workingHours) > 1 || $index > 0)
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeScheduleRow(this)"><i class="bi bi-trash"></i></button>
                                @endif
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            @if(isset($editDoctor))
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100 py-2">Lưu Thay Đổi</button>
                                <a href="{{ route('admin.doctors.index') }}" class="btn btn-light w-100 py-2 border">Hủy</a>
                            </div>
                            @else
                            <button type="submit" class="btn btn-primary w-100 py-2">Thêm Bác Sĩ Mới</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Area (Right) -->
        <div class="col-xl-8 col-lg-7">
            <div class="card border shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">Danh sách Bác sĩ ({{ count($doctors) }})</h6>
                    
                    <form method="GET" action="{{ route('admin.doctors.index') }}" class="d-flex" style="width: 280px;">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control bg-light" placeholder="Tìm tên, chuyên môn..." value="{{ $search ?? '' }}">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary" style="font-size: 13px; text-transform: uppercase;">
                                <tr>
                                    <th class="ps-4">Thông tin bác sĩ</th>
                                    <th>Liên hệ</th>
                                    <th>Chuyên môn</th>
                                    <th class="text-end pe-4">Hành động</th>
                                </tr>
                            </thead>
                            <tbody style="border-top: 0;">
                                @forelse($doctors as $doctor)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            @if($doctor->image)
                                            <img src="{{ asset($doctor->image) }}" alt="{{ optional($doctor->user)->name }}" class="rounded-circle object-fit-cover shadow-sm border" style="width: 44px; height: 44px;">
                                            @else
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold shadow-sm border" style="width: 44px; height: 44px;">
                                                {{ substr(optional($doctor->user)->name, 0, 1) }}
                                            </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark">{{ optional($doctor->user)->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column text-secondary" style="font-size: 13px;">
                                            <span class="mb-1"><i class="bi bi-telephone me-2 text-muted"></i>{{ optional($doctor->user)->phone }}</span>
                                            <span><i class="bi bi-envelope me-2 text-muted"></i>{{ optional($doctor->user)->email }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1" style="font-weight: 500;">{{ $doctor->specialty }}</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm shadow-sm">
                                            <a href="{{ route('admin.doctors.index', ['edit_id' => $doctor->id]) }}" class="btn btn-light border text-primary" title="Sửa thông tin">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.doctors.destroy', $doctor->id) }}" class="d-inline-block m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-light border text-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa bác sĩ {{ optional($doctor->user)->name }} không?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-secondary">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                                        Chưa có bác sĩ nào hoặc không tìm thấy kết quả.
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
@endsection

@push('scripts')
<script>
    function addScheduleRow() {
        let container = document.getElementById('schedule');
        let index = container.querySelectorAll('.schedule-row').length;
        
        let row = document.createElement('div');
        row.className = 'schedule-row d-flex gap-2 align-items-center bg-light p-2 rounded mt-2';
        
        row.innerHTML = `
            <select name="working_hours[${index}][day]" class="form-select form-select-sm">
                <option value="Monday">Thứ Hai</option>
                <option value="Tuesday">Thứ Ba</option>
                <option value="Wednesday">Thứ Tư</option>
                <option value="Thursday">Thứ Năm</option>
                <option value="Friday">Thứ Sáu</option>
                <option value="Saturday">Thứ Bảy</option>
                <option value="Sunday">Chủ Nhật</option>
            </select>
            <select name="working_hours[${index}][shift]" class="form-select form-select-sm">
                <option value="morning">08:00 - 12:00</option>
                <option value="afternoon">14:00 - 18:00</option>
            </select>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeScheduleRow(this)"><i class="bi bi-trash"></i></button>
        `;
        
        container.appendChild(row);
    }

    function removeScheduleRow(button) {
        button.closest('.schedule-row').remove();
    }
</script>
@endpush
