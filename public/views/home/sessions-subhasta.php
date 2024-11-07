<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/css/header/styles.css">
    <link rel="stylesheet" href="views/css/header/header.css">
    <link rel="stylesheet" href="views/css/venedor/sessions-subhasta.css">
    <link rel="stylesheet" href="views/css/header/headerPanel.css">
    <link rel="stylesheet" href="views/css/carrusell.css">
    <title>TopBid</title>
    <link rel="icon" href="views/images/icons/logo.ico">
</head>
<script>
    let productes = <?= json_encode($subhasta->productes) ?>;
    let currentIndex = 0;

    function changeImage() {
        if (productes.length > 0) {
            currentIndex = (currentIndex + 1) % productes.length;
            document.getElementById('product-image').src = productes[currentIndex].imatge;
        }
    }

    setInterval(changeImage, 3000);
</script>

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
                <input type="hidden" name="action" value="buscarSubhastes">
                <input type="text" name="search" placeholder="Buscar subhastes..."
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit"><img src="views/images/icons/lupa.png" alt="Buscar"></button>
            </form>
        </div>
        <div class="subhasta-card">
            <?php if (!empty($subhastes)): ?>
                <?php foreach ($subhastes as $index => $subhasta): ?>
                    <div class="subhasta-item">
                        <h2><?= htmlspecialchars($subhasta->nom) ?></h2>
                        <p><?= htmlspecialchars($subhasta->date_hora) ?></p>
                        <p><?= htmlspecialchars($subhasta->lloc) ?></p>
                        <p><?= htmlspecialchars($subhasta->descripcio) ?></p>
                        <div class="productImage">
                            <?php if (!empty($subhasta->productes)): ?>
                                <img id="product-image-<?= $index ?>" class="product-image" src="<?= htmlspecialchars($subhasta->productes[0]->imatge) ?>" alt="Product Image">
                            <?php else: ?>
                                <p>No hi ha productes a aquesta subhasta.</p>
                            <?php endif; ?>
                        </div>
                        <a href="index.php?controller=products&action=veureProductes&id_subhasta=<?php echo htmlspecialchars($subhasta->id_subhasta); ?>">
                            <button class="button">Veure productes</button>
                        </a>
                    </div>
                    <script>
                        (function() {
                            let productes = <?= json_encode($subhasta->productes) ?>;
                            let currentIndex = 0;

                            function changeImage() {
                                if (productes.length > 0) {
                                    currentIndex = (currentIndex + 1) % productes.length;
                                    document.getElementById('product-image-<?= $index ?>').src = productes[currentIndex].imatge;
                                }
                            }

                            setInterval(changeImage, 3000);
                        })();
                    </script>

                <?php endforeach; ?>
            <?php else: ?>
                <p>No hi ha subhastes disponibles.</p>
            <?php endif; ?>
        </div>
    </main>
    <?php
    include 'views/includes/footer.php';
    ?>
</body>

</html>