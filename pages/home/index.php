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

                    /* .list {
                                                                        display: flex;
                                                                        flex-wrap: wrap;
                                                                        list-style: none;
                                                                        padding: 0;
                                                                        margin: 0;
                                                                    } */

                    tbody>tr {
                        border: 1px solid #ddd;
                        transition: transform 0.3s;
                        cursor: pointer;
                    }

                    tbody>tr:hover {

                        background-color: burlywood;
                    }

                    td {
                        vertical-align: middle !important;
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
                                        <div class="search-box ms-2">
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