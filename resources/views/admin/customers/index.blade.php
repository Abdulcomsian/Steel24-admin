@extends('admin.layouts.main', ['activePage' => 'customers', 'titlePage' => 'Customers'])
@section('content')
<style>
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 1.5rem;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
    height: 1.5rem;
    width: 3rem;
}

.slider:before {
    position: absolute;
    content: "";
    height: 1.5rem;
    width: 1.5rem;
    left: -2px;
    bottom: 0px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
}

input:checked+.slider {
    background-color: #d567e8;
}

input:focus+.slider {
    box-shadow: 0 0 1px #2196F3;
}

input:checked+.slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
}

.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}

.header_customer {
    display:flex;
    justify-content:space-between;
    align-items: center;
}
.header_customer h4 {
    font-size: 22px;
    font-weight: 500;
}

</style>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <!-- <div class="card-header card-header-primary">
                                <h4 class="card-title">Customers</h4>
                            </div> -->
                            <div class="card-body">
                                @if (session('success'))
                                <div class="alert alert-success" role="success">
                                    {{ session('success') }}
                                </div>
                                @endif
                                <div class="row">
                                    <div class="col-12 text-right header_customer">
                                    <div>
                                         <div >
                                <h4 >Customers</h4>
                            </div>
                        </div>
                                        <a href="{{ route('admin.customers.create') }}" class="btn btn-outline-info add_New_Button"
                                           >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="mr-2"
                                                fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z" />
                                            </svg>New</a>
                                    </div>
                                </div>
                                <div class="">
                                    <table class="table data-table table-striped w-100">
                                        <thead class="text-primary text-center">
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Approved</th>
                                            <th>User</th>
                                        </thead>
                                        <tbody class="text-center">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer mr-auto">
                                {{-- {{ $auctions->links() }} --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{!! route('admin.customers.index') !!}",
        lengthChange: false, // This disables the "Show [X] entries" dropdown
        searching: false, 
        columns: [
            {
                data: 'id',
                name: 'id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                responsivePriority: 1,
                targets: 0,
                className: "text-center m2",
                render: function(o) {
                    var element = '<div class="btn-group">';
                    element += `
                        <label class="switch" for="flexSwitchCheckDefault_${o.id}">
                            <input ${o.isApproved == "1"?'checked':''} onchange="couponstatus(` + o.id + `, event )" type="checkbox" name="active-box" id="flexSwitchCheckDefault_${o.id}">
                            <span class="slider round"></span>
                        </label>
                    `;
                    element += '</div>';
                    return element;
                }
            },
            {
                data: null,
                sorting: false,
                render: function(data, type, row) {
                    return (`<div><a href="{{ url('admin/customers/${data.id}') }}" class="btn btn-info btn-sm" style="font-weight:500">Details</a>`);
                },
            }
        ]
    });
});
</script>

<script>
$(document).on('click', '.remove', function(e) {
    e.preventDefault();
    var id = $(this).attr('id');
    var token = $("meta[name='csrf-token']").attr("content");
    Swal.fire({
        title: 'Are you sure?',
        text: 'Once deleted, you will not be able to recover this Customer!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, Remove!",
        showLoaderOnConfirm: true,
        preConfirm: function() {
            return new Promise(function(resolve, reject) {
                setTimeout(function() {
                    $.ajax({
                        url: "{{ url('/admin/customers') }}" + "/" + id,
                        type: 'delete',
                        data: {
                            "id": id,
                            "_token": token,
                        },
                        success: function(data) {
                            Swal.fire(
                                "Success! Customer has been deleted!", {
                                    icon: "success",
                                });
                            $('.data-table').DataTable().ajax
                                .reload(null, true);
                        }
                    });
                }, 0);
            });
        },
    });
});
</script>
<style>
.sorting:before,
.sorting_asc:before,
.sorting_desc:before,
.sorting:after,
.sorting_asc:after,
.sorting_desc:after,
.sorting::after {
    display: none !important;
}
</style>
<script>
function couponstatus(o, event) {
    if (event.srcElement.checked) {
        $.ajax({
            type: 'get',
            url: `customer/status/${o}/1`,
            success: function(d) {
                toastr.success("status Active successfuly");
            },
            error: function(xhr, status, error) {
                toastr.error('Something went wrong, please try again later.');
            }
        });
    } else {
        $.ajax({
            type: 'get',
            url: `customer/status/${o}/0`,
            success: function(d) {
                toastr.success("status DeActive successfuly");
            },
            error: function(xhr, status, error) {
                toastr.error('Something went wrong, please try again later.');
            }
        });
    }

}
</script>