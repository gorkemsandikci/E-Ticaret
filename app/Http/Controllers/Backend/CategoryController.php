<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('category:id,cat_ust,name')->get();
        return view('backend.pages.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        return view('backend.pages.category.edit', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = $request->name;
            $destination_path = 'img/categories';
            $image_url = image_upload($image, $image_name, $destination_path, rand(99,9999));
        }

        Category::create([
            'name' => $request->name,
            'content' => $request->description,
            'cat_ust' => $request->cat_ust,
            'image' => $image_url ?? null,
            'status' => $request->status,
        ]);

        return back()->withSuccess('Kategori oluşturuldu!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::where('id', $id)->first();
        $categories = Category::get();
        return view('backend.pages.category.edit', compact('category','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        $category = Category::where('id', $id)->firstOrFail();
        $image_url = $category->image;

        if ($request->hasFile('image')) {
            delete_file($category->image);
            $image = $request->file('image');
            $image_name = $request->name;
            $destination_path = 'img/categories';
            $image_url = image_upload($image, $image_name, $destination_path, $category->id);
        }

        $category->update([
            'name' => $request->name,
            'content' => $request->description,
            'cat_ust' => $request->cat_ust,
            'image' => $image_url ?? $category->image,
            'status' => $request->status,
        ]);

        return back()->withSuccess('Kategori güncellendi!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $category = Category::where('id', $request->id)->firstOrFail();

        delete_file($category->image);

        $category->delete();

        return response(['error' => false, 'message' => 'Başarıyla Silindi.']);
    }

    public function statusUpdate(Request $request)
    {
        $update = $request->state;
        $update_check = $update == "false" ? '0' : '1';

        Category::where('id', $request->id)->update(['status' => $update_check]);
        return response(['error' => false, 'status' => $update]);
    }
}
