<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TopBid</title>
    <link rel="icon" href="views/images/icons/logo.ico">
    <link rel="stylesheet" href="views/css/header/header.css">
    <link rel="stylesheet" href="views/css/header/styles.css">
    <link rel="stylesheet" href="views/css/header/headerPanel.css">
    <link rel="stylesheet" href="views/css/notificacions/notificacions.css">
</head>

<body>
    <?php include 'views/includes/header-panel.php'; ?>
    <main>
        <button class="button" onclick="window.location.href='index.php?controller=venedors&action=index'">Tornar al
            panell</button>
        <div class="container">
            <table>
                <thead>
                    <tr>
                        <th>Missatge</th>
                        <th>Accions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $missatge): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($missatge->emisor) . " diu: " . htmlspecialchars($missatge->producte_id) . " " . htmlspecialchars($missatge->missatge); ?> </td>
                            <td>
                                <?php if (!$missatge->llegit): ?>
                                    <form method="POST" action="index.php?controller=notificacions&action=marcarLlegit">
                                        <input type="hidden" name="id_missatge" value="<?php echo $missatge->id_missatge; ?>">
                                        <button type="submit">Marcar com a llegit</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <table>
                <thead>
                    <tr>
                        <th>Notificació</th>
                        <th>Accions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notificacions as $notificacio): ?>
                        <tr>
                            <td><?php echo  htmlspecialchars($notificacio->nom) . " s'ha actualitzat a " . htmlspecialchars($notificacio->status); ?>
                            </td>
                            <td>
                                <?php if (!$notificacio->llegida): ?>
                                    <form method="POST" action="index.php?controller=notificacions&action=marcarLlegit">
                                        <input type="hidden" name="id_notificacio"
                                            value="<?php echo $notificacio->id_notificacio; ?>">
                                        <button type="submit">Marcar com a llegida</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <?php include 'views/includes/footer.php'; ?>
</body>

</html>