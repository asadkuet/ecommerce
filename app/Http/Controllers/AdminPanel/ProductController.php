<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Http\Requests\Products\ProductRequest;

class ProductController extends Controller
{
    private $productService;
    private $productRepository;
    function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;

        $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
        $this->middleware('permission:product-create', ['only' => ['create','store']]);
        $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
    
    public function index()
    {
        $products = $this->productRepository->getAll();
        
        return view('products.index',compact('products'));
    }
    
    public function create()
    {
        return view('products.create');
    }
    
    public function store(ProductRequest $request)
    {    
        $this->productRepository->create($request->all());
    
        return redirect()->route('products.index')
                        ->with('success','Product created successfully.');
    }
    
    public function show($id)
    {
        $product = $this->productRepository->getOneById($id);
        return view('products.show',compact('product'));
    }
    
    public function edit($id)
    {
        $product = $this->productRepository->getOneById($id);
        return view('products.edit',compact('product'));
    }
    
    public function update(ProductRequest $request, $id) //, Product $product)
    {    
        $this->productRepository->update($request->all(), $id);        
        
        return redirect()->route('products.index')
                        ->with('success','Product updated successfully');
    }
    
    public function destroy($id)
    {
        $this->productRepository->delete($id);
    
        return redirect()->route('products.index')
                        ->with('success','Product deleted successfully');
    }
}
