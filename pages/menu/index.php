<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/TCC/Procafeinacao/database/connect.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/TCC/Procafeinacao/database/sqls/UAC/login_uac_sql.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/TCC/Procafeinacao/controller/item.php');

if (session_status() === PHP_SESSION_ACTIVE) {
    if (isset($_SESSION['UID'])) {
        $user_id = $_SESSION['UID'];
        $user = findUserByID($user_id);
        if ($user) {
            if (!empty($user['address_id']) || $user['address_id'] !== null) {
                //if ($user['is_blocked'] === 0 && $user['acesso'] !== "CLIENTE") {

                $items = selectAllItems($user_id);
                ?>

                <!DOCTYPE html>
                <html lang="pt-br">

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Início - Procafeinação</title>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                    <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>
                    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


                    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
                    <link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap" rel="stylesheet">
                </head>

                <style>
                    .photo {
                        clip-path: polygon(20% 0%, 80% 0%, 100% 20%, 100% 80%, 80% 100%, 20% 100%, 0% 80%, 0% 20%);
                        position: absolute;
                        margin-top: -330px;
                        width: 100px;
                        height: 100px;
                        object-fit: cover;
                        object-position: center;
                        background: #03A9F4;
                    }


                    .scroll-images::-webkit-scrollbar {
                        width: 5px;
                        height: 8px;
                        background-color: #aaa;
                    }

                    .scroll-images::-webkit-scrollbar-thumb {
                        background-color: black;
                    }







                    #test-list {
                        max-width: 800px;
                        /* Ajuste conforme necessário */
                        margin: 0 auto;
                    }


                    th {
                        border: none !important;
                    }

                    tbody>tr {
                        border: 1px solid #ddd;
                        transition: transform 0.3s;
                        cursor: pointer;
                    }

                    td {
                        vertical-align: middle !important;
                    }

                    tbody>tr:hover {
                        background-color: burlywood;
                    }

                    .name {
                        font-size: 1.2em;
                        font-weight: bold;
                    }

                    .descricao,
                    .preco,
                    .categoria {
                        margin: 5px 0;
                    }


                    .roboto {
                        font-family: 'Roboto';
                    }

                    .coffee-font {
                        font-size: 150%;
                        font-family: 'Kaushan Script';
                    }
                </style>

                <body>
                    <?php require($_SERVER['DOCUMENT_ROOT'] . '/TCC/Procafeinacao/parts/header.php'); ?>

                    <div class="container" style="padding-top:60px">


                        <div id="test-list">
                            <div class="row g-4 mb-3">
                                <div class="col-sm">
                                    <div class="d-flex justify-content-sm-end">

                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItemModal">
                                            Adicionar Item
                                        </button>
                                        <div class="search-box ms-2 ml-2">
                                            <input type="text" class="form-control search" placeholder="Pesquisar...">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive table-card mt-3 mb-1">
                                <table class="table align-middle table-nowrap table-sm" id="customerTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nome</th>
                                            <th scope="col">Descrição</th>
                                            <th scope="col">Valor</th>
                                            <th scope="col">Categoria</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        <?php if (!empty($items)): ?>
                                            <?php foreach ($items as $item): ?>
                                                <tr class="table-waiting table-wa">
                                                    <td class="nome coffee-font">
                                                        <?= $item['item_name']; ?>
                                                    </td>
                                                    <td class="descricao roboto">
                                                        <?= $item['item_description']; ?>
                                                    </td>
                                                    <td class="valor roboto">
                                                        <?= $item['item_price']; ?>
                                                    </td>
                                                    <td class="categoria roboto">
                                                        <?= $item['category_name']; ?>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-info btn-edit" data-item-id="<?= $item['item_id']; ?>"
                                                            data-toggle="modal" data-target="#editItemModal">Editar</button>
                                                        <button class="btn btn-danger btn-delete" data-item-id="<?= $item['item_id']; ?>"
                                                            data-toggle="modal" data-target="#confirmDeleteModal">Deletar</button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3">Nenhum item encontrado.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>


                                <div class="noresult" style="display: none">
                                    <div class="text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                            colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">Desculpe! Nenhum resultado encontrado</h5>
                                        <p class="text-muted mb-0">Foram encontrados 0 resultados para sua
                                            pesquisa.</p>
                                    </div>
                                </div>

                            </div>

                            <div class="d-flex justify-content-end">
                                <ul class="pagination listjs-pagination mb-0">
                                    <li class="active"><a class="page" href="#" data-i="1" data-page="8">1</a></li>
                                    <li><a class="page" href="#" data-i="2" data-page="8">2</a></li>
                                </ul>
                            </div>



                        </div>
                        <script>
                            var itemList = new List('test-list', {
                                valueNames: ['nome', 'descricao', 'preco', 'categoria'],
                                page: 6,
                                pagination: true
                            });
                        </script>


                        <!-- Modal -->
                        <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addItemModalLabel">Adicionar Item</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="addItemForm">
                                            <!-- <div class="form-group">
                                                <label for="foto">Foto:</label>
                                                <input type="file" class="form-control-file" id="foto" name="foto">
                                            </div> -->
                                            <input type="hidden" class="form-control" name="form_type" value="create">
                                            <div class="form-group">
                                                <label for="titulo">Nome:</label>
                                                <input type="text" class="form-control" id="titulo" name="item_nome" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="descricao">Descrição:</label>
                                                <textarea class="form-control" id="descricao" name="item_descricao" rows="3"
                                                    required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="preco">Preço:</label>
                                                <input type="number" class="form-control" id="preco" name="item_preco" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="categoria">Categoria:</label>
                                                <select class="form-control" id="categoria" name="item_categoria" required>
                                                    <option value="comida">Comida</option>
                                                    <option value="bebida">Bebida</option>
                                                </select>
                                            </div>

                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button> -->
                                        <button type="button" class="btn btn-primary addItem">Adicionar Item</button>
                                    </div>
                                    <script>
                                        $(document).ready(function () {
                                            $('.addItem').click(function () {
                                                // Serializar os dados do formulário
                                                var formData = $('#addItemForm').serialize();

                                                // Enviar dados do formulário via AJAX
                                                $.ajax({
                                                    type: 'POST',
                                                    url: '/TCC/Procafeinacao/controller/item',  // Substitua com a URL correta
                                                    data: formData,
                                                    success: function (response) {
                                                        // Lógica a ser executada após o sucesso do envio

                                                        // Fechar o modal
                                                        $('#addItemModal').modal('hide');
                                                        location.reload();
                                                    },
                                                    error: function (error) {
                                                        // Lógica a ser executada em caso de erro
                                                        console.error('Erro:', error);
                                                    }
                                                });
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>


                        <!-- Modal de Edição -->
                        <div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editItemModalLabel">Editar Item</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editItemForm">
                                            <input type="hidden" class="form-control" name="form_type" value="saveEdit">
                                            <input type="hidden" class="form-control" name="itemId" id="item-idedt">
                                            <div class="form-group">
                                                <label for="titulo">Nome:</label>
                                                <input type="text" class="form-control" id="tituloedt" name="item_nome" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="descricao">Descrição:</label>
                                                <textarea class="form-control" id="descricaoedt" name="item_descricao" rows="3"
                                                    required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="preco">Preço:</label>
                                                <input type="numeric" class="form-control" id="precoedt" name="item_preco" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="categoria">Categoria:</label>
                                                <select class="form-control" id="categoriaedt" name="item_categoria" required>
                                                    <option value="comida">Comida</option>
                                                    <option value="bebida">Bebida</option>
                                                </select>
                                            </div>

                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button> -->
                                        <button type="button" class="btn btn-primary editItem">Salvar Edição</button>
                                    </div>
                                    <script>
                                        $(document).ready(function () {
                                            $('.editItem').click(function () {
                                                // Serializar os dados do formulário
                                                var formData = $('#editItemForm').serialize();

                                                // Enviar dados do formulário via AJAX
                                                $.ajax({
                                                    type: 'POST',
                                                    url: '/TCC/Procafeinacao/controller/item',  // Substitua com a URL correta
                                                    data: formData,
                                                    success: function (response) {
                                                        // Lógica a ser executada após o sucesso do envio

                                                        // Fechar o modal
                                                        $('#editItemModal').modal('hide');
                                                        location.reload();
                                                    },
                                                    error: function (error) {
                                                        // Lógica a ser executada em caso de erro
                                                        console.error('Erro:', error);
                                                    }
                                                });
                                            });
                                        });




                                    </script>
                                </div>
                            </div>
                        </div>

                        <!-- Modal de Confirmação de Exclusão -->
                        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
                            aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Tem certeza de que deseja excluir este item?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Confirmar</button>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <script>
                            $(document).ready(function () {
                                // Manipula o clique no botão de editar
                                $('.btn-edit').click(function () {
                                    var itemId = $(this).data('item-id');

                                    // Fazer uma requisição AJAX para obter os dados do item
                                    $.ajax({
                                        type: 'POST',
                                        url: '/TCC/Procafeinacao/controller/item', // Substitua com a URL correta
                                        data: {
                                            itemId: itemId,
                                            form_type: 'edit'
                                        },
                                        success: function (data) {
                                            // Converter a string JSON para um objeto JavaScript
                                            var itemData = JSON.parse(data);

                                            // Preencher os campos do modal com os dados retornados
                                            $('#item-idedt').val(itemData.item_id);
                                            $('#tituloedt').val(itemData.item_name);
                                            $('#descricaoedt').val(itemData.item_description);
                                            $('#precoedt').val(itemData.item_price);
                                            $('#categoriaedt').val(itemData.category_name.toLowerCase());
                                        },

                                        error: function (error) {
                                            console.error('Erro ao obter dados do item:', error);
                                        }
                                    });
                                });

                                // Manipula o clique no botão de excluir
                                $('.btn-delete').click(function () {
                                    var itemId = $(this).data('item-id');
                                    $('#confirmDeleteModal').modal('show');

                                    // Configurar o botão de confirmação no modal de exclusão
                                    $('#confirmDeleteBtn').click(function () {
                                        // Fazer uma requisição AJAX para excluir o item
                                        $.ajax({
                                            type: 'POST',
                                            url: '/TCC/Procafeinacao/controller/item', // Substitua com a URL correta
                                            data: {
                                                itemId: itemId,
                                                form_type: 'delete'
                                            },
                                            success: function (response) {
                                                // Lógica a ser executada após a exclusão bem-sucedida

                                                // Fechar o modal de confirmação
                                                $('#confirmDeleteModal').modal('hide');

                                                // Atualizar a página ou fazer qualquer outra ação necessária
                                                location.reload();
                                            },
                                            error: function (error) {
                                                console.error('Erro ao excluir o item:', error);
                                            }
                                        });
                                    });
                                });
                            });
                        </script>


                    </div>



                </body>

                </html>



            <?php } else {
                session_destroy();
                header("Location: /TCC/Procafeinacao/acesso/login");
            }
        } else {
            session_destroy();
            header("Location: /TCC/Procafeinacao/acesso/login");
        }
    } else {
        session_destroy();
        header("Location: /TCC/Procafeinacao/acesso/login");
    }
} else {
    session_destroy();
    header("Location: /TCC/Procafeinacao/acesso/login");
} ?>