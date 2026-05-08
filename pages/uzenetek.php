<?php

    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=fooldal');
        exit;
    }
?>

<h1 class="page-title">Üzenetek</h1>
<p class="page-subtitle">Hamarosan...</p>