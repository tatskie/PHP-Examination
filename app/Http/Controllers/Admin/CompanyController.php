<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyPostRequest;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
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
        return view('admin.company.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CompanyPostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyPostRequest $request)
    {
        checkIfUserAuthenticated();

        $validatedData = $request->validated();

        if($request->hasFile('logo')){
            $logo = time().'_'.$request->logo->getClientOriginalName();
            $file = $request->file('logo');
            $file->move('storage', $logo);  
            $validatedData['logo'] = $logo;
        }

        $createQuery = Company::create($validatedData);

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
        $name = $request->input('name');
        $email = $request->input('email');
        $website = $request->input('website');

        checkIfUserAuthenticated();

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

        $query = $query->orderBy('id', 'desc')->paginate($noOfRecords);

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
    public function edit(Company $company)
    {
        $data = null;

        checkIfUserAuthenticated();

        $data[] = [
            'id' => $company->id,
            'name' => $company->name,
            'email' => $company->email,
            'logo' => $company->logo,
            'website' => $company->website
        ];

        $result['success'] = true;
        $result['data'] = $data;

        return $result;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\CompanyPostRequest  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyPostRequest $request, Company $company)
    {
        checkIfUserAuthenticated();

        $validatedData = $request->validated();
  
        if($request->hasFile('logo')){
            $logo = time().'_'.$request->logo->getClientOriginalName();
            $file = $request->file('logo');
            $file->move('storage', $logo);  
            $validatedData['logo'] = $logo;

            if ($company['logo'] != null) {
                $link = 'storage/'.$company['logo'];

                if (\File::exists(public_path($link))) {
                    \File::delete(public_path($link));
                }
            } 
        }

        $company->update($validatedData);

        checkIfCollectionNotEmpty($company);

        $result['success'] = true;
        $result['message'] = 'Record has been updated successfully.';

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        checkIfUserAuthenticated();

        if ($company['logo'] != null) {
            $link = 'storage/'.$company['logo'];

            if (\File::exists(public_path($link))) {
                \File::delete(public_path($link));
            }
        }

        $company->delete();

        checkIfCollectionNotEmpty($company);

        $result['success'] = true;
        $result['message'] = 'Record has been deleted successfully.';
        return $result;
    }
}
