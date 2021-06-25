<div class="form-row">
    <div class="form-group col-md-6">
        <label for="inputCreateFirstName">First Name<span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="first_name" id="inputCreateFirstName">
    </div>
    <div class="form-group col-md-6">
        <label for="inputCreateLastName">Last Name<span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="last_name" id="inputCreateLastName">
    </div>
    <div class="form-group col-md-4">
        <label for="inputCreateEmail">Email</label>
        <input type="email" class="form-control" name="email" id="inputCreateEmail">
    </div>
    <div class="form-group col-md-4">
        <label for="inputCreatePhone">Phone</label>
        <input type="text" class="form-control" name="phone" id="inputCreatePhone">
    </div>
    <div class="form-group col-md-4">
        <label for="inputCreateCompany">Company<span class="text-danger">*</span></label>
        <select class="form-control border selectpicker" name="company_id" id="inputCreateCompany" title="Select Company">
            @php
                $arr = getCompany();
                echo getSelectOptions($arr,'','')
            @endphp
        </select>
    </div>
</div>


