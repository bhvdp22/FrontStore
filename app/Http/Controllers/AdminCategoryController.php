<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str; // Needed for generating slugs like "fresh-fruits"

class AdminCategoryController extends Controller
{
    // 1. Show List & Form
    public function index()
    {
        $categories = Category::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    // 2. Save New Category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name|max:255'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // "Fresh Apple" -> "fresh-apple"
        ]);

        return back()->with('success', 'Category Created Successfully!');
    }

    // 3. Delete Category
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return back()->with('success', 'Category Deleted!');
    }
}