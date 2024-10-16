<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categoryPage()
    {
        return view('pages.components.category');
    }

    public function categoryList(Request $request)
    {
        $user_id = $request->header('id');
        return Category::where('user_id', $user_id)->get();
    }
    public function addCategory(Request $request)
    {
        $user_id = $request->header('id');
        return Category::create([
            'name' => $request->input('name'),
            'user_id' => $user_id
        ]);
    }
    public function deleteCategory(Request $request)
    {
        $category_id = $request->input('id');
        $user_id = $request->header('id');
        return Category::where('id', $category_id)->where('user_id', $user_id)->delete();
    }
    public function categoryId(Request $request)
    {
        $category_id = $request->input('id');
        $user_id = $request->header('id');
        return Category::where('id', $category_id)->where('user_id', $user_id)->first();
    }
    public function updateCategory(Request $request)
    {
        $category_id = $request->input('id');
        $user_id = $request->header('id');
        return Category::where('id', $category_id)->where('user_id', $user_id)->update(['name' => $request->input('name')]);
    }

}