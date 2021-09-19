@extends('layouts.front.app')

@section('og')
    <meta property="og:type" content="home"/>
    <meta property="og:title" content="{{ config('app.name') }}"/>
    <meta property="og:description" content="{{ config('app.name') }}"/>
@endsection

@section('content')
    @if($products->isNotEmpty())
        <section class="new-product t100 home">
            <div class="container">
                <div class="section-title b50">
                    <h2>Catalogue</h2>
                    <hr/>
                </div>
                @include('front.products.product-list', ['products' => $products])
            </div>
        </section>
    @endif
@endsection