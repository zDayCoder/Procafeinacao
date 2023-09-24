<?php
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'] . '/TCC/Procafeinacao/database/connect.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/TCC/Procafeinacao/database/sqls/login_page_sql.php');
//$_SESSION['Uname'] = findUserByCPF('56044447820')['user_name'];
// Inserir a categoria "bebida"
//createCategory("bebida");

// Inserir a categoria "comida"
//createCategory("comida");

// Produto 1
$productDetails1 = array(
	'product_name' => 'Cappuccino',
	'description' => 'Um cappuccino cremoso com uma pitada de cacau.',
	'price' => 3.49,
	'item_available' => 1,
	'category_id' => 1
);

// Produto 2
$productDetails2 = array(
	'product_name' => 'Sanduíche de Frango',
	'description' => 'Um sanduíche de frango grelhado com alface e tomate.',
	'price' => 4.99,
	'item_available' => 1,
	'category_id' => 2
);

// Produto 3
$productDetails3 = array(
	'product_name' => 'Mocha',
	'description' => 'Um mocha com chocolate e café expresso.',
	'price' => 3.79,
	'item_available' => 1,
	'category_id' => 1
);

// Produto 4
$productDetails4 = array(
	'product_name' => 'Pizza Margherita',
	'description' => 'Uma pizza margherita com molho de tomate, mozzarella e manjericão.',
	'price' => 8.99,
	'item_available' => 1,
	'category_id' => 2
);

// Produto 5
$productDetails5 = array(
	'product_name' => 'Chá de Camomila',
	'description' => 'Um chá de camomila calmante.',
	'price' => 2.49,
	'item_available' => 1,
	'category_id' => 1
);

// Chamando a função createItem para inserir os produtos
/*createItem($productDetails1);
createItem($productDetails2);
createItem($productDetails3);
createItem($productDetails4);
createItem($productDetails5);*/

$items = array($productDetails1,$productDetails2, $productDetails5);//selectAllItems();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cardápio</title>
	<!-- Adicione o link para o Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js""></script>
	<link href=" ../css/style.css" rel="stylesheet" type="text/css" />
	<link href="../css/menu.css" rel="stylesheet" type="text/css">
</head>

<body>

	<nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light" style="background-color: #111!important;">
		<div class="container-fluid">
			<a class="navbar-brand text-white" href="#">Procafeinação</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
				aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarText">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item">
						<a class="nav-link active text-white" aria-current="page" href="#">Home</a>
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
						<a class="nav-link text-white" href="../../logout">Sair</a>
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

	<?php for ($i = 0; $i < 20; $i++) { ?>
		<div class="container" style="margin-top: 5em;">
			<div class="row">
				<div class="col-md-6">
					<h3 class="mb-4">Bebidas</h3>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Nome</th>
								<th>Preço</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($items as $item): ?>
								<?php if ($item['category_id'] == 1): ?>
									<tr>
										<td>
											<h4 class="mb-0">
												<?php echo $item['product_name']; ?>
											</h4>
										</td>
										<td>
											<h4 class="mb-0">R$
												<?php echo str_replace(".", ",", $item['price']); ?>
											</h4>
										</td>
									</tr>
								<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="col-md-6">
					<h3 class="mb-4">Comidas</h3>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Nome</th>
								<th>Preço</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($items as $item): ?>
								<?php if ($item['category_id'] == 2): ?>
									<tr>
										<td>
											<h4 class="mb-0">
												<?php echo $item['product_name']; ?>
											</h4>
										</td>
										<td>
											<h4 class="mb-0">R$
												<?php echo str_replace(".", ",", $item['price']); ?>
											</h4>
										</td>
									</tr>
								<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php } ?>
</body>

</html>