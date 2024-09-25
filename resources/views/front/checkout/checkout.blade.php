<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/checkout/style.css') }}">
</head>
<body>
    <header class="bg-black text-center py-3 mb-5"><img src="{{ asset('storage/images/logobranca.png') }}" alt="">
    </header>
    <div class="container">
        <div class="row justify-content-center">
            <div class="step-icons d-flex col-10 col-md-8 align-items-center">
                <div class="d-flex align-items-center"><i class="bi bi-bag-check-fill color-2"></i></div>
                <div class="line"></div>
                <div class="d-flex align-items-center"><i class="bi bi-person-fill"></i></div>
                <div class="line"></div>
                <div class="d-flex align-items-center"><i class="bi bi-truck"></i></div>
                <div class="line"></div>
                <div class="d-flex align-items-center"><i class="bi bi-credit-card"></i></div>
            </div>
            <div class="step col-10 row align-items-center justify-content-center mt-4">
                <div class="d-flex align-items-center bg-top my-4 py-2">
                    <h6 class="col-8">PRODUTO</h6>
                    <h6 class="col-2 d-lg-block d-none">QUANTIDADE</h6>
                    <h6 class="col-2 d-lg-block d-none">PREÇO</h6>
                </div>@foreach ($products as $product)<div
                    class="d-flex flex-wrap align-items-center p-0 pb-5 border-bottom-f1 mb-4">
                    <div class="col-lg-8 prod-img"><img src="https://i.ibb.co/gSKcHxR/relogio.png" alt="">
                        <div class="pl-4">
                            <h5 class="fw-normal fs-16">Relógio Integrity Cronógrafo Malha Dourado Rosa</h5>
                            <p class="color-1">Código do produto: abjd60533</p>
                        </div>
                    </div>
                    <div class="col-12 d-flex align-items-center bg-top my-4 py-2 d-lg-none d-block">
                        <h6 class="col-6">QUANTIDADE</h6>
                        <h6 class="col-6">PREÇO</h6>
                    </div>
                    <p class="col-lg-2 col-6 m-lg-0 mt-3">2</p>
                    <div class="col-lg-2 prices col-6">
                        <h5 class="mb-0 fw-semibold">R$ 123.49</h5><span>U$ 25.00</span>
                    </div>
                </div>@endforeach<div class="bg-top py-5 d-flex flex-wrap justify-content-between mt-3">
                    <div class="prices d-flex justify-content-between px-2 col-12 col-md-7">
                        <p class="color-1 m-0">Total (2 itens):</p>
                        <div class="px-lg-5">
                            <h5 class="mb-0 fw-semibold">R$ 370.47</h5><span class="color-1 m-0">U$ 75.00</span>
                        </div>
                    </div><button class="px-5 btn-continue col-md-4 col-lg-3 col-12 mt-4 mt-md-0">Continuar</button>
                </div>
            </div>
            <div class="step col-sm-10 row align-items-center justify-content-center mt-4">
                <div class="d-flex align-items-center p-0 pb-3 border-bottom-f1">
                    <h5 class="fw-semibold">Dados pessoais</h5>
                </div>
                <div class="bg-top py-5 row justify-content-center mt-5 personal-data">
                    <div class="col-md-6 mb-3">
                        <p class="m-0 color-1 fw-semibold px-1">Nome</p><input id="name" name="name" class="col-5 mx-1"
                            type="text">
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="m-0 color-1 fw-semibold px-1">CI o RUC</p><input id="ruc" name="ruc"
                            class="col-5 mx-1" type="text">
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="m-0 color-1 fw-semibold px-1">E-mail</p><input id="email" name="email"
                            class="col-5 mx-1" type="text">
                    </div>
                    <div class="col-md-6 mb-3">
                        <p class="m-0 color-1 fw-semibold px-1">Telefone</p><input id="phone" name="phone"
                            class="col-5 mx-1" type="text">
                    </div>
                    <div class="col-12 text-end mt-4 d-md-block d-none"><button class="btn-back">Voltar</button><button
                            class="px-5 btn-continue">Continuar</button></div>
                    <div class="col-12 text-center mt-4 d-md-none d-block"><button
                            class="btn-back">Voltar</button><button class="px-5 btn-continue">Continuar</button></div>
                </div>
            </div>
            <div class="step col-sm-10 row align-items-center justify-content-center mt-4">
                <div class="d-flex align-items-center p-0 pb-3 border-bottom-f1">
                    <h5 class="fw-semibold">Opções de Entrega</h5>
                </div>
                <div class="bg-top mt-5 py-4">
                    <div class="border-bottom-f1 pb-4 d-flex justify-content-between">
                        <div><input id="myaddress" name="shipping" value="1" type="radio"><label for="myaddress">Receber
                                no meu endereço</label>
                            <p style="font-size: 14px;" class="mb-0 color-1 px-3">Rua Manaus 1356, Foz Do Iguaçu - PR
                            </p>
                        </div>
                        <h6 class="px-2 color-3">U$10</h6>
                    </div>
                    <div class="border-bottom-f1 py-4 d-flex flex-wrap justify-content-between">
                        <div><input id="newaddress" name="shipping" value="2" type="radio"><label
                                for="newaddress">Adicionar endereço</label></div>
                        <h6 class="px-2 color-3">U$10</h6>
                        <div class="d-none col-12 mt-3 new-address">
                            <div class="col-md-4">
                                <p style="font-size: 14px;" class="m-0 color-1 fw-semibold px-1">Departamento</p><input
                                    id="country" name="country" type="text">
                            </div>
                            <div class="col-md-4 px-1">
                                <p style="font-size: 14px;" class="m-0 color-1 fw-semibold px-1">Cidade</p><input
                                    id="city" name="city" type="text">
                            </div>
                            <div class="col-md-4">
                                <p style="font-size: 14px;" class="m-0 color-1 fw-semibold px-1">Endereço</p><input
                                    id="address" name="address" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="py-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div><input id="withdrawal" name="shipping" value="3" type="radio"><label
                                    for="withdrawal">Retirar na SAX</label></div><select
                                class="select-local d-none mx-2" name="local" id="local">
                                <option value="1">CDE</option>
                                <option value="2">ASSUNÇÃO</option>
                            </select>
                        </div><span style="font-size: 14px;">GRÁTIS</span>
                    </div>
                    <div class="col-12"><iframe style="height: 300px;" class="w-100 CDE-MAP d-none"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14403.483045695173!2d-54.625295595894784!3d-25.509356390177906!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94f69aaaec5ef03d%3A0xff12a8b090a63ebd!2sShopping%20China%20Importados!5e0!3m2!1spt-BR!2sbr!4v1691342273039!5m2!1spt-BR!2sbr"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe><iframe style="height: 300px;"
                            class="w-100 ASU-MAP d-none"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d115398.13329242056!2d-57.637876049999996!3d-25.296836649999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x945da8e2ae3ff05f%3A0x8aa5f6f5f49ff064!2sShopping%20China%20Importados!5e0!3m2!1spt-BR!2sbr!4v1691342234510!5m2!1spt-BR!2sbr"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe></div>
                </div>
                <div class="col-12 text-end mt-4 d-md-block d-none"><button class="btn-back">Voltar</button><button
                        class="px-5 btn-continue">Continuar</button></div>
                <div class="col-12 text-center mt-4 d-md-none d-block"><button class="btn-back">Voltar</button><button
                        class="px-5 btn-continue">Continuar</button></div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-q3QlRvErlyiC1Wv+cPpTEosMF7lME/gd2EeSZjf9rfl+0G07EhgIkjOq4nwAzG7s" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets/checkout/scripts.js') }}"></script>
</body>

</html>