<?php
if(isset($_POST['felhasznalo']) && isset($_POST['jelszo'])) {
    try {
        $dbh = getDB();
        
        $sqlSelect = "SELECT id, vezeteknev, keresztnev, login, jelszo FROM felhasznalok WHERE login = :login";
        $sth = $dbh->prepare($sqlSelect);
        $sth->execute(array(':login' => $_POST['felhasznalo']));
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        
        if($row && password_verify($_POST['jelszo'], $row['jelszo'])) {
            $_SESSION['csn'] = $row['vezeteknev'];
            $_SESSION['un']  = $row['keresztnev'];
            $_SESSION['login'] = $row['login'];
        }
    }
    catch (PDOException $e) {
        $errormessage = "Hiba: ".$e->getMessage();
    }
} else {
    header("Location: index.php");
}
?>