<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TopBid</title>
    <link rel="icon" href="views/images/icons/logo.ico">
    <link rel="stylesheet" href="views/css/subhastador/estat-comptes.css" />
    <link rel="stylesheet" href="views/css/subhastador/dashboard.css"/>
</head>

<body>
    <div class="container">
        <?php include 'views/includes/nav-admin.php'; ?>
        <section class="main">
            <div class="main-top">
                <h1>Estat de Comptes</h1>
            </div>
            <div class="course-box">
                <h2>Detalls de la Subhasta</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Usuari</th>
                            <th>Compres</th>
                            <th>Ventes</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($estatComptes)): ?>
                            <?php foreach ($estatComptes as $compte): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($compte->usuari); ?></td>
                                    <td><?php echo htmlspecialchars($compte->guany_comprador); ?> €</td>
                                    <td><?php echo htmlspecialchars($compte->guany_propietari); ?> €</td>
                                    <td><?php echo htmlspecialchars($compte->total_guany); ?> €</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No hi ha dades disponibles.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <button id="Torna" onclick="location.href='index.php?controller=subhastador&action=sessio'">Torna</button>
            </div>
        </section>
    </div>
</body>

</html>