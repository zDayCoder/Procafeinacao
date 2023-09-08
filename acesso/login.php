<?php
$message = null;
session_start();
require_once '../sqls/login_page_sql.php';
if (session_status() === PHP_SESSION_ACTIVE) {
    //session_destroy();
    if (!empty($_SESSION)) {
        if (isset($_SESSION['Ucpf']) && isset($_SESSION['Upassword'])) {
            $user_cpf = $_SESSION['Ucpf'];
            $user_password = $_SESSION['Upassword'];
            $page = "../pages/menu/menu-itens";
            $user = array(
                'user_cpf' => $user_cpf,
                'user_password' => $user_password,
            );
            $message = login($user, $page);
            //$message = login($user_cpf, $user_password, $page);
        }
    } else {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //nao entra nessa parte do codigo
            if (isset($_POST['user_cpf']) && isset($_POST['user_password'])) {
                $user_cpf = $_POST['user_cpf'];
                $user_password = $_POST['user_password'];
                if (empty(trim($user_cpf)) || empty(trim($user_password))) {
                    $message = "Por favor, preencha todos os campos obrigatórios.";
                } else {
                    $page = "../pages/menu/menu-itens";
                    $user = array(
                        'user_cpf' => $user_cpf,
                        'user_password' => hash("sha512", $user_password),
                    );
                    $message = login($user, $page);
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
        <title>Entrar</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>

    <body>
        <div class="auth-page-wrapper pt-5">
            <div class="container">
                <div class="main-content">
                    <div class="page-content">
                        <div class="container-fluid">
                            <div class="row justify-content-center">
                                <div class="col">
                                    <div class="row justify-content-center">
                                        <div class="col-md-8 col-lg-6 col-xl-5">
                                            <div class="card mt-4">

                                                <div class="card ">
                                                    <form method="post"> <!--action="javascript:void(0);"-->
                                                        <fieldset class="d-block">
                                                            <div class="card-header text-center">
                                                                <h4 class="card-title mb-0 text-primary">Bem Vindo(a)</h4>
                                                                <p style="font-size:14px; margin:4px 0px 0px 0px">Faça seu
                                                                    login.</p>
                                                            </div>

                                                            <div class="card-body">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="mb-3">
                                                                            <label for="user_cpf"
                                                                                class="form-label">CPF</label>
                                                                            <input required type="text" class="form-control"
                                                                                name="user_cpf" placeholder="Digite seu CPF"
                                                                                id="user_cpf">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="mb-3 position-relative">
                                                                            <label for="user_password"
                                                                                class="form-label">Senha</label>
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
                                                                                class="btn btn-primary">Entrar</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <a href="register" class="text-decoration-none">
                                                                <div class="card-footer text-center text-muted"
                                                                    style="background-color: #affaaa;">
                                                                    <h6 class="card-title mb-0 text-primary">Novo por aqui?
                                                                    </h6>
                                                                    <p
                                                                        style="text-decoration: underline;font-size:14px;margin:4px 0px 0px 0px">
                                                                        Cadastre-se</p>
                                                                </div>
                                                            </a>
                                                        </fieldset>
                                                    </form>
                                                </div>

                                            </div>
                                            <a href="forgotpass" class="text-decoration-none">
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

            <?php include('../parts/rodape.php') ?>

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