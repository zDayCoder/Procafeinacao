<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar</title>
    <link href="./initialPage/css/purple.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" >
</head>

<body>


    <div class="page-wrap gradient-primary">
        <div class="container">
            <h1 class="logo"><a href="https://www.heroku.com" title="Heroku">Heroku</a></h1>
            <div class="content">
                <div class="panel" id="login">
                    <h2 class="h3">Faça login na sua conta</h2>
                    <form action="/login" method="post" role="form">
                        <input name="_csrf" type="hidden" value="bPrwL6_FysovRaNeU4xr36niA304RIu02FPzOdUL5hg">
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <div class="input-icon icon-username"></div>
                            <input autofocus="true" class="form-control" id="email" name="email"
                                placeholder="Email address" type="email">
                        </div>
                        <div class="form-group">
                            <label for="password">Senha</label>
                            <div class="input-icon icon-password"></div>
                            <input autocomplete="off" class="form-control password" id="password" name="password"
                                placeholder="Password" type="password">
                        </div>
                        <button class="btn btn-primary btn-lg btn-block" name="commit" type="submit" value="Log In">Log
                            In</button>
                    </form>
                    <a class="panel-footer" href="/signup/login">Novo por aqui? &nbsp;<span>Cadastre-se</span></a>
                </div>
                <a href="/account/password/reset" style="margin-left: 25px">Esqueceu sua senha?</a>
            </div>
        </div>
        <footer class="footer">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="text-center">
                                                <p class="mb-0 text-muted text-white">©
                                                    <script>
                                                        document.write(new Date().getFullYear())
                                                    </script> - Procafeinação. Criado por <i class="mdi mdi-heart text-danger"></i> Coda Fofo
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </footer>
    </div>


</body>

</html>