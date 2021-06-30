<script type="text/javascript">
    show('form#filterForm');

    function show(data) {
        let form = $('#filterForm');
        let url = '{{route('admin.company.show')}}';

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
                    tableCode += '<th scope="col">Logo</th>'
                    tableCode += '<th scope="col">Name</th>'
                    tableCode += '<th scope="col">Email</th>'
                    
                    tableCode += '<th scope="col">Website</th>'
                    tableCode += '<th scope="col">Created</th>'
                    tableCode += '<th scope="col">Actions</th>'
                    tableCode += '</tr>'
                    tableCode += '</thead>'
                    tableCode += '<tbody id="table-body">'
                    queryData.forEach(function (row) {
                        tableCode += '<tr>'
                        tableCode += '<th scope="row">' + counter + '</th>'
                        tableCode += '<td><img src="' + row.logo + '"class="img-thumbnail" width="100" height="100"></td>'
                        tableCode += '<td>' + row.name + '</td>'
                        tableCode += '<td>' + row.email + '</td>'
                        tableCode += '<td>' + row.website + '</td>'
                        tableCode += '<td>' + row.created_at + '</td>'
                        tableCode += '<td>';
                        tableCode += '<button type="button" class="btn btn-warning btn-sm mr-1" onclick="editCompany(' + row.id + ')">Edit</button>';
                        tableCode += '<button type="button" class="btn btn-danger btn-sm" onclick="deleteCompany(' + row.id + ')">Delete</button>';
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

    $('form#createCompanyForm').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);
        let url = '{{route('admin.company.store')}}';
        let formData = new FormData();
        let imagefile = document.querySelector('#inputCreateLogo');

        if( document.getElementById("inputCreateLogo").files.length != 0 ){
            formData.append("logo", imagefile.files[0]);
        }

        formData.append("name", jQuery('#inputCreateName').val());
        formData.append("email", jQuery('#inputCreateEmail').val());
        formData.append("website", jQuery('#inputCreateWebsite').val());

        loaderBtn(true, '#createCompanySubmitBtn');

        // console.log(formData);
        axios.post(url, formData)
            .then(function (response) {
                loaderBtn(false, '#createCompanySubmitBtn');

                if (response.data.success == true) {
                    toastr.success(response.data.message)
                    show('form#filterForm');
                    $('#createCompanyModal').modal('hide');
                    $('#createCompanyForm')[0].reset();
                } else {
                    console.log(response)
                    toastr.error(response.data.message)
                }
            })
            .catch(function (error) {
                toastr.error(error)
            });
    });

    function editCompany(id) {
        let url = '{{route('admin.company.edit', ':id')}}';
        url = url.replace(':id', id);

        let data = {
            id: id,
        };

        axios.get(url, data)
            .then(function (response) {
                if (response.data.success == true) {
                    let data = response.data.data[0];
                    $('#editCompanyModal').modal('show');
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#website').val(data.website);
                    $('#id').val(data.id);
                } else {
                    toastr.error(response.data.message)
                }
            }).catch(function (error) {
            toastr.error(error)
        });
    }

    $('form#updateCompanyForm').submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let url = '{{route('admin.company.update', ':id')}}';
        url = url.replace(':id', $('#id').val());
        let formData = new FormData();
        let imagefile = document.querySelector('#logo');

        if( document.getElementById("logo").files.length != 0 ){
            formData.append("logo", imagefile.files[0]);
        }

        formData.append("name", jQuery('#name').val());
        formData.append("email", jQuery('#email').val());
        formData.append("website", jQuery('#website').val());
        formData.append("id", jQuery('#id').val());

        loaderBtn(true, '#editCompanySubmitBtn');
        axios.post(url,formData)
            .then(function (response){
                loaderBtn(false, '#editCompanySubmitBtn');
                if (response.data.success == true) {
                    toastr.success(response.data.message)
                    $('#editCompanyModal').modal("hide");
                    $('#updateCompanyForm')['0'].reset();
                    show('form#filterForm');
                }else{
                    toastr.error(response.data.message)
                }
            })
            .catch(function (error){
                toastr.error(error)
            });

    });

    function deleteCompany(id) {
        $('#deleteCompanyModal').modal('show');
        $('#inputDeleteId').val(id);
    }

    $('form#deleteCompanyForm').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);
        let url = '{{route('admin.company.destroy', ':id')}}';
        url = url.replace(':id', $('#inputDeleteId').val());
        let data = $(this).serialize();
        loaderBtn(true, '#deleteCompanySubmitBtn');
        axios.delete(url, data)
            .then(function (response) {
                loaderBtn(false, '#deleteCompanySubmitBtn');

                if (response.data.success == true) {
                    toastr.error(response.data.message)
                    show('form#filterForm');
                    $('#deleteCompanyModal').modal('hide');
                    $('#deleteCompanyForm')[0].reset();
                } else {
                    toastr.error(response.data.message)
                }
            })
            .catch(function (error) {
                toastr.error(error)

            });
    });
</script>