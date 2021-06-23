@extends('layouts.app)

@section('content')

    <div class="container">

        @foreach($items as $item)
            <div class="row">
                <div class="col-2">
                    <img src="{{ $item.image_url }}" alt="">
                </div>
                <div class="col-4"> {{ $item.title }} </div>
                <div class="col-2"> {{ $item.quantity }} </div>
                <div class="col-2"> {{ $item.price }} </div>
            </div>
        @endforeach

        <form method="post" action="https://wl.walletone.com/checkout/checkout/Index">
            <input type="hidden" name="WMI_MERCHANT_ID"    value="146004015805">
            <input type="hidden" name="WMI_PAYMENT_AMOUNT" value="{{ total }}">
            <input type="hidden" name="WMI_CURRENCY_ID"    value="643">
            <input type="hidden" name="WMI_DESCRIPTION"    value="{{ description }}">
            <input type="hidden" name="WMI_SUCCESS_URL"    value="{{ url_success }}">
            <input type="hidden" name="WMI_FAIL_URL"       value="{{ url_fail }}">
            <input type="hidden" name="WMI_SIGNATURE"      value="{{ signature }}">
            <button type="submit" class="button text outline">Перейти к оплате</button>
        </form>

    </div>

@endsection