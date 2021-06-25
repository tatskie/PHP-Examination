<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('index');
    }

    protected $rules = array(
        'first_name' => 'required',
        'last_name' => 'required',
        'company_id' => 'not_in:0',
        'phone' => 'integer'
    );

    protected $customMessages = [
        'company_id.not_in' => 'Please select company.'
    ];

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $company_id = $request->input('company_id');

        if (!Auth::user()) {
            $result['success'] = false;
            $result['message'] = 'Seems like you have been logged Out, Please Refresh Your Page';
            return $result;
        }

        $this->rules['email'] = 'email|unique:employees';

        $validators = Validator::make($request->all(), $this->rules, $this->customMessages);

        if ($validators->fails()) {
            $result['success'] = false;
            $result['message'] = $validators->errors()->first();
            return $result;
        }

        $data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'company_id' => $company_id,
            "created_at" =>  \Carbon\Carbon::now(),
            "updated_at" => \Carbon\Carbon::now()
        ];

        $createQuery = Employee::insert($data);

        if (!$createQuery) {
            $result['success'] = false;
            $result['message'] = 'Something Went Wrong while Inserting into Database';
            return $result;
        }

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
        $name = $request->input('name');
        $email = $request->input('email');
        $website = $request->input('website');

        if (!Auth::user()) {
            $result['success'] = false;
            $result['message'] = 'Seems like you have been logged Out, Please Refresh Your Page';
            return $result;
        }

        $query = Employee::select('companies.*');

        if (!empty($name)) {
            $query = $query->where('companies.name', $name);
            $name = ucwords($this->source($name));
            $filterApplied['name'] = 'Name: ' . $name;
        }

        if (!empty($email)) {
            $query = $query->where('companies.email', $name);
            $email = ucwords($this->source($email));
            $filterApplied['email'] = 'Email: ' . $email;
        }

        if (!empty($website)) {
            $query = $query->where('companies.website', $website);
            $website = ucwords($this->source($website));
            $filterApplied['website'] = 'Website: ' . $website;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $id = $request->input('id');
        $data = null;

        if (!Auth::user()) {
            $result['success'] = false;
            $result['message'] = 'Seems like you have been logged Out, Please Refresh Your Page';
            return $result;
        }

        $query = Employee::find($id);

        if (!$query) {
            $result['success'] = false;
            $result['message'] = 'Something Went Wrong Will Fetching data, Please Refresh Your Page';
            return $result;
        }

        $data[] = [
            'id' => $query->id,
            'first_name' => $query->first_name,
            'last_name' => $query->last_name,
            'email' => $query->email,
            'phone' => $query->phone,
            'company' => $query->company_id
        ];

        $result['success'] = true;
        $result['data'] = $data;

        return $result;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->input('id');
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $company = $request->input('company');
        $phone = $request->input('phone');

        if (!Auth::user()) {
            $result['success'] = false;
            $result['message'] = 'Seems like you have been logged Out, Please Refresh Your Page';
            return $result;
        }

        $query = Employee::find($id);

        if (!$query) {
            $result['success'] = false;
            $result['message'] = 'Something Went Wrong Will Fetching data, Please Refresh Your Page';
            return $result;
        }

        $this->rules['email'] = 'email|unique:employees,id,'.$id;

        $validators = Validator::make($request->all(), $this->rules, $this->customMessages);

        if ($validators->fails()) {
            $output['success'] = false;
            $output['message'] = $validators->errors()->first();
            return $output;
        }

        $updateValues = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'company_id' => $company,
            'phone' => $phone
        ];

        $query = Employee::where('id', $id)->update($updateValues);

        if (!$query) {
            $result['success'] = false;
            $result['message'] = 'Something Went Wrong while Updating into Database';
            return $result;
        }

        $result['success'] = true;
        $result['message'] = 'Record has been updated successfully.';

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');

        if (!Auth::user()) {
            $result['success'] = false;
            $result['message'] = 'Seems like you have been logged Out, Please Refresh Your Page';
            return $result;
        }

        $query = Employee::find($id);

        if (!$query) {
            $result['success'] = false;
            $result['message'] = 'Something Went Wrong Will Fetching data, Please Refresh Your Page';
            return $result;
        }

        $query = Employee::where('id', $id)->delete();

        if (!$query) {
            $result['success'] = false;
            $result['message'] = 'Something Went Wrong while Deleted into Database';
            return $result;
        }

        $result['success'] = true;
        $result['message'] = 'Record has been deleted successfully.';
        return $result;
    }
}
