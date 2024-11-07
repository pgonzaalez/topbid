<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TopBid</title>
    <link rel="stylesheet" href="views/css/subhastador/dashboard.css"/>
    <link rel="icon" href="views/images/icons/logo.ico">
    <link rel="stylesheet" href="views/css/subhastador/productes.css">
    <link rel="stylesheet" href="views/css/subhastador/pop-up.css">
</head>

<body>
    <div class="container">
        <?php include 'views/includes/nav-admin.php'; ?>
        <section class="main">
            <div class="main-top">
                <h1>Productes</h1>
            </div>
            <div class="course-box">
                <div class="taula">
                    <div class="headerDiv">
                        <p>Productes pendents: <?= $pendingCount ?></p>
                        <div class="buttonHeaderDiv">
                            <button id="edit" style="display:none;" class="button"
                                onclick="window.location.href='index.php?controller=subhastador&action=editProducts'">Editar</button>
                            <button id="toggleButton" class="button" onclick="cambiaTaules()">Veure tots els
                                productes</button>
                        </div>
                    </div>
                    <table id="table1">
                        <thead>
                            <tr>
                                <th>Id del Producte</th>
                                <th>Nom del Producte</th>
                                <th>Descripció Curta</th>
                                <th>Descripció Llarga</th>
                                <th>Imatge</th>
                                <th>Preu</th>
                                <th>Nom del Usuari</th>
                                <th>Observacions</th>
                                <th>Accions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pendingProducts)): ?>
                                <?php foreach ($pendingProducts as $index => $product): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($product->id_producte) ?></td>
                                        <td><?= htmlspecialchars($product->nom) ?></td>
                                        <td><?= htmlspecialchars($product->descripcio_curta) ?></td>
                                        <td><?= htmlspecialchars($product->descripcio_llarga) ?></td>
                                        <td><img src="<?= htmlspecialchars($product->imatge) ?>" alt="Imatge del producte"></td>
                                        <td><?= htmlspecialchars($product->preu) ?>€</td>
                                        <td><?= htmlspecialchars($product->username) ?></td>
                                        <td><?= htmlspecialchars($product->observacions) ?></td>
                                        <td class="actions">
                                            <button class="buttons openModal" id="openModal-<?= $index ?>">Responder</button>
                                            <div id="mostraResposta<?= $index ?>" class="hidden">
                                                <form method="POST" class="actions" id="form-<?= $index ?>">
                                                    <input type="hidden" name="id_producte"
                                                        value="<?= htmlspecialchars($product->id_producte) ?>">
                                                    <label for="enviaMissatge-<?= $index ?>">Missatge:</label>
                                                    <input type="text" id="enviaMissatge-<?= $index ?>" name="enviaMissatge"
                                                        maxlength="50">
                                                    <button type="submit" name="action" value="accept">Aceptar</button>
                                                    <button type="submit" name="action" value="reject">Rebutjar</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <table id="table2" style="display:none;">
                        <thead>
                            <tr>
                                <th>Id del Producte</th>
                                <th>Nom del Producte</th>
                                <th>Descripció Curta</th>
                                <th>Descripció Llarga</th>
                                <th>Imatge</th>
                                <th>Preu</th>
                                <th>Nom del Usuari</th>
                                <th>Observacions</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($allProducts)): ?>
                                <?php foreach ($allProducts as $index => $product): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($product->id_producte) ?></td>
                                        <td><?= htmlspecialchars($product->nom) ?></td>
                                        <td><?= htmlspecialchars($product->descripcio_curta) ?></td>
                                        <td><?= htmlspecialchars($product->descripcio_llarga) ?></td>
                                        <td><img src="<?= htmlspecialchars($product->imatge) ?>" alt="Imatge del producte"></td>
                                        <td><?= htmlspecialchars($product->preu) ?>€</td>
                                        <td><?= htmlspecialchars($product->username) ?></td>
                                        <td><?= htmlspecialchars($product->observacions) ?></td>
                                        <td><?php
                                            $status = htmlspecialchars($product->status);
                                            $emoji = '';
                                            switch ($status) {
                                                case 'pendent-assignacio':
                                                    $emoji = '🟢';
                                                    break;
                                                case 'rebutjat':
                                                    $emoji = '🔴';
                                                    break;
                                                case 'retirat':
                                                    $emoji = '🟣';
                                                    break;
                                                case 'venut':
                                                    $emoji = '🟡';
                                                    break;
                                                case 'asignat':
                                                    $emoji = '🔵';
                                                    break;
                                                default:
                                                    $emoji = '🟠';
                                                    break;
                                            }
                                            ?>
                                            <p><?= $status, $emoji ?></p>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10">No hi ha productes disponibles</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
    <!-- Modal para responder -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modal-content"></div>
        </div>
    </div>
    <script src="views/scripts/subhastador.js"></script>
    <script src="views/scripts/valida-forms/valida-missatge.js"></script>
</body>

</html>