<?php

namespace Tests\Unit;

use App\Models\Patient;
use Tests\TestCase;

class PatientFillableTest extends TestCase
{
    public function test_patient_allows_expected_fields_for_mass_assignment()
    {
        $patient = new Patient();

        $this->assertSame([
            'name',
            'age',
            'gender',
            'phone',
            'address',
        ], $patient->getFillable());
    }
}
