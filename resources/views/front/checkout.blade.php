@extends('layouts.front.app')

@section('content')
    <div class="container product-in-cart-list">
        @if(!$products->isEmpty())
            <div class="row">
                <div class="col-md-12">
                    <ol class="breadcrumb">
                        <li><a href="{{ route('home') }}"> <i class="fa fa-home"></i> Home</a></li>
                        <li class="active">Shopping Cart</li>
                    </ol>
                </div>
                <div class="col-md-12 content">
                    <div class="box-body">
                        @include('layouts.errors-and-messages')
                    </div>
                    @if(count($addresses) > 0)
                        <div class="row">
                            <div class="col-md-12">
                                @include('front.products.product-list-table', compact('products'))
                            </div>
                        </div>
                        @if(isset($addresses))
                            <div class="row">
                                <div class="col-md-12">
                                    <legend><i class="fa fa-home"></i> Addresses</legend>
                                    <table class="table table-striped">
                                        <thead>
                                            <th>Address</th>
                                            <th>Billing Address</th>
                                            <th>Delivery Address</th>
                                        </thead>
                                        <tbody>
                                            @foreach($addresses as $key => $address)
                                                <tr>
                                                    <td>
                                                        {{ $address->address }} {{ $address->address_2 }} <br />
                                                        @if(!is_null($address->province))
                                                            {{ $address->city }} {{ $address->province->name }} <br />
                                                        @endif
                                                        {{ $address->city }} {{ $address->state_code }} <br>
                                                        {{ @$address->country->name }} {{ $address->zip }}
                                                    </td>
                                                    <td>
                                                        <label class="col-md-6 col-md-offset-3">
                                                        <input
                                                                    type="radio"
                                                                    value="{{ $address->id }}"
                                                                    name="billing_address"
                                                                    @if($billingAddress->id == $address->id) checked="checked"  @endif>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        @if($billingAddress->id == $address->id)
                                                            <label for="sameDeliveryAddress">
                                                                <input type="checkbox" id="sameDeliveryAddress" checked="checked" disabled> Same as billing
                                                            </label>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tbody style="display: none" id="sameDeliveryAddressRow">
                                            @foreach($addresses as $key => $address)
                                                <tr>
                                                    <td>{{ $address->alias }}</td>
                                                    <td>
                                                        {{ $address->address }} {{ $address->address_2 }} <br />
                                                        @if(!is_null($address->province))
                                                            {{ $address->city }} {{ $address->province->name }} <br />
                                                        @endif
                                                        {{ $address->city }} {{ $address->state_code }} <br>
                                                        {{ @$address->country->name }} {{ $address->zip }}
                                                    </td>
                                                    <td></td>
                                                    <td>
                                                        <label class="col-md-6 col-md-offset-3">
                                                            <input
                                                                    type="radio"
                                                                    value="{{ $address->id }}"
                                                                    name="delivery_address"
                                                                    @if(old('') == $address->id) checked="checked"  @endif>
                                                        </label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif                        
                        <div class="row">
                            <div class="col-md-12">
                                <legend><i class="fa fa-credit-card"></i> Payment Method</legend>
                                <div class="text-right">
                                    <form action="{{ route('checkout.store') }}" method="post" class="pull-right">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="payment" value="COD">
                                        <input type="hidden" name="delivery_address" value="{{$addresses[0]->id}}">
                                        <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-warning pull-right">Cash On Delivery <i class="fa fa-money"></i></button>
                                    </form>  &nbsp;
                                    <form action="{{ route('checkout.store') }}" method="post" class="pull-right">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="payment" value="smanager_gateway">
                                        <input type="hidden" name="delivery_address" value="{{$addresses[0]->id}}">
                                        <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-success pull-right">Online Payment <i class="fa fa-card"></i></button>
                                    </form> 
                                </div>
                                
                            </div>
                        </div>
                    @else
                        <p class="alert alert-danger"><a href="{{ route('customer.address.create', [$customer->id]) }}">No address found. You need to create an address first here.</a></p>
                    @endif
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-12">
                    <p class="alert alert-warning">No products in cart yet. <a href="{{ route('home') }}">Show now!</a></p>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('js')
    <script type="text/javascript">

        function setTotal(total, shippingCost) {
            let computed = +shippingCost + parseFloat(total);
            $('#total').html(computed.toFixed(2));
        }

        function setShippingFee(cost) {
            el = '#shippingFee';
            $(el).html(cost);
            $('#shippingFeeC').val(cost);
        }

        function setCourierDetails(courierId) {
            $('.courier_id').val(courierId);
        }

        $(document).ready(function () {

            let clicked = false;

            $('#sameDeliveryAddress').on('change', function () {
                clicked = !clicked;
                if (clicked) {
                    $('#sameDeliveryAddressRow').show();
                } else {
                    $('#sameDeliveryAddressRow').hide();
                }
            });

            let billingAddress = 'input[name="billing_address"]';
            $(billingAddress).on('change', function () {
                let chosenAddressId = $(this).val();
                $('.address_id').val(chosenAddressId);
                $('.delivery_address_id').val(chosenAddressId);
            });

            let deliveryAddress = 'input[name="delivery_address"]';
            $(deliveryAddress).on('change', function () {
                let chosenDeliveryAddressId = $(this).val();
                $('.delivery_address_id').val(chosenDeliveryAddressId);
            });

            let courier = 'input[name="courier"]';
            $(courier).on('change', function () {
                let shippingCost = $(this).data('cost');
                let total = $('#total').data('total');

                setCourierDetails($(this).val());
                setShippingFee(shippingCost);
                setTotal(total, shippingCost);
            });

            if ($(courier).is(':checked')) {
                let shippingCost = $(courier + ':checked').data('cost');
                let courierId = $(courier + ':checked').val();
                let total = $('#total').data('total');

                setShippingFee(shippingCost);
                setCourierDetails(courierId);
                setTotal(total, shippingCost);
            }
        });
    </script>
@endsection