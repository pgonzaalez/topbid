<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/css/subhastador/productes-en-subhasta.css" />
    <link rel="stylesheet" href="views/css/subhastador/pop-up.css" />
    <link rel="stylesheet" href="views/css/subhastador/dashboard.css" />
    <title>TopBid</title>
    <link rel="icon" href="views/images/icons/logo.ico">
</head>

<body>
    <div class="container">
        <?php include 'views/includes/nav-admin.php'; ?>
        <section class="main">
            <div class="main-top">
                <h1>Subhasta Iniciada</h1>
                <i class="fas fa-user-cog"></i>
            </div>
            <div class="course-box">
                <h2>Productes</h2>
                <?php if (isset($products) && is_array($products)): ?>
                    <div class="products-list">
                        <?php foreach ($products as $product): ?>
                            <div class="card">
                                <div class="card-info">
                                    <h2><?php echo htmlspecialchars($product->nom); ?></h2>
                                    <p>Preu Incial: <?php echo htmlspecialchars($product->preu); ?> €</p>
                                    <p>Usuari: <?php echo htmlspecialchars($product->username ?? ''); ?></p>
                                    <form method="post" action="index.php?controller=subhastador&action=assignComprador"
                                        class="assign-comprador-form">
                                        <input type="hidden" name="product_id"
                                            value="<?php echo (int) $product->id_producte; ?>">
                                        <label for="comprador">Comprador:</label>
                                        <select name="comprador_id" class="comprador-select"
                                            data-product-id="<?php echo $product->id_producte; ?>">
                                            <?php foreach ($users as $user): ?>
                                                <option value="<?php echo $user->id_usuari; ?>" <?php echo ($user->id_usuari == $product->comprador_id) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($user->username); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="assign-button">Asignar Usuario</button>
                                    </form>
                                    <button id="openModal" class="openModal">Registrar Usuario</button>
                                    <form method="post" action="index.php?controller=subhastador&action=assignPreu">
                                        <input type="hidden" name="product_id"
                                            value="<?php echo (int) $product->id_producte; ?>">
                                        <label for="final_price">Precio Final:</label>
                                        <input type="number" name="preu_final" step="0.01" min="0"
                                            placeholder="Introduce el precio final"
                                            value="<?php echo htmlspecialchars($product->preu_final ?? ''); ?>">
                                        <button type="submit">Asignar Precio</button>
                                    </form>
                                </div>
                                <div class="card-image">
                                    <img class="product-image" src="<?= htmlspecialchars($product->imatge) ?>"
                                        alt="Product Image">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (isset($subhasta) && is_object($subhasta)): ?>
                        <button id="finalitza"
                            onclick="location.href='index.php?controller=subhastador&action=finalitzaSubhasta&id_subhasta=<?php echo $subhasta->id_subhasta; ?>'">Finalitza</button>
                        <button id="estatComptes"
                            onclick="location.href='index.php?controller=subhastador&action=estatComptes&id_subhasta=<?php echo $subhasta->id_subhasta; ?>'">Estat
                            de Comptes</button>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
    <!-- Modal para registrar usuario -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Registrar Usuario</h2>
            <form action="index.php?controller=subhastador&action=registerUser" method="POST">
                <label for="dni">DNI:</label>
                <input type="text" id="dni" name="dni" required>
                <span id="dni-error" class="error-message"></span>

                <label for="username">Nombre:</label>
                <input type="text" id="username" name="username" required>
                <span id="username-error" class="error-message"></span>

                <button type="submit">Registrar</button>
            </form>
        </div>
    </div>
    </section>
    </div>
    <script src="views/scripts/validar-sessio-iniciada.js"></script>
    <script src="views/scripts/valida-forms/valida-registra-usuari.js"></script>
</body>

</html>