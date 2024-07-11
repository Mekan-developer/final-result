<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\c;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::all();
        return response()->json($category);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            // $path = 'public/files/' . $fileName;

            // Store the file
            Storage::putFileAs('public/web_images/categories', $file, $fileName);
            Storage::putFileAs('public/tablet_images/categories', $file, $fileName);
            $message = 'File upload successfully';

        }
        $names = json_decode($request->name, true);
        $is_active = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);

        Category::create([
            'name' => $names,
            'image' => $fileName,
            'is_active' => $is_active,
        ]);
        return $message;

    }

    /**
     * Display the specified resource.
     */
    // public function show(c $c)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(c $c)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, c $c)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, $id)
    {
        $category = Category::findOrFail($id);

        if ($category->image) {
            $imagePathWeb = 'public/web_images/categories/' . $category->image;
            $imagePathTablet = 'public/tablet_images/categories/' . $category->image;

            if (Storage::exists($imagePathWeb)) {
                Storage::delete($imagePathWeb);
            }
            if (Storage::exists($imagePathTablet)) {
                Storage::delete($imagePathTablet);
            }

        }
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
