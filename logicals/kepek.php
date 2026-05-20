<?php
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['login'])) {
    if(isset($_FILES['kep']) && $_FILES['kep']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size      = 5 * 1024 * 1024;

        if(!in_array($_FILES['kep']['type'], $allowed_types)) {
            $kepek_hiba = 'Csak JPG, PNG, GIF vagy WEBP fájl tölthető fel!';
        } elseif($_FILES['kep']['size'] > $max_size) {
            $kepek_hiba = 'A fájl mérete maximum 5MB lehet!';
        } else {
            $kiterjesztes = pathinfo($_FILES['kep']['name'], PATHINFO_EXTENSION);
            $uj_nev       = uniqid() . '.' . $kiterjesztes;
            $cel          = './kepek/' . $uj_nev;

            if(move_uploaded_file($_FILES['kep']['tmp_name'], $cel)) {
                $dbh = getDB();
                $ins = $dbh->prepare('INSERT INTO kepek (fajlnev, feltolto_id, datum) VALUES (?,?,NOW())');
                $ins->execute([$uj_nev, 1]);
                $kepek_siker = 'Kép sikeresen feltöltve!';
            } else {
                $kepek_hiba = 'Feltöltés sikertelen!';
            }
        }
    }
}
?>