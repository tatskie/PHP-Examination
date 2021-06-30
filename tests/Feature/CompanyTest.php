<?php

namespace Tests\Feature;

use App\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_company_can_be_added_to_database()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/company/store', $this->data());

        $company = Company::all();

        $response->assertOk();
        $this->assertCount(1, $company);
    }

    /** @test */
    public function a_company_name_is_required()
    {
        $response = $this->post('/company/store', array_merge($this->data(), ['name' => '']));

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_company_email_must_be_email()
    {
        $response = $this->post('/company/store', array_merge($this->data(), ['email' => 'Company Email']));

        $response->assertSessionHasErrors('email');
    }

    private function data()
    {
        return [
            'name' => 'Company Name',
            'email' => 'company@gmail.com',
            'logo' => 'logo.png',
            'website' => 'www.website.com'
        ];
    }
}
