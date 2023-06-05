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
                            <h4 class="card-title">Lot Materials </h4>
                        </div>
                        <!--End header-->
                        <!--Body-->
                        <div class="card-body">
                            @if ($material_keys && $materialilist)
                                <h4>Materials</h4>
                                <div class="col-12">
                                    <form method="POST" onsubmit="submitMaterialForm(event)"
                                        action="{{ url('admin/materialslots/' . $lots->id) }}" class="form-horizontal"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" class="form-control" id="newheader" name="materialqnt"
                                            value="{{ count($newmateriali) }}" />
                                        <table class="table" style="display: block;overflow-x: auto; ">
                                            <thead>
                                                <tr>

                                                    @foreach ($material_keys as $key)
                                                        <th scope="col">{{ $key }}</th>
                                                    @endforeach
                                                    <th scope="col">Image</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($newmateriali as $materiali)
                                                    <tr>
                                                        <input type="hidden" class="form-control" id="newheader"
                                                            name="materialid[]" value="{{ $materiali['materialid'] }}" />

                                                        @foreach ($materiali['data'] as $key => $val)
                                                            <td>
                                                                <input type="text" class="form-control" id="newheader"
                                                                    name="{{ $key }}[]"
                                                                    value="{{ $val }}" />
                                                            </td>
                                                        @endforeach
                                                        <td>
                                                            <img src="{{ url('files/' . $materiali['image']) }}"
                                                                style="width:50px; height:50px;">

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="card-footer justify-content-center ml-auto mr-auto">
                                            <a href="{{ url('admin/lots/' . $lots->id) }}" class="btn btn-primary">Back</a>
                                            <button type="submit" class="btn btn-primary">Save And Add Material</button>
                                        </div>
                                    </form>
                                </div>
                            @endif

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
