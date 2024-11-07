<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TopBid</title>
    <link rel="icon" href="views/images/icons/logo.ico">
    <link rel="stylesheet" href="views/css/header/styles.css">
    <link rel="stylesheet" href="views/css/header/headerPanel.css">
    <link rel="stylesheet" href="views/css/header/header.css">
    <link rel="stylesheet" href="views/css/venedor/venedor.css">
</head>

<body>
    <?php require_once 'views/includes/header-panel.php'; ?>
    <main>
        <div class="buscador">
            <h1>Productes desitjats</h1>
        </div>
        <div class="product-card">
            <?php if (empty($likedProducts)): ?>
                <p>No has marcado ningún producto con "like".</p>
            <?php else: ?>
                <?php foreach ($likedProducts as $product): ?>
                    <div class="product-item">
                        <div class="item-header">
                            <h2><?= htmlspecialchars($product->nom) ?></h2>
                            <p><?= htmlspecialchars($product->descripcio_curta) ?></p>
                        </div>
                        <div class="image-container">
                            <img class="product-image" src="<?= htmlspecialchars($product->imatge) ?>" alt="Product Image">
                        </div>
                        <div class="price-like-container">
                            <p class="price">Preu: <?= htmlspecialchars($product->preu) ?>€</p>
                        </div>
                        <div class="like-container">
                            <form method="post" action="index.php?controller=products&action=like">
                                <input type="hidden" name="product_id" value="<?= $product->id_producte ?>">
                                <div class="megusta">
                                    <button type="submit" name="like" style="background: none; border: none; cursor: pointer;">
                                        <img src="views/images/icons/<?= $product->hasLiked ? 'corazon.png' : 'corazon-vacio.png' ?>"
                                            alt="<?= $product->hasLiked ? 'Quitar Me gusta' : 'Me gusta' ?>">
                                    </button>
                                    <span><?= $product->likesCount ?> Me gusta</span>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</body>
<?php require_once 'views/includes/footer.php'; ?>

</html>