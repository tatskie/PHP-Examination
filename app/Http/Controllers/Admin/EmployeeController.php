<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeePostRequest;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.employee.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\EmployeePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeePostRequest $request)
    {
        checkIfUserAuthenticated();

        $validatedData = $request->validated();

        $createQuery = Employee::create($validatedData);

        checkIfCollectionNotEmpty($createQuery);

        $result['success'] = true;
        $result['message'] = 'Record has been added successfully.';
        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $noOfRecords = $request->input('no_of_records');
        $filterApplied = null;
        $queryData = null;
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $company = $request->input('company');
        $phone = $request->input('phone');

        checkIfUserAuthenticated();

        $query = Employee::select('employees.*');

        if (!empty($first_name)) {
            $query = $query->where('employees.first_name', $first_name);
            $first_name = ucwords($first_name);
            $filterApplied['first_name'] = 'First Name: ' . $first_name;
        }

        if (!empty($last_name)) {
            $query = $query->where('employees.last_name', $last_name);
            $last_name = ucwords($last_name);
            $filterApplied['last_name'] = 'Last Name: ' . $last_name;
        }

        if (!empty($company)) {
            $query = $query->where('employees.company_id', $company);
            $company = ucwords(Company::find($company)->name);
            $filterApplied['company'] = 'Company: ' . $company;
        }

        if (!empty($email)) {
            $query = $query->where('employees.email', $email);
            $email = ucwords($email);
            $filterApplied['email'] = 'Email: ' . $email;
        }

        if (!empty($phone)) {
            $query = $query->where('employees.phone', $phone);
            $phone = ucwords($phone);
            $filterApplied['phone'] = 'Website: ' . $phone;
        }

        $query = $query->paginate($noOfRecords);

        foreach ($query as $data) {
            $queryData[] = [
                'id' => $data->id,
                'first_name' => ucwords($data->first_name),
                'last_name' => ucwords($data->last_name),
                'email' => $data->email,
                'phone' => $data->phone,
                'company' => ucwords(Company::find($data->company_id)->name),
                'created_at' => $data->created_at->diffForHumans()
            ];
        }

        $response['success'] = 'true';
        $response['filterApplied'] = $filterApplied;
        $response['totalRecords'] = $query->total();
        $response['data'] = $queryData;
        $response['webPagination'] = str_replace("\r\n", "", $query->links('layouts.pagination'));

        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        $data = null;

        checkIfUserAuthenticated();

        $arr = getCompany();
        $html_company = getSelectOptions($arr, $employee->company_id,'');
        $data[] = [
            'id' => $employee->id,
            'first_name' => $employee->first_name,
            'last_name' => $employee->last_name,
            'email' => $employee->email,
            'phone' => $employee->phone,
            'company' => $html_company
        ];

        $result['success'] = true;
        $result['data'] = $data;

        return $result;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\EmployeePostRequest  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeePostRequest $request, Employee $employee)
    {
        checkIfUserAuthenticated();

        $validatedData = $request->validated();

        $employee->update($validatedData);

        checkIfCollectionNotEmpty($employee);

        $result['success'] = true;
        $result['message'] = 'Record has been updated successfully.';

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        checkIfUserAuthenticated();

        $employee->delete();

        checkIfCollectionNotEmpty($employee);

        $result['success'] = true;
        $result['message'] = 'Record has been deleted successfully.';
        return $result;
    }
}
