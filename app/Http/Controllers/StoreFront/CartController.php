<?php

namespace App\Http\Controllers\StoreFront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Carts\AddToCartRequest;
use App\Http\Requests\Carts\UpdateCartRequest;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;

class CartController extends Controller
{
    private $cartRepo;

    private $productRepo;

    public function __construct(
        CartRepository $cartRepository,
        ProductRepository $productRepository
    ) {
        $this->cartRepo = $cartRepository;
        $this->productRepo = $productRepository;
    }

    public function index()
    {
        $shippingFee = 0;
        return view('front.carts.cart', [
            'cartItems' => $this->cartRepo->getCartItemsTransformed(),
            'subtotal' => $this->cartRepo->getSubTotal(),
            'tax' => $this->cartRepo->getTax(),
            'shippingFee' => $shippingFee,
            'total' => $this->cartRepo->getTotal(2, $shippingFee),
            'cartCount' => $this->cartRepo->countItems()
        ]);
    }

    public function store(AddToCartRequest $request)
    {
        $product = $this->productRepo->getOneById($request->input('product'));

        $options = [];

        $this->cartRepo->addToCart($product, $request->input('quantity'), $options);

        return redirect()->route('cart.index')
            ->with('message', 'Add to cart successful');
    }

    public function update(UpdateCartRequest $request, $id)
    {
        $this->cartRepo->updateQuantityInCart($id, $request->input('quantity'));

        request()->session()->flash('message', 'Cart updated successfully');
        return redirect()->route('cart.index');
    }

    public function destroy($id)
    {
        $this->cartRepo->removeToCart($id);

        request()->session()->flash('message', 'Removed from cart successfully');
        return redirect()->route('cart.index');
    }
}
