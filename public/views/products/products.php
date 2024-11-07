<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TopBid</title>
    <link rel="icon" href="views/images/icons/logo.ico">
    <link rel="stylesheet" href="views/css/header/header.css">
    <link rel="stylesheet" href="views/css/header/headerPanel.css">
    <link rel="stylesheet" href="views/css/header/styles.css">
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
            <h1>TopBid</h1>
            <form class="search-container" method="GET" action="index.php">
                <input type="hidden" name="controller" value="products">
                <input type="hidden" name="action" value="index">
                <input type="text" name="search" placeholder="Buscar..."
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit"><img src="views/images/icons/lupa.png" alt="Buscar"></button>
                <select name="order" onchange="this.form.submit()">
                    <option value="nom" <?= (isset($_GET['order']) && $_GET['order'] == 'nom') ? 'selected' : '' ?>>Ordenar
                        por nombre</option>
                    <option value="preu" <?= (isset($_GET['order']) && $_GET['order'] == 'preu') ? 'selected' : '' ?>>
                        Ordenar por precio</option>
                </select>
            </form>
        </div>
        <div class="product-card">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-item">
                        <div class="item-header">
                            <h2><?= htmlspecialchars($product->nom) ?></h2>
                            <p><?= htmlspecialchars($product->descripcio_curta) ?></p>
                        </div>
                        <div class="image-container">
                            <img class="product-image" src="<?= htmlspecialchars($product->imatge) ?>" alt="Product Image">
                        </div>
                        <div class="price-like-container">
                            <?php if (!empty($product->subhastaNom)): ?>
                                <p class="price"><?= htmlspecialchars($product->subhastaNom) ?></p>
                            <?php endif; ?>
                            <p class="price">Preu: <?= htmlspecialchars($product->preu) ?>€</p>
                            <div class="like-container">
                                <form method="post" action="index.php?controller=products&action=like">
                                    <input type="hidden" name="product_id" value="<?= $product->id_producte ?>">
                                    <div class="megusta">
                                        <button type="submit" name="like"
                                            style="background: none; border: none; cursor: pointer;">
                                            <img src="views/images/icons/<?= $product->hasLiked ? 'corazon.png' : 'corazon-vacio.png' ?>"
                                                alt="<?= $product->hasLiked ? 'Quitar Me gusta' : 'Me gusta' ?>">
                                        </button>
                                        <span><?= $product->likesCount ?> Me gusta</span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No se encontraron productos</p>
            <?php endif; ?>
        </div>

        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?controller=products&action=index&search=<?= htmlspecialchars($search) ?>&order=<?= htmlspecialchars($order) ?>&page=<?= $i ?>"
                    class="pagination-link <?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
        <?php if (isset($conn) && $conn !== null): ?>
            <?php $conn->close(); ?>
        <?php endif; ?>
    </main>
    <?php
    include 'views/includes/footer.php';
    ?>
</body>

</html>