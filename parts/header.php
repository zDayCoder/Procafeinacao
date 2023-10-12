<?php 
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/TCC/Procafeinacao/database/connect.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/TCC/Procafeinacao/database/sqls/UAC/login_uac_sql.php');
?>
<style>
            @font-face {
                font-family: "generic";
                src: url('/TCC/Procafeinacao/assets/fonts/generic.ttf') format('truetype');
                /* Inclua outros formatos de fonte, se necessário */
            }
        </style>
<nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light" style="background-color: #111!important;">
					<div class="container-fluid">
						<a class="navbar-brand text-white" style="font-family:'generic'" href="javascript:window.location.reload();">Procafeinação</a>
						<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
							aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
						</button>
						<div class="collapse navbar-collapse" id="navbarText">
							<ul class="navbar-nav me-auto mb-2 mb-lg-0">
								<li class="nav-item">
									<a class="nav-link active text-white" aria-current="page" href="/TCC/Procafeinacao/acesso/login">Início</a>
								</li>
								<li class="nav-item">
									<a class="nav-link text-white" href="#">Features</a>
								</li>
								<li class="nav-item">
									<a class="nav-link text-white" href="#">Pricing</a>
								</li>
							</ul>
							<ul class="navbar-nav ml-auto">
								<li class="nav-item">
									<span class="nav-link text-white">Olá,
										<?php echo ucfirst($_SESSION['Uname']); ?>
									</span>
								</li>
								<li class="nav-item">
									<a class="nav-link text-white" href="/TCC/Procafeinacao/utils/logout">Sair</a>
								</li>
								<!--li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						Opções
					</a>
					<ul class="dropdown-menu dropdown-menu-right">
						<li><a class="dropdown-item" href="../../logout">Sair</a></li>
					</ul>
					</li-->
							</ul>
						</div>
					</div>
					<hr />
				</nav>