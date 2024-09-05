{{-- ADD / EDIT MODAL --}}
<div class="modal fade" id="simplified-checkout-modal" tabindex="-1" role="dialog"
    aria-labelledby="simplified-checkout-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header top-header algin-simplify-checkout" style="padding: 1rem 1rem !important; border-radius: 0 !important;">
                <div class="modal-title"><h3>{{ __('Simplified Checkout') }}</h3></div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="container algin-simplify-checkout">
                <h5>{{ __('Buy in a simple and interactive way') }}</h5>
            </div>
            <div class="modal-body mt-3">
                @include('includes.simplified-checkout')
            </div>
        </div>
    </div>
</div>
{{-- ADD / EDIT MODAL ENDS --}}