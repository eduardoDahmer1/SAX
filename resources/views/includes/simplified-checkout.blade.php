<div class="content-area">
    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        @include('includes.admin.form-error')
                        @php $isBridalEnabled = env('ENABLE_SAX_BRIDAL', false); @endphp
                        <form id="geniusformdata" action="{{ route('front.simplified_checkout-create') }}" method="GET" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="customer_name">{{ __('Name') }} *</label>
                                    <input type="text" id="customer_name" class="input-field" name="name" placeholder="{{ __('Name') }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="customer_phone">{{ __('Phone') }} *</label>
                                    <input type="text" id="customer_phone" class="input-field" name="phone" placeholder="{{ __('Phone') }}" required>
                                    <div id="phone-error" style="color: red; display: none;">Número inválido. Use +55 ou +595 e pelo menos 5 dígitos.</div>
                                </div>
                            </div>
                            @if(!$isBridalEnabled)
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="delivery_method">{{ __('Delivery or Collection?') }} *</label>
                                    <select id="delivery_method" name="delivery_method" class="input-field" required onchange="toggleFields()">
                                        <option value="Retirar">{{ __('Pick up in store') }}</option>
                                        <option value="Delivery">{{ __('Delivery') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="location_row" style="display: none;">
                                <div class="col-lg-12">
                                    <label for="location">{{ __('Your location') }} *</label>
                                    <select id="location" class="input-field">
                                        <option value="Cde">{{ __('Ciudad del Este') }}</option>
                                        <option value="Asuncion">{{ __('Asunción') }}</option>
                                        <option value="Brasil">{{ __('Brasil') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="description_row" style="display: none;">
                                <div class="col-lg-12">
                                    <label for="description">{{ __('Your more detailed address') }} *</label>
                                    <textarea id="description" class="input-field" placeholder=""></textarea>
                                </div>
                            </div>
                            <div class="row" id="payment_method_row">
                                <div class="col-lg-12">
                                    <label for="payment_method">{{ __('Payment method') }} *</label>
                                    <select id="payment_method" name="payment_method" class="input-field" required onchange="togglePaymentOptions()">
                                        <option value="Pagar_na_loja">{{ __('Pay in store') }}</option>
                                        <option value="Pague_agora">{{ __('Pay now') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="payment_options_row" style="display: none;">
                                <div class="col-lg-12">
                                    <label for="payment">{{ __('Payment options') }} *</label>
                                    <select id="payment_options" class="input-field" name="payment">
                                        <option value="Qr_code">{{ __('QR Code') }}</option>
                                        <option value="Transferencia">{{ __('Transfer') }}</option>
                                        <option value="Cartao_de_credito">{{ __('Credit card') }}</option>
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="row mt-30">
                                <div class="col-lg-12" style="text-align: right;">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                    <button id="submit-button" onclick="btnDisabled(this)" class="btn btn-success" type="submit" disabled>{{ __('Submit') }}</button>
                                </div>
                            </div>
                        </form>

                        <script>
                        let clickCount = 0;

                        // Desativa o botão de envio após o primeiro clique
                        function btnDisabled(button) {
                            clickCount++;
                            if (clickCount > 1) {
                                button.disabled = true;
                            }
                        }

                        // Habilita o botão de envio apenas se nome e telefone forem válidos
                        function validateForm() {
                            const nameInput = document.getElementById('customer_name').value.trim();
                            const phoneInput = document.getElementById('customer_phone').value.trim();
                            const phoneRegex = /^\+?(55|595)?\d{5,}$/; // Aceita +55 ou +595 e pelo menos 5 números
                            const submitButton = document.getElementById('submit-button');

                            if (nameInput && phoneRegex.test(phoneInput)) {
                                submitButton.disabled = false;
                            } else {
                                submitButton.disabled = true;
                            }
                        }

                        // Validação do número de telefone
                        document.getElementById('customer_phone').addEventListener('input', function() {
                            const phoneInput = this;
                            const phoneValue = phoneInput.value.trim();
                            const regex = /^\+?(55|595)?\d{5,}$/; // Aceita +55 ou +595 e pelo menos 5 números
                            const errorDiv = document.getElementById('phone-error');

                            if (regex.test(phoneValue)) {
                                errorDiv.style.display = 'none';
                                phoneInput.style.borderColor = '';
                            } else {
                                errorDiv.style.display = 'block';
                                phoneInput.style.borderColor = 'red';
                            }
                            validateForm();
                        });

                        // Validação do nome
                        document.getElementById('customer_name').addEventListener('input', validateForm);

                        // Lógica para exibir ou ocultar campos baseados no método de entrega
                        function toggleFields() {
                            const deliveryMethod = document.getElementById('delivery_method').value;
                            const locationRow = document.getElementById('location_row');
                            const descriptionRow = document.getElementById('description_row');
                            const locationField = document.getElementById('location');
                            const descriptionField = document.getElementById('description');

                            if (deliveryMethod === 'Delivery') {
                                locationRow.style.display = 'block';
                                descriptionRow.style.display = 'block';
                                locationField.name = 'location';
                                descriptionField.name = 'description';
                            } else {
                                locationRow.style.display = 'none';
                                descriptionRow.style.display = 'none';
                                locationField.removeAttribute('name');
                                descriptionField.removeAttribute('name');
                            }
                        }

                        // Lógica para exibir ou ocultar opções de pagamento
                        function togglePaymentOptions() {
                            const paymentMethod = document.getElementById('payment_method').value;
                            const paymentOptionsRow = document.getElementById('payment_options_row');
                            const paymentOptionsField = document.getElementById('payment_options');

                            if (paymentMethod === 'Pague_agora') {
                                paymentOptionsRow.style.display = 'block';
                                paymentOptionsField.name = 'payment';
                            } else {
                                paymentOptionsRow.style.display = 'none';
                                paymentOptionsField.removeAttribute('name');
                            }
                        }
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
