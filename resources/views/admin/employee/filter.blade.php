<div class="form-row">
    <div class="form-group col-md-6">
        <label for="inputFilterFirstName">First Name<span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="first_name" id="inputFilterFirstName">
    </div>
    <div class="form-group col-md-6">
        <label for="inputFilterLastName">Last Name<span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="last_name" id="inputFilterLastName">
    </div>
    <div class="form-group col-md-4">
        <label for="inputFilterEmail">Email</label>
        <input type="email" class="form-control" name="email" id="inputFilterEmail">
    </div>
    <div class="form-group col-md-4">
        <label for="inputFilterPhone">Phone</label>
        <input type="text" class="form-control" name="phone" id="inputFilterPhone">
    </div>
    <div class="form-group col-md-4">
        <label for="inputFilterCompany">Company<span class="text-danger">*</span></label>
        <select class="form-control border selectpicker" name="company" id="inputFilterCompany" title="Select Company">
            @php
                $arr = getCompany();
                echo getSelectOptions($arr,'','Select company')
            @endphp
        </select>
    </div>
</div>


