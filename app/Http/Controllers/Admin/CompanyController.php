<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('index');
    }

    protected $rules = array(
        'name' => 'required',
        'logo' => 'mimes:jpeg,png,jpg|dimensions:min_width=100,min_height=100'
    );

    protected $customMessages = [
        //
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.company.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $logo = null;
        $website = $request->input('website');

        if (!Auth::user()) {
            $result['success'] = false;
            $result['message'] = 'Seems like you have been logged Out, Please Refresh Your Page';
            return $result;
        }

        $this->rules['email'] = 'email|unique:companies';

        $validators = Validator::make($request->all(), $this->rules, $this->customMessages);

        if ($validators->fails()) {
            $result['success'] = false;
            $result['message'] = $validators->errors()->first();
            return $result;
        }

        if($request->hasFile('logo')){
            $logo = time().'_'.$request->logo->getClientOriginalName();
            $file = $request->file('logo');
            $file->move('storage', $logo);  
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'logo' => $logo,
            'website' => $website,
            "created_at" =>  \Carbon\Carbon::now(),
            "updated_at" => \Carbon\Carbon::now()
        ];

        $createQuery = Company::insert($data);

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

        $query = Company::select('companies.*');

        if (!empty($name)) {
            $query = $query->where('companies.name', $name);
            $name = ucwords($name);
            $filterApplied['name'] = 'Name: ' . $name;
        }

        if (!empty($email)) {
            $query = $query->where('companies.email', $name);
            $email = ucwords($email);
            $filterApplied['email'] = 'Email: ' . $email;
        }

        if (!empty($website)) {
            $query = $query->where('companies.website', $website);
            $website = ucwords($website);
            $filterApplied['website'] = 'Website: ' . $website;
        }

        $query = $query->paginate($noOfRecords);

        foreach ($query as $data) {
            $queryData[] = [
                'id' => $data->id,
                'name' => ucwords($data->name),
                'email' => $data->email,
                'logo' => $data->logo ? asset('storage/'. $data->logo) : asset('storage/default.png'),
                'website' => $data->website,
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
     * @return Response
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

        $query = Company::find($id);

        if (!$query) {
            $result['success'] = false;
            $result['message'] = 'Something Went Wrong Will Fetching data, Please Refresh Your Page';
            return $result;
        }

        $data[] = [
            'id' => $query->id,
            'name' => $query->name,
            'email' => $query->email,
            'logo' => $query->logo,
            'website' => $query->website
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
        $name = $request->input('name');
        $email = $request->input('email');
        $logo = null;
        $website = $request->input('website');

        if (!Auth::user()) {
            $result['success'] = false;
            $result['message'] = 'Seems like you have been logged Out, Please Refresh Your Page';
            return $result;
        }

        $query = Company::find($id);

        if (!$query) {
            $result['success'] = false;
            $result['message'] = 'Something Went Wrong Will Fetching data, Please Refresh Your Page';
            return $result;
        }

        $this->rules['email'] = 'email|unique:companies,id,'.$id;

        $validators = Validator::make($request->all(), $this->rules, $this->customMessages);

        if ($validators->fails()) {
            $output['success'] = false;
            $output['message'] = $validators->errors()->first();
            return $output;
        }

        if($request->hasFile('logo')){
            $logo = time().'_'.$request->logo->getClientOriginalName();
            $file = $request->file('logo');
            $file->move('storage', $logo);  

            if ($query['logo'] != null) {
                $link = 'storage/'.$query['logo'];

                if (\File::exists(public_path($link))) {
                    \File::delete(public_path($link));
                }
            } 
        }

        $updateValues = [
            'name' => $name,
            'email' => $email,
            'logo' => $logo ?? $query['logo'],
            'website' => $website
        ];

        $query = Company::where('id', $id)->update($updateValues);

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

        $query = Company::find($id);

        if (!$query) {
            $result['success'] = false;
            $result['message'] = 'Something Went Wrong Will Fetching data, Please Refresh Your Page';
            return $result;
        }

        if ($query['logo'] != null) {
            $link = 'storage/'.$query['logo'];

            if (\File::exists(public_path($link))) {
                \File::delete(public_path($link));
            }
        }

        $query = Company::where('id', $id)->delete();

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
