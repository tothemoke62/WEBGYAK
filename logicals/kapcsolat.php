<?php
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nev'])) {
    $nev    = trim($_POST['nev']    ?? '');
    $email  = trim($_POST['email']  ?? '');
    $uzenet = trim($_POST['uzenet'] ?? '');

    if($nev === '' || $email === '' || $uzenet === '') {
        $kapcsolat_hiba = 'Minden mező kitöltése kötelező!';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $kapcsolat_hiba = 'Érvénytelen e-mail cím!';
    } elseif(strlen($uzenet) < 10) {
        $kapcsolat_hiba = 'Az üzenet legalább 10 karakter kell legyen!';
    } else {
        $dbh = getDB();
        $ins = $dbh->prepare('INSERT INTO uzenetek (nev, email, uzenet, datum) VALUES (?,?,?,NOW())');
        $ins->execute([$nev, $email, $uzenet]);
        $kapcsolat_siker = 'Üzenete sikeresen elküldve!';
    }
}
?>