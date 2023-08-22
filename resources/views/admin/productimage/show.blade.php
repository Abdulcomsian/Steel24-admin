@extends('admin.layouts.main', ['activePage' => 'posts', 'titlePage' => 'Detalles del post'])

@section('content')
<div class="container">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!-- Header -->
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Product Images</h4>
                        </div>
                        <!-- End header -->
                        <!-- Body -->
                        <div class="card-body">
                            <div class="row">
                                <!-- Product Image Details -->
                                <div class="col-md-12">
                                    <div class="card card-user">
                                        <div class="card-body">
                                            <p class="card-text">
                                                <div class="author">
                                                    <div class="block block-one"></div>
                                                    <div class="block block-two"></div>
                                                    <div class="block block-three"></div>
                                                    <div class="block block-four"></div>
                                                    <a href="#">
                                                        <h3 class="title mt-3 text-center">Product Images Details</h3>
                                                    </a>
                                                    <table class="table align-items-center mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                                    Fields</th>
                                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                                    Details</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex px-2 py-1">
                                                                        <div class="d-flex flex-column justify-content-center">
                                                                            <h6 class="mb-0 text-sm">Product Images name</h6>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs text-secondary mb-0">
                                                                        {{ $productimage->title }}</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex px-2 py-1">
                                                                        <div class="d-flex flex-column justify-content-center">
                                                                            <h6 class="mb-0 text-sm">Description</h6>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <p class="text-xs text-secondary mb-0">
                                                                        {{ $productimage->description }}</p>
                                                                </td>
                                                            </tr>

                                                            {{-- <tr>
                                                                <td>
                                                                    <div class="d-flex px-2 py-1">
                                                                        <div class="d-flex flex-column justify-content-center">
                                                                            <h6 class="mb-0 text-sm">Image</h6>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <img src="{{ asset('storage/' . $productimage->image) }}" alt="" class="img-fluid">
                                                                </td>
                                                            </tr> --}}

                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex px-2 py-1">
                                                                        <div class="d-flex flex-column justify-content-center">
                                                                            <h6 class="mb-0 text-sm">Image</h6>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    {{-- <img src="{{ asset($productimage->image) }}" alt="Product Image" class="img-fluid" style="max-width: 200px; max-height: 200px;"> --}}
                                                                    <img src="{{ asset($productimage->image) }}" alt="Product Image" class="img-fluid" style="max-width: 200px; max-height: 200px;">
                                                                </td>                                                                
                                                            </tr>                                                            
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </p>
                                        </div>

                                        <div class="card-footer">
                                            <div class="button-container">
                                                <a href="{{ url('admin/productimage/') }}" class="btn btn-sm btn-primary Back_btn_customer">Back</a>
                                                <a href="{{ url('admin/productimages/' . $productimage->id . '/edit') }}" class="btn btn-sm btn-success">Update</a>
                                                <a href="javascript:void(0)" id="{{ $productimage->id }}" class="btn btn-danger btn-sm remove" onclick="confirmRemoval({{ $productimage->id }})">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Product Image Details -->
                            </div>
                            <!-- End row -->
                        </div>
                        <!-- End card body -->
                    </div>
                    <!-- End card -->
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

<script>
    // $(document).on('click', '.remove', function(e) 
    // {
    //     e.preventDefault();
    //     var id = $(this).attr('id');
    //     var token = $("meta[name='csrf-token']").attr("content");
    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: 'Once deleted, you will not be able to recover this Product Images!',
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: "#DD6B55",
    //         confirmButtonText: "Yes, Remove!",
    //         showLoaderOnConfirm: true,
    //         preConfirm: function() 
    //         {
    //             return new Promise(function(resolve, reject) 
    //             {
    //                 setTimeout(function() 
    //                 {
    //                     $.ajax({
    //                         url: "{{ url('/admin/productimages/destroy') }}" + "/" + id,
    //                         type: 'post',
    //                         data: {
    //                             "id": id,
    //                             "_token": token,
    //                         },
    //                         success: function(data) {
    //                             Swal.fire("Success! Product Images has been deleted!", {
    //                                 icon: "success",
    //                             });
    //                             $('.data-table').DataTable().ajax.reload(null, true);
    //                         }
    //                     });
    //                 }, 0);
    //             });
    //         },
    //     });
    // });

//     $(document).on('click', '.remove', function(e) 
// {
//     e.preventDefault();
//     var id = $(this).attr('id');
//     var token = $("meta[name='csrf-token']").attr("content");
//     Swal.fire({
//         title: 'Are you sure?',
//         text: 'Once deleted, you will not be able to recover this Product Images!',
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: "#DD6B55",
//         confirmButtonText: "Yes, Remove!",
//         showLoaderOnConfirm: true,
//         preConfirm: function() 
//         {
//             return new Promise(function(resolve, reject) 
//             {
//                 setTimeout(function() 
//                 {
//                     $.ajax({
//                         url: "{{ url('/admin/productimages/destroy') }}" + "/" + id,
//                         type: 'post',
//                         data: {
//                             "id": id,
//                             "_token": token,
//                         },
//                         success: function(data) {
//                             Swal.fire({
//                                 title: 'Success!',
//                                 text: 'Product Images has been deleted!',
//                                 icon: 'success'
//                             }).then(function(result) {
//                                 if (result.value) {
//                                     // Reload only the current page without redirecting
//                                     $('.data-table').DataTable().ajax.reload(null, false);
//                                 }
//                             });
//                         }
//                     });
//                 }, 0);
//             });
//         },
//     });
// });

$(document).on('click', '.remove', function(e) 
{
    e.preventDefault();
    var id = $(this).attr('id');
    var token = $("meta[name='csrf-token']").attr("content");
    Swal.fire({
        title: 'Are you sure?',
        text: 'Once deleted, you will not be able to recover this Product Images!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, Remove!",
        showLoaderOnConfirm: true,
        preConfirm: function() 
        {
            return new Promise(function(resolve, reject) 
            {
                setTimeout(function() 
                {
                    $.ajax({
                        url: "{{ url('/admin/productimages/destroy') }}" + "/" + id,
                        type: 'post',
                        data: {
                            "id": id,
                            "_token": token,
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Product Images has been deleted!',
                                icon: 'success'
                            }).then(function(result) {
                                if (result.value) {
                                    // Redirect to the index route
                                    window.location.href = "{{ url('admin/productimage') }}";
                                }
                            });
                        }
                    });
                }, 0);
            });
        },
    });
});


</script>
