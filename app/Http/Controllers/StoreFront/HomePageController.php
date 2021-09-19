<?php

namespace App\Http\Controllers\StoreFront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use App\Repositories\CartRepository;

class HomePageController extends Controller
{
    private $productRepository;
    private $cartRepo;
    function __construct(
        ProductRepository $productRepository, 
        CartRepository $cartRepository
    ) {
        $this->productRepository = $productRepository;     
        $this->cartRepo = $cartRepository;     
    }
    
    public function index()
    {
        $products = $this->productRepository->getAll();
        $cartCount = $this->cartRepo->countItems();
        return view('front.index',compact('products', 'cartCount'));
    }
}
