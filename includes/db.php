<?php
function getDB() {
    static $dbh = null;
    if ($dbh === null) {
        try {
            $dbh = new PDO(
                'mysql:host=mysql.omega;dbname=adat1;charset=utf8',
                'adat1',
                'NakamuraHaru22',
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        } catch (PDOException $e) {
            die('Adatbázis hiba: ' . $e->getMessage());
        }
    }
    return $dbh;
}
?>