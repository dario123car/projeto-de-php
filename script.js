
document.addEventListener('DOMContentLoaded', () => {

    const formulario = document.querySelector('#form-inscricao');
    const mensagem = document.querySelector('#mensagem');
    const contador = document.querySelector('#contador');
    const erroOficinas = document.querySelector('#oficinas-error');

    const oficinas = document.querySelectorAll(
        'input[name="oficinas[]"]'
    );

    // Contador de caracteres
    function atualizarContador() {

        const restantes = mensagem.maxLength - mensagem.value.length;

        contador.textContent = `Restam ${restantes} caracteres`;
    }

    mensagem.addEventListener('input', atualizarContador);

    atualizarContador();

    // Remove o aviso quando uma oficina for selecionada
    oficinas.forEach((oficina) => {

        oficina.addEventListener('change', () => {

            const selecionada = [...oficinas].some(
                item => item.checked
            );

            if (selecionada) {
                erroOficinas.textContent = '';
            }
        });

    });

    // Validação antes de enviar o formulário
    formulario.addEventListener('submit', (event) => {

        const selecionada = [...oficinas].some(
            item => item.checked
        );

        if (!selecionada) {

            event.preventDefault();

            erroOficinas.textContent =
                'Selecione pelo menos uma oficina antes de enviar.';

            oficinas[0].focus();
        }

    });

});
