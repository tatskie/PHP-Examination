<?php

namespace Tests\Feature;

use App\User;
use App\Company;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    public function setUp(): void
    {
        parent::setUp();
        $role = Role::create(['name' => 'admin']);
        $this->admin = factory(\App\User::class)->create();
        $this->admin->assignRole('admin');
    }

    /** @test */
    public function a_company_can_be_added_to_database()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->admin)
            ->post(route('admin.company.store'), $this->data())
            ->assertStatus(200);
        $this->assertCount(1, Company::all());
    }

    /** @test */
    public function a_company_name_is_required()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.company.store'), array_merge($this->data(), ['name' => '']))
            ->assertStatus(200);
    }

    /** @test */
    public function a_company_email_must_be_email()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.company.store'), array_merge($this->data(), ['email' => 'Company Email']))
            ->assertStatus(200);
    }

    /** @test */
    public function a_company_can_be_updated()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.company.store'), $this->data())
            ->assertStatus(200);

        $company = Company::first();

        $this->actingAs($this->admin)
            ->post(route('admin.company.update', $company->id), [
                'name' => 'New Company Name',
                'email' => 'new.company@gmail.com',
                'website' => 'www.new-website.com'
            ])
            ->assertStatus(200);

        $this->assertEquals('New Company Name', Company::first()->name);
    }

    /** @test */
    public function a_company_can_be_deleted()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.company.store'), $this->data());

        $company = Company::first();
        $this->assertCount(1, Company::all());

        $this->actingAs($this->admin)
            ->delete(route('admin.company.destroy', $company->id))->assertStatus(200);

        $this->assertCount(0, Company::all());
    }

    private function data()
    {
        return [
            'name' => 'Company Name',
            'email' => 'company@gmail.com',
            'website' => 'www.website.com'
        ];
    }
}
