<?php
$message = null;
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/TCC/Procafeinacao/database/sqls/UAC/login_uac_sql.php');
if (session_status() === PHP_SESSION_ACTIVE) {
    if (!empty($_SESSION)) {
        if (isset($_SESSION['Ucpfcnpj']) && isset($_SESSION['Upassword'])) {
            $base_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            $page = $base_url . "/TCC/Procafeinacao/acesso/login";
            header("Location: " . $page);
        }
    } else {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //nao entra nessa parte do codigo
            if (isset($_POST['user_type']) && isset($_POST['user_name']) && isset($_POST['user_cpfcnpj']) && isset($_POST['user_email']) && isset($_POST['user_password'])) {
                $user_type = $_POST['user_type'];
                $user_name = $_POST['user_name'];
                $user_cpfcnpj = $_POST['user_cpfcnpj'];
                $user_email = $_POST['user_email'];
                $user_password = $_POST['user_password'];
                if (empty(trim($user_name)) || empty(trim($user_cpfcnpj)) || empty(trim($user_email)) || empty(trim($user_password))) {
                    $message = "Por favor, preencha todos os campos obrigatórios.";
                } else if ((strlen($user_cpfcnpj) !== 11 && $user_type === 'C') || (strlen($user_cpfcnpj) !== 14 && $user_type === 'B')) {
                    //$message = "Por favor, verifique o campo CPF/CNPJ e tente novamente.";
                    $message = $user_type === 'C' ? "Por favor, verifique o campo CPF e tente novamente." : "Por favor, verifique o campo CNPJ e tente novamente.";
                } else {
                    $base_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
                    $page = $base_url . "/TCC/Procafeinacao/pages/menu/menu-itens";

                    if (strlen($user_cpfcnpj) === 11) {
                        $user = array(
                            'user_name' => $user_name,
                            'user_type' => $user_type,
                            'user_email' => $user_email,
                            'client_cpf' => $user_cpfcnpj,
                            'user_password' => hash("sha512", $user_password),
                        );
                    } else if (strlen($user_cpfcnpj) === 14) {
                        $user = array(
                            'user_name' => $user_name,
                            'user_type' => $user_type,
                            'user_email' => $user_email,
                            'business_cnpj' => $user_cpfcnpj,
                            'user_password' => hash("sha512", $user_password),
                        );
                    }

                    $message = Register($user, $page);
                }
            }
        }
    }

    ?>

    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registrar</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="/TCC/Procafeinacao/assets/css/style.css">
        <style>
            @font-face {
                font-family: "generic";
                src: url('/TCC/Procafeinacao/assets/fonts/generic.ttf') format('truetype');
                /* Inclua outros formatos de fonte, se necessário */
            }
        </style>
    </head>

    <body
        style="background: url(/TCC/Procafeinacao/pages/img/cardapio.jpg) no-repeat center fixed;background-size: cover; ">
        <div class="auth-page-wrapper pt-5">
            <div class="container">
                <div class="main-content">
                    <div class="page-content">
                        <div class="container-fluid">
                            <div class="row justify-content-center">
                                <div class="col">
                                    <div class="row justify-content-center">
                                        <div class="col-md-8 col-lg-6 col-xl-5" style="background-color: #FBF5E6; 
                                        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 
                                        0 6px 20px 0 rgba(0, 0, 0, 0.19);padding-bottom: 30px;">
                                            <div class="card mt-4">

                                                <div class="card " style="background-color: #FFFDFA; border-radius: 5px;">
                                                    <style>
                                                        .selected {
                                                            background-color: rgba(54, 22, 11, 0.966);
                                                        }
                                                    </style>
                                                    <form method="post">
                                                        <div class="card-header m-0 p-0" style="overflow:hidden">
                                                            <div class="row" id="change">
                                                                <h4 class="card-title mb-0 w-50 text-primary text-center p-2 tab selected"
                                                                    style="cursor:pointer;" data-target="cliente">
                                                                    <div
                                                                        style="font-family: 'generic'; font-weight: bold; color:#a87b26;">
                                                                        Cliente</div>
                                                                </h4>
                                                                <h4 class="card-title mb-0 w-50 text-primary text-center tab p-2"
                                                                    style="cursor:pointer;" data-target="empresa">
                                                                    <div
                                                                        style="font-family: 'generic'; font-weight: bold; color:#a87b26;">
                                                                        Empresa</div>
                                                                </h4>
                                                            </div>
                                                        </div>



                                                        <fieldset id="cliente" class="d-block tab-content">
                                                            <input type="hidden" value="C" name="user_type" />
                                                            <div class="card-body">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="mb-3">
                                                                            <label for="user_name" class="form-label" style="font-family: 'generic';
                                                                        font-weight: bold; color:#503f20;">Nome
                                                                                completo</label>
                                                                            <input required type="text" class="form-control"
                                                                                name="user_name"
                                                                                placeholder="Digite seu nome completo"
                                                                                id="user_name">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="mb-3">
                                                                            <label for="user_cpfcnpj" class="form-label"
                                                                                style="font-family: 'generic';
                                                                                font-weight: bold; color:#503f20;">CPF</label>
                                                                            <input required type="text" class="form-control"
                                                                                name="user_cpfcnpj"
                                                                                placeholder="Digite seu CPF"
                                                                                onKeyPress="if(this.value.length>=11) return false;"
                                                                                id="user_cpfcnpj">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="mb-3">
                                                                            <label for="user_email" class="form-label"
                                                                                style="font-family: 'generic';
                                                                                font-weight: bold; color:#503f20;">E-mail</label>
                                                                            <input required type="email"
                                                                                class="form-control" name="user_email"
                                                                                placeholder="Digite seu e-mail"
                                                                                id="user_email">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="mb-3 position-relative">
                                                                            <label for="user_password" class="form-label"
                                                                                style="font-family: 'generic';
                                                                                font-weight: bold; color:#503f20;">Senha</label>
                                                                            <div class="input-group">
                                                                                <input required autocomplete="off"
                                                                                    type="password" class="form-control"
                                                                                    name="user_password"
                                                                                    placeholder="Digite sua senha"
                                                                                    id="user_password">
                                                                                <div id="password-toggle"
                                                                                    class="btn btn-link text-muted" style="position: absolute;
                                                                            top: 50%;
                                                                            right: 0px;
                                                                            transform: translateY(-50%);
                                                                            z-index: 10; cursor: pointer;">
                                                                                    <i id="password-toggle-ico"
                                                                                        class="bi bi-eye-slash fs-20"></i>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="mb-3">
                                                                            <div id="capsLockWarning" class="text-danger"
                                                                                style="display: none;">Caps Lock está
                                                                                ativado.</div>
                                                                        </div>
                                                                        <script>
                                                                            // Adicionar evento de verificação do Caps Lock
                                                                            $('#user_password').on('keydown', function (e) {
                                                                                var capsLockWarning = document.getElementById('capsLockWarning');
                                                                                var isCapsLockOn = e.originalEvent.getModifierState('CapsLock');
                                                                                capsLockWarning.style.display = isCapsLockOn ? 'block' : 'none';
                                                                            });
                                                                        </script>
                                                                    </div>
                                                                    <?php if ($message != null && $user_type === 'C') { ?>
                                                                        <div class="mt-2">
                                                                            <div class="alert alert-danger"
                                                                                style="font-size:14px" role="alert">
                                                                                <?php $messageLines = explode("\n", $message);
                                                                                foreach ($messageLines as $line) {
                                                                                    echo $line . "<br>";
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <div class="col-lg-12">
                                                                        <div class="text-right">
                                                                            <button type="submit" class="btn btn-primary"
                                                                                style="background-color:#503f20; 
                                                                                font-family: 'generic'; font-weight: bold; color:#e7d0a5;">
                                                                                Cadastrar</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>

                                                    </form>
                                                    <form method="post">
                                                        <fieldset id="empresa" class="d-none tab-content">
                                                            <input type="hidden" value="B" name="user_type" />
                                                            <div class="card-body">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="mb-3">
                                                                            <label for="user_name" class="form-label" style="font-family: 'generic';
                                                                                font-weight: bold; color:#503f20;">Nome
                                                                                fantasia</label>
                                                                            <input required type="text" class="form-control"
                                                                                name="user_name"
                                                                                placeholder="Digite seu nome completo"
                                                                                id="user_name">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="mb-3">
                                                                            <label for="user_cpfcnpj" class="form-label"
                                                                                style="font-family: 'generic';
                                                                                font-weight: bold; color:#503f20;">CNPJ</label>
                                                                            <input required type="text" class="form-control"
                                                                                name="user_cpfcnpj"
                                                                                placeholder="Digite o CNPJ"
                                                                                onKeyPress="if(this.value.length>=14) return false;"
                                                                                id="user_cpfcnpj">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="mb-3">
                                                                            <label for="user_email" class="form-label"
                                                                                style="font-family: 'generic';
                                                                                font-weight: bold; color:#503f20;">E-mail</label>
                                                                            <input required type="email"
                                                                                class="form-control" name="user_email"
                                                                                placeholder="Digite seu e-mail"
                                                                                id="user_email">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="mb-3 position-relative">
                                                                            <label for="user_password" class="form-label"
                                                                                style="font-family: 'generic';
                                                                                font-weight: bold; color:#503f20;">Senha</label>

                                                                            <div class="input-group">
                                                                                <input required autocomplete="off"
                                                                                    type="password" class="form-control"
                                                                                    name="user_password"
                                                                                    placeholder="Digite sua senha"
                                                                                    id="user_password">
                                                                                <div id="password-toggle"
                                                                                    class="btn btn-link text-muted" style="position: absolute;
                                                                            top: 50%;
                                                                            right: 0px;
                                                                            transform: translateY(-50%);
                                                                            z-index: 10; cursor: pointer;">
                                                                                    <i id="password-toggle-ico"
                                                                                        class="bi bi-eye-slash fs-20"></i>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="mb-3">
                                                                            <div id="capsLockWarning" class="text-danger"
                                                                                style="display: none;">Caps Lock está
                                                                                ativado.</div>
                                                                        </div>
                                                                        <script>
                                                                            // Adicionar evento de verificação do Caps Lock
                                                                            $('#user_password').on('keydown', function (e) {
                                                                                var capsLockWarning = document.getElementById('capsLockWarning');
                                                                                var isCapsLockOn = e.originalEvent.getModifierState('CapsLock');
                                                                                capsLockWarning.style.display = isCapsLockOn ? 'block' : 'none';
                                                                            });
                                                                        </script>
                                                                    </div>
                                                                    <?php if ($message != null && $user_type === 'B') { ?>
                                                                        <div class="mt-2">
                                                                            <div class="alert alert-danger"
                                                                                style="font-size:14px" role="alert">
                                                                                <?php $messageLines = explode("\n", $message);
                                                                                foreach ($messageLines as $line) {
                                                                                    echo $line . "<br>";
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <div class="col-lg-12">
                                                                        <div class="text-right">
                                                                            <button type="submit" class="btn btn-primary"
                                                                                style="background-color:#503f20; 
                                                                                font-family: 'generic'; font-weight: bold; color:#e7d0a5;">
                                                                                Cadastrar</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </fieldset>
                                                    </form>
                                                    <a href="login" class="text-decoration-none">
                                                        <div class="card-footer text-center text-muted"
                                                            style="background-color: #d6bb87;">
                                                            <h6 style="font-family: 'generic'; 
                                                                    font-weight: bold; color:#776035;">Já possuí uma
                                                                conta?
                                                            </h6>
                                                            <p
                                                                style="font-family: 'generic';text-decoration: underline;font-size:14px;
                                                                    margin:4px 0px 0px 0px; font-weight: bold; color:#36280e;">
                                                                Entrar</p>
                                                        </div>
                                                    </a>
                                                    <script>
                                                        $(document).ready(function () {
                                                            // Verifique se há uma preferência de aba salva no localStorage
                                                            var preferredTab = localStorage.getItem('preferredTab');

                                                            // Se a preferência de aba estiver definida no localStorage, use-a
                                                            if (preferredTab) {
                                                                // Remove a classe 'selected' de todas as abas
                                                                $(".tab").removeClass("selected");

                                                                // Adicione a classe 'selected' à aba preferida
                                                                $("[data-target='" + preferredTab + "']").addClass("selected");

                                                                // Oculta todos os conteúdos das abas
                                                                $(".tab-content").removeClass("d-block").addClass("d-none");

                                                                // Mostra o conteúdo da aba correspondente à preferência
                                                                $("#" + preferredTab).removeClass("d-none").addClass("d-block");
                                                            }

                                                            $(".tab").click(function () {
                                                                // Remove a classe 'selected' de todas as abas
                                                                $(".tab").removeClass("selected");

                                                                // Adicione a classe 'selected' à aba clicada
                                                                $(this).addClass("selected");

                                                                // Oculta todos os conteúdos das abas
                                                                $(".tab-content").removeClass("d-block").addClass("d-none");

                                                                // Obtém o alvo (target) da aba clicada
                                                                var target = $(this).data("target");

                                                                // Mostra o conteúdo da aba correspondente
                                                                $("#" + target).removeClass("d-none").addClass("d-block");

                                                                // Salve a preferência de aba no localStorage
                                                                localStorage.setItem('preferredTab', target);
                                                            });
                                                        });
                                                    </script>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/TCC/Procafeinacao/parts/rodape.php'); ?>

        </div>

        <script>
            $(document).ready(function () {
                $('#password-toggle').click(function () {
                    var passwordInput = $('#user_password');
                    var passwordToggle = $(this);

                    if (passwordInput.attr('type') === 'password') {
                        passwordInput.attr('type', 'text');
                        $('#password-toggle-ico').removeClass('bi-eye-slash').addClass('bi-eye');
                    } else {
                        passwordInput.attr('type', 'password');
                        $('#password-toggle-ico').removeClass('bi-eye').addClass('bi-eye-slash');
                    }
                });
            });
        </script>

    </body>

    </html>

<?php } ?>