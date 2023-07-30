<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $price = $request->input('price');
        $category = $request->input('category');

        $product = Product::with(['categories_id']);

        if ($id) {
            error_log($id);

            // jika parameter memiliki id
            $product = Product::with(['categories_id'])->find($id);
            if ($product) {
                return ResponseFormatter::success($product, 'Data Product berhasil diambil ');
            } else {
                return ResponseFormatter::success(null, 'Data Product tidak ada ');
            }
        }

        if ($category) {
            $product->where('categories_id', $category);
        }
        
        return ResponseFormatter::success($product->get(), 'Data Product berhasil diambil ');
    }

    public function addProduct(Request $request){
        try {
            $request->validate([
                'name' => 'required',
                'price' => 'required',
                'categories_id' => 'required'
            ]);
            Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'categories_id' => $request->categories_id,
            ]);

            $product = Product::where('name', $request->name)->first();
            return ResponseFormatter::success(['product'=>$product], 'Berhasil menambahkan jenis sampah');
        } catch (Exception $e){
            return ResponseFormatter::error($e);
        }
    }
    
    public function deleteProduct(Request $request){

        $id = $request->input('id');
        error_log('ssss');
        error_log("entop");
        $product = Product::find($id);
// cek apakah ada produk dengan id tsb
        if (!$product){
            return ResponseFormatter::error(null, 'Produk dengan id tersebut tidak ditemukan');
        }
        $product->delete();
        return ResponseFormatter::success(null, 'Produk dengan id tersebut sudah dihapus');
        
    }
}