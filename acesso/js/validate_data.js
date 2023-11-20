async function FormAdds() {
    return new Promise((resolve, reject) => {
        $(document).ready(function() {
            $("#user_adds_cep").mask("99999-999");
            $(".btn-submit").click(function() {
                // Limpa as mensagens de erro anteriores
                $(".error-message").remove();

                // Validação do campo CEP
                var cep = $("#user_adds_cep").val().trim();
                if (!cep) {
                    $("#user_adds_cep").after('<span class="error-message" style="color: red;">Campo obrigatório</span>');
                    return;
                } else if (!/^\d{8}$/.test(cep)) {
                    $("#user_adds_cep").after('<span class="error-message" style="color: red;">CEP inválido</span>');
                    return;
                }

                // Validação do campo Complemento
                var complemento = $("#user_adds_complemento").val().trim();
                if (!complemento) {
                    $("#user_adds_complemento").after('<span class="error-message" style="color: red;">Campo obrigatório</span>');
                    return;
                }

                // Validação do campo Número
                var numero = $("#user_adds_number").val().trim();
                if (!numero) {
                    $("#user_adds_number").after('<span class="error-message" style="color: red;">Campo obrigatório</span>');
                    return;
                }

                // Validação do campo Logradouro
                var logradouro = $("#user_adds_logradouro").val().trim();
                if (!logradouro) {
                    $("#user_adds_logradouro").after('<span class="error-message" style="color: red;">Campo obrigatório</span>');
                    return;
                }

                // Validação do campo Bairro
                var bairro = $("#user_adds_bairro").val().trim();
                if (!bairro) {
                    $("#user_adds_bairro").after('<span class="error-message" style="color: red;">Campo obrigatório</span>');
                    return;
                }

                // Validação do campo Localidade
                var localidade = $("#user_adds_localidade").val().trim();
                if (!localidade) {
                    $("#user_adds_localidade").after('<span class="error-message" style="color: red;">Campo obrigatório</span>');
                    return;
                }

                // Validação do campo Estado
                var estado = $("#user_adds_estado").val().trim();
                if (!estado) {
                    $("#user_adds_estado").after('<span class="error-message" style="color: red;">Campo obrigatório</span>');
                    return;
                }

                // Encontrar o fieldset atual
                /* var currentFieldset = $(this).closest("fieldset");
                currentFieldset.removeClass("d-block").addClass(
                    "d-none");
                var nextFieldset = currentFieldset.next();
                nextFieldset.removeClass("d-none").addClass("d-block"); */
                resolve(true);

            });

            // Remove máscara e limita quantidade de caracteres ao dar focus
            $("#user_adds_cep").focus(function() {
                    $(this).mask("99999-999");
                }) // Não permite colar caracteres
                .bind("paste", function(e) {
                    e.preventDefault();
                }).blur(function() {
                    $(this).unmask();
                });

        });
    });
}


$(document).ready(async function() {
    if ($("#form").length > 0) {

        if (await FormAdds()) {
            var formData = new FormData($("#form")[0]);
            $.ajax({
                type: 'POST',
                url: '/TCC/Procafeinacao/acesso/request/update_data',
                data: formData,
                contentType: false, // Necessário para enviar FormData corretamente
                processData: false, // Necessário para enviar FormData corretamente
                success: function(response) {

                    Toastify({
                        text: response,
                        duration: 2000,
                        close: false,
                        gravity: "top",
                        position: "center",
                        stopOnFocus: false,
                        style: {
                            background: "#f06548",
                            textAlign: "center",
                        },
                        callback: function() {
                            var newWindow = window.open('/TCC/Procafeinacao/acesso/login', '_self');
                            newWindow.onload();
                        }

                    }).showToast();

                },
                error: function() {
                    // Lidar com erros de requisição AJAX, se houverem
                    alert('Erro na requisição AJAX');
                }
            });
        }
    }
});