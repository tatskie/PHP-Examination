@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 my-2">
        	<div class="row">
            	<div class="col-md-8">
                	{{ __('List of Companies.') }}
                </div>
            	<div class="col-md-4 text-right">
	                <div class="btn btn-primary btn-sm text-right" data-toggle="modal" data-target="#createCompanyModal">
						Add New Company
	                </div>
	            </div>
	        </div>
        </div>

        <!-- Filter Form -->
        <div class="col-md-12 my-2">
    		@component('layouts.components.filter_form',
                [
                'body' => 'admin.company.filter',
                'form' => 'true',
                'formId' => 'filterForm',
                'formMethod' => 'POST',
                'searchBtn' => 'search',
                'resetBtn' => 'Reset',
                ])
            @endcomponent
        </div>

        <!-- Show Data -->
        <div class="col-12">
            <div id="filter-show"></div>
            <div id="show" class="">
                <div id="top-text" class="font-weight-bolder" style="white-space: pre-wrap;">               	
                </div>
                <div class="table-basic-1 table-wrap  p-0 table-responsive">
                	<table id="tracker-table" class="table table-bordered m-0 table-striped table-hover">
                	</table>
                </div>
                <div id="bottom-text" class="font-weight-bolder" style="white-space: pre-wrap;">
                </div>
            </div>
            <div id="pagination-show">
          
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')   
    @component('layouts.components.modal', [
	    'id' => 'createCompanyModal',
	    'size'=>'modal-lg',
	    'title' => 'Add New Company',
	    'body' => 'admin.company.create',
	    'submitBtnId' => 'createCompanySubmitBtn',
	    'submitBtn' => 'Submit',
	    'closeBtn' => 'Close',
	    'footer'=> 'true',
	    'form' => 'true',
	    'formId' => 'createCompanyForm',
	    'formMethod' => 'POST'
	    ])
    @endcomponent

    @component('layouts.components.modal', [
	    'id' => 'editCompanyModal',
	    'size'=>'modal-lg',
	    'title' => 'Edit Company',
	    'body' => 'admin.company.edit',
	    'submitBtnId' => 'editCompanySubmitBtn',
	    'submitBtn' => 'Save',
	    'closeBtn' => 'Close',
	    'footer'=> 'true',
	    'form' => 'true',
	    'formId' => 'updateCompanyForm',
	    'formMethod' => 'POST'
	    ])
    @endcomponent

    @component('layouts.components.modal', [
	    'id' => 'deleteCompanyModal',
	    'size'=>'modal-lg',
	    'title' => 'Delete Company',
	    'body' => 'admin.company.delete',
	    'submitBtnId' => 'deleteCompanySubmitBtn',
	    'submitBtn' => 'Delete',
	    'closeBtn' => 'Close',
	    'footer'=> 'true',
	    'form' => 'true',
	    'formId' => 'deleteCompanyForm',
	    'formMethod' => 'POST'
	    ])
    @endcomponent
@endsection

@section('script')
    @include('layouts.components.filter_form_script')
    @include('admin.company.script')
@endsection