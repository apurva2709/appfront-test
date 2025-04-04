<?php

namespace App\Services;

use App\Models\Product;
use App\Services\CURLService as CURLService;

class ProductService {

    protected $curlService;

    public function __construct(CURLService $curlService)
    {
        $this->curlService = $curlService;
    }

    public function getProductDetail($request) {
        $id = $request->route('product_id');
        $product = Product::find($id);
        $exchangeRate =  $this->curlService->callConversionAPI();

        return compact('product','exchangeRate');
    }

    public function getProductList() {
        $products = Product::all();
        $exchangeRate =  $this->curlService->callConversionAPI();

        return compact('products','exchangeRate');
    }
}
