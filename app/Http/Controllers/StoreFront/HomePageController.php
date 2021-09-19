<?php

namespace App\Http\Controllers\StoreFront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;

class HomePageController extends Controller
{
    private $productRepository;
    function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;     
    }
    
    public function index()
    {
        $products = $this->productRepository->getAll();
        return view('front.index',compact('products'));
    }
}
