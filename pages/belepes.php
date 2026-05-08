<?php
// Munkamenet indítása a bejelentkezéshez
session_start();

// Adatbázis kapcsolat beolvasása
include_once 'includes/db.php';

// Változók inicializálása alaphelyzetbe
$reg_hiba = '';
$reg_ok = '';
$login_hiba = '';

// === BEJELENTKEZÉS FELDOLGOZÁSA ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $login = trim($_POST['login'] ?? '');
    $jelszo = $_POST['jelszo'] ?? '';

    if ($login === '' || $jelszo === '') {
        $login_hiba = 'Minden mező kitöltése kötelező!';
    } else {
        $db = getDB();
        // Lekérdezzük a felhasználót a megadott név alapján
        $stmt = $db->prepare('SELECT * FROM felhasznalok WHERE login = ?');
        $stmt->execute([$login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Jelszó ellenőrzése (ha létezik a felhasználó)
        if ($user && password_verify($jelszo, $user['jelszo'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'login' => $user['login'],
                'vezeteknev' => $user['vezeteknev'],
                'keresztnev' => $user['keresztnev']
            ];
            header('Location: index.php?page=fooldal');
            exit;
        } else {
            $login_hiba = 'Hibás felhasználónév vagy jelszó!';
        }
    }
}

// === REGISZTRÁCIÓ FELDOLGOZÁSA ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reg') {
    $vezeteknev = trim($_POST['vezeteknev'] ?? '');
    $keresztnev = trim($_POST['keresztnev'] ?? '');
    $login = trim($_POST['reg_login'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $jelszo = $_POST['reg_jelszo'] ?? '';
    $jelszo2 = $_POST['reg_jelszo2'] ?? '';

    // Validációk
    if ($vezeteknev === '' || $keresztnev === '' || $login === '' || $email === '' || $jelszo === '') {
        $reg_hiba = 'Minden mező kitöltése kötelező!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $reg_hiba = 'Érvénytelen e-mail cím!';
    } elseif (strlen($jelszo) < 6) {
        $reg_hiba = 'A jelszó legalább 6 karakter kell legyen!';
    } elseif ($jelszo !== $jelszo2) {
        $reg_hiba = 'A két jelszó nem egyezik!';
    } else {
        $db = getDB();
        // Ellenőrizzük, hogy foglalt-e a név vagy az email
        $stmt = $db->prepare('SELECT id FROM felhasznalok WHERE login = ? OR email = ?');
        $stmt->execute([$login, $email]);
        
        if ($stmt->fetch()) {
            $reg_hiba = 'Ez a felhasználónév vagy e-mail már foglalt!';
        } else {
            // Jelszó titkosítása és mentés
            $hash = password_hash($jelszo, PASSWORD_DEFAULT);
            $ins = $db->prepare('INSERT INTO felhasznalok (vezeteknev, keresztnev, login, email, jelszo) VALUES (?, ?, ?, ?, ?)');
            $ins->execute([$vezeteknev, $keresztnev, $login, $email, $hash]);
            $reg_ok = 'Sikeres regisztráció! Most már bejelentkezhet.';
        }
    }
}
?>

<h1 class="page-title">Fiókkezelés</h1>
<p class="page-subtitle">Jelentkezzen be, vagy hozzon létre új fiókot</p>

<div class="auth-wrap">

    <div class="form-card">
        <h2 class="section-title">Bejelentkezés</h2>

        <?php if (!empty($login_hiba)): ?>
            <div class="alert alert-danger" style="color: red; margin-bottom: 15px;">
                <?php echo $login_hiba; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=belepes" id="loginForm" novalidate>
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label for="login">Felhasználónév</label>
                <input type="text" id="login" name="login" placeholder="pl. kovacs.janos">
                <span class="error-msg" id="loginErr" style="display:none; color:red;">Kötelező mező!</span>
            </div>
            <div class="form-group">
                <label for="jelszo">Jelszó</label>
                <input type="password" id="jelszo" name="jelszo" placeholder="••••••••">
                <span class="error-msg" id="passErr" style="display:none; color:red;">Kötelező mező!</span>
            </div>
            <button type="submit" class="btn btn-primary">Bejelentkezés</button>
        </form>
    </div>

    <div class="form-card">
        <h2 class="section-title">Regisztráció</h2>

        <?php if (!empty($reg_hiba)): ?>
            <div class="alert alert-danger" style="color: red; margin-bottom: 15px;">
                <?php echo $reg_hiba; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($reg_ok)): ?>
            <div class="alert alert-success" style="color: green; margin-bottom: 15px;">
                <?php echo $reg_ok; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=belepes" id="regForm" novalidate>
            <input type="hidden" name="action" value="reg">
            <div class="form-group">
                <label for="vezeteknev">Vezetéknév</label>
                <input type="text" id="vezeteknev" name="vezeteknev" placeholder="Kovács">
                <span class="error-msg" id="vnErr" style="display:none; color:red;">Kötelező mező!</span>
            </div>
            <div class="form-group">
                <label for="keresztnev">Keresztnév</label>
                <input type="text" id="keresztnev" name="keresztnev" placeholder="János">
                <span class="error-msg" id="knErr" style="display:none; color:red;">Kötelező mező!</span>
            </div>
            <div class="form-group">
                <label for="reg_login">Felhasználónév</label>
                <input type="text" id="reg_login" name="reg_login" placeholder="kovacs.janos">
                <span class="error-msg" id="rlErr" style="display:none; color:red;">Kötelező mező!</span>
            </div>
            <div class="form-group">
                <label for="email">E-mail cím</label>
                <input type="email" id="email" name="email" placeholder="pelda@email.hu">
                <span class="error-msg" id="emailErr" style="display:none; color:red;">Érvényes e-mail szükséges!</span>
            </div>
            <div class="form-group">
                <label for="reg_jelszo">Jelszó (min. 6 karakter)</label>
                <input type="password" id="reg_jelszo" name="reg_jelszo" placeholder="••••••••">
                <span class="error-msg" id="rp1Err" style="display:none; color:red;">Legalább 6 karakter szükséges!</span>
            </div>
            <div class="form-group">
                <label for="reg_jelszo2">Jelszó megerősítése</label>
                <input type="password" id="reg_jelszo2" name="reg_jelszo2" placeholder="••••••••">
                <span class="error-msg" id="rp2Err" style="display:none; color:red;">A jelszavak nem egyezkenek!</span>
            </div>
            <button type="submit" class="btn btn-primary">Regisztráció</button>
        </form>
    </div>

</div>

<script>
// Kliens oldali validáció a bejelentkezéshez
function validateLogin(e) {
    let ok = true;
    const l = document.getElementById('login');
    const p = document.getElementById('jelszo');

    if (!l.value.trim()) {
        l.classList.add('invalid');
        document.getElementById('loginErr').style.display = 'block';
        ok = false;
    } else {
        l.classList.remove('invalid');
        document.getElementById('loginErr').style.display = 'none';
    }
    
    if (!p.value) {
        p.classList.add('invalid');
        document.getElementById('passErr').style.display = 'block';
        ok = false;
    } else {
        p.classList.remove('invalid');
        document.getElementById('passErr').style.display = 'none';
    }
    
    if (!ok) e.preventDefault();
}

// Kliens oldali validáció a regisztrációhoz
function validateReg(e) {
    let ok = true;
    const fields = [
        ['vezeteknev', 'vnErr', v => v.trim() !== ''],
        ['keresztnev', 'knErr', v => v.trim() !== ''],
        ['reg_login', 'rlErr', v => v.trim() !== ''],
        ['email', 'emailErr', v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)],
        ['reg_jelszo', 'rp1Err', v => v.length >= 6],
    ];

    fields.forEach(([id, errId, check]) => {
        const el = document.getElementById(id);
        const err = document.getElementById(errId);
        if (!check(el.value)) {
            el.classList.add('invalid'); 
            err.style.display = 'block'; 
            ok = false;
        } else {
            el.classList.remove('invalid'); 
            err.style.display = 'none';
        }
    });

    const p1 = document.getElementById('reg_jelszo');
    const p2 = document.getElementById('reg_jelszo2');
    const p2e = document.getElementById('rp2Err');

    if (p1.value !== p2.value || p2.value === '') {
        p2.classList.add('invalid'); 
        p2e.style.display = 'block'; 
        ok = false;
    } else {
        p2.classList.remove('invalid'); 
        p2e.style.display = 'none';
    }

    if (!ok) e.preventDefault();
}

document.getElementById('loginForm').addEventListener('submit', validateLogin);
document.getElementById('regForm').addEventListener('submit', validateReg);
</script>