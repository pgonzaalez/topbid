<!DOCTYPE html>
<html lang="ca">

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
        <div class="buscador">
            <h1>Visualitza els teus Productes</h1>
        </div>
        <div class="product-card">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-item">
                        <div class="item-header">
                            <h2><?= htmlspecialchars($product->nom) ?></h2>
                            <p><?= htmlspecialchars($product->descripcio_curta) ?></p>
                        </div>
                        <img class="product-image" src="<?= htmlspecialchars($product->imatge) ?>" alt="Product Image">
                        <p>Preu: <?= htmlspecialchars($product->preu) ?>€</p>
                        <?php
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
                        <p>Status: <?= $status, $emoji ?></p>
                        <form method="POST" action="index.php?controller=venedors&action=delete" class="delete-form">
                            <input type="hidden" name="id" value="<?= $product->id_producte ?>">
                            <input type="hidden" name="action" value="delete">
                            <button class="button delete-button" type="submit" data-status="<?= $product->status ?>">Eliminar Producte</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No se encontraron productos</p>
            <?php endif; ?>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-button');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    const status = button.getAttribute('data-status');
                    if (status === 'asignat') {
                        event.preventDefault();

                        // Crear el mensaje de error
                        let errorMessage = button.nextElementSibling;
                        if (!errorMessage || !errorMessage.classList.contains('error-message')) {
                            errorMessage = document.createElement('div');
                            errorMessage.classList.add('error-message');
                            button.insertAdjacentElement('afterend', errorMessage);
                        }
                        errorMessage.textContent = 'No puedes eliminar este producto porque está asignado a una subasta.';
                    }
                });
            });
        });
    </script>
    <?php include 'views/includes/footer.php'; ?>
</body>

</html>