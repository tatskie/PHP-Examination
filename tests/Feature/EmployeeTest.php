<?php

namespace Tests\Feature;

use App\User;
use App\Company;
use App\Employee;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeTest extends TestCase
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
    public function a_employee_can_be_added_to_database()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->admin)
            ->post(route('admin.employee.store'), $this->data())
            ->assertStatus(200);
        $this->assertCount(1, Employee::all());
    }

    /** @test */
    public function a_employee_first_name_is_required()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.employee.store'), array_merge($this->data(), ['first_name' => '']))
            ->assertStatus(200);
    }

    /** @test */
    public function a_employee_last_name_is_required()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.employee.store'), array_merge($this->data(), ['last_name' => '']))
            ->assertStatus(200);
    }

    /** @test */
    public function a_employee_email_must_be_email()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.employee.store'), array_merge($this->data(), ['email' => 'Employee Email']))
            ->assertStatus(200);
    }

    /** @test */
    public function a_employee_can_be_updated()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.employee.store'), $this->data())
            ->assertStatus(200);

        $employee = Employee::first();

        $this->actingAs($this->admin)
            ->put(route('admin.employee.update', $employee->id), [
                'first_name' => 'John Edited',
                'last_name' => 'Doe',
                'email' => 'john@gmail.com',
                'phone' => '912345678',
                'company_id' => $employee->company_id
            ])
            ->assertStatus(200);

        $this->assertEquals('John Edited', Employee::first()->first_name);
    }

    /** @test */
    public function a_employee_can_be_deleted()
    {
        $this->actingAs($this->admin)
            ->post(route('admin.employee.store'), $this->data());

        $employee = Employee::first();
        $this->assertCount(1, Employee::all());

        $this->actingAs($this->admin)
            ->delete(route('admin.employee.destroy', $employee->id))->assertStatus(200);

        $this->assertCount(0, Employee::all());
    }

    private function data()
    {
        $company = factory(\App\Company::class)->create();
        return [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@gmail.com',
            'phone' => '912345678',
            'company_id' => $company->id
        ];
    }
}
