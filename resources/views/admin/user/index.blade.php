@extends('admin.layouts.main', ['activePage' => 'users', 'titlePage' => 'User'])
@section('content')
<style>
    .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
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
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }

  input:checked + .slider {
    background-color: #2196F3;
  }

  input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked + .slider:before {
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
</style>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header card-header-primary">
                    <h4 class="card-title">User</h4>
                  </div>
                  <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success" role="success">
                      {{ session('success') }}
                    </div>
                    @endif
                    <div class="row">
                      <div class="col-12 text-right">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-facebook">Add</a>
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table data-table">
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
            ajax:"{!! route('admin.users.index') !!}",

            columns: [
                {
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },   {
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
                    sorting:false,
                    render: function(o) {
                        // var element = '<div class="btn-group btn-sm">';
                       var element = `
                                    <label class="switch" for="flexSwitchCheckDefault_${o.id}">
                                        <input ${o.isApproved == "1"?'checked':''} onchange="couponstatus(` + o.id + ` ,event )" type="checkbox" name="active-box" id="flexSwitchCheckDefault_${o.id}">
                                        <span class="slider round"></span>
                                       </label>
                                `;
                        // element += '</div>';
                        return element;
                    }
                },
                {
                data: null,
                sorting:false,
                render: function(data, type, row) {
                    return(`<div><a href="{{ url('admin/users/${data.id}') }}"class="btn btn-info"><i class="material-icons">person</i></a>
                    `);
                },
            }
            ]
        });
    });

    function couponstatus(o, event) {
        if (event.srcElement.checked) {
            $.ajax({
                type: 'get',
                url: `coupon/status/${o}/1`,
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
                url: `coupon/status/${o}/0`,
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
