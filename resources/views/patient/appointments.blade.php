@extends('layouts.app')

@section('title', 'Lich hen cua toi')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="h3 mb-0">Lich hen cua toi</h1>
        <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">Dat lich moi</a>
    </div>

    @if($appointments->isEmpty())
        <div class="alert alert-info">Ban chua co lich hen nao.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Bac si</th>
                        <th>Ngay hen</th>
                        <th>Ca kham</th>
                        <th>Trang thai</th>
                        <th>Mo ta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                        <tr>
                            <td>{{ optional(optional($appointment->doctor)->user)->name ?? 'Chua co thong tin' }}</td>
                            <td>{{ $appointment->appointment_date }}</td>
                            <td>
                                @if($appointment->shift === 'morning')
                                    08:00 - 12:00
                                @elseif($appointment->shift === 'afternoon')
                                    14:00 - 18:00
                                @else
                                    {{ $appointment->shift ?? 'Chua chon' }}
                                @endif
                            </td>
                            <td>{{ $appointment->status }}</td>
                            <td>{{ $appointment->description ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
