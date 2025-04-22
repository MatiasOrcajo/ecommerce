<?php

namespace App\Services;


use App\Models\Category;
use Yajra\DataTables\DataTables;

class CategoryService
{

    public function listCategories()
    {
        $categories = Category::all();

        return DataTables::of($categories->map(function ($category) {
            return [
                "id" => $category->id,
                "name" => $category->name,

            ];
        }))->make(true);
    }

}
