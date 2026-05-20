<?php
if(isset($_POST['felhasznalo']) && isset($_POST['jelszo']) && isset($_POST['vezeteknev']) && isset($_POST['utonev'])) {
    try {
        $dbh = getDB();
        
        
        $sqlSelect = "SELECT id FROM felhasznalok WHERE login = :login";
        $sth = $dbh->prepare($sqlSelect);
        $sth->execute(array(':login' => $_POST['felhasznalo']));
        if($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $uzenet = "A felhasználói név már foglalt!";
            $ujra = true;
        } else {
            $hash = password_hash($_POST['jelszo'], PASSWORD_DEFAULT);
            $sqlInsert = "INSERT INTO felhasznalok(vezeteknev, keresztnev, login, email, jelszo)
                          VALUES(:vezeteknev, :utonev, :login, '', :jelszo)";
            $stmt = $dbh->prepare($sqlInsert);
            $stmt->execute(array(
                ':vezeteknev' => $_POST['vezeteknev'],
                ':utonev' => $_POST['utonev'],
                ':login' => $_POST['felhasznalo'],
                ':jelszo' => $hash
            ));
            if($stmt->rowCount()) {
                $uzenet = "A regisztráció sikeres! Most már bejelentkezhet.";
                $ujra = false;
            } else {
                $uzenet = "A regisztráció nem sikerült.";
                $ujra = true;
            }
        }
    }
    catch (PDOException $e) {
        $uzenet = "Hiba: ".$e->getMessage();
        $ujra = true;
    }
} else {
    header("Location: index.php");
}
?>