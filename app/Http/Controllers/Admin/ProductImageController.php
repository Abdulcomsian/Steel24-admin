<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\productimage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use DataTables;


class ProductImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin.auth:admin');
    }
    public function index(Request $request)
    {
        $productimage = productimage::all();
        if ($request->ajax()) {
          return Datatables::of($productimage)
                  ->addIndexColumn()
                  ->rawColumns(['action'])
                  ->make(true);
      }
        return view('admin.productimage.index')
            ->with('productimage', $productimage);
    }

    public function create()
    {
        $productimage  = productimage::all();
        return view('admin.productimage.create', compact('productimage'));
    }

    // public function store(Request $request)
    // {
    //     productimage::create($request->validate([
    //         'title' => 'required',
    //         "description" => "nullable",
    //     ]));
    //     return redirect('admin/productimage');
    // }





    //     public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'title' => 'required',
    //         'description' => 'nullable',
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     if ($request->hasFile('image')) 
    //     {
    //         $image = $request->file('image');
    //         $imageName = time() . '_' . $image->getClientOriginalName();
            
    //         $image->storeAs('public/productimages', $imageName);


    //         $validatedData['image'] = 'productimages/' . $imageName;
    //     }

    //     // Create a new product image record with the validated data
    //     productimage::create($validatedData);

    //     return redirect('admin/productimage');
    // }

    public function store(Request $request)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($request->hasFile('image')) 
        {
            // Get the uploaded image
            $image = $request->file('image');
    
            // Generate a unique image name
            $imageName = time() . '_' . $image->getClientOriginalName();
            
            // Store the image in the public/productimages directory
            $image->move(public_path('productimages'), $imageName);
    
            // Update the validated data with the image path
            $validatedData['image'] = 'productimages/' . $imageName;
        }
    
        // Create a new product image record with the validated data
        productimage::create($validatedData);
    
        return redirect('admin/productimage');
    }
    

   
    
    public function show($id)
    {
        $productimage  = productimage::find($id);
        return view('admin.productimage.show', compact('productimage'));
    }


    public function edit(productimage $productimage)
    {
        $parentproductimage  = productimage::all();
        return view('admin.productimage.edit', compact('productimage', 'parentproductimage'));
    }

    // public function update(Request $request, Productimage $productimage)
    // {

    //     $productimage->update($request->validate([
    //         'title' => 'required',
    //         "description" => "nullable",
    //     ]));
    //     return redirect('admin/productimages/show/' . $productimage->id);
    // }

    // public function update(Request $request, Productimage $productimage)
    // {
    //     // Validate the request data
    //     $validatedData = $request->validate([
    //         'title' => 'required',
    //         'description' => 'nullable',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);
    
    //     // Handle image upload and storage if a new image is provided
    //     if ($request->hasFile('image')) 
    //     {
    //         // Delete the old image if it exists
    //         if ($productimage->image) {
    //             Storage::disk('public')->delete($productimage->image);
    //         }
    
    //         // Upload the new image
    //         $imagePath = $request->file('image')->store('product-images', 'public');
    //         $validatedData['image'] = $imagePath;
    //     }
    
    //     // Update the product image record with the validated data
    //     $productimage->update($validatedData);
    
    //     return redirect('admin/productimages/show/' . $productimage->id);
    // }




      
    // public function update(Request $request, Productimage $productimage)
    // {
    //     $validatedData = $request->validate([
    //         'title' => 'required',
    //         'description' => 'nullable',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);
    
    //     // Handle image upload and storage if a new image is provided
    //     if ($request->hasFile('image')) 
    //     {
    //         // Delete the old image if it exists
    //         if ($productimage->image) {
    //             Storage::disk('public')->delete($productimage->image);
    //         }
    
    //         // Store the new image with the same name and path
    //         $imagePath = $request->file('image')->storeAs('public/productimages', basename($productimage->image));
    //         $validatedData['image'] = str_replace('public/', '', $imagePath);
    //     }
    
    //     // Update the product image record with the validated data
    //     $productimage->update($validatedData);
    
    //     return redirect('admin/productimages/show/' . $productimage->id);
    // }

    public function update(Request $request, Productimage $productimage)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Handle image upload and storage if a new image is provided
        if ($request->hasFile('image')) 
        {
            // Delete the old image if it exists
            if ($productimage->image) {
                $oldImagePath = public_path($productimage->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
    
            // Store the new image in the public/productimages directory
            $newImage = $request->file('image');
            $newImagePath = 'public/productimages/' . time() . '_' . $newImage->getClientOriginalName();
            $newImage->move(public_path('productimages'), $newImagePath);
    
            $validatedData['image'] = $newImagePath;
        }
    
        // Update the product image record with the validated data
        $productimage->update($validatedData);
    
        return redirect('admin/productimages/show/' . $productimage->id);
    }
    
    
    
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\productimage  $productimage
     * @return \Illuminate\Http\Response
     */
    public function destroy(productimage $productimage)
    {
        $productimage->delete();
   }
}
