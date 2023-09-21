@extends('admin.layouts.main', ['activePage' => 'materials', 'titlePage' => 'Edit Materials'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" onsubmit="submitMaterialForm(event)"      enctype="multipart/form-data"
                        action="{{ url('admin/materialslots/' . $lots->id) }}" class="form-horizontal">
                        @csrf
                        @method('PATCH')

                        <div class="card">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <!--Header-->
                            <!-- <div class="card-header card-header-primary mx-0">
                                <h4 class="card-title">Edit Materials</h4>
                            </div> -->
                            <!--End header-->
                            <!--Body-->
                            <div class="card-body">
                            <div class="header_customer ">
                                         <div >
                                <h4 >Edit Materials</h4>
                            </div>
                        </div>
                                <input type="hidden" class="form_customer" id="lotid" name="lotid"
                                    value="{{ $lots->id }}" />


                                <input type="hidden" class="form-control" id="materialqnt" name="materialqnt"
                                    value="{{ count($materialilist) }}" />

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
                                        @foreach ($materialilist as $material)
                                            <tr>
                                                <input type="hidden" class="form-control" id="id" name="id[]"
                                                    value="{{ $material->id }}" />

                                                <td><input type='text' class='form-control' id='Product'
                                                        name='Product[]' autocomplete='off' autofocus
                                                        value="{{ $material->Product }}" required /></td>

                                                <td><input type='text' class='form-control' id='thick'
                                                        name='Thickness[]' autocomplete='off' autofocus
                                                        value="{{ $material->Thickness }}" required /></td>

                                                <td><input type='text' class='form-control' id='width' name='Width[]'
                                                        autocomplete='off' autofocus value="{{ $material->Width }}"
                                                        required />
                                                </td>

                                                <td><input type='text' class='form-control' id='length'
                                                        name='Length[]' autocomplete='off' autofocus
                                                        value="{{ $material->Length }}" required />
                                                </td>

                                                <td><input type='text' class='form-control' id='weight'
                                                        name='Weight[]' autocomplete='off' autofocus
                                                        value="{{ $material->Weight }}" required />
                                                </td>

                                                <td><input type='text' class='form-control' id='grade' name='Grade[]'
                                                        autocomplete='off' autofocus value="{{ $material->Grade }}"
                                                        required />
                                                </td>

                                                <td><input type='text' class='form-control' id='remark'
                                                        name='Remark[]' autocomplete='off' autofocus
                                                        value="{{ $material->Remark }}" required />
                                                </td>

                                                <td><input type='file' class='form-control' id='images'
                                                        name='images[]' accept='image/png, image/gif, image/jpeg' /></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{-- <button type="button" class="btn-sm btn btn-primary" onclick="newmaterial()">Add
                                    Material</button> --}}
                                <div>
                                    @foreach ($materialilist as $material)
                                        <img src="{{ url('files/' . $material->images) }}"
                                            style="width: 250px;height: 200px;">
                                    @endforeach
                                </div>

                            </div>

                            <!--End body-->
                            <!--Footer-->
                            <div class="card-footer ml-auto mr-auto">
                                <a href="{{ url('admin/lots/'.$lots->id) }}" class="btn btn-primary mr-3 Back_btn_customer">Back</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                        <!--End footer-->
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script>
        let materialqnt = <?php echo count($materialilist); ?>

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
@endsection
