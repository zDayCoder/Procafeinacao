<?php
$message = null;
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/TCC/Procafeinacao/database/sqls/UAC/login_uac_sql.php');
if (session_status() === PHP_SESSION_ACTIVE) {
    if (!empty($_SESSION) && isset($_SESSION['Ucpfcnpj'])) {
        if (isset($_SESSION['Ucpfcnpj']) && isset($_SESSION['Upassword'])) {
            $user_cpfcnpj = $_SESSION['Ucpfcnpj'];
            $user_password = $_SESSION['Upassword'];
            $base_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            $page = $base_url . "/TCC/Procafeinacao/pages/menu/menu-itens";

            if (strlen($user_cpfcnpj) === 11) {
                $user = array(
                    'user_type' => 'C',
                    'client_cpf' => $user_cpfcnpj,
                    'user_password' => $user_password,
                );
            } else if (strlen($user_cpfcnpj) === 14) {
                $user = array(
                    'user_type' => 'B',
                    'business_cnpj' => $user_cpfcnpj,
                    'user_password' => $user_password,
                );
            }
            $message = login($user, $page);
            exit;
        } else {
            //header("Location: senhaincorreta");
        }
    } else {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //nao entra nessa parte do codigo
            if (isset($_POST['user_cpfcnpj']) && isset($_POST['user_password'])) {
                $user_cpfcnpj = $_POST['user_cpfcnpj'];
                $user_password = $_POST['user_password'];
                if (empty(trim($user_cpfcnpj)) || empty(trim($user_password))) {
                    $message = "Por favor, preencha todos os campos obrigatórios.";
                } else if (strlen($user_cpfcnpj) !== 11 && strlen($user_cpfcnpj) !== 14) {
                    $message = "Por favor, verifique o campo CPF/CNPJ e tente novamente.";
                } else {
                    $base_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
                    $page = $base_url . "/TCC/Procafeinacao/pages/menu/menu-itens";

                    if (strlen($user_cpfcnpj) === 11) {
                        $user = array(
                            'user_type' => 'C',
                            'client_cpf' => $user_cpfcnpj,
                            'user_password' => hash("sha512", $user_password),
                        );
                    } else if (strlen($user_cpfcnpj) === 14) {
                        $user = array(
                            'user_type' => 'B',
                            'business_cnpj' => $user_cpfcnpj,
                            'user_password' => hash("sha512", $user_password),
                        );
                    }

                    $message = login($user, $page);
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
        <title>Entrar</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <style>
            @font-face {
                font-family: "generic";
                src: url('/TCC/Procafeinacao/assets/fonts/generic.ttf') format('truetype');
                /* Inclua outros formatos de fonte, se necessário */
            }
        </style>
    </head>

    <body style="background: url(../pages/img/cardapio.jpg) no-repeat center fixed;
background-size: cover;"> <!--css 01-->
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
                                    0 6px 20px 0 rgba(0, 0, 0, 0.19);"> <!--css 02-->
                                            <div class="card mt-4">

                                                <div class="card " style="background-color: #FFFDFA; border-radius: 5px;">
                                                    <!--css 03-->
                                                    <form method="post"> <!--action="javascript:void(0);"-->
                                                        <fieldset class="d-block">
                                                            <div class="card-header text-center">
                                                                <h4
                                                                    style="font-family: 'generic'; font-weight: bold; color:#503f20;">
                                                                    Bem Vindo(a)</h4> <!--css 04-->
                                                                <p
                                                                    style="font-family: 'generic'; font-weight: bold; color:#83642c;">
                                                                    Faça seu
                                                                    login.</p> <!--css 05-->
                                                            </div>

                                                            <div class="card-body">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="mb-3">
                                                                            <label for="user_cpfcnpj" class="form-label"
                                                                                style="font-family: 'generic'; font-weight: bold; color:#503f20;">CPF/CNPJ</label>
                                                                            <!--css 06-->
                                                                            <input required type="text" class="form-control"
                                                                                name="user_cpfcnpj"
                                                                                placeholder="Digite o CPF ou CNPJ"
                                                                                id="user_cpfcnpj">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="mb-3 position-relative">
                                                                            <label for="user_password" class="form-label"
                                                                                style="font-family: 'generic'; font-weight: bold; 
                                                                            color:#503f20;">Senha</label> <!--css 07-->
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
                                                                            <div class="alert alert-danger" style="font-size:14px" role="alert">
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
                                                                                style="background-color:#503f20; font-family: 'generic'; 
                                                                            font-weight: bold; color:#e7d0a5;">Entrar</button>
                                                                            <!--css 08-->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <a href="register" class="text-decoration-none">
                                                                <div class="card-footer text-center text-muted"
                                                                    style="background-color: #d6bb87;"> <!--css 09-->
                                                                    <h6
                                                                        style="font-family: 'generic'; font-weight: bold; color:#776035;">
                                                                        Novo por aqui?
                                                                    </h6> <!--css 10-->
                                                                    <p
                                                                        style="font-family: 'generic';text-decoration: underline;font-size:14px;
                                                                        margin:4px 0px 0px 0px; font-weight: bold; color:#36280e;">
                                                                        Cadastre-se</p> <!--css 11-->
                                                                </div>
                                                            </a>
                                                        </fieldset>
                                                    </form>
                                                </div>

                                            </div>
                                            <a href="javascript:window.location.href=('https://pbs.twimg.com/media/F4YayJPWgAEkwoO?format=jpg&name=small')" class="text-decoration-none">
                                                <div class="text-center text-muted">
                                                    <p
                                                        style="text-decoration: underline;font-size:12px;margin:14px 0px 0px 0px; padding: 8px;">
                                                        Esqueceu sua senha?</p>
                                                </div>
                                            </a>
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