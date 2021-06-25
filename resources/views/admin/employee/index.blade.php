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
	                <div class="btn btn-primary btn-sm text-right" data-toggle="modal" data-target="#createEmployeeModal">
						Add New Employee
	                </div>
	            </div>
	        </div>
        </div>

        <!-- Filter Form -->
        <div class="col-md-12 my-2">
    		@component('layouts.components.filter_form',
                [
                'body' => 'admin.employee.filter',
                'form' => 'true',
                'formAction' => 'admin.employee.show',
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
	    'id' => 'createEmployeeModal',
	    'size'=>'modal-lg',
	    'title' => 'Add New Employee',
	    'body' => 'admin.employee.create',
	    'submitBtnId' => 'createEmployeeSubmitBtn',
	    'submitBtn' => 'Submit',
	    'closeBtn' => 'Close',
	    'footer'=> 'true',
	    'form' => 'true',
	    'formAction' => 'admin.employee.store',
	    'formId' => 'createEmployeeForm',
	    'formMethod' => 'POST'
	    ])
    @endcomponent

    @component('layouts.components.modal', [
	    'id' => 'editEmployeeModal',
	    'size'=>'modal-lg',
	    'title' => 'Edit Employee',
	    'body' => 'admin.employee.edit',
	    'submitBtnId' => 'editEmployeeSubmitBtn',
	    'submitBtn' => 'Save',
	    'closeBtn' => 'Close',
	    'footer'=> 'true',
	    'form' => 'true',
	    'formAction' => 'admin.employee.update',
	    'formId' => 'updateEmployeeForm',
	    'formMethod' => 'POST'
	    ])
    @endcomponent

    @component('layouts.components.modal', [
	    'id' => 'deleteEmployeeModal',
	    'size'=>'modal-lg',
	    'title' => 'Delete Employee',
	    'body' => 'admin.employee.delete',
	    'submitBtnId' => 'deleteEmployeeSubmitBtn',
	    'submitBtn' => 'Delete',
	    'closeBtn' => 'Close',
	    'footer'=> 'true',
	    'form' => 'true',
	    'formAction' => 'admin.employee.delete',
	    'formId' => 'deleteEmployeeForm',
	    'formMethod' => 'POST'
	    ])
    @endcomponent
@endsection

@section('script')
    @include('layouts.components.filter_form_script')
    @include('admin.employee.script')
@endsection