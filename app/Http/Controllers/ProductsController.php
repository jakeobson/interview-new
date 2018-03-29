<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Storage::get('json.json');
        $products = json_decode($products);

        $total = 0;
        foreach ($products as $product) {
            $total += ($product->quantity * $product->price);
        }

        return view('products.index', compact('products', 'total'));
    }

    public function store()
    {

        $item = request()->all();

        unset($item['_token']);

        $item['datetime'] = Carbon::now()->format('d-m-Y H:i');

        $json = Storage::get('json.json');

        $products = $items = json_decode($json);

        $items[] = $item;

        $items = json_encode($items);

        Storage::put('json.json', $items);


        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->startDocument();
        $xml->startElement('Products');

        foreach (json_decode($items) as $product) {
            $xml->startElement('data');
            $xml->writeAttribute('product-name', $product->name);
            $xml->writeAttribute('quantity', $product->quantity);
            $xml->writeAttribute('price', $product->price);
            $xml->writeAttribute('date', $product->datetime);
            $xml->endElement();
        }
        $xml->endElement();
        $xml->endDocument();

        $content = $xml->outputMemory();
        $xml = null;

        Storage::put('xml.xml', $content);

        return $item;
    }
}
