<?php
if(isset($_POST['felhasznalo']) && isset($_POST['jelszo'])) {
    try {
        $dbh = getDB();
        
        $sqlSelect = "SELECT id, csaladi_nev, uto_nev FROM felhasznalok WHERE login = :login AND jelszo = :jelszo";
        $sth = $dbh->prepare($sqlSelect);
        $sth->execute(array(':login' => $_POST['felhasznalo'], ':jelszo' => password_hash($_POST['jelszo'], PASSWORD_DEFAULT)));
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $_SESSION['csn'] = $row['vezeteknev'];
            $_SESSION['un'] = $row['keresztnev'];
            $_SESSION['login'] = $_POST['felhasznalo'];
        }
    }
    catch (PDOException $e) {
        $errormessage = "Hiba: ".$e->getMessage();
    }
} else {
    header("Location: index.php");
}
?>