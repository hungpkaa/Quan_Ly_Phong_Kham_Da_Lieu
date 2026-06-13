@extends('layouts.admin_layout')

@section('title', 'Lịch Làm Việc')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Page -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-1" style="color: #0056b3; font-weight: 600;">Quản lý Lịch Làm Việc</h3>
            <p class="text-secondary mb-0">Theo dõi và cập nhật lịch trực của bác sĩ theo từng chuyên môn.</p>
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
        <!-- Bảng Tổng Hợp Lịch Trực -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">
                        <i class="bi bi-calendar3-week me-2"></i> Tổng Hợp Lịch Trực Trong Tuần
                    </h6>
                    <button class="btn btn-sm btn-outline-primary rounded-pill" onclick="document.getElementById('loaddoctor').scrollIntoView({behavior: 'smooth'})">
                        Chỉnh sửa lịch <i class="bi bi-arrow-down-short"></i>
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0 text-center" style="min-width: 1000px;">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 text-secondary fw-medium small bg-light" style="width: 250px;">Bác sĩ</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small bg-light">Thứ 2</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small bg-light">Thứ 3</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small bg-light">Thứ 4</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small bg-light">Thứ 5</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small bg-light">Thứ 6</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small bg-light">Thứ 7</th>
                                    <th class="border-0 py-3 text-secondary fw-medium small bg-light">Chủ Nhật</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                    $shiftLabels = ['morning' => 'Sáng (08-12h)', 'afternoon' => 'Chiều (14-18h)'];
                                @endphp

                                @forelse($specialtyGroups as $specialty => $doctorspecialty)
                                    @php
                                        // Khởi tạo biến kiểm tra ca trực trống
                                        $specialtyShiftEmpty = [];
                                        foreach ($weekdays as $day) {
                                            $specialtyShiftEmpty[$day] = ['morning' => true, 'afternoon' => true];
                                        }
                                    @endphp

                                    <!-- Tiêu đề chuyên môn -->
                                    <tr class="bg-primary bg-opacity-10 text-start">
                                        <td colspan="8" class="py-2 px-4 border-0">
                                            <span class="fw-bold text-primary"><i class="bi bi-bookmark-star-fill me-2"></i>Chuyên môn: {{ $specialty }}</span>
                                        </td>
                                    </tr>

                                    <!-- Danh sách bác sĩ trong chuyên môn -->
                                    @foreach($doctorspecialty as $doctor)
                                        <tr>
                                            <td class="text-start px-4">
                                                <div class="d-flex align-items-center">
                                                    @if($doctor->image)
                                                        <img src="{{ asset($doctor->image) }}" class="rounded-circle shadow-sm me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-light d-flex justify-content-center align-items-center text-secondary shadow-sm me-3" style="width: 40px; height: 40px;">
                                                            <i class="bi bi-person-fill"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-semibold text-dark">{{ optional($doctor->user)->name }}</div>
                                                        <div class="text-secondary small" style="font-size: 11px;"><i class="bi bi-telephone-fill me-1"></i>{{ optional($doctor->user)->phone }}</div>
                                                    </div>
                                                </div>
                                            </td>

                                            @php $working_hours = $doctor->working_hours ?? []; @endphp

                                            @foreach($weekdays as $day)
                                                <td class="align-middle">
                                                    @php
                                                        $shifts = collect($working_hours)->where('day', $day)->pluck('shift')->toArray();
                                                    @endphp
                                                    
                                                    @if(!empty($shifts))
                                                        <div class="d-flex flex-column gap-1 align-items-center">
                                                        @foreach($shifts as $shift)
                                                            <span class="badge {{ $shift == 'morning' ? 'bg-info text-dark' : 'bg-warning text-dark' }} border rounded-pill px-2" style="font-size: 11px;">
                                                                {{ $shiftLabels[$shift] }}
                                                            </span>
                                                            @php $specialtyShiftEmpty[$day][$shift] = false; @endphp
                                                        @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-secondary opacity-25">-</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach

                                    {{-- Cảnh báo ca trống --}}
                                    @foreach($specialtyShiftEmpty as $day => $shifts)
                                        @foreach($shifts as $shift => $isEmpty)
                                            @if($isEmpty && $day != 'Sunday') {{-- Bỏ qua Chủ Nhật --}}
                                                <tr class="bg-danger bg-opacity-10">
                                                    <td colspan="8" class="py-1 text-danger small">
                                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> <strong>Cảnh báo:</strong> Chuyên môn "{{ $specialty }}" đang trống ca trực vào <strong>{{ __("Thứ " . (array_search($day, $weekdays) + 2)) }} - {{ $shiftLabels[$shift] }}</strong>!
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach

                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-secondary">
                                            Chưa có dữ liệu lịch làm việc.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chỉnh Sửa Lịch Làm Việc -->
        <div class="col-12" id="loaddoctor">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4">
                    <h6 class="mb-0" style="color: #0056b3; font-weight: 600;">
                        <i class="bi bi-pencil-square me-2"></i> Phân Ca Lịch Làm Việc
                    </h6>
                </div>
                <div class="card-body p-4">
                    
                    <div class="row g-4">
                        <!-- Cột Chọn Bác Sĩ -->
                        <div class="col-lg-4">
                            <label for="doctorSelect" class="form-label text-secondary fw-medium small mb-1">Chọn bác sĩ để chỉnh sửa <span class="text-danger">*</span></label>
                            <select id="doctorSelect" class="form-select bg-light border-0 focus-ring focus-ring-primary py-2 mb-4" onchange="loadDoctorSchedule()">
                                <option value="">-- Chọn bác sĩ --</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ isset($selectedDoctor) && $selectedDoctor->id == $doctor->id ? 'selected' : '' }}>
                                        BS. {{ optional($doctor->user)->name }} ({{ $doctor->specialty }})
                                    </option>
                                @endforeach
                            </select>

                            @if(isset($selectedDoctor))
                            <!-- Thông tin tóm tắt -->
                            <div class="p-3 bg-light rounded-4 text-center border">
                                @if($selectedDoctor->image)
                                    <img src="{{ asset($selectedDoctor->image) }}" class="rounded-circle shadow-sm mb-3" style="width: 90px; height: 90px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-white d-flex justify-content-center align-items-center text-secondary shadow-sm mb-3 mx-auto" style="width: 90px; height: 90px;">
                                        <i class="bi bi-person-fill fs-1"></i>
                                    </div>
                                @endif
                                <h6 class="fw-bold mb-1">{{ $selectedDoctor?->user?->name }}</h6>
                                <p class="text-primary small mb-1">{{ $selectedDoctor->specialty }}</p>
                                <p class="text-secondary small mb-0"><i class="bi bi-telephone me-1"></i>{{ $selectedDoctor?->user?->phone }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Cột Cập Nhật Ca Trực -->
                        <div class="col-lg-8">
                            @if(isset($selectedDoctor))
                            <form method="POST" action="{{ route('admin.updateSchedule', $selectedDoctor->id) }}" id="updateScheduleForm">
                                @csrf
                                <div class="card border-primary border-opacity-25 rounded-4 shadow-sm h-100">
                                    <div class="card-header bg-primary bg-opacity-10 border-bottom-0 py-3 px-4 d-flex justify-content-between align-items-center rounded-top-4">
                                        <span class="fw-semibold text-primary">Danh sách ca trực hiện tại</span>
                                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm" onclick="addScheduleRow()">
                                            <i class="bi bi-plus-lg me-1"></i>Thêm Ca
                                        </button>
                                    </div>
                                    <div class="card-body bg-white" id="scheduleContainer" style="min-height: 200px;">
                                        @php
                                            $workingHours = is_array($selectedDoctor->working_hours) ? $selectedDoctor->working_hours :
                                                json_decode($selectedDoctor->working_hours, true) ?? [];
                                        @endphp

                                        @if(empty($workingHours))
                                            <p class="text-center text-secondary mt-4" id="emptyScheduleMsg">Bác sĩ này chưa có ca trực nào. Hãy bấm "Thêm Ca" để tạo lịch.</p>
                                        @endif

                                        @foreach ($workingHours as $index => $schedule)
                                            <div class="schedule-row row g-2 align-items-center mb-3">
                                                <div class="col-sm-5">
                                                    <select name="working_hours[{{ $index }}][day]" class="form-select bg-light border-0 focus-ring focus-ring-primary">
                                                        <option value="Monday" {{ $schedule['day'] == 'Monday' ? 'selected' : '' }}>Thứ Hai</option>
                                                        <option value="Tuesday" {{ $schedule['day'] == 'Tuesday' ? 'selected' : '' }}>Thứ Ba</option>
                                                        <option value="Wednesday" {{ $schedule['day'] == 'Wednesday' ? 'selected' : '' }}>Thứ Tư</option>
                                                        <option value="Thursday" {{ $schedule['day'] == 'Thursday' ? 'selected' : '' }}>Thứ Năm</option>
                                                        <option value="Friday" {{ $schedule['day'] == 'Friday' ? 'selected' : '' }}>Thứ Sáu</option>
                                                        <option value="Saturday" {{ $schedule['day'] == 'Saturday' ? 'selected' : '' }}>Thứ Bảy</option>
                                                        <option value="Sunday" {{ $schedule['day'] == 'Sunday' ? 'selected' : '' }}>Chủ Nhật</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-5">
                                                    <select name="working_hours[{{ $index }}][shift]" class="form-select bg-light border-0 focus-ring focus-ring-primary">
                                                        <option value="morning" {{ $schedule['shift'] == 'morning' ? 'selected' : '' }}>Sáng (08:00 - 12:00)</option>
                                                        <option value="afternoon" {{ $schedule['shift'] == 'afternoon' ? 'selected' : '' }}>Chiều (14:00 - 18:00)</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2 text-end">
                                                    <button type="button" class="btn btn-light text-danger border rounded-circle" onclick="removeScheduleRow(this)" title="Xóa ca">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="card-footer bg-white border-top py-3 px-4 rounded-bottom-4">
                                        <button type="submit" class="btn btn-warning w-100 rounded-pill fw-medium py-2 shadow-sm text-dark">
                                            Lưu Thay Đổi
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @else
                            <div class="h-100 d-flex flex-column justify-content-center align-items-center bg-light rounded-4 border border-dashed py-5 text-secondary">
                                <i class="bi bi-calendar2-range fs-1 mb-2 opacity-50"></i>
                                <p class="mb-0">Vui lòng chọn một bác sĩ ở cột bên trái để phân ca.</p>
                            </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const scheduleDays = [
        ['Monday', 'Thứ Hai'],
        ['Tuesday', 'Thứ Ba'],
        ['Wednesday', 'Thứ Tư'],
        ['Thursday', 'Thứ Năm'],
        ['Friday', 'Thứ Sáu'],
        ['Saturday', 'Thứ Bảy'],
        ['Sunday', 'Chủ Nhật'],
    ];

    const scheduleShifts = [
        ['morning', 'Sáng (08:00 - 12:00)'],
        ['afternoon', 'Chiều (14:00 - 18:00)'],
    ];

    function getScheduleRows() {
        return Array.from(document.querySelectorAll('.schedule-row'));
    }

    function getNextScheduleIndex() {
        const indexes = Array.from(document.querySelectorAll('.schedule-row select[name^="working_hours["]'))
            .map(select => {
                const match = select.name.match(/working_hours\[(\d+)\]/);
                return match ? parseInt(match[1], 10) : -1;
            });

        return indexes.length ? Math.max(...indexes) + 1 : 0;
    }

    function getSchedulePair(row) {
        return {
            day: row.querySelector('select[name$="[day]"]')?.value || '',
            shift: row.querySelector('select[name$="[shift]"]')?.value || '',
        };
    }

    function getUsedSchedulePairs(ignoreRow = null) {
        return new Set(getScheduleRows()
            .filter(row => row !== ignoreRow)
            .map(row => {
                const pair = getSchedulePair(row);
                return pair.day && pair.shift ? `${pair.day}|${pair.shift}` : '';
            })
            .filter(Boolean));
    }

    function findFirstAvailableSchedulePair(ignoreRow = null) {
        const usedPairs = getUsedSchedulePairs(ignoreRow);

        for (const [day] of scheduleDays) {
            for (const [shift] of scheduleShifts) {
                if (!usedPairs.has(`${day}|${shift}`)) {
                    return { day, shift };
                }
            }
        }

        return null;
    }

    function buildScheduleOptions(items, selectedValue) {
        return items.map(([value, label]) => (
            `<option value="${value}" ${value === selectedValue ? 'selected' : ''}>${label}</option>`
        )).join('');
    }

    function isDuplicateSchedulePair(row) {
        const pair = getSchedulePair(row);
        if (!pair.day || !pair.shift) return false;

        return getUsedSchedulePairs(row).has(`${pair.day}|${pair.shift}`);
    }

    function updateScheduleOptions() {
        getScheduleRows().forEach(row => {
            const pair = getSchedulePair(row);
            const usedPairs = getUsedSchedulePairs(row);
            const daySelect = row.querySelector('select[name$="[day]"]');
            const shiftSelect = row.querySelector('select[name$="[shift]"]');

            if (daySelect) {
                Array.from(daySelect.options).forEach(option => {
                    option.disabled = scheduleShifts.every(([shift]) => usedPairs.has(`${option.value}|${shift}`));
                });
            }

            if (shiftSelect) {
                Array.from(shiftSelect.options).forEach(option => {
                    option.disabled = usedPairs.has(`${pair.day}|${option.value}`);
                });
            }
        });

        const addButton = document.querySelector('button[onclick="addScheduleRow()"]');
        if (addButton) {
            const hasAvailablePair = Boolean(findFirstAvailableSchedulePair());
            addButton.disabled = !hasAvailablePair;
            addButton.title = hasAvailablePair ? 'Thêm ca trực' : 'Bác sĩ đã có đủ tất cả các ca trong tuần';
        }
    }

    function attachScheduleRowListeners(row) {
        row.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', () => {
                if (isDuplicateSchedulePair(row)) {
                    const replacement = findFirstAvailableSchedulePair(row);
                    if (replacement) {
                        row.querySelector('select[name$="[day]"]').value = replacement.day;
                        row.querySelector('select[name$="[shift]"]').value = replacement.shift;
                    }
                    alert('Ca trực này đã tồn tại. Hệ thống đã chọn ca trống khác cho bạn.');
                }

                updateScheduleOptions();
            });
        });
    }

    function addScheduleRow() {
        const availablePair = findFirstAvailableSchedulePair();
        if (!availablePair) {
            alert('Bác sĩ đã có đủ tất cả các ca trực trong tuần.');
            return;
        }

        let emptyMsg = document.getElementById('emptyScheduleMsg');
        if(emptyMsg) emptyMsg.style.display = 'none';

        let index = getNextScheduleIndex();
        let scheduleDiv = document.createElement('div');
        scheduleDiv.classList.add('schedule-row', 'row', 'g-2', 'align-items-center', 'mb-3');

        scheduleDiv.innerHTML = `
            <div class="col-sm-5">
                <select name="working_hours[${index}][day]" class="form-select bg-light border-0 focus-ring focus-ring-primary">
                    <option value="Monday">Thứ Hai</option>
                    <option value="Tuesday">Thứ Ba</option>
                    <option value="Wednesday">Thứ Tư</option>
                    <option value="Thursday">Thứ Năm</option>
                    <option value="Friday">Thứ Sáu</option>
                    <option value="Saturday">Thứ Bảy</option>
                    <option value="Sunday">Chủ Nhật</option>
                </select>
            </div>
            <div class="col-sm-5">
                <select name="working_hours[${index}][shift]" class="form-select bg-light border-0 focus-ring focus-ring-primary">
                    <option value="morning">Sáng (08:00 - 12:00)</option>
                    <option value="afternoon">Chiều (14:00 - 18:00)</option>
                </select>
            </div>
            <div class="col-sm-2 text-end">
                <button type="button" class="btn btn-light text-danger border rounded-circle" onclick="removeScheduleRow(this)" title="Xóa ca">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        document.getElementById('scheduleContainer').appendChild(scheduleDiv);
        scheduleDiv.querySelector('select[name$="[day]"]').value = availablePair.day;
        scheduleDiv.querySelector('select[name$="[shift]"]').value = availablePair.shift;
        attachScheduleRowListeners(scheduleDiv);
        updateScheduleOptions();
    }

    function removeScheduleRow(button) {
        button.closest('.schedule-row').remove();
        updateScheduleOptions();
    }

    function loadDoctorSchedule() {
        let doctorId = document.getElementById('doctorSelect').value;
        if (doctorId) {
            localStorage.setItem("scrollToForm", "true");
            window.location.href = `?doctor_id=${doctorId}`;
        } else {
            window.location.href = `?`; // Trở về trang trống
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        getScheduleRows().forEach(attachScheduleRowListeners);
        updateScheduleOptions();

        const scheduleForm = document.getElementById('updateScheduleForm');
        if (scheduleForm) {
            scheduleForm.addEventListener('submit', function (event) {
                const seenPairs = new Set();
                const duplicatedPair = getScheduleRows().some(row => {
                    const pair = getSchedulePair(row);
                    const key = `${pair.day}|${pair.shift}`;
                    if (seenPairs.has(key)) return true;
                    seenPairs.add(key);
                    return false;
                });

                if (duplicatedPair) {
                    event.preventDefault();
                    alert('Danh sách ca trực đang có ca bị trùng. Vui lòng kiểm tra lại trước khi lưu.');
                }
            });
        }
    });

    window.onload = function () {
        if (localStorage.getItem("scrollToForm") === "true") {
            setTimeout(() => {
                let el = document.getElementById('loaddoctor');
                if(el) {
                    el.scrollIntoView({ behavior: 'smooth' });
                }
                localStorage.removeItem("scrollToForm");
            }, 300);
        }
    };
</script>
@endpush
@endsection
