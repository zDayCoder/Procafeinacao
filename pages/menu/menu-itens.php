<?php
session_start();
require_once '../../database/connect.php';

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

$items = selectAllItems();

?>
<link href="../css/menu.css" rel="stylesheet" type="text/css">
<a href="../../logout">FAZER LOGOUT</a>
Nome:
<?php echo $_SESSION['Ufname']; ?>

<section
	class="elementor-section elementor-top-section elementor-element elementor-element-5b150b37 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
	data-id="5b150b37" data-element_type="section"
	data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
	<div class="elementor-container elementor-column-gap-default">
		<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-652b7cf4"
			data-id="652b7cf4" data-element_type="column">
			<div class="elementor-widget-wrap elementor-element-populated">
				<div class="elementor-element elementor-element-2cb091d6 elementor-widget elementor-widget-heading"
					data-id="2cb091d6" data-element_type="widget" data-widget_type="heading.default">
					<div class="elementor-widget-container">
						<h3 class="elementor-heading-title elementor-size-default">Nosso cardápio</h3>
					</div>
				</div>
				<div class="elementor-element elementor-element-793e8290 elementor-widget-divider--view-line_icon elementor-view-default elementor-widget-divider--element-align-center elementor-widget elementor-widget-divider"
					data-id="793e8290" data-element_type="widget" data-widget_type="divider.default">
					<div class="elementor-widget-container">
						<div class="elementor-divider">
							<span class="elementor-divider-separator">
								<div class="elementor-icon elementor-divider__element">
									<i aria-hidden="true" class="fas fa-cannabis"></i>
								</div>
							</span>
						</div>
					</div>
				</div>

				<?php
				if (!empty($items)) {
					// Loop para exibir os itens em divs
					foreach ($items as $item) { ?>
						<section
							class="elementor-section elementor-inner-section elementor-element elementor-element-703e0993 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
							data-id="703e0993" data-element_type="section">
							<div class="elementor-container elementor-column-gap-default">
								<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-194fb5bb"
									data-id="194fb5bb" data-element_type="column">
									<div class="elementor-widget-wrap elementor-element-populated">
										<div class="" data-id="7bf3fc74" data-element_type="widget"
											data-widget_type="heading.default">
											<div class="">
												<h4 class="elementor-heading-title elementor-size-default">
													<?php echo $item['product_name']; ?>
													..................................... $
													<?php echo $item['price']; ?>
												</h4>
											</div>
										</div>
										<div class="elementor-element elementor-element-5f709f46 elementor-widget elementor-widget-text-editor"
											data-id="5f709f46" data-element_type="widget"
											data-widget_type="text-editor.default">
											<div class="elementor-widget-container">
												<p>
													<?php echo $item['description']; ?>
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</section>
					<?php }
				} ?>



				<div class="elementor-element elementor-element-5a79fd9e elementor-widget elementor-widget-spacer"
					data-id="5a79fd9e" data-element_type="widget" data-widget_type="spacer.default">
					<div class="elementor-widget-container">
						<style>
							/*! elementor - v3.13.2 - 11-05-2023 */
							.elementor-column .elementor-spacer-inner {
								height: var(--spacer-size)
							}

							.e-con {
								--container-widget-width: 100%
							}

							.e-con-inner>.elementor-widget-spacer,
							.e-con>.elementor-widget-spacer {
								width: var(--container-widget-width, var(--spacer-size));
								--align-self: var(--container-widget-align-self, initial);
								--flex-shrink: 0
							}

							.e-con-inner>.elementor-widget-spacer>.elementor-widget-container,
							.e-con-inner>.elementor-widget-spacer>.elementor-widget-container>.elementor-spacer,
							.e-con>.elementor-widget-spacer>.elementor-widget-container,
							.e-con>.elementor-widget-spacer>.elementor-widget-container>.elementor-spacer {
								height: 100%
							}

							.e-con-inner>.elementor-widget-spacer>.elementor-widget-container>.elementor-spacer>.elementor-spacer-inner,
							.e-con>.elementor-widget-spacer>.elementor-widget-container>.elementor-spacer>.elementor-spacer-inner {
								height: var(--container-widget-height, var(--spacer-size))
							}
						</style>
						<div class="elementor-spacer">
							<div class="elementor-spacer-inner"></div>
						</div>
					</div>
				</div>
				<section
					class="elementor-section elementor-inner-section elementor-element elementor-element-6044b860 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
					data-id="6044b860" data-element_type="section">
					<div class="elementor-container elementor-column-gap-default">
						<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-23c0b20a"
							data-id="23c0b20a" data-element_type="column">
							<div class="elementor-widget-wrap elementor-element-populated">
								<div class="elementor-element elementor-element-47b6befa elementor-widget elementor-widget-heading animated slideInUp"
									data-id="47b6befa" data-element_type="widget"
									data-settings="{&quot;_animation&quot;:&quot;slideInUp&quot;}"
									data-widget_type="heading.default">
									<div class="elementor-widget-container">
										<h4 class="elementor-heading-title elementor-size-default">Peça seu café agora
										</h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</section>