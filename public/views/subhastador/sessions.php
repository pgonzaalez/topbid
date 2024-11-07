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
                <h1>Sessions de subhastes</h1>
                <i class="fas fa-user-cog"></i>
            </div>
            <div class="course-box">
                <?php if (!empty($allSubhastes)): ?>
                    <div class="subhasta-card">
                        <?php foreach ($allSubhastes as $subhasta): ?>
                            <div class="subhasta-item">
                                <h2><?php echo htmlspecialchars($subhasta->nom); ?></h2>
                                <p><?php echo htmlspecialchars($subhasta->date_hora); ?></p>
                                <p><?php echo htmlspecialchars($subhasta->lloc); ?></p>
                                <button class="boton" id="inicia"
                                    onclick="location.href='index.php?controller=subhastador&action=<?php echo $subhasta->inici === 'iniciat' ? 'subhastaIniciada' : 'iniciaSubhasta'; ?>&id_subhasta=<?php echo $subhasta->id_subhasta; ?>'">
                                    <?php echo $subhasta->inici === 'iniciat' ? 'Visualitza' : 'Inicia'; ?>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No hi ha subhastes disponibles.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>
</body>

</html>