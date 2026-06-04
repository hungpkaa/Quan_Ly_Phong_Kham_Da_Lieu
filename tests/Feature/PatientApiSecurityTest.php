<?php

namespace Tests\Feature;

use Tests\TestCase;

class PatientApiSecurityTest extends TestCase
{
    public function test_patient_api_requires_authentication()
    {
        $this->getJson('/api/patients')->assertUnauthorized();
    }
}
