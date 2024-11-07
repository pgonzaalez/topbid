<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/css/header/styles.css">
    <link rel="stylesheet" href="views/css/header/header.css">
    <link rel="stylesheet" href="views/css/venedor/sessions-subhasta.css">
    <link rel="stylesheet" href="views/css/header/headerPanel.css">
    <title>TopBid</title>
    <link rel="icon" href="views/images/icons/logo.ico">
</head>

<body>
    <?php
    if (isset($_SESSION['id_usuari'])) {
        include 'views/includes/header-panel.php';
    } else {
        include 'views/includes/header.php';
    }
    ?>
    <main>
        <div class="buscador">
            <?php if (!empty($subhasta) && isset($subhasta->nom)): ?>
                <h1><?= htmlspecialchars($subhasta->nom) ?></h1>
            <?php endif; ?>
        </div>

        <div class="product-card">
            <?php if (!empty($productes)): ?>
                <?php foreach ($productes as $product): ?>
                    <div class="product-item">
                        <div class="item-header">
                            <h2><?= htmlspecialchars($product->nom) ?></h2>
                        </div>
                        <div class="image-container">
                            <img class="product-image" src="<?= htmlspecialchars($product->imatge) ?>" alt="Product Image">
                        </div>
                        <div class="price-like-container">
                            <p class="price">Preu: <?= htmlspecialchars($product->preu) ?>€</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hi ha productes a aquesta subhasta.</p>
            <?php endif; ?>
        </div>
    </main>
    <?php
    include 'views/includes/footer.php';
    ?>
</body>

</html>