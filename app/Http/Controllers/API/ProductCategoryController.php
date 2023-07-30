<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function all(Request $request){
        $id = $request->input('id');
        
        if ($id) {
            // jika parameter memiliki id
            $category = ProductCategory::with(['products'])->find($id);
            if ($category) {
                return ResponseFormatter::success($category, 'Data Kategori berhasil diambil ');
            } else {
                return ResponseFormatter::success(null, 'Data Kategori tidak ada ');
            }
        }
        $category = ProductCategory::with('products');

        return ResponseFormatter::success($category->get(), 'Data Kategori berhasil diambil ');
    }
   
    public function addCategories(Request $request){
            try {
                $request->validate([
                    'name' => 'required',
                ]);
                ProductCategory::create([
                    'name' => $request->name,
                ]);
    
                $product = ProductCategory::where('name', $request->name)->first();
                return ResponseFormatter::success(['product'=>$product], 'Berhasil menambahkan kategori sampah');
            } catch (Exception $e){
                return ResponseFormatter::error($e);
            } 
    }

}
