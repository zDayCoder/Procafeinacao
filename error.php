<?php
$hiddenMessage = null;
$attb1 = null;
$attb2 = null;
$attb3 = null;
$cookieData = null;
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_COOKIE['hidden_message']) && $_COOKIE['hidden_message'] !== null) {

    // Para recuperar os atributos do cookie
    if (isset($_COOKIE['hidden_message'])) {
        $cookieData = $_COOKIE['hidden_message'];
        $atributos = json_decode($cookieData, true);

        // Agora você pode acessar os atributos individualmente
        $attb1 = $atributos['attb1'];
        $attb2 = $atributos['attb2'];
        $attb3 = $atributos['attb3'];
    }

} else {
    //header("Location: login.php");
    //exit;
}
?>

<!doctype html>
<html lang="pt-br" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>Ops, ocorreu um erro...</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require '../imports.php';?>
</head>

<body>

    <div class="auth-page-wrapper pt-5">

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <!-- end row -->

                <div class="row justify-content-center" style="">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="text-center mt-sm-5 mb-4 text-black-50">
                                            <div>
                                                <a href="index" class="d-inline-block auth-logo">
                                                    <img src="../assets/images/<?php echo $attb1; ?>" alt="" height="250">
                                                </a>
                                            </div>
                                            <p class="mt-3 fs-15 fw-bolder">Ops, ocorreu um
                                                erro...</p>
                                            <p class="mt-3 fs-15 fw-normal">
                                                <?php if ($cookieData !== null && $attb2 !== null && $attb2 !== 0) {echo $attb2;}?>
                                            </p>

                                            <p class="mt-3 fs-15 fw-normal">
                                                <?php if ($cookieData !== null && $attb3 !== null) {echo $attb3;}?>
                                            </p>

                                            <a class="btn btn-info" href="login.php">Sair</a>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                <script>
                                document.write(new Date().getFullYear())
                                </script> Duos Leni. Criado por <span class="text-danger"> Lenitech </span><i class="mdi bi-heart text-danger mb-3"></i> 
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->
</body>

</html>