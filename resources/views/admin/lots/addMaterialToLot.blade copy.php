@extends('admin.layouts.main', ['activePage' => 'lots', 'titlePage' => 'New Lots'])
{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card ">
                        <!--Header-->
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Add Lot Material </h4>
                        </div>
                        <!--End header-->
                        <!--Body-->
                        <div class="card-body">

                            <div class="row" id="headerForm">
                                <label for="participate_fee" class="col-2 col-form-label col-form-label">Add
                                    Title</label>
                                <input type="text" class="col-6 form-control" id="newheader" name="newheader"
                                    autocomplete="off" autofocus>
                                <div class="col-2">
                                    <button type="button" class="btn-sm btn btn-primary"
                                        onclick="addHeaderValue(event,this)">Add</button>
                                </div>
                            </div>
                            <button type="button" class="btn-sm btn btn-primary" onclick="newmaterial()">Add
                                Material</button>
                            <form method="POST" onsubmit="submitMaterialForm(event)"
                                action="{{ url('admin/addmaterialslots/' . $lots->id) }}" class="form-horizontal"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="materialqnt" name="materialqnt" />
                                <table class="table" style="display: block;overflow-x: auto; ">
                                    <thead>
                                        <tr id="dynamicHeader"></tr>
                                    </thead>
                                    <tbody id="dynamicBody">
                                        <tr></tr>
                                    </tbody>
                                </table>
                                <div class="card-footer justify-content-center ml-auto mr-auto">
                                    <a href="{{ url('admin/lots') }}" class="btn btn-primary">Back</a>
                                    <button type="submit" class="btn btn-primary">Save And Add Material</button>
                                </div>
                            </form>

                        </div>

                        <!--End body-->

                        <!--Footer-->
                        <!--End footer-->
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    var headerlist = [];
    var materialsData = [];
    // let bodyRow = tbodyRef.insertRow()
    let materialqnt = 0;


    function addHeaderValue(event, that) {
        event.preventDefault()
        let inputval = $('#newheader').val();
        if ($('#newheader').val().trim() == '') {
            alert('Add Title.');
        } else {
            headerlist.push(inputval);
            var dynamicHeader = document.getElementById("dynamicHeader");
            var cell = dynamicHeader.insertCell();
            cell.innerHTML = inputval;

            // var newCell = bodyRow.insertCell();
            // newCell.innerHTML = '<input type="text" class="form-control" id="newheader" name="' + inputval + '[]">'
            $('#newheader').val('')
        }
        return false;
    }

    function newmaterial() {

        document.getElementById('headerForm').style.display = 'none'

        var tbodyRef = document.getElementById('dynamicBody');

        let newRow = tbodyRef.insertRow()
        headerlist.forEach((header, index) => {
            var newCell = newRow.insertCell();
            newCell.innerHTML = '<input type="text" class="form-control" id="newheader" name="' + header +
                '[]" >'
        });
        var newCell = newRow.insertCell();
        newCell.innerHTML =
            '<input type="file" class="form-control" id="newheader" name="images[]" accept="image/png, image/gif, image/jpeg" >'

        materialqnt++;
    }

    function submitMaterialForm(event) {
        $('#materialqnt').val(materialqnt);

        var cell = dynamicHeader.insertCell();
        cell.innerHTML = 'images';
        return true;
    }
</script>
