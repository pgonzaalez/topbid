<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8"/>
    <title>TopBid</title>
    <link rel="icon" href="views/images/icons/logo.ico">
    <link rel="stylesheet" href="views/css/subhastador/dashboard.css"/>
</head>

<body>

    <div class="container">
        <?php include 'views/includes/nav-admin.php'; ?>
        <section class="main">
            <div class="main-top">
                <h1>Dashboard</h1>
                <i class="fas fa-user-cog"></i>
            </div>
            <div class="main-skills">
                <div class="card">
                    <h3>Crear subhasta</h3>
                    <button
                        onclick="location.href='index.php?controller=subhastador&action=createSubhasta'">Crear</button>
                </div>
                <div class="card">
                    <h3>Veure subhasta</h3>
                    <button
                        onclick="location.href='index.php?controller=subhastador&action=subhastaAdmin'">Veure</button>
                </div>
                <div class="card">
                    <h3>Veure productes</h3>
                    <button
                        onclick="location.href='index.php?controller=subhastador&action=viewProducts'">Veure</button>
                </div>
            </div>
            <section class="main-course">
                <h1>Productes</h1>
                <div class="course-box">
                    <ul>
                        <li class="active" id="filter-price">Per preu</li>
                        <li id="filter-likes">Per interès</li>
                    </ul>
                    <div class="course">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Imatge</th>
                                    <th>Preu</th>
                                    <th>Me gusta</th>
                                </tr>
                            </thead>
                            <tbody id="product-list">
                                <?php foreach ($products as $product): ?>
                                    <tr class="product-row" data-id="<?php echo htmlspecialchars($product->id_producte); ?>"
                                        data-interest="<?php echo htmlspecialchars($product->interest ?? ''); ?>"
                                        data-price="<?php echo htmlspecialchars($product->preu); ?>"
                                        data-likes="<?php echo htmlspecialchars($product->likesCount); ?>">
                                        <td>
                                            <h2><?php echo htmlspecialchars($product->nom); ?></h2>
                                        </td>
                                        <td>
                                            <?php if (!empty($product->imatge)): ?>
                                                <img src="<?php echo htmlspecialchars($product->imatge); ?>"
                                                    alt="Imatge de <?php echo htmlspecialchars($product->nom); ?>" width="100">
                                            <?php else: ?>
                                                <p>Imatge no disponible</p>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <p><?php echo htmlspecialchars($product->preu); ?> €</p>
                                        </td>
                                        <td>
                                            <span><?php echo htmlspecialchars($product->likesCount); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </section>
    </div>
    <script src="views/scripts/filtrar-dashboard.js"></script>
</body>

</html>