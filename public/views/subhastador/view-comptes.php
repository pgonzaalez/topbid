<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/css/subhastador/subhasta.css">
    <link rel="stylesheet" href="views/css/subhastador/dashboard.css"/>
    <title>TopBid</title>
    <link rel="icon" href="views/images/icons/logo.ico">
</head>

<body>
    <div class="container">
        <?php include 'views/includes/nav-admin.php'; ?>
        <section class="main">
            <div class="main-top">
                <h1>Visualitza totes les factures de les subhastes</h1>
                <i class="fas fa-user-cog"></i>
            </div>
            <div class="course-box">
                <div class="subhasta-card">
                    <?php if (!empty($allSubhastes)): ?>
                        <?php foreach ($allSubhastes as $subhasta): ?>
                            <div class="subhasta-item">
                                <h2><?php echo htmlspecialchars($subhasta->nom); ?></h2>
                                <p><?php echo htmlspecialchars($subhasta->date_hora); ?></p>
                                <p><?php echo htmlspecialchars($subhasta->lloc); ?></p>

                                <button class="boton" id="veure"
                                    onclick="location.href='index.php?controller=subhastador&action=estatComptes&id_subhasta=<?php echo $subhasta->id_subhasta; ?>'">Visualitza la factura</button>

                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hi ha subhastes disponibles.</p>
                    <?php endif; ?>
                </div>
            </div>
    </div>
    </section>
    </div>
</body>

</html>