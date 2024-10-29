<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class BrandController extends Controller
{
    public function index()
    {
        // $brands = Brand::with(['products' => function($query){
        //     $query->where('price','=',1000);
        // }])->get();

        User::select('id', 'name')->whereHas('Articles', function (Builder $query) {
            $query->where('status', 1);
        });

        User::select('id', 'name')->whereRelation('Article', 'status', 1)->get();

        $users = User::query()
            ->with('books:id,user_id,title')
            ->whereHas('books')
            ->get();

        $users = User::query()
            ->with(['books' => function ($query) {
                $query->select('id', 'user_id', 'title')
                    ->where('status', 1);
            }])->whereHas('books', function ($query) {
                $query->where('status', 1);
            })->get();


        $users = User::query()
            ->withWhereHas('books', function ($query) {
                $query->select('id', 'user_id', 'title')
                    ->where('status', 1);
            })->get();
        return $users;
    }

    public function show()
    {
        $product = Product::find(1);
        $brand = $product->load('brand');
    }

    public function store(Request $request)
    {
        Product::create([
            'brand_id ' => $request->brand_id
        ]);
        $brand = Brand::find(2);
        $product = new Product();
        $product->name = 'iPhone';
        $brand->products()->save($product);
    }
}
