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

    public static function storeAsXmlFile($items)
    {


        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->startDocument();
        $xml->startElement('Product');

        foreach ($items as $product) {

            $product = (array)$product;

            $xml->startElement('data');
            $xml->writeAttribute('id', $product['id']);
            $xml->writeAttribute('product-name', $product['name']);
            $xml->writeAttribute('quantity', $product['quantity']);
            $xml->writeAttribute('price', $product['price']);
            $xml->writeAttribute('date', $product['datetime']);
            $xml->endElement();
        }
        $xml->endElement();
        $xml->endDocument();

        $content = $xml->outputMemory();
        $xml = null;

        Storage::put('xml.xml', $content);
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
