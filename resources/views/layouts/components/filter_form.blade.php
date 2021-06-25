@if(isset($form) && $form == 'true')
<form action="{{isset($formAction) ? route($formAction) : ''}}" id="{{isset($formId) ? $formId : ''}}"
    method="{{isset($formMethod) ? $formMethod : ''}}">
    @csrf
    @endif
    <div id="accordion">
        <div class="card-filter-basic card shadow-on-hover">
            <div class="card-header">
                <div class="row align-items-center  no-gutters">
                    <div style="font-size: 16px;" class="col cursor-pointer" data-toggle="collapse"
                        data-target="#{{isset($targetId) ? $targetId : 'accFilters'}}" aria-expanded="true"
                        aria-controls="{{isset($targetId) ? $targetId : 'accFilters'}}">
                        Filters
                    </div>

                    {!!isset($copyBtn)?$copyBtn:''!!}
                    
                </div>
            </div>
            <div id="{{isset($targetId) ? $targetId : 'accFilters'}}" class="collapse " aria-labelledby="headingOne"
                 data-parent="#accordion">
                <div class="card-body">
                    <input type="hidden" name="page" id="filterPage" value="1">
                    <input type="hidden" name="no_of_records" id="filterNoOfRecords" value="10">
                    @include(isset($body) ? $body : '')
                    <div class="form-group float-right">
                        @if(isset($resetBtn))
                            <button class="mt-2 btn btn-outline-secondary text-capitalize" type="button"
                                    id="resetFilter">{{isset($resetBtn) ? $resetBtn : ''}} </button>@endif
                        @if(isset($searchBtn))
                            <button class="mt-2 btn btn-primary text-capitalize" id="submitFilter" type="submit"
                                    name="submit">{{isset($searchBtn) ? $searchBtn : ''}}</button>@endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>
