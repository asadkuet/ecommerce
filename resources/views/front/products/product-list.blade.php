@if(!empty($products) && !collect($products)->isEmpty())
    <ul class="row text-center list-unstyled">
        @foreach($products as $product)
            <li class="col-md-3 col-sm-6 col-xs-12 product-list">
                <div class="single-product">
                    <div class="product">
                        <a href="{{ route('products.show', $product->id) }}">
                            @if(isset($product->image))
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-bordered img-responsive">
                            @else
                                <img src="https://via.placeholder.com/200x200.png?text=Product+Image" alt="{{ $product->name }}" class="img-bordered img-responsive">
                            @endif
                        </a>
                    </div>

                    <div class="product-text">
                        <h4>{{ $product->name }}</h4>
                        <p>
                            Tk. {{ number_format($product->price, 2) }}
                        </p>
                    </div>
                    <div class="row">
                        <div class="vcenter">
                            <div class="centrize">
                                <ul class="list-unstyled list-group">
                                    <li>
                                        <form action="{{ route('cart.store') }}" class="form-inline" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="quantity" value="1" />
                                            <input type="hidden" name="product" value="{{ $product->id }}">
                                            <button id="add-to-cart-btn" type="submit" class="btn btn-success" data-toggle="modal" data-target="#cart-modal"> <i class="fa fa-cart-plus"></i> Add to cart</button>
                                        </form>
                                    </li>
                                    {{-- <li>  <a class="btn btn-default product-btn" href="{{ route('products.show', $product->id) }}"> <i class="fa fa-link"></i> Show product</a> </li> --}}
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="myModal_{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                @include('layouts.front.product')
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
@else
    <p class="alert alert-warning">No products created.</p>
@endif