@extends('layouts.app')

@section('title', 'Đặt lịch khám')

@section('content')
    @php
        $currentUser = Auth::user();
    @endphp

<style>
/* ========== APPOINTMENT PAGE STYLES ========== */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

.appt-hero {
    background: linear-gradient(135deg, #03428E 0%, #0074D9 50%, #00AEEF 100%);
    padding: 48px 0 36px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.appt-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
    border-radius: 50%;
}
.appt-hero::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
    border-radius: 50%;
}
.appt-hero h1 {
    font-family: 'Poppins', sans-serif;
    font-size: 32px;
    font-weight: 700;
    color: #fff;
    margin: 0 0 8px;
    position: relative;
    z-index: 1;
}
.appt-hero p {
    font-family: 'Poppins', sans-serif;
    font-size: 15px;
    color: rgba(255,255,255,0.85);
    margin: 0;
    position: relative;
    z-index: 1;
}
.appt-hero .hero-icon {
    font-size: 48px;
    color: rgba(255,255,255,0.9);
    margin-bottom: 12px;
    display: block;
    position: relative;
    z-index: 1;
}

/* Steps indicator */
.appt-steps {
    display: flex;
    justify-content: center;
    gap: 0;
    margin-top: 28px;
    position: relative;
    z-index: 1;
}
.appt-step {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    font-weight: 500;
    color: rgba(255,255,255,0.7);
    position: relative;
}
.appt-step.active {
    color: #fff;
}
.appt-step .step-num {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 13px;
}
.appt-step.active .step-num {
    background: #fff;
    color: #03428E;
}
.appt-step + .appt-step::before {
    content: '';
    position: absolute;
    left: -16px;
    top: 50%;
    transform: translateY(-50%);
    width: 32px;
    height: 2px;
    background: rgba(255,255,255,0.25);
}

/* Form container */
.appt-form-wrapper {
    max-width: 960px;
    margin: -28px auto 48px;
    padding: 0 16px;
    position: relative;
    z-index: 2;
}
.appt-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 8px 40px rgba(3,66,142,0.12);
    overflow: hidden;
}

/* Section headers inside the card */
.appt-section {
    padding: 28px 36px 8px;
}
.appt-section:first-child {
    padding-top: 36px;
}
.appt-section-title {
    font-family: 'Poppins', sans-serif;
    font-size: 18px;
    font-weight: 600;
    color: #03428E;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.appt-section-title i {
    font-size: 22px;
    color: #00AEEF;
}
.appt-section-title::after {
    content: '';
    flex: 1;
    height: 2px;
    background: linear-gradient(90deg, #e0f2fe, transparent);
    margin-left: 12px;
}

/* Form fields */
.appt-field {
    margin-bottom: 20px;
}
.appt-field label {
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    font-weight: 500;
    color: #555;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.appt-field label i {
    color: #00AEEF;
    font-size: 15px;
}
.appt-field .form-control,
.appt-field .form-select {
    border: 2px solid #e8eef5;
    border-radius: 12px;
    padding: 12px 16px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    color: #333;
    transition: all 0.25s ease;
    background-color: #fafcff;
}
.appt-field .form-control:focus,
.appt-field .form-select:focus {
    border-color: #00AEEF;
    box-shadow: 0 0 0 4px rgba(0,174,239,0.1);
    background-color: #fff;
    outline: none;
}
.appt-field .form-control::placeholder {
    color: #aab;
}

/* Two-column grid */
.appt-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 28px;
}

/* Divider */
.appt-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, #e0ecf7, transparent);
    margin: 8px 36px 0;
}

/* Submit section */
.appt-submit {
    padding: 24px 36px 36px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}
.appt-submit-info {
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    color: #888;
    display: flex;
    align-items: center;
    gap: 6px;
}
.appt-submit-info i {
    color: #00AEEF;
}
.appt-submit-btn {
    font-family: 'Poppins', sans-serif;
    font-size: 15px;
    font-weight: 600;
    color: #fff;
    background: linear-gradient(135deg, #03428E, #0074D9);
    border: none;
    border-radius: 14px;
    padding: 14px 48px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 16px rgba(3,66,142,0.25);
    display: flex;
    align-items: center;
    gap: 8px;
}
.appt-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(3,66,142,0.35);
    background: linear-gradient(135deg, #023570, #005fba);
}
.appt-submit-btn:active {
    transform: translateY(0);
}

/* Alert custom */
.appt-alert {
    margin: 0 36px 0;
    border-radius: 12px;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    padding: 14px 20px;
}
.appt-alert-success {
    background: #e8faf0;
    border: 1px solid #b8e6cc;
    color: #1a7a45;
}
.appt-alert-danger {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
}

/* Responsive */
@media (max-width: 768px) {
    .appt-grid {
        grid-template-columns: 1fr;
    }
    .appt-section {
        padding: 20px 20px 8px;
    }
    .appt-submit {
        flex-direction: column;
        padding: 20px;
    }
    .appt-submit-btn {
        width: 100%;
        justify-content: center;
    }
    .appt-divider {
        margin: 8px 20px 0;
    }
    .appt-alert {
        margin: 0 20px 0;
    }
    .appt-hero h1 {
        font-size: 24px;
    }
    .appt-steps {
        flex-wrap: wrap;
        gap: 4px;
    }
    .appt-step + .appt-step::before {
        display: none;
    }
}
</style>

<!-- ===== HERO BANNER ===== -->
<div class="appt-hero">
    <i class="bi bi-calendar2-check hero-icon"></i>
    <h1>Đặt Lịch Khám Trực Tuyến</h1>
    <p>Nhanh chóng · Tiện lợi · Chính xác</p>
    <div class="appt-steps">
        <div class="appt-step active">
            <span class="step-num">1</span>
            Chọn dịch vụ
        </div>
        <div class="appt-step active">
            <span class="step-num">2</span>
            Điền thông tin
        </div>
        <div class="appt-step active">
            <span class="step-num">3</span>
            Xác nhận
        </div>
    </div>
</div>

<!-- ===== FORM CARD ===== -->
<div class="appt-form-wrapper">
    <div class="appt-card">

        {{-- Alerts --}}
        @if (session('success'))
            <div class="appt-alert appt-alert-success" style="margin-top: 28px;">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="appt-alert appt-alert-danger" style="margin-top: 28px;">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="appt-alert appt-alert-danger" style="margin-top: 28px;">
                <div style="font-weight:600; margin-bottom:6px;"><i class="bi bi-exclamation-triangle-fill"></i> Không thể đặt lịch:</div>
                <ul style="margin:0; padding-left:20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('appointments.store') }}">
            @csrf

            {{-- SECTION 1: Dịch vụ & Bác sĩ --}}
            <div class="appt-section">
                <div class="appt-section-title">
                    <i class="bi bi-hospital"></i>
                    Chọn Dịch Vụ & Bác Sĩ
                </div>

                <div class="appt-grid">
                    <div class="appt-field">
                        <label for="specialty"><i class="bi bi-heart-pulse"></i> Dịch vụ khám</label>
                        <select name="specialty" id="specialty" class="form-control" required>
                            <option value="">-- Chọn Dịch Vụ --</option>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty }}" {{ old('specialty') == $specialty ? 'selected' : '' }}>
                                    {{ $specialty }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="appt-field">
                        <label for="doctor_id"><i class="bi bi-person-badge"></i> Bác sĩ phụ trách</label>
                        <select name="doctor_id" id="doctor_id" class="form-control" required>
                            <option value="">-- Chọn mục Dịch Vụ trước --</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="appt-divider"></div>

            {{-- SECTION 2: Thông tin bệnh nhân --}}
            <div class="appt-section">
                <div class="appt-section-title">
                    <i class="bi bi-person-vcard"></i>
                    Thông Tin Bệnh Nhân
                </div>

                <div class="appt-grid">
                    <div class="appt-field">
                        <label for="name"><i class="bi bi-person"></i> Họ và tên</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Nhập họ và tên đầy đủ" value="{{ old('name', optional($currentUser)->name) }}" required>
                    </div>

                    <div class="appt-field">
                        <label for="email"><i class="bi bi-envelope"></i> Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="example@email.com" value="{{ old('email', optional($currentUser)->email) }}" required>
                    </div>

                    <div class="appt-field">
                        <label for="phone"><i class="bi bi-telephone"></i> Số điện thoại</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="09xx xxx xxx" value="{{ old('phone', optional($currentUser)->phone) }}" required>
                    </div>

                    <div class="appt-field">
                        <label for="age"><i class="bi bi-calendar-heart"></i> Tuổi</label>
                        <input type="number" name="age" id="age" class="form-control" placeholder="Nhập tuổi" value="{{ old('age') }}" required>
                    </div>

                    <div class="appt-field">
                        <label for="cccd"><i class="bi bi-credit-card-2-front"></i> Số CCCD</label>
                        <input type="text" name="cccd" id="cccd" class="form-control" placeholder="Nhập số CCCD" value="{{ old('cccd') }}" required>
                    </div>
                </div>
            </div>

            <div class="appt-divider"></div>

            {{-- SECTION 3: Lịch hẹn --}}
            <div class="appt-section">
                <div class="appt-section-title">
                    <i class="bi bi-calendar-event"></i>
                    Thời Gian Khám
                </div>

                <div class="appt-grid">
                    <div class="appt-field">
                        <label for="appointment_date"><i class="bi bi-calendar-date"></i> Ngày hẹn</label>
                        <input type="date" name="appointment_date" id="appointment_date" class="form-control" value="{{ old('appointment_date') }}" required>
                    </div>

                    <div class="appt-field">
                        <label for="shift"><i class="bi bi-clock"></i> Ca làm việc</label>
                        <select name="shift" id="shift" class="form-control" required>
                            <option value="">-- Vui lòng chọn ngày trước --</option>
                            @if(isset($editAppointment))
                                <option value="morning" {{ $editAppointment->shift == 'morning' ? 'selected' : '' }}>08:00 - 12:00</option>
                                <option value="afternoon" {{ $editAppointment->shift == 'afternoon' ? 'selected' : '' }}>14:00 - 18:00</option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="appt-field">
                    <label for="description"><i class="bi bi-chat-left-text"></i> Mô tả triệu chứng (không bắt buộc)</label>
                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Mô tả ngắn gọn triệu chứng hoặc lý do khám..."></textarea>
                </div>
            </div>

            <div class="appt-divider"></div>

            {{-- SUBMIT --}}
            <div class="appt-submit">
                <div class="appt-submit-info">
                    <i class="bi bi-shield-check"></i>
                    Thông tin của bạn được bảo mật tuyệt đối
                </div>
                <button type="submit" class="appt-submit-btn">
                    <i class="bi bi-send-check"></i>
                    Xác nhận đặt lịch
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== JAVASCRIPT (giữ nguyên logic cũ) ===== --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Load shifts when date or doctor changes
    $(document).ready(function () {
        function loadPublicAvailableShifts() {
            var selectedDate = $('#appointment_date').val();
            var doctorId = $('#doctor_id').val();
            $('#shift').html('<option value="">-- Đang tải ca làm việc... --</option>');

            if (selectedDate && doctorId) {
                $.ajax({
                    url: '/get-working-hours',
                    type: 'GET',
                    data: {
                        doctor_id: doctorId,
                        date: selectedDate
                    },
                    success: function (data) {
                        $('#shift').html(
                            '<option value="">-- Chọn Ca Làm Việc --</option>');
                        if (data.morning || data.afternoon) {
                            if (data.morning) {
                                $('#shift').append(
                                    '<option value="morning">08:00 - 12:00</option>'
                                );
                            }
                            if (data.afternoon) {
                                $('#shift').append(
                                    '<option value="afternoon">14:00 - 18:00</option>'
                                );
                            }
                        } else {
                            $('#shift').html(
                                '<option value="">-- Không có ca làm việc --</option>'
                            );
                        }
                    },
                    error: function () {
                        $('#shift').html(
                            '<option value="">-- Lỗi khi tải dữ liệu --</option>');
                    }
                });
            } else {
                $('#shift').html('<option value="">-- Vui lòng chọn ngày trước --</option>');
            }
        }

        $('#appointment_date, #doctor_id').change(loadPublicAvailableShifts);
    });

    // Load doctors by specialty
    const oldSpecialty = @json(old('specialty'));
    const oldDoctorId = @json(old('doctor_id'));
    const oldShift = @json(old('shift'));
    const oldAppointmentDate = @json(old('appointment_date'));

    function loadDoctorsBySpecialty(specialty) {
        const doctorSelect = document.getElementById('doctor_id');
        doctorSelect.innerHTML = '<option value="">-- Đang tải danh sách bác sĩ... --</option>';

        if (!specialty) {
            doctorSelect.innerHTML = '<option value="">-- Chọn mục Dịch Vụ trước --</option>';
            return Promise.resolve();
        }

        return fetch('/get-doctors/' + encodeURIComponent(specialty))
            .then(response => response.json())
            .then(data => {
                doctorSelect.innerHTML = '<option value="">-- Chọn bác sĩ --</option>';
                data.forEach(doctor => {
                    doctorSelect.innerHTML += `<option value="${doctor.id}">${doctor.name}</option>`;
                });
            })
            .catch(() => {
                doctorSelect.innerHTML = '<option value="">-- Lỗi khi tải bác sĩ --</option>';
            });
    }

    document.getElementById('specialty').addEventListener('change', function () {
        loadDoctorsBySpecialty(this.value);
    });

    // Restore previous selections after validation errors
    window.addEventListener('DOMContentLoaded', async function () {
        if (oldSpecialty) {
            const specialtySelect = document.getElementById('specialty');
            specialtySelect.value = oldSpecialty;
            await loadDoctorsBySpecialty(oldSpecialty);
            if (oldDoctorId) {
                document.getElementById('doctor_id').value = oldDoctorId;
            }
        }

        // Trigger shift loading again if we have doctor + date
        if (oldDoctorId && oldAppointmentDate) {
            const appointmentDateInput = document.getElementById('appointment_date');
            appointmentDateInput.value = oldAppointmentDate;
            appointmentDateInput.dispatchEvent(new Event('change'));

            // Wait a bit for the ajax call to populate shifts, then select old shift
            setTimeout(() => {
                if (oldShift) {
                    const shiftSelect = document.getElementById('shift');
                    shiftSelect.value = oldShift;
                }
            }, 700);
        }
    });
</script>

@endsection
