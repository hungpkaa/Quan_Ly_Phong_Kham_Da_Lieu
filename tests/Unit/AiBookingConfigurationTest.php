<?php

namespace Tests\Unit;

use App\Models\Appointment;
use App\Services\Appointments\AvailableSlotService;
use Carbon\Carbon;
use Tests\TestCase;

class AiBookingConfigurationTest extends TestCase
{
    public function test_appointment_allows_source_for_ai_booking()
    {
        $appointment = new Appointment();

        $this->assertContains('source', $appointment->getFillable());
    }

    public function test_available_slot_service_blocks_unfinished_and_completed_slots()
    {
        $service = new AvailableSlotService();

        $this->assertSame(['pending', 'approved', 'completed'], $service->blockingStatuses());
    }

    public function test_available_slot_service_blocks_past_shift_on_current_day()
    {
        $service = new AvailableSlotService();
        $now = Carbon::parse('2026-06-12 14:00:00', 'Asia/Ho_Chi_Minh');

        $this->assertFalse($service->isShiftStillBookable('2026-06-12', 'morning', $now));
        $this->assertTrue($service->isShiftStillBookable('2026-06-12', 'afternoon', $now));
        $this->assertTrue($service->isShiftStillBookable('2026-06-13', 'morning', $now));
    }
}
