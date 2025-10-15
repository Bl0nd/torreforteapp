/*FUNÇÃO PARA FAZER O TEXTO DO DATA-TTS SER FALADO*********************************************/
function speak(text) {
    if ('speechSynthesis' in window) {
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'pt-BR';
        window.speechSynthesis.cancel();//para parar a fala anterior
        window.speechSynthesis.speak(utterance);//para falar a fala atual
    }
}

/*SELECIONA TODOS OS ELEMENTOS DA TELA PARA LEITURA********************************************/
document.querySelectorAll('[data-tts]').forEach(el => {
    const texto = el.getAttribute('data-tts');

    //FALAR AO CLICAR
    el.addEventListener('click', function () {
        speak(texto);
    });

    //FALAR AO FOCAR NO OBJETO
    el.addEventListener('focus', function () {
        speak(texto);
    });

});

/*LINK DE REDEFINIÇÃO DE SENHA*****************************************************************/
function enviarEmail(event) {
    event.preventDefault();
    const msg = document.getElementById('mensagem');
    msg.style.display = "block";

    //DESATIVAR O INPUT E O BUTTON
    document.getElementById('email').disabled = true;
    event.target.querySelector("button").disabled = true;

    //APÓS 5 SEGUNDOS REDIRECIONAR PARA A INDEX
    setTimeout(() => {
        window.location.href = "index.php";
    }, 5000);
}

let pixel = 0.5; // MEDIDA EM PIXEL QUE VAI AUMENTAR OU DIMINUIR A FONTE

function aumentarFonte() {
    const elementos = document.querySelectorAll('body, body *');

    elementos.forEach(el => {
        const estilo = window.getComputedStyle(el);
        const tamanho = parseFloat(estilo.fontSize);

        if (!isNaN(tamanho)) {
            el.style.fontSize = (tamanho + pixel) + "px";
        }
    })
}

function diminuirFonte() {
    const elementos = document.querySelectorAll('body, body *');

    elementos.forEach(el => {
        const estilo = window.getComputedStyle(el);
        const tamanho = parseFloat(estilo.fontSize);

        if (!isNaN(tamanho)) {
            el.style.fontSize = (tamanho - pixel) + "px";
        }
    })
}

// Carrosel menu
document.addEventListener('DOMContentLoaded', () => {
    const carouselContainer = document.getElementById('carouselContainer');

    if (!carouselContainer) {
        return;
    }

    // Calcula a largura de um card (Largura do card + gap de 30px)
    const cardElement = carouselContainer.querySelector('.card-servico');
    // 380 = 350px (card width) + 30px (gap)
    const cardWidthWithGap = cardElement ? cardElement.offsetWidth + 30 : 380;

    let autoScrollInterval;
    const scrollDelay = 5000; // 5 segundos para a rolagem automática

    const autoScroll = () => {
        // Verifica se está no final do scroll
        if (carouselContainer.scrollLeft + carouselContainer.clientWidth >= carouselContainer.scrollWidth - 1) {
            // Se estiver no final, volta para o início
            carouselContainer.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
            // Rola para o próximo card (um "snap-point")
            carouselContainer.scrollBy({ left: cardWidthWithGap, behavior: 'smooth' });
        }
    };

    const startAutoScroll = () => {
        // Limpa o intervalo existente e inicia um novo
        clearInterval(autoScrollInterval);
        autoScrollInterval = setInterval(autoScroll, scrollDelay);
    };

    // Inicia a rolagem automática
    startAutoScroll();

    carouselContainer.addEventListener('mouseenter', () => clearInterval(autoScrollInterval));
    carouselContainer.addEventListener('mouseleave', startAutoScroll);

    // Pausa e reinicia ao arrastar com o toque (mobile)
    carouselContainer.addEventListener('touchstart', () => clearInterval(autoScrollInterval));
    carouselContainer.addEventListener('touchend', startAutoScroll);
});

// Carregar foto do perfil
document.addEventListener('DOMContentLoaded', () => {
    // ... Seu código JS existente ...

    // Código do Perfil
    const btnUpload = document.getElementById('btn-upload');
    const fileInput = document.getElementById('foto-perfil-input');
    const preview = document.getElementById('profile-pic-preview');

    if (btnUpload && fileInput && preview) {
        // 1. Faz o botão visível disparar o input file escondido
        btnUpload.addEventListener('click', () => {
            fileInput.click();
        });

        // 2. Lê o arquivo selecionado e mostra a prévia
        fileInput.addEventListener('change', (event) => {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = (e) => {
                    preview.src = e.target.result;
                };

                reader.readAsDataURL(file);
            }
        });
    }
});

// Máscara pro tel e cpf
document.addEventListener('DOMContentLoaded', () => {
    // Selecionamos os campos usando o atributo 'name' com o sufixo '_cliente'
    const inputTelefone = document.querySelector('input[name="telefone_cliente"]');
    const inputCpf = document.querySelector('input[name="cpf_cliente"]');

    // MÁSCARA DE TELEFONE: (00) 00000-0000
    if (inputTelefone) {
        inputTelefone.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); 
            
            // Limita a 11 dígitos (DDD + 9 dígitos)
            if (value.length > 11) {
                value = value.substring(0, 11);
            }

            // Aplica a formatação
            if (value.length > 0) {
                value = '(' + value;
            }
            if (value.length > 2) {
                value = value.substring(0, 3) + ') ' + value.substring(3);
            }
            if (value.length > 9) {
                value = value.substring(0, 10) + '-' + value.substring(10);
            }
            
            e.target.value = value;
        });
        // Define o limite máximo de caracteres (15 = (00) 00000-0000)
        inputTelefone.setAttribute('maxlength', '15'); 
    }

    // MÁSCARA DE CPF: 000.000.000-00
    if (inputCpf) {
        inputCpf.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // Limita a 11 dígitos
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            
            // Aplica a formatação
            if (value.length > 3) {
                value = value.substring(0, 3) + '.' + value.substring(3);
            }
            if (value.length > 6) {
                value = value.substring(0, 7) + '.' + value.substring(6);
            }
            if (value.length > 9) {
                value = value.substring(0, 11) + '-' + value.substring(9);
            }
            
            e.target.value = value;
        });
        // Define o limite máximo de caracteres (14 = 000.000.000-00)
        inputCpf.setAttribute('maxlength', '14');
    }
});

// botões do historico
document.addEventListener('DOMContentLoaded', () => {
    const filterButtons = document.querySelectorAll('.filtro-btn');
    // Agora selecionamos usando a nova classe 'historico-item'
    const historyCards = document.querySelectorAll('.historico-item');

    // Se não houver botões ou cards, não faz nada
    if (filterButtons.length === 0 || historyCards.length === 0) {
        return;
    }

    // Função principal para filtrar os cards
    const filterHistory = (filter) => {
        historyCards.forEach(card => {
            // SOLUÇÃO ROBUSTA: Pega o status do atributo data-status
            const status = card.getAttribute('data-status');

            // Lógica de filtragem:
            // 1. Mostrar se o filtro for 'todos'
            // 2. OU se o status encontrado coincidir com o valor do filtro
            if (filter === 'todos' || status === filter) {
                card.classList.remove('hidden'); // Mostra o card
            } else {
                card.classList.add('hidden'); // Esconde o card
            }
        });
    };

    // Adiciona o listener de clique em cada botão de filtro
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filterValue = button.getAttribute('data-filter');

            // 1. Atualiza o estado do botão (qual está ativo)
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            // 2. Chama a função de filtragem
            filterHistory(filterValue);
        });
    });

    // Filtra inicialmente (garantindo que o filtro 'todos' ou inicial esteja aplicado)
    const initialFilterButton = document.querySelector('.filtro-btn.active');
    if (initialFilterButton) {
        filterHistory(initialFilterButton.getAttribute('data-filter'));
    }
});

let deferredPrompt;
const instalar = document.getElementById("instalar");
const btnInstalar = document.getElementById('btnInstalar');
const btnFechar = document.getElementById('btnFechar');

if (instalar) {
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        instalar.style.display = 'block';

        btnInstalar.addEventListener('click', async () => {
            instalar.style.display = 'none';
            deferredPrompt.prompt();

            const { outcome } = await deferredPrompt.userChoice;
            console.log(`Instalação: ${outcome}`);
            deferredPrompt = null;
        });

        btnFechar.addEventListener('click', () => {
            instalar.style.display = 'none';
        });
    });
}

if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('../public/sw.js')
            .then(function (registration) {
                console.log('ServiceWorker registrado com sucesso:', registration.scope);
            })
            .catch(function (error) {
                console.log('Falha ao registrar o ServiceWorker:', error);
            });
    });
}
