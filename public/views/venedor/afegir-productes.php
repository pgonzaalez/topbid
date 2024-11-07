<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TopBid</title>
    <link rel="stylesheet" href="views/css/header/header.css">
    <link rel="stylesheet" href="views/css/venedor/venedor.css">
    <link rel="stylesheet" href="views/css/header/styles.css">
    <link rel="stylesheet" href="views/css/header/headerPanel.css">
    <link rel="icon" href="views/images/icons/logo.ico">
</head>

<body>
    <?php include 'views/includes/header-panel.php'; ?>
    
    <main>
    <button class="button" onclick="window.location.href='index.php?controller=venedors&action=index'">Tornar</button>
        <div class="form-container">
            <h2>Afegir Nou Producte</h2>
            <form id="productForm" action="index.php?controller=venedors&action=storeProducte" method="POST" enctype="multipart/form-data">
                <div>
                    <label for="nomProducte">Nom del Producte:</label>
                    <input type="text" id="nomProducte" name="nomProducte" maxlength="40" required>
                    <span class="error-message" id="nomProducteError"></span>
                </div>
                <div>
                    <label for="descripcioCurtaProducte">Descripció Curta:</label>
                    <input type="text" id="descripcioCurtaProducte" name="descripcioCurtaProducte" maxlength="100" required>
                    <span class="error-message" id="descripcioCurtaProducteError"></span>
                </div>
                <div>
                    <label for="descripcioLlargaProducte">Descripció Llarga:</label>
                    <textarea id="descripcioLlargaProducte" name="descripcioLlargaProducte" maxlength="255" required></textarea>
                    <span class="error-message" id="descripcioLlargaProducteError"></span>
                </div>
                <div>
                    <label for="preuProducte">Preu:</label>
                    <input type="number" id="preuProducte" name="preuProducte" step="0.01" required>
                    <span class="erro   r-message" id="preuProducteError"></span>
                </div>
                <div>
                    <label for="observacions">Observacions:</label>
                    <textarea id="observacions" name="observacions" maxlength="255"></textarea>
                    <span class="error-message" id="observacionsError"></span>
                </div>
                <div>
                    <label for="imatgeProducte">Imatge del Producte:</label>
                    <input type="file" id="imatgeProducte" name="imatgeProducte" accept="image/*" required>
                    <span class="error-message" id="imatgeProducteError"></span>
                </div>
                <div class="botones">
                    <button class="button" type="submit">Afegir Producte</button>
                    <button class="close" type="button" onclick="window.location.href='index.php?controller=venedors&action=index'">Cancel·lar</button>
                </div>
            </form>
        </div>
    </main>
    <?php include 'views/includes/footer.php'; ?>

    <script src="views/scripts/valida-forms/validacio-producte.js"></script>
</body>

</html>