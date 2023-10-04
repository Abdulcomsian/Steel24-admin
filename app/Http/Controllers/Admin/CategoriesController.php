<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\categories;
use Illuminate\Http\Request;
use DataTables;
// use Yajra\DataTables\DataTables;


class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }
    
    // public function index(Request $request)
    // {
    //     $categories = categories::all();
    //     if ($request->ajax()) {
    //       return Datatables::of($categories)
    //               ->addIndexColumn()
    //               ->rawColumns(['action'])
    //               ->make(true);
    //   }
    //     return view('admin.categories.index')
    //         ->with('categories', $categories);
    // }
    
    public function index(Request $request)
    {
        if ($request->ajax()) 
        {
            $categories = categories::all();
             
            return Datatables::of($categories)
                ->addIndexColumn()
                ->addColumn('action', function ($category) 
                {
                    return '<a href="' . url('admin/categories/show/' . $category->id) . '" class="btn btn-info btn-sm">Details</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $categories = categories::all();
        
        return view('admin.categories.index')
            ->with('categories', $categories);
    }

    public function create()
    {
        $parentcategories  = categories::all();
        return view('admin.categories.create', compact('parentcategories'));
    }

    public function store(Request $request)
    {
        categories::create($request->validate([
            'title' => 'required',
            "description" => "nullable",
        ]));
        return redirect('admin/categories');
    }

    public function show(categories $categories)
    {

        return view('admin.categories.show', compact('categories'));
    }


    public function edit(categories $categories)
    {
        $parentcategories  = categories::all();
        return view('admin.categories.edit', compact('categories', 'parentcategories'));
    }

    public function update(Request $request, categories $categories)
    {

        $categories->update($request->validate([
            'title' => 'required',
            "description" => "nullable",
        ]));
        return redirect('admin/categories/show/' . $categories->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\categories  $categories
     * @return \Illuminate\Http\Response
     */
//     public function destroy(categories $categories)
//     {
//         $categories->delete();
//         // return redirect->back();
//    }

    public function destroy(categories $categories)
    {
        $categories->delete();

        // Redirect to the desired page (e.g., 'admin/categories')
        return redirect('admin/categories')->with('success', 'Category has been deleted successfully');
    }


   
}