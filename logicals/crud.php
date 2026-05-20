<?php
$dbh = getDB();

if(isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['nev'])) {
    $stmt = $dbh->prepare('DELETE FROM pizza WHERE nev = ?');
    $stmt->execute([$_GET['nev']]);
    $crud_siker = 'Pizza sikeresen törölve!';
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $nev          = trim($_POST['nev']          ?? '');
    $kategorianev = trim($_POST['kategorianev'] ?? '');
    $vegetarianus = isset($_POST['vegetarianus']) ? 1 : 0;

    if($nev === '' || $kategorianev === '') {
        $crud_hiba = 'A név és kategória megadása kötelező!';
    } elseif($_POST['action'] === 'add') {
        $check = $dbh->prepare('SELECT nev FROM pizza WHERE nev = ?');
        $check->execute([$nev]);
        if($check->fetch()) {
            $crud_hiba = 'Ez a pizza már létezik!';
        } else {
            $ins = $dbh->prepare('INSERT INTO pizza (nev, kategorianev, vegetarianus) VALUES (?,?,?)');
            $ins->execute([$nev, $kategorianev, $vegetarianus]);
            $crud_siker = 'Pizza sikeresen hozzáadva!';
        }
    } elseif($_POST['action'] === 'edit') {
        $regi_nev = trim($_POST['regi_nev'] ?? '');
        $upd = $dbh->prepare('UPDATE pizza SET nev=?, kategorianev=?, vegetarianus=? WHERE nev=?');
        $upd->execute([$nev, $kategorianev, $vegetarianus, $regi_nev]);
        $crud_siker = 'Pizza sikeresen módosítva!';
    }
}
?>