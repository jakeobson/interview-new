<?php

namespace App;

use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

class Product extends Model
{
    public static function storeAsJsonFile($items)
    {
        $items = json_encode($items);

        Storage::put('json.json', $items);

        return true;
    }

    public static function getProductsFromJsonFile()
    {
        return json_decode(Storage::get('json.json'));
    }

    public static function getTotalValue($products)
    {

        $total = 0;

        if ($products) {
            foreach ($products as $product) {
                $total += ($product->quantity * $product->price);
            }
        }

        return $total;
    }

    public static function createNewProduct($request, $last_product)
    {

        $id = 1;

        if ($last_product)
            $id = $last_product->id + 1;

        $newProduct = $request;

        $newProduct['id'] = $id;

        unset($newProduct['_token']);

        $newProduct['datetime'] = Carbon::now()->format('d-m-Y H:i');

        return $newProduct;
    }

}
