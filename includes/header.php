<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Familia Pizzéria</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Raleway:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- Főoldal header része -->
<header class="site-header">
    <div class="header-inner">
        <div class="logo">
            <a href="index.php?page=fooldal">
                <span class="logo-icon">🍕</span>
                <span class="logo-text">La <em>Familia</em></span>
            </a>
        </div>

        <?php if (isset($_SESSION['user'])): ?>
        <div class="user-badge">
            Bejelentkezett: 
            <strong><?= htmlspecialchars($_SESSION['user']['vezeteknev'] . ' ' . $_SESSION['user']['keresztnev']) ?></strong>
        </div>
        <?php endif; ?>

        <button class="menu-toggle" id="menuToggle" aria-label="Menü">
            <span></span><span></span><span></span>
        </button>
    </div>

    <nav class="main-nav" id="mainNav">
        <ul>
            <li><a href="index.php?page=fooldal" <?= $page==='fooldal'?'class="active"':'' ?>>Főoldal</a></li>

            <li><a href="index.php?page=kepek" <?= $page==='kepek'?'class="active"':'' ?>>Képek</a></li>

            <li><a href="index.php?page=kapcsolat" <?= $page==='kapcsolat'?
            'class="active"':'' ?>>Kapcsolat</a></li>
            <li><a href="index.php?page=crud" <?= $page==='crud'?'class="active"':'' ?>>CRUD</a></li>

            <?php if (isset($_SESSION['user'])): ?>
                <li><a href="index.php?page=uzenetek" <?= $page==='uzenetek'?'class="active"':'' ?>>Üzenetek</a></li>

                <li><a href="index.php?page=kilepes" class="nav-btn nav-btn--logout">Kilépés</a></li>

            <?php else: ?>
                <li><a href="index.php?page=belepes" class="nav-btn nav-btn--login" <?= $page==='belepes'?'class="active"':'' ?>>Bejelentkezés</a></li>
                
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main class="site-main">