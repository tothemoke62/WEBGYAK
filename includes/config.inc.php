<?php
$ablakcim = array(
    'cim' => 'La Familia Pizzéria',
);

$fejlec = array(
    'cim' => 'La Familia Pizzéria',
    'motto' => 'Az igazi olasz pizza élménye'
);

$lablec = array(
    'copyright' => 'Copyright '.date("Y").'.',
    'ceg' => 'La Familia Pizzéria'
);

$oldalak = array(
    '/' => array('fajl' => 'cimlap', 'szoveg' => 'Főoldal', 'menun' => array(1,1)),
    'kepek' => array('fajl' => 'kepek', 'szoveg' => 'Képek', 'menun' => array(1,1)),
    'kapcsolat' => array('fajl' => 'kapcsolat', 'szoveg' => 'Kapcsolat', 'menun' => array(1,1)),
    'crud' => array('fajl' => 'crud', 'szoveg' => 'CRUD', 'menun' => array(1,1)),
    'uzenetek' => array('fajl' => 'uzenetek', 'szoveg' => 'Üzenetek', 'menun' => array(0,1)),
    'belepes' => array('fajl' => 'belepes', 'szoveg' => 'Bejelentkezés', 'menun' => array(1,0)),
    'kilepes' => array('fajl' => 'kilepes', 'szoveg' => 'Kilépés', 'menun' => array(0,1)),
    'belep' => array('fajl' => 'belep', 'szoveg' => '', 'menun' => array(0,0)),
    'regisztral' => array('fajl' => 'regisztral', 'szoveg' => '', 'menun' => array(0,0))
);

$hiba_oldal = array('fajl' => '404', 'szoveg' => 'A keresett oldal nem található!');

function getDB() {
    static $dbh = null;
    if ($dbh === null) {
        try {
            $dbh = new PDO(
                'mysql:host=mysql.omega;dbname=IDE_AZ_ADATBAZISNEV;charset=utf8',
                'IDE_A_FELHASZNALONEV',
                'IDE_A_JELSZO',
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        } catch (PDOException $e) {
            die('Adatbázis hiba: ' . $e->getMessage());
        }
    }
    return $dbh;
}
?>