<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem Vindo - Procafeinação</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body style="background: url(/TCC/Procafeinacao/pages/img/cardapio.jpg) no-repeat center fixed;
background-size: cover; ">
    <style>
body {margin:0;}

ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #333;
  position: relative;
  top: 0;
  width: 100%;
}

li {
  float: left;
}

li a {
  font-family: Verdana;
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
}

li a:hover:not(.active) {
  background-color: #111;
}

.active {
  background-color: #04AA6D;
}
.active:hover{
  background-color: #000;
}

li a:hover:not(.active),.active,li a{
  transition: 0.2s;
  text-decoration: none !important;
  color: white !important;
}

</style>

<ul>
<li><a href="javascript:window.location.reload();">Início</a></li>
  <li><a href="/TCC/Procafeinacao/acesso/register">Cadastre-se</a></li>
  <li><a href="/TCC/Procafeinacao/acesso/login">Entrar</a></li>
  <li style="float:right"><a class="active" href="javascript:alert('Ainda nao temos nada a exibir');">Sobre</a></li>
</ul>
<br><br>


    <!-- <a href="https://preview.themeforest.net/item/koffe-cafe-coffee-shop-template-kit/full_screen_preview/26300402" target="_blank">Site referencia</a> -->


    <div class="container">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <h3>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor, ullam. Iure aliquid et non vero
                        nihil
                        deleniti delectus harum laborum dicta iste, dolores alias consequatur voluptate eum quasi sit!
                        Omnis.
                    </h3>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <h3>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor, ullam. Iure aliquid et non vero
                        nihil
                        deleniti delectus harum laborum dicta iste, dolores alias consequatur voluptate eum quasi sit!
                        Omnis.
                    </h3>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <h3>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor, ullam. Iure aliquid et non vero
                        nihil
                        deleniti delectus harum laborum dicta iste, dolores alias consequatur voluptate eum quasi sit!
                        Omnis.
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/TCC/Procafeinacao/parts/rodape.php'); ?>


</body>

</html>