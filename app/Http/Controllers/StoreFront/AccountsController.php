<?php

namespace App\Http\Controllers\StoreFront;


use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Models\Order;
// use App\Shop\Orders\Transformers\OrderTransformable;

class AccountsController extends Controller
{
    // use OrderTransformable;
    private $customerRepo;

    public function __construct(UserRepository $userRepository) {
        $this->customerRepo = $userRepository;
    }

    public function index()
    {
        $customer = $this->customerRepo->getOneById(auth()->user()->id);

        dd($customer->addresses());

        // $customerRepo = new CustomerRepository($customer);
        $orders = $this->customerRepo->findOrders(['*'], 'created_at');
        // $orders->transform(function (Order $order) {
        //     return $this->transformOrder($order);
        // });
        $orders->load('products');

        $addresses = $this->customerRepo->findAddresses();
        dd($addresses);

        return view('front.accounts', [
            'customer' => $customer,
            'orders' => $this->customerRepo->paginateArrayResults($orders->toArray(), 15),
            'addresses' => $addresses
        ]);
    }
}
