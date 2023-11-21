<?php
$message = null;
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/TCC/Procafeinacao/database/sqls/UAC/login_uac_sql.php');

if (session_status() === PHP_SESSION_ACTIVE) {
    echo $_SESSION;
    if (!empty($_SESSION)) {
        
        if (isset($_SESSION['Ucpfcnpj']) && isset($_SESSION['Upassword'])) {                

            if (isset($_SESSION['UID'])) {
                $user_id = $_SESSION['UID'];
                $find = findUserByID($user_id);
                if (!empty($find['address_id']) || $find['address_id'] !== null) {
                    $base_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
                    $page = $base_url . "/TCC/Procafeinacao/acesso/login";
                    header("Location: " . $page);
                }

            }
        }
    } else { ////////continuar aqui
        if (empty($_SESSION)) {
            gotoPage('/TCC/Procafeinacao/acesso/login');
        }

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



<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/TCC/Procafeinacao/database/connect.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/TCC/Procafeinacao/database/sqls/UAC/login_uac_sql.php');
if (session_status() === PHP_SESSION_ACTIVE) {
    if (isset($_SESSION['UID'])) {
        $user_id = $_SESSION['UID'];
        $user = findUserByID($user_id);
        if ($user) {
            if (empty($user['address_id']) || $user['address_id'] === null) {
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
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
                    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
                    <link rel="icon" type="imagem/png" href="../assets/img/cafe.png" />
                    <link rel="stylesheet" href="/TCC/Procafeinacao/assets/css/style.css">
                    <link rel="stylesheet" href="../assets/css/header.css">
                    <link rel="stylesheet" href="../assets/css/footer.css">
                    <style>
                        @font-face {
                            font-family: "generic";
                            src: url('/TCC/Procafeinacao/assets/fonts/generic.ttf') format('truetype');
                            /* Inclua outros formatos de fonte, se necessário */
                        }
                    </style>
                </head>

                <body style="background-color: #f5efe0;">

                <!--Menu-->
                <header class="header">
                <nav class="nav">
                    <a href="/TCC/Procafeinacao/" class="logo"><img src="../assets/img/cafe.png"> </a>
                    <button class="hamburger"></button>
                        <ul  class="nav-list">
                            <li><a style="color: #111;" href="/TCC/Procafeinacao/">App</a></li>
                            <li><a style="color: #111;" href="/TCC/Procafeinacao/acesso/register">Cadastro</a></li>
                            <li><a style="color: #111;" href="/TCC/Procafeinacao/acesso/login">Login</a></li>
                        </ul>
                    </nav>
                </header>
                <!--Fim Menu-->
                    <div class="auth-page-wrapper pt-5 pb-5">
                        <div class="container">
                            <div class="main-content">
                                <div class="page-content">
                                    <div class="container-fluid">
                                        <div class="row justify-content-center">
                                            <div class="col">
                                                <div class="row justify-content-center">
                                                    <div class="col-md-12 col-lg-8 col-xl-6" style="background-color: #FBF5E6; 
                                        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 
                                        0 6px 20px 0 rgba(0, 0, 0, 0.19);padding-bottom: 30px;">
                                                        <div class="card mt-4">

                                                            <div class="card " style="background-color: #FFFDFA; border-radius: 5px;">
                                                                <form method="post" id="form">
                                                                    <fieldset class="d-block">
                                                                        <div class="card-header">
                                                                            <div class="text-center">
                                                                                <h4
                                                                                    style="font-family: 'generic'; font-weight: bold; color:#503f20;">
                                                                                    Conclua seu cadastro</h4>
                                                                                <p
                                                                                    style="font-family: 'generic'; font-weight: bold; color:#83642c;">
                                                                                    Complete as informações abaixo para finalizar seu
                                                                                    cadastro.
                                                                                </p>
                                                                            </div>
                                                                            <!-- <p style="font-size:12px; margin:4px 0px 0px 0px">Os campos
                                                                marcados
                                                                com (<span style="color:#ff000090">*</span>) são
                                                                obrigatórios
                                                            </p> -->
                                                                        </div>
                                                                        <div class="card-body">

                                                                            <div class="row">
                                                                                <div class="col-4">
                                                                                    <div class="mb-3">
                                                                                        <label for="user_adds_cep" class="form-label"
                                                                                            style="font-family: 'generic';
                                                                        font-weight: bold; color:#503f20;">CEP</label>
                                                                                        <input required type="text" class="form-control"
                                                                                            name="user_adds_cep"
                                                                                            placeholder="Digite seu CEP"
                                                                                            id="user_adds_cep">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-5">
                                                                                    <div class="mb-3">
                                                                                        <label for="user_adds_complemento"
                                                                                            class="form-label"
                                                                                            style="font-family: 'generic';
                                                                                font-weight: bold; color:#503f20;">Complemento</label>
                                                                                        <input required type="text" class="form-control"
                                                                                            name="user_adds_complemento"
                                                                                            placeholder="Digite o complemento"
                                                                                            id="user_adds_complemento">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-3">
                                                                                    <div class="mb-3">
                                                                                        <label for="user_adds_number" class="form-label"
                                                                                            style="font-family: 'generic';
                                                                                font-weight: bold; color:#503f20;">Número</label>
                                                                                        <input required type="number"
                                                                                            class="form-control" name="user_adds_number"
                                                                                            placeholder="Digite o número"
                                                                                            id="user_adds_number">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <div class="mb-3">
                                                                                        <label for="user_adds_logradouro"
                                                                                            class="form-label"
                                                                                            style="font-family: 'generic';
                                                                                font-weight: bold; color:#503f20;">Logradouro</label>
                                                                                        <input required type="text" class="form-control"
                                                                                            name="user_adds_logradouro"
                                                                                            placeholder="Digite o logradouro"
                                                                                            id="user_adds_logradouro">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <div class="mb-3">
                                                                                        <label for="user_adds_bairro" class="form-label"
                                                                                            style="font-family: 'generic';
                                                                                font-weight: bold; color:#503f20;">Bairro</label>
                                                                                        <input required type="text" class="form-control"
                                                                                            name="user_adds_bairro"
                                                                                            placeholder="Digite o bairro"
                                                                                            id="user_adds_bairro">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <div class="mb-3">
                                                                                        <label for="user_adds_localidade"
                                                                                            class="form-label"
                                                                                            style="font-family: 'generic';
                                                                                font-weight: bold; color:#503f20;">Localidade</label>
                                                                                        <input required type="text" class="form-control"
                                                                                            name="user_adds_localidade"
                                                                                            placeholder="Digite a localidade"
                                                                                            id="user_adds_localidade">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <div class="mb-3">
                                                                                        <label for="user_adds_estado" class="form-label"
                                                                                            style="font-family: 'generic';
                                                                                font-weight: bold; color:#503f20;">Estado</label>
                                                                                        <input required type="text" class="form-control"
                                                                                            name="user_adds_estado"
                                                                                            placeholder="Digite o estado"
                                                                                            id="user_adds_estado">
                                                                                    </div>
                                                                                </div>

                                                                                <script>
                                                                                    $(document).ready(function () {
                                                                                        $("#user_adds_cep").on("input", function () {
                                                                                            var cepcap = $(this).val();
                                                                                            var cep = cepcap.replace("-",
                                                                                                "");
                                                                                            if (cep.length === 8 && /^\d+$/
                                                                                                .test(cep)) {
                                                                                                var url =
                                                                                                    "https://viacep.com.br/ws/" +
                                                                                                    cep + "/json/";

                                                                                                $.getJSON(url, function (
                                                                                                    data) {
                                                                                                    if (!("erro" in
                                                                                                        data)) {
                                                                                                        if (data
                                                                                                            .complemento !==
                                                                                                            "") {
                                                                                                            $("#user_adds_complemento")
                                                                                                                .val(
                                                                                                                    data
                                                                                                                        .complemento
                                                                                                                );
                                                                                                        }
                                                                                                        $("#user_adds_logradouro")
                                                                                                            .val(
                                                                                                                data
                                                                                                                    .logradouro
                                                                                                            );
                                                                                                        $("#user_adds_bairro")
                                                                                                            .val(
                                                                                                                data
                                                                                                                    .bairro
                                                                                                            );
                                                                                                        $("#user_adds_localidade")
                                                                                                            .val(
                                                                                                                data
                                                                                                                    .localidade
                                                                                                            );
                                                                                                        $("#user_adds_estado")
                                                                                                            .val(
                                                                                                                data
                                                                                                                    .uf
                                                                                                            );
                                                                                                    } else {
                                                                                                        alert(
                                                                                                            "CEP não encontrado");
                                                                                                    }
                                                                                                });
                                                                                            } else {
                                                                                                $("#user_adds_logradouro," +
                                                                                                    "#user_adds_bairro,#user_adds_localidade,#user_adds_estado"
                                                                                                ).val("");
                                                                                            }
                                                                                        });
                                                                                    });
                                                                                </script>


                                                                                <!-- <-?php if ($message != null && $user_type === 'C') { ?>
                                                                                    <div class="mt-2">
                                                                                        <div class="alert alert-danger"
                                                                                            style="font-size:14px" role="alert">
                                                                                            <-?php $messageLines = explode("\n", $message);
                                                                                            foreach ($messageLines as $line) {
                                                                                                echo $line . "<br>";
                                                                                            }
                                                                                            ?>
                                                                                        </div>
                                                                                    </div>
                                                                                <-?php } ?> -->

                                                                                <div class="col-lg-12">
                                                                                    <div class="text-right">
                                                                                        <?php if ($user['user_type'] === 'C'): ?>
                                                                                            <button type="submit" class="btn btn-primary"
                                                                                                style="background-color:#503f20; 
                                                                                font-family: 'generic'; font-weight: bold; color:#e7d0a5;">
                                                                                                Cadastrar</button>
                                                                                        <?php else: ?>
                                                                                            <button type="button"
                                                                                                class="btn btn-primary next-form"
                                                                                                style="background-color:#503f20; 
                                                                                font-family: 'generic'; font-weight: bold; color:#e7d0a5;">
                                                                                                Próximo</button>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </fieldset>


                                                                        <fieldset class="d-block">
                                                                            <div class="card-header">
                                                                                <div class="text-center">
                                                                                    <h4
                                                                                        style="font-family: 'generic'; font-weight: bold; color:#503f20;">
                                                                                        Defina seus horários</h4>
                                                                                    <p
                                                                                        style="font-family: 'generic'; font-weight: bold; color:#83642c;">
                                                                                        Informe seus horários de trabalho.
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="card-body">

                                                                                <div class="row">
                                                                                    <!-- Arrumando -->

                                                                                    <div class="mb-3">
                                                                                        <label for="daysOfWeek" class="form-label" 
                                                                                            style="font-family: 'generic'; font-weight: 
                                                                                            bold; color:#503f20;">Dias
                                                                                            da semana</label>
                                                                                        <div class="btn-group" data-toggle="buttons">
                                                                                            <label class="btn btn-secondary" style="font-size: 12px;
                                                                                            background-color: #503f20;">
                                                                                                <input type="checkbox" name="daysOfWeek[]"
                                                                                                    value="Segunda-feira"> Segunda-feira
                                                                                            </label>
                                                                                            <label class="btn btn-secondary" style="font-size: 12px;
                                                                                            background-color: #503f20;">
                                                                                                <input type="checkbox" name="daysOfWeek[]"
                                                                                                    value="Terça-feira"> Terça-feira
                                                                                            </label>
                                                                                            <label class="btn btn-secondary" style="font-size: 12px;
                                                                                            background-color: #503f20;">
                                                                                                <input type="checkbox" name="daysOfWeek[]"
                                                                                                    value="Quarta-feira"> Quarta-feira
                                                                                            </label>
                                                                                            <label class="btn btn-secondary" style="font-size: 12px;
                                                                                            background-color: #503f20;">
                                                                                                <input type="checkbox" name="daysOfWeek[]"
                                                                                                    value="Quinta-feira"> Quinta-feira
                                                                                            </label>
                                                                                            <label class="btn btn-secondary" style="font-size: 12px;
                                                                                            background-color: #503f20;">
                                                                                                <input type="checkbox" name="daysOfWeek[]"
                                                                                                    value="Sexta-feira"> Sexta-feira
                                                                                            </label>
                                                                                            <label class="btn btn-secondary" style="font-size: 12px;
                                                                                            background-color: #503f20;">
                                                                                                <input type="checkbox" name="daysOfWeek[]"
                                                                                                    value="Sábado"> Sábado
                                                                                            </label>
                                                                                            <label class="btn btn-secondary" style="font-size: 12px;
                                                                                            background-color: #503f20;">
                                                                                                <input type="checkbox" name="daysOfWeek[]"
                                                                                                    value="Domingo"> Domingo
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label for="openingTimes" class="form-label"
                                                                                            style="font-family: 'generic'; font-weight: bold; color:#503f20; margin: 2px;">Horários
                                                                                            de abertura</label>
                                                                                        <input type="text" class="form-control" 
                                                                                            name="openingTimes[]">
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label for="closingTimes" class="form-label"
                                                                                            style="font-family: 'generic'; font-weight: bold; 
                                                                                            color:#503f20; margin: 2px; margin-left: 8px;">Horários
                                                                                            de fechamento</label>
                                                                                        <input type="text" class="form-control" style="margin-left: 5px;"
                                                                                            name="closingTimes[]">
                                                                                    </div>

                                                                                    <!-- Arrumando -->

                                                                                    <div class="col-lg-12">
                                                                                        <div class="text-right">
                                                                                            <button type="submit" class="btn btn-primary"
                                                                                                style="background-color:#503f20;font-family: 'generic'; font-weight: bold; color:#e7d0a5;">
                                                                                                Cadastrar</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </fieldset>

                                                                    <?php endif; ?>

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

                        <!--?php require_once($_SERVER['DOCUMENT_ROOT'] . '/TCC/Procafeinacao/parts/rodape.php'); ?-->

                    </div>

                </body>

                </html>
            <?php } else {
                header("Location: /TCC/Procafeinacao/acesso/login");
            }
        } else {
            header("Location: /TCC/Procafeinacao/acesso/login");
        }
    } else {
        header("Location: /TCC/Procafeinacao/acesso/login");
    }
} else {
    header("Location: /TCC/Procafeinacao/acesso/login");
} ?>