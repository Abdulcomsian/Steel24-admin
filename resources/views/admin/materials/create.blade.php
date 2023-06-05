@extends('admin.layouts.main', ['activePage' => 'materials', 'titlePage' => 'New Material'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" onsubmit="submitMaterialForm(event)"
                        action="{{ url('admin/addmaterialslots/' . $lots->id) }}" class="form-horizontal"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card ">
                            <!--Header-->
                            <div class="card-header card-header-primary">
                                <h4 class="card-title">Materials</h4>
                            </div>
                            <!--End header-->
                            <!--Body-->
                            <div class="card-body">

                                <input type="hidden" class="form-control" id="lotid" name="lotid"
                                    value="{{ $lots->id }}" />


                                <input type="hidden" class="form-control" id="materialqnt" name="materialqnt" />

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th> Product</th>
                                            <th> Thickness</th>
                                            <th> Width</th>
                                            <th> Length</th>
                                            <th> Weight</th>
                                            <th> Grade</th>
                                            <th> Remark</th>
                                            <th> images</th>

                                        </tr>
                                    </thead>
                                    <tbody id='tablebody'>

                                    </tbody>
                                </table>

                                <button type="button" class="btn-sm btn btn-primary" onclick="newmaterial()">Add
                                    Material</button>
                                <div class="card-footer d-flex justify-content-center ml-auto mr-auto">
                                    <a href="{{ url('admin/lots/create') }}" class="btn btn-primary">Back</a>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </div>

                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection


<script>
    let materialqnt = 0;

    function newmaterial() {

        tbody =
            "<td><input type='text' class='form-control' id='Product' name='Product[]' autocomplete='off' autofocus required/></td>";
        tbody +=
            "<td><input type='text' class='form-control' id='thick' name='Thickness[]' autocomplete='off' autofocus required/></td>";
        tbody +=
            "<td><input type='text' class='form-control' id='width' name='Width[]' autocomplete='off'  autofocus required/></td>";
        tbody +=
            "<td><input type='text' class='form-control' id='length' name='Length[]' autocomplete='off' autofocus required/></td>";
        tbody +=
            "<td><input type='text' class='form-control' id='weight' name='Weight[]' autocomplete='off' autofocus required/></td>";
        tbody +=
            "<td><input type='text' class='form-control' id='grade' name='Grade[]' autocomplete='off' autofocus required/></td>";
        tbody +=
            "<td><input type='text' class='form-control' id='remark' name='Remark[]' autocomplete='off' autofocus required/></td>";
        tbody +=
            "<td><input type='file' class='form-control' id='images' name='images[]' accept='image/png, image/gif, image/jpeg' /></td>";
        var tablebodyRef = document.getElementById('tablebody');
        let newRow = tablebodyRef.insertRow()
        newRow.innerHTML = tbody;
        materialqnt++;
    }

    function submitMaterialForm(event) {
        $('#materialqnt').val(materialqnt);

        return true;
    }
</script>
