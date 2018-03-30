<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;
use Validator;

class ProductsController extends Controller
{
    public function index()
    {

        $products = Product::getProductsFromJsonFile();

        $total = Product::getTotalValue($products);

        return view('products.index', compact('products', 'total'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = json_decode($errors);

            return response()->json([
                'success' => false,
                'message' => $errors
            ], 422);
        }

        $products = Product::getProductsFromJsonFile();

        $last_product = null;

        if ($products) {
            $last_product = end($products);
        }

        $newProduct = Product::createNewProduct($request->all(), $last_product);

        $products[] = $newProduct;

        Product::storeAsJsonFile($products);

        Product::storeAsXmlFile($products);

        return $newProduct;
    }

    public function edit()
    {
        $data = request()->all();

        $products = Product::getProductsFromJsonFile();

        $id = $data['pk'];

        $attribute = $data['name'];

        if($data['value']){
            $total_all = 0;
            $total = null;
            foreach ($products as $product) {


                if ($product->id == $id) {
                    $product->{$attribute} = $data['value'];

                    $total = $product->quantity * $product->price;
                }

                $total_all += $product->quantity * $product->price;
            }

            Product::storeAsJsonFile($products);

            Product::storeAsXmlFile($products);

            return response()->json([
                'total' => $total,
                'total_all' => $total_all,
                'id' => $id,
            ], 200);
        }

    }
}
