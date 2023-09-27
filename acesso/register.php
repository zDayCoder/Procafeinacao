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
            if (isset($_POST['user_fullname']) && isset($_POST['user_cpf']) && isset($_POST['user_password'])) {
                $user_fullname = $_POST['user_fullname'];
                $user_cpf = $_POST['user_cpf'];
                $user_password = $_POST['user_password'];
                if (empty(trim($user_fullname)) || empty(trim($user_cpf)) || empty(trim($user_password))) {
                    $message = "Por favor, preencha todos os campos obrigatórios.";
                } else {
                    $page = "../pages/menu/menu-itens";
                    $user = array(
                        'user_fullname' => $user_fullname,
                        'user_cpf' => $user_cpf,
                        'user_password' => $user_password,
                    );
                    //Create new account
                    $message = Register($user, $page);

                    //$message = login($user_cpf, hash("sha512", $user_password), $page);
                    //header("Location: ../pages/menu/menu-itens");
                    exit;
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>

    <body style="background: url(../pages/img/cardapio.jpg) no-repeat center fixed;
background-size: cover; "> <!--css 01-->
        <div class="auth-page-wrapper pt-5">
            <div class="container">
                <div class="main-content">
                    <div class="page-content">
                        <div class="container-fluid">
                            <div class="row justify-content-center">
                                <div class="col">
                                    <div class="row justify-content-center">
                                        <div class="col-md-8 col-lg-6 col-xl-5"
                                        style="background-color: #FBF5E6; 
                                        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 
                                        0 6px 20px 0 rgba(0, 0, 0, 0.19);padding-bottom: 30px;"> <!--css 02-->
                                            <div class="card mt-4">

                                                <div class="card " style="background-color: #FFFDFA; border-radius: 5px;"> <!--css 03-->
                                                    <form method="post"> <!--action="javascript:void(0);"-->
                                                        <div class="card-header m-0 p-0" style="overflow:hidden">
                                                            <div class="row" id="change">
                                                                <h4 class="card-title mb-0 w-50 text-primary text-center p-2 tab selected"
                                                                    style="cursor:pointer;" data-target="cliente">
                                                                    <div style="font-family: Times, serif; font-weight: bold; color:#a87b26;">
                                                                    Cliente</div>
                                                                </h4><!--css 04-->
                                                                <h4 class="card-title mb-0 w-50 text-primary text-center tab p-2"
                                                                    style="cursor:pointer;" data-target="empresa">
                                                                    <div style="font-family: Times, serif; font-weight: bold; color:#a87b26;">
                                                                    Empresa</div>
                                                                </h4><!--css 05-->
                                                            </div>
                                                        </div>


                                                        <style>
                                                            .selected {
                                                            background-color: rgba(54, 22, 11, 0.966); 
                                                        }
                                                    </style> <!--css 06-->
                                                        <fieldset id="cliente" class="d-block tab-content">

                                                            <div class="card-body">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="mb-3">
                                                                        <label for="user_fullname"
                                                                        class="form-label" style="font-family: Times, serif;
                                                                        font-weight: bold; color:#503f20;">Nome completo</label> <!--css 07-->
                                                                            <input required type="text" class="form-control"
                                                                                name="user_fullname"
                                                                                placeholder="Digite seu nome completo"
                                                                                id="user_fullname">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="mb-3">
                                                                            <label for="user_cpf"
                                                                                class="form-label" style="font-family: Times, serif;
                                                                                font-weight: bold; color:#503f20;">CPF</label> <!--css 08-->
                                                                            <input required type="text" class="form-control"
                                                                                name="user_cpf" placeholder="Digite seu CPF"
                                                                                id="user_cpf">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="mb-3 position-relative">
                                                                            <label for="user_password"
                                                                                class="form-label" style="font-family: Times, serif;
                                                                                font-weight: bold; color:#503f20;">Senha</label> <!--css 09-->
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
                                                                    <?php if ($message != null) { ?>
                                                                        <div class="mt-2">
                                                                            <div class="alert alert-danger" role="alert">
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
                                                                            <button type="submit"
                                                                                class="btn btn-primary" style="background-color:#503f20; 
                                                                                font-family: Times, serif; font-weight: bold; color:#e7d0a5;">
                                                                                Cadastrar</button> <!--css 10-->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>

                                                        <fieldset id="empresa" class="d-none tab-content">

                                                            <div class="card-body">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="mb-3">
                                                                            <label for="user_fullname"
                                                                                class="form-label" style="font-family: Times, serif;
                                                                                font-weight: bold; color:#503f20;">Nome fantasia</label> <!--css 11-->
                                                                            <input required type="text" class="form-control"
                                                                                name="user_fullname"
                                                                                placeholder="Digite seu nome completo"
                                                                                id="user_fullname">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="mb-3">
                                                                            <label for="user_cpf"
                                                                                class="form-label" style="font-family: Times, serif;
                                                                                font-weight: bold; color:#503f20;">CNPJ</label> <!--css 12-->
                                                                            <input required type="text" class="form-control"
                                                                                name="user_cpf" placeholder="Digite o CNPJ"
                                                                                id="user_cpf">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="mb-3 position-relative">
                                                                            <label for="user_password"
                                                                                class="form-label" style="font-family: Times, serif;
                                                                                font-weight: bold; color:#503f20;">Senha</label> <!--css 13-->
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
                                                                    <?php if ($message != null) { ?>
                                                                        <div class="mt-2">
                                                                            <div class="alert alert-danger" role="alert">
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
                                                                            <button type="submit"
                                                                                class="btn btn-primary" style="background-color:#503f20; 
                                                                                font-family: Times, serif; font-weight: bold; color:#e7d0a5;">
                                                                                Cadastrar</button> <!--css 14-->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </fieldset>
                                                        <a href="login" class="text-decoration-none">
                                                            <div class="card-footer text-center text-muted"
                                                                style="background-color: #d6bb87;"> <!--css 15-->
                                                                <h6  style="font-family: Times, serif; 
                                                                    font-weight: bold; color:#776035;">Já possuí uma conta?
                                                                </h6> <!--css 16-->
                                                                <p
                                                                    style="text-decoration: underline;font-size:14px;
                                                                    margin:4px 0px 0px 0px; font-weight: bold; color:#36280e;">
                                                                    Entrar</p> <!--css 17-->
                                                            </div>
                                                        </a>
                                                        <script>
                                                            $(document).ready(function () {
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
                                                                });
                                                            });
                                                        </script>

                                                    </form>
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