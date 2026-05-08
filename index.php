<?php
session_start();

$page = $_GET['page'] ?? 'fooldal';

$allowed = ['fooldal', 'kepek', 'kapcsolat', 'uzenetek', 'crud', 'belepes', 'kilepes'];
if (!in_array($page, $allowed)) {
    $page = 'fooldal';
}

if ($page === 'kilepes') {
    session_destroy();
    header('Location: index.php?page=fooldal');
    exit;
}

include 'includes/header.php';
include 'pages/' . $page . '.php';
include 'includes/footer.php';
?>