<section>
    <style>
    #fecha {
        margin: 0 auto;
        text-align: center;
        font-size: 1em;
        white-space: nowrap;
        background-color: black;
        font-weight: 600;
        width: 100%;
        padding: 4px;
        z-index: 999;
    }

    #modalLink {
        color: white;
    }

    #contador span {
        color: white;
    }

    #contador {
        color: white;
        position: relative;
        animation: mover 18s linear infinite;
        width: 60vw;
    }

    @keyframes mover {
        from {
            transform: translateX(-75%);
        }

        to {
            transform: translateX(120%);
        }
    }

    /* Estilos para o modal */
    #modal {
        display: none;
        /* Inicialmente escondido */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.77);
        justify-content: center;
        align-items: center;
    }

    #modal img {
        max-width: 90%;
        max-height: 90%;
    }

    #closeModal {
        position: absolute;
        top: 20px;
        right: 20px;
        background-color: transparent;
        border: none;
        padding: 10px;
        color: white;
        font-size: 3em;
    }
    </style>

    @if (env('SHOW_COUNTDOWN', false))
    <!-- false é o valor padrão se não for definido -->
    <div onload="iniciarContagem()">
        <div id="fecha">
            <div id="contador" onmouseover="pauseAnimation()" onmouseout="resumeAnimation()">
                <a href="#" id="modalLink">
                    <!-- link para modal -->
                    Faltan <span id="dias"></span> dias, <span id="hora"></span> horas, <span id="minuto"></span>
                    minutos,
                    <span id="segundo"></span> segundos para iniciar nosso fashion sale
                </a>
            </div>
        </div>
    </div>
    @endif
    <!-- Modal -->
    <div id="modal">
        <button id="closeModal">X</button>
        <img src="{{ $admstore->pagesettings->banner_search4 ? asset('storage/images/banners/' . $admstore->pagesettings->banner_search4) : asset('assets/images/noimage.png') }}" alt="Imagem do Fashion Sale">
    </div>
    <!-- <img src="{{ asset('assets/images/theme15/teste.jpeg')}}" alt="Imagem do Fashion Sale"> -->
    <script>
    // Variáveis globais
    var intervalo = setInterval(iniciarContagem, 1000); // Atualiza a cada segundo
    var animationPaused = false;

    function iniciarContagem() {
        var agora = new Date(); // Data e hora atuais
        var futuro = new Date("2024-11-14T00:00:00"); // Data futura desejada

        // Calcula a diferença em milissegundos
        var diferenca = futuro - agora;

        if (diferenca > 0) {
            // Converte a diferença para dias, horas, minutos e segundos
            var dias = Math.floor(diferenca / (1000 * 60 * 60 * 24));
            var horas = Math.floor((diferenca % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutos = Math.floor((diferenca % (1000 * 60 * 60)) / (1000 * 60));
            var segundos = Math.floor((diferenca % (1000 * 60)) / 1000);

            // Atualiza o conteúdo dos elementos HTML
            document.getElementById("dias").innerHTML = dias;
            document.getElementById("hora").innerHTML = horas;
            document.getElementById("minuto").innerHTML = minutos;
            document.getElementById("segundo").innerHTML = segundos;
        } else {
            clearInterval(intervalo);
            document.getElementById("fecha").innerHTML = "A data já chegou!";
        }
    }

    function pauseAnimation() {
        animationPaused = true;
        document.getElementById("contador").style.animationPlayState = "paused";
    }

    function resumeAnimation() {
        animationPaused = false;
        document.getElementById("contador").style.animationPlayState = "running";
    }

    document.getElementById("modalLink").addEventListener("click", function(event) {
        event.preventDefault(); // Impede o comportamento padrão do link
        document.getElementById("modal").style.display = "flex"; // Mostra o modal
    });

    document.getElementById("closeModal").addEventListener("click", function() {
        document.getElementById("modal").style.display = "none"; // Esconde o modal
    });

    // Fechar o modal ao clicar fora da imagem
    window.addEventListener("click", function(event) {
        var modal = document.getElementById("modal");
        if (event.target == modal) {
            modal.style.display = "none"; // Esconde o modal
        }
    });

    // Verifica a rolagem da página
    window.addEventListener("scroll", function() {
        var scrollTop = window.scrollY; // Obtém a posição de rolagem
        var fecha = document.getElementById("fecha");

        if (scrollTop > 0) {
            // Move o texto para a parte inferior
            fecha.style.top = 'auto'; // Remove a posição fixa no topo
            fecha.style.bottom = '0'; // Posiciona no fundo
            fecha.style.position = 'fixed';
        } else {
            // Retorna ao topo
            fecha.style.top = '0'; // Retorna ao topo
            fecha.style.bottom = 'auto'; // Remove a posição fixa na parte inferior
            fecha.style.position = 'relative';
        }
    });
    </script>
</section>

<section class="top-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="content">
                    <div class="left-content">
                        <div class="list">
                            @php
                            $top_curr = ($slocale->id == '1')
                            ? App\Models\Currency::where('sign', 'R$')->first()
                            : App\Models\Currency::where('sign', 'GS$')->first();
                            @endphp
                            <ul>
                                @if (config('features.lang_switcher') && $gs->is_language == 1)
                                <li class="separador-right">
                                    <div class="language-selector">
                                        <i class="fas fa-globe-americas"></i>
                                        <select id="changeLanguage" name="language" class="language selectors nice">
                                            @foreach ($locales as $language)
                                            <option
                                                value="{{ route('front.language', [$language->id, $top_curr->id]) }}"
                                                {{ $slocale->id == $language->id ? 'selected' : '' }}>
                                                {{ $language->language }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                                @endif

                                @if ($gs->show_currency_values == 1 && $top_curr->id != 1)
                                <li>
                                    <div class="currency-selector">
                                        <span>
                                            <i class="fas fa-coins"></i>
                                            {{ __('Currency Rate') }}:
                                            {{ $curr->sign . number_format($curr->value, $curr->decimal_digits, $curr->decimal_separator, $curr->thousands_separator) }}
                                            =
                                            {{ $top_curr->sign . ' ' . number_format($top_curr->value, $top_curr->decimal_digits, $top_curr->decimal_separator, $top_curr->thousands_separator) }}
                                        </span>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="right-content">
                        <div class="list">
                            <ul>
                                @if (config('features.currency_switcher') && $gs->is_currency == 1)
                                <li>
                                    <div hidden class="currency-selector" style="padding-right:12px;">
                                        <select id="changeCurrency" name="currency" class="currency selectors nice">
                                            @foreach ($currencies as $currency)
                                            <option value="{{ route('front.currency', $currency->id) }}"
                                                @selected($top_curr->sign == $currency->sign)>
                                                {{ $currency->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                                @endif
                                @if (config('features.productsListPdf'))
                                <li class="login ml-0 separador-left">
                                    <a target="_blank" href="{{ route('download-list-pdf') }}">
                                        <div class="links">
                                            {{ __('Products list - PDF') }}
                                            <i class="fas fa-download"></i>
                                        </div>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
const selectLanguage = document.getElementById('changeLanguage');
const selectCurrency = document.getElementById('changeCurrency');
window.addEventListener('load', function() {});
</script>