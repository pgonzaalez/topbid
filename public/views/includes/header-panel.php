<?php
if (isset($_SESSION['rol'])) {
    $userRole = $_SESSION['rol'];
}
?>
<header>
    <div class="logo">
        <button onclick="location.href='index.php'">
            <img src="views/images/icons/logo.png" alt="logo">
        </button>
    </div>
    <nav>
        <ul>
            <li>
                <?php if ($userRole === 'venedor'): ?>
                    <div class="dropdown-notification">
                        <button alt="icon" onclick="location.href='index.php?controller=notificacions&action=index'"
                            class="dropdown-notification-toggle">
                            <img src="views/images/icons/notificacion.png">
                        </button>
                    </div>
                <?php endif; ?>
            </li>
            <li><a href="index.php?controller=products&action=index">Productes</a></li>
            <li><a href="index.php?controller=products&action=viewSubhastes">Subhastes</a></li>
            <li>
                <div class="dropdown">
                    <img src="views/images/icons/usuario.png" alt="icon" class="dropdown-toggle">
                    <div class="dropdown-content">
                        <?php
                        if (isset($_SESSION['rol'])) {
                            if ($_SESSION['rol'] == 'subhastador') {
                                echo '<a href="index.php?controller=subhastador&action=index">Panell d\'usuari</a>';
                            } elseif ($_SESSION['rol'] == 'venedor') {
                                echo '<a href="index.php?controller=venedors&action=index">Panell d\'usuari</a>';
                                echo '<a href="index.php?controller=venedors&action=storeProducte">Afegir Producte</a>';
                                echo '<a href="index.php?controller=products&action=likedProducts">Productes desitjas</a>';
                            }
                        }
                        ?>
                        <a href="index.php?controller=auth&action=logout">Log Out</a>
                    </div>
                </div>
            </li>
        </ul>
    </nav>
</header>