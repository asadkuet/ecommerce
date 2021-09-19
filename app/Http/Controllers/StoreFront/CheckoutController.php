<?php

namespace App\Http\Controllers\StoreFront;


use App\Shop\Cart\Requests\CartCheckoutRequest;

use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;
use App\Repositories\AddressRepository;
use App\Repositories\UserRepository;
use App\Repositories\OrderRepository;

use Exception;
use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    private $cartRepo;

    private $addressRepo;

    private $userRepo;

    private $productRepo;

    private $orderRepo;

    private $payPal;

    public function __construct(
        CartRepository $cartRepository,
        ProductRepository $productRepository,
        AddressRepository $addressRepository,
        UserRepository $userRepository,
        OrderRepository $orderRepository
    ) {
        $this->cartRepo = $cartRepository;
        $this->productRepo = $productRepository;
        $this->addressRepo = $addressRepository;
        $this->userRepo = $userRepository;
        $this->orderRepo = $orderRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = $this->cartRepo->getCartItems();
        $customer = $request->user();
        $rates = null;
        $shipment_object_id = null;

        if (env('ACTIVATE_SHIPPING') == 1) {
            $shipment = $this->createShippingProcess($customer, $products);
            if (!is_null($shipment)) {
                $shipment_object_id = $shipment->object_id;
                $rates = $shipment->rates;
            }
        }

        // Get payment gateways
        $paymentGateways = explode(',', config('payees.name'));
        
        $billingAddress = $customer->addresses()->first();

        return view('front.checkout', [
            'customer' => $customer,
            'billingAddress' => $billingAddress,
            'addresses' => $customer->addresses()->get(),
            'products' => $this->cartRepo->getCartItems(),
            'subtotal' => $this->cartRepo->getSubTotal(),
            'tax' => $this->cartRepo->getTax(),
            'total' => $this->cartRepo->getTotal(2),
            'payments' => $paymentGateways,
            'cartItems' => $this->cartRepo->getCartItemsTransformed(),
            'shipment_object_id' => $shipment_object_id,
            'rates' => $rates
        ]);
    }

    public function store(Request $request)
    {
        $shippingFee = 0;
        $orderCreated = null;
        switch ($request->input('payment')) {
            case 'COD':
                return $this->orderRepo->create([
                    'reference' => uniqid(),
                    'user_id' => auth()->user()->id,
                    'address_id' => $request->input('delivery_address'),
                    'order_status_id' => 'received',
                    'payment' => $request->input('payment'),
                    'discounts' => 0,
                    'total_products' => $this->cartRepo->getSubTotal(),
                    'total' => $this->cartRepo->getTotal(),
                    'total_paid' => 0,
                    'tax' => $this->cartRepo->getTax()
                ]);
                
                if($orderCreated)
                    $this->success();
                break;
            case 'smanager_gateway': 
                return 'Online Payment is under construction!';               
                break;
            default:
        }
    }
    
    public function cancel(Request $request)
    {
        return view('front.checkout-cancel', ['data' => $request->all()]);
    }

    /**
     * Success page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function success()
    {
        return view('front.checkout-success');
    }
    
}
