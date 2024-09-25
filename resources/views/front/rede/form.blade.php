@extends('front.themes.' . env('THEME', 'theme-15') . '.layout')
@section('content')
<div class="container mt-5 mb-5">
    @include('includes.form-success')
    <div class="card-group col-lg-6 m-auto">
        <div class="card">
            <img class="card-img-top m-auto pt-5" src="{{ asset('assets/general/rede-logo-1.png') }}" alt="Rede imagem logo" style="width: 80%">
            <div class="card-body">
                <h5 class="card-title text-center">{{ __('Enter your card details') }}</h5>
                <form action="{{ route('rede.notify') }}" method="POST">
                    <div class="form-group">
                        <label>{{ __('Card number') }}</label>
                        <input type="tel" class="form-control" name="cardNumber" placeholder="•••• •••• •••• ••••" value="{{ old('cardNumber') }}" pattern="[0-9]+" title="{{ __('Field only accepts numbers') }}">
                        <small class="form-text text-muted">{{ __('Your data is redirected directly to Rede.') }}</small>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="kind" id="customRadioDebit" value="debitCard" onclick="hideInstallments();" checked>
                        <label class="form-check-label" for="customRadioDebit">{{ __('Debit') }}</label>
                        <input class="form-check-input" type="radio" name="kind" id="customRadioCredit" value="creditCard" onclick="showInstallments();">
                        <label class="form-check-label" for="customRadioCredit">{{ __('Credit') }}</label>
                    </div>
                    <h3 class="text-center">{{ __('Total Purchase') }} <span class="badge badge-light" style="border: 1px solid; color: #ff7800;">R$ {{ number_format((float) $pedido->pay_amount, 2, '.', '') }}</span></h3>
                    <div id="show-installments" style="display: none">
                        <label>{{ __('Installments') }}</label>
                        <select class="form-control" name={{ $admstore->rede_installments >= 2 ? 'installments' : '' }}>
                            <option value="1">R$ {{ number_format((float) $pedido->pay_amount, 2, '.', '') }} à vista</option>
                            @if ($admstore->rede_minimum_installment_price != null && (float) $admstore->rede_minimum_installment_price > 0)
                                @for ($i = 1; $i <= $admstore->rede_installments && $i <= 12; $i++)
                                    @if ($i != 1 && $pedido->pay_amount / $i >= (float) $admstore->rede_minimum_installment_price)
                                        <option value="{{ $i }}">{{ $i }} x R$ {{ number_format((float) ($pedido->pay_amount / $i), 2, '.', '') }}</option>
                                    @endif
                                @endfor
                            @else
                                @for ($i = 1; $i <= $admstore->rede_installments && $i <= 12; $i++)
                                    @if ($i != 1)
                                        <option value="{{ $i }}">{{ $i }} x R$ {{ number_format((float) ($pedido->pay_amount / $i), 2, '.', '') }}</option>
                                    @endif
                                @endfor
                            @endif
                        </select>
                        @if ($admstore->rede_minimum_installment_price != null && $admstore->rede_minimum_installment_price > 0)
                            <small>{{ __('Minimum Installment Price') . ": R$ " . number_format((float) $admstore->rede_minimum_installment_price, 2, '.', '') }}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>{{ __('Name printed on card') }}</label>
                        <input type="text" class="form-control" name="cardHolderName" value="{{ old('cardHolderName') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Security code') }}</label>
                        <input type="tel" class="form-control" name="securityCode" placeholder="CVC" value="{{ old('securityCode') }}" pattern="[0-9]+" title="{{ __('Field only accepts numbers') }}" maxlength="4">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Card expiring date') }}</label>
                        <div class="row">
                            <div class="col-5">
                                <input type="tel" class="form-control" name="expirationMonth" placeholder="MM" value="{{ old('expirationMonth') }}" pattern="[0-9]+" title="{{ __('Field only accepts numbers') }}" maxlength="2">
                            </div>
                            /
                            <div class="col-5">
                                <input type="tel" class="form-control" name="expirationYear" placeholder="AA" value="{{ old('expirationYear') }}" pattern="[0-9]+" title="{{ __('Field only accepts numbers') }}" maxlength="2">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="Amount" value="{{ $pedido->pay_amount }}">
                    <input type="hidden" name="reference" value="{{ $pedido->order_number }}">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">{{ __('Confirm') }}</button>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <small class="text-muted">{{ __('Integration to e-Rede, if you have questions call:') }} <small>{{ __('4001 4490 (capitals and metropolitan regions) - 0800 728 4490 (other locations)') }}</small></small>
            </div>
        </div>
    </div>
</div>
<script>
    function hideInstallments() {
        document.getElementById('show-installments').style.display = 'none';
    }
    function showInstallments() {
        document.getElementById('show-installments').style.display = 'block';
    }
</script>
@endsection
