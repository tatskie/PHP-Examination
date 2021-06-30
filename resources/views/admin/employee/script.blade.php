<script type="text/javascript">
    show('form#filterForm');

    function show(data) {
        let form = $('#filterForm');
        let url = '{{route('admin.employee.show')}}';

        let method = form.attr('method');
        let theData = $(data).serialize();

        axios.post(url, theData)
            .then(function (response) {
                let htmlCode = '';
                let tableCode = "";
                let topTextCode = "";
                let bottomTextCode = "";
                let paginationCode = "";
                let queryData = response.data.data;
                let filterAppliedData = response.data.filterApplied;

                let recordsFound = response.data.totalRecords;
                let onPage = $('#filterPage').val();

                if (recordsFound || filterAppliedData) {
                    htmlCode += '<div class="filter-form mb-3">';
                    if (recordsFound == 1) {
                        htmlCode += '<span class="mx-1 badge-dark filter-message ">' + recordsFound + ' result found</span>';
                    } else if (recordsFound > 1) {
                        htmlCode += '<span class="mx-1 badge-dark filter-message ">' + recordsFound + ' results found</span>';
                    }

                    if (onPage > 1) {
                        htmlCode += '<span class="mx-1 badge-dark filter-message  "> on page ' + onPage + '</span>';
                    }

                    for (const property in filterAppliedData) {
                        htmlCode += '<span class="mx-1 badge-dark filter-message ">' + filterAppliedData[property] + '';
                        htmlCode += '<span class="mdi mdi-window-close arrow-down   align-middle cursor-pointer thatTooltip pl-3" onclick="filterCancel(\'' + property + '\')"></span>';
                        htmlCode += '</span>';
                    }
                    htmlCode += '</div>';
                }



                if (queryData != null && queryData != "" && queryData) {
                    var counter = 1;

                    tableCode += '<thead>'
                    tableCode += '<tr>'
                    tableCode += '<th scope="col">NO</th>'
                    tableCode += '<th scope="col">First Name</th>'
                    tableCode += '<th scope="col">Last Name</th>'
                    tableCode += '<th scope="col">Email</th>'
                    tableCode += '<th scope="col">Phone</th>'
                    tableCode += '<th scope="col">Company</th>'
                    tableCode += '<th scope="col">Created</th>'
                    tableCode += '<th scope="col">Actions</th>'
                    tableCode += '</tr>'
                    tableCode += '</thead>'
                    tableCode += '<tbody id="table-body">'
                    queryData.forEach(function (row) {
                        tableCode += '<tr>'
                        tableCode += '<th scope="row">' + counter + '</th>'
                        tableCode += '<td>' + row.first_name + '</td>'
                        tableCode += '<td>' + row.last_name + '</td>'
                        tableCode += '<td>' + row.email + '</td>'
                        tableCode += '<td>' + row.phone + '</td>'
                        tableCode += '<td>' + row.company + '</td>'
                        tableCode += '<td>' + row.created_at + '</td>'
                        tableCode += '<td>';
                        tableCode += '<button type="button" class="btn btn-warning btn-sm mr-1" onclick="editEmployee(' + row.id + ')">Edit</button>';
                        tableCode += '<button type="button" class="btn btn-danger btn-sm" onclick="deleteEmployee(' + row.id + ')">Delete</button>';
                         tableCode += '</td>'
                        tableCode += '</tr>'

                        counter++;
                    });
                    tableCode += '</tbody>'

                    paginationCode += '<div id="filterFormPagination">' + response.data.webPagination + '</div>';
                }else if(recordsFound == 0 && filterAppliedData){
                    htmlCode += '<div class="alert alert-secondary text-center" role="alert">';
                    htmlCode += 'Requested data does not match with our database';
                    htmlCode += '</div>';
                } else {
                    htmlCode += '<div class="alert alert-secondary text-center" role="alert">';
                    htmlCode += 'There are no records, please add a new record';
                    htmlCode += '</div>';
                }
                $('#filter-show').html(htmlCode);
                $('#tracker-table').html(tableCode);
                $('#pagination-show').html(paginationCode);
                $('.ViewTopPerformerBtn').tooltip();
                $('.thatTooltip').tooltip();
            })
            .catch(function (error) {
                toastr.error(error)

            });
    }

    $('form#createEmployeeForm').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);
        let url = '{{route('admin.employee.store')}}';
        let data = $(this).serialize();

        loaderBtn(true, '#createEmployeeSubmitBtn');
        axios.post(url, data)
            .then(function (response) {
                loaderBtn(false, '#createEmployeeSubmitBtn');

                if (response.data.success == true) {
                    toastr.success(response.data.message)
                    show('form#filterForm');
                    $('#createEmployeeModal').modal('hide');
                    $('#createEmployeeForm')[0].reset();
                } else {
                    toastr.error(response.data.message)
                }
            })
            .catch(function (error) {
                toastr.error(error)
            });
    });

    function editEmployee(id) {
        let url = '{{route('admin.employee.edit', ':id')}}';
        url = url.replace(':id', id);

        let data = {
            id: id,
        };
        $('#inputEditCompany').html('');

        axios.get(url, data)
            .then(function (response) {
                if (response.data.success == true) {
                    let data = response.data.data[0];
                    $('#editEmployeeModal').modal('show');
                    $('#inputEditFirstName').val(data.first_name);
                    $('#inputEditLastName').val(data.last_name);
                    $('#inputEditEmail').val(data.email);
                    $('#inputEditPhone').val(data.phone);
                    $('#inputEditCompany').append(data.company);
                    $('#inputEditId').val(data.id);
                } else {
                    toastr.error(response.data.message)
                }
            }).catch(function (error) {
            toastr.error(error)
        });
    }

    $('form#updateEmployeeForm').submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let url = '{{route('admin.employee.update', ':id')}}';
        url = url.replace(':id', $('#inputEditId').val());
        let data = $(this).serialize();

        loaderBtn(true, '#editEmployeeSubmitBtn');
        axios.put(url,data)
            .then(function (response){
                loaderBtn(false, '#editEmployeeSubmitBtn');
                if (response.data.success == true) {
                    toastr.success(response.data.message)
                    $('#editEmployeeModal').modal("hide");
                    $('#updateEmployeeForm')['0'].reset();
                    show('form#filterForm');
                }else{
                    toastr.error(response.data.message)
                }
            })
            .catch(function (error){
                toastr.error(error)
            });

    });

    function deleteEmployee(id) {
        $('#deleteEmployeeModal').modal('show');
        $('#inputDeleteId').val(id);
    }

    $('form#deleteEmployeeForm').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);
        let url = '{{route('admin.employee.destroy', ':id')}}';
        url = url.replace(':id', $('#inputDeleteId').val());
        let data = $(this).serialize();
        loaderBtn(true, '#deleteEmployeeSubmitBtn');
        axios.delete(url, data)
            .then(function (response) {
                loaderBtn(false, '#deleteEmployeeSubmitBtn');

                if (response.data.success == true) {
                    toastr.error(response.data.message)
                    show('form#filterForm');
                    $('#deleteEmployeeModal').modal('hide');
                    $('#deleteEmployeeForm')[0].reset();
                } else {
                    toastr.error(response.data.message)
                }
            })
            .catch(function (error) {
                toastr.error(error)

            });
    });
</script>