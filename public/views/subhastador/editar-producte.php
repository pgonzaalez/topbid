<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TopBid</title>
    <link rel="icon" href="views/images/icons/logo.ico">
    <link rel="stylesheet" href="views/css/subhastador/editar.css">
    <link rel="stylesheet" href="views/css/subhastador/dashboard.css" />
</head>

<body>
    <div class="container">
        <?php include 'views/includes/nav-admin.php'; ?>
        <section class="main">
            <div class="main-top">
                <h1>Editar Productes</h1>
                <i class="fas fa-user-cog"></i>
            </div>
            <div class="course-box">
                <?php if (isset($products) && is_array($products)): ?>
                    <form id="editProductForm" action="index.php?controller=subhastador&action=editProducts" method="post">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Descripció Curta</th>
                                    <th>Descripció Llarga</th>
                                    <th>Preu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td>
                                            <input type="text" name="nom" value="<?php echo $product->nom; ?>" maxlength="45">
                                            <span class="error-message" id="nom-error"></span>
                                        </td>
                                        <td>
                                            <input type="text" name="descripcio_curta"
                                                value="<?php echo $product->descripcio_curta; ?>" maxlength="100">
                                            <span class="error-message" id="descripcio-curta-error"></span>
                                        </td>
                                        <td>
                                            <input type="text" name="descripcio_llarga"
                                                value="<?php echo $product->descripcio_llarga; ?>" maxlength="255">
                                            <span class="error-message" id="descripcio-llarga-error"></span>
                                        </td>
                                        <td>
                                            <input type="number" name="preu" value="<?php echo $product->preu; ?>" step="0.01"
                                                max="99999999.99">
                                            <span class="error-message" id="preu-error"></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button type="submit">Guardar</button>
                    </form>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <script src="views/scripts/valida-forms/valida-formulari-editar.js"></script>
</body>

</html>