<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CURLService as CURLService;
use App\Services\ProductService as ProductService;

class ProductController extends Controller
{
    protected $curlService;
    protected $productService;

    public function __construct(CURLService $curlService, ProductService $productService)
    {
        $this->curlService = $curlService;
        $this->productService = $productService;
    }

    public function index()
    {
        $data = $this->productService->getProductList();

        return view('products.list', $data);
    }

    public function show(Request $request)
    {
        $data = $this->productService->getProductDetail($request);

        return view('products.show', $data);
    }
}
