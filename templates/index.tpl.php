<?php if(isset($keres) && file_exists($_SERVER['DOCUMENT_ROOT']."/logicals/".$keres['fajl'].'.php')) { include($_SERVER['DOCUMENT_ROOT']."/logicals/{$keres['fajl']}.php"); } ?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $ablakcim['cim'] ?></title>
    <link rel="stylesheet" href="./styles/style.css" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Raleway:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<header class="site-header">
    <div class="header-inner">
        <div class="logo">
            <a href="index.php">
                <span class="logo-icon">🍕</span>
                <span class="logo-text">La <em>Familia</em></span>
            </a>
        </div>
        <?php if(isset($_SESSION['login'])): ?>
        <div class="user-badge">
            Bejelentkezett: <strong><?= $_SESSION['csn']." ".$_SESSION['un']." (".$_SESSION['login'].")" ?></strong>
        </div>
        <?php endif; ?>
        <button class="menu-toggle" id="menuToggle">
            <span></span><span></span><span></span>
        </button>
    </div>
    <nav class="main-nav" id="mainNav">
        <ul>
            <?php foreach($oldalak as $url => $oldal): ?>
                <?php if(!isset($_SESSION['login']) && $oldal['menun'][0] || isset($_SESSION['login']) && $oldal['menun'][1]): ?>
                    <li>
                        <a href="<?= ($url == '/') ? 'index.php' : 'index.php?page='.$url ?>"
                           <?= ($oldal == $keres) ? 'class="active"' : '' ?>>
                            <?= $oldal['szoveg'] ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </nav>
</header>

<main class="site-main">
  <?php include($_SERVER['DOCUMENT_ROOT']."/templates/pages/{$keres['fajl']}.tpl.php"); ?>
</main>

<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-logo">🍕 La <em>Familia</em></div>
        <p>&copy; <?= $lablec['copyright'] ?> <?= $lablec['ceg'] ?></p>
    </div>
</footer>

<script src="./js/main.js"></script>
</body>
</html>