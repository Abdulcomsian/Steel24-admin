<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


use App\Models\categories;
use App\Models\materials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MaterialFiles;

use DataTables;

class MaterialsController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }

    public function index(Request $request)
    {
        $materials = materials::all();
        if ($request->ajax()) {
            return Datatables::of($materials)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.materials.index')
            ->with('materials', $materials);
    }

    public function create()
    {
        $addForm = true;
        $materials = null;
        $categorys = categories::all();
        return view('admin.materials.create', compact('addForm', 'materials', 'categorys'));
    }

    public function store(Request $request)
    {
        $files = [];
        $userdetails = Auth::guard('admin')->user();
        $this->validate($request, [
            'title' => 'required|min:0',
            'description' => 'nullable',
            'thick' => 'required|min:0',
            'weight' => 'required|min:0',
            'width' => 'required|min:0',
            'price' => 'required|min:0',
            'coilLength' => 'nullable',
            'JSWgrade' => 'nullable',
            'grade' => 'nullable',
            'qty' => 'nullable',
            'majorDefect' => 'nullable',
            'coating' => 'nullable',
            'testedCoating' => 'nullable',
            'tinTemper' => 'nullable',
            'eqSpeci' => 'nullable',
            'heatNo' => 'nullable',
            'passivation' => 'nullable',
            'coldTreatment' => 'nullable',
            'plantNo' => 'nullable',
            'qualityRemark' => 'nullable',
            'storageLocation' => 'nullable',
            'edgeCondition' => 'nullable',
            'plantLotNo' => 'nullable',
            'inStock' => 'nullable',
            'filenames' => 'required',
            'filenames.*' => 'image'
        ]);
        $input = $request->only([
            'title',
            'description',
            'thick',
            'weight',
            'width',
            'price',
            'coilLength',
            'JSWgrade',
            'grade',
            'qty',
            'majorDefect',
            'coating',
            'testedCoating',
            'tinTemper',
            'eqSpeci',
            'heatNo',
            'passivation',
            'coldTreatment',
            'plantNo',
            'qualityRemark',
            'storageLocation',
            'edgeCondition',
            'plantLotNo',
            'inStock',
        ]);
        $input['uid'] = $userdetails->id;
        // dd($input);
        $details = materials::create($input);

        if ($request->hasfile('filenames')) {
            foreach ($request->file('filenames') as $file) {
                $name = time() . rand(1, 100) . '.' . $file->extension();
                $file->move(public_path('files'), $name);
                $files[] = $name;
            }
        }

        $file = new MaterialFiles();
        $file->filenames = $files;
        $file->material_id = $details->id;
        $file->save();
        return redirect('admin/materials');
    }

    public function show(materials $materials)
    {
        $id = $materials->id;
        $categorys = categories::all();
        $images = MaterialFiles::select("images_names")->where("material_id", "=", $id)->first();
        // dd(json_decode($images));
        return view('admin.materials.show', compact('materials', 'categorys', 'images'));
    }

    public function edit(materials $materials)
    {
        $categorys = categories::all();
        return view('admin.materials.edit', compact('materials', 'categorys'));
    }

    public function update(Request $request, materials $materials)
    {

        $files = [];

        $materials->update($request->validate([
            'title' => 'required|',
            'description' => 'nullable',
            'thick' => 'required|min:0',
            'weight' => 'required|min:0',
            'width' => 'required|min:0',
            'price' => 'required|min:0',
            'coilLength' => 'nullable',
            'JSWgrade' => 'nullable',
            'grade' => 'nullable',
            'qty' => 'nullable',
            'majorDefect' => 'nullable',
            'coating' => 'nullable',
            'testedCoating' => 'nullable',
            'tinTemper' => 'nullable',
            'eqSpeci' => 'nullable',
            'heatNo' => 'nullable',
            'passivation' => 'nullable',
            'coldTreatment' => 'nullable',
            'plantNo' => 'nullable',
            'qualityRemark' => 'nullable',
            'storageLocation' => 'nullable',
            'edgeCondition' => 'nullable',
            'plantLotNo' => 'nullable',
            'inStock' => 'nullable',
        ]));

        $materialFiles = MaterialFiles::find($materials->id);

        if ($request->hasFile('filenames')) {
            foreach ($request->file('filenames') as $file) {
                $name = time() . rand(1, 100) . '.' . $file->extension();
                $file->move(public_path('files'), $name);
                $files[] = $name;
            }
            $materialFiles->filenames = $files;
            $materialFiles->update($materialFiles->filenames = $files);
        }
        return redirect('admin/materials');
    }

    public function destroy(materials $materials)
    {
        $materials->delete();
        return redirect('admin/materials');
    }
}
