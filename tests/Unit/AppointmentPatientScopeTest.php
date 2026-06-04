<?php

namespace Tests\Unit;

use App\Models\Appointment;
use Tests\TestCase;

class AppointmentPatientScopeTest extends TestCase
{
    public function test_for_patient_scope_filters_by_user_id()
    {
        $sql = Appointment::query()->forPatient(123)->toSql();

        $this->assertStringContainsString('`user_id` = ?', $sql);
    }
}
