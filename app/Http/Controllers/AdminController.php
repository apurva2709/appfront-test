<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Services\ProductService as ProductService;
use App\Http\Requests\ProductRequest;
use App\Actions\CreateProduct;
use App\Actions\UpdateProduct;

class AdminController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function loginPage()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        if (Auth::attempt($request->except('_token'))) {
            return redirect()->route('admin.products');
        }

        return redirect()->back()->with('error', 'Invalid login credentials');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }

    public function products()
    {
        $data = $this->productService->getProductList();

        return view('admin.products', $data);
    }

    public function editProduct($id)
    {
        $product = Product::find($id);

        return view('admin.edit_product', compact('product'));
    }

    public function updateProduct(ProductRequest $request, $id, UpdateProduct $updateProduct)
    {
        $request->validated();

        $updateProduct->handle($request, $id);

        return redirect()->route('admin.products')->with('success', 'Product updated successfully');
    }

    public function deleteProduct($id)
    {
        $product = Product::find($id);
        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Product deleted successfully');
    }

    public function addProductForm()
    {
        return view('admin.add_product');
    }

    public function addProduct(ProductRequest $request, CreateProduct $createProduct)
    {
        $request->validated();

        $createProduct->handle($request);

        return redirect()->route('admin.products')->with('success', 'Product added successfully');
    }
}
