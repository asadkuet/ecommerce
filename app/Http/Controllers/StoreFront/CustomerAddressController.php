<?php

namespace App\Http\Controllers\StoreFront;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\AddressRepository;
use App\Http\Requests\Addresses\CreateAddressRequest;

class CustomerAddressController extends Controller
{
    private $addressRepo;

    // private $countryRepo;

    // private $cityRepo;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepo = $addressRepository;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        return redirect()->route('accounts', ['tab' => 'address']);
    }

    /**
     * @param  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $customer = auth()->user();
        return view('front.customers.addresses.create', [
            'customer' => $customer,
            // 'countries' => $this->countryRepo->listCountries(),
            // 'cities' => $this->cityRepo->listCities(),
            // 'provinces' => $this->provinceRepo->listProvinces()
        ]);
    }

    public function store(CreateAddressRequest $request)
    {
        $request['user_id'] = auth()->user()->id;

        $this->addressRepo->create($request->except('_token', '_method'));
        return redirect()->route('checkout.index')->with('message', 'Address creation successful');
        // return redirect()->route('accounts', ['tab' => 'address'])
        //     ->with('message', 'Address creation successful');
    }

    /**
     * @param $addressId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($customerId, $addressId)
    {
        $countries = $this->countryRepo->listCountries();

        $address = $this->addressRepo->findCustomerAddressById($addressId, auth()->user());

        return view('front.customers.addresses.edit', [
            'customer' => auth()->user(),
            'address' => $address,
            'countries' => $countries,
            'cities' => $this->cityRepo->listCities(),
            'provinces' => $this->provinceRepo->listProvinces()
        ]);
    }

    /**
     * @param UpdateAddressRequest $request
     * @param $addressId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateAddressRequest $request, $customerId, $addressId)
    {
        $address = $this->addressRepo->findCustomerAddressById($addressId, auth()->user());

        $request = $request->except('_token', '_method');
        $request['customer_id'] = auth()->user()->id;

        $addressRepo = new AddressRepository($address);
        $addressRepo->updateAddress($request);

        return redirect()->route('accounts', ['tab' => 'address'])
            ->with('message', 'Address update successful');
    }

    /**
     * @param $addressId
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($customerId, $addressId)
    {
        $address = $this->addressRepo->findCustomerAddressById($addressId, auth()->user());

       if ($address->orders()->exists()) {
             $address->status=0;
             $address->save();
       }
       else {
             $address->delete();
       }
        return redirect()->route('accounts', ['tab' => 'address'])
            ->with('message', 'Address delete successful');
    }
}

