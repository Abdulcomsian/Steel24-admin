<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\MaterialFiles;
use Illuminate\Http\Request;
use App\Models\categories;
use App\Models\materials;
use Illuminate\Support\Facades\Auth;
use DataTables;

class MaterialFilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }
    public function create()
    { $addForm = true;
        $materials = materials::all();
        $lots = false;
        return view('admin.materials.uploadImage', compact('addForm', 'materials', 'lots'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'filenames' => 'required',
            'filenames.*' => 'image'
    ]);

    $files = [];

    if($request->hasfile('filenames'))
     {
        foreach($request->file('filenames') as $file)
        {
            $name = time().rand(1,100).'.'.$file->extension();
            $file->move(public_path('files'), $name);
            $files[] = $name;
        }
     }

     $file= new MaterialFiles();
     $file->filenames = $files;
     $file->material_id = $request->materialId;
     $file->save();

    return back()->with('success', 'Data Your files has been successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MaterialFiles  $materialFiles
     * @return \Illuminate\Http\Response
     */
    public function show(MaterialFiles $materialFiles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MaterialFiles  $materialFiles
     * @return \Illuminate\Http\Response
     */
    public function edit(MaterialFiles $materialFiles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MaterialFiles  $materialFiles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MaterialFiles $materialFiles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MaterialFiles  $materialFiles
     * @return \Illuminate\Http\Response
     */
    public function destroy(MaterialFiles $materialFiles)
    {
        //
    }
}
