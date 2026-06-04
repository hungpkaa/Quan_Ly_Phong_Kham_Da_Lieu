@extends('layouts.app')

@section('title', 'Tai khoan cua toi')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="h3 mb-0">Tai khoan cua toi</h1>
        <a href="{{ route('patient.appointments') }}" class="btn btn-primary btn-sm rounded-pill px-3">Lich hen cua toi</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="text-muted small">Ho ten</div>
                    <div class="fw-semibold">{{ $user->name }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Email</div>
                    <div class="fw-semibold">{{ $user->email }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">So dien thoai</div>
                    <div class="fw-semibold">{{ $user->phone ?? 'Chua cap nhat' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Vai tro</div>
                    <div class="fw-semibold">Benh nhan</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
