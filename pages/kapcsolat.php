<?php
include_once 'includes/db.php';

$siker = '';
$hiba  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nev     = trim($_POST['nev']     ?? '');
    $email   = trim($_POST['email']   ?? '');
    $uzenet  = trim($_POST['uzenet']  ?? '');

    if ($nev === '' || $email === '' || $uzenet === '') {
        $hiba = 'Minden mező kitöltése kötelező!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $hiba = 'Érvénytelen e-mail cím!';
    } elseif (strlen($uzenet) < 10) {
        $hiba = 'Az üzenet legalább 10 karakter kell legyen!';
    } else {
        $db  = getDB();
        $ins = $db->prepare('INSERT INTO uzenetek (nev, email, uzenet, datum) VALUES (?,?,?,NOW())');
        $ins->execute([$nev, $email, $uzenet]);
        $siker = 'Üzenete sikeresen elküldve! Hamarosan felvesszük Önnel a kapcsolatot.';
    }
}
?>

<h1 class="page-title">Kapcsolat</h1>
<p class="page-subtitle">Írjon nekünk, hamarosan válaszolunk!</p>

<?php if ($siker): ?>
    <div class="alert alert-success"><?= htmlspecialchars($siker) ?></div>
<?php endif; ?>
<?php if ($hiba): ?>
    <div class="alert alert-error"><?= htmlspecialchars($hiba) ?></div>
<?php endif; ?>

<div class="form-card">
    <form method="POST" action="index.php?page=kapcsolat" id="kapcsolatForm" novalidate>
        <div class="form-group">
            <label for="nev">Teljes név</label>
            <input type="text" id="nev" name="nev" placeholder="Kovács János">
            <span class="error-msg" id="nevErr">Kötelező mező!</span>
        </div>
        <div class="form-group">
            <label for="email">E-mail cím</label>
            <input type="email" id="email" name="email" placeholder="pelda@email.hu">
            <span class="error-msg" id="emailErr">Érvényes e-mail cím szükséges!</span>
        </div>
        <div class="form-group">
            <label for="uzenet">Üzenet</label>
            <textarea id="uzenet" name="uzenet" placeholder="Írja ide üzenetét..."></textarea>
            <span class="error-msg" id="uzenetErr">Az üzenet legalább 10 karakter kell legyen!</span>
        </div>
        <button type="submit" class="btn btn-primary">Üzenet küldése</button>
    </form>
</div>

<script>
document.getElementById('kapcsolatForm').addEventListener('submit', function(e) {
    let ok = true;

    const nev    = document.getElementById('nev');
    const email  = document.getElementById('email');
    const uzenet = document.getElementById('uzenet');

    // Név
    if (!nev.value.trim()) {
        nev.classList.add('invalid');
        document.getElementById('nevErr').classList.add('show');
        ok = false;
    } else {
        nev.classList.remove('invalid');
        document.getElementById('nevErr').classList.remove('show');
    }

    // Email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value)) {
        email.classList.add('invalid');
        document.getElementById('emailErr').classList.add('show');
        ok = false;
    } else {
        email.classList.remove('invalid');
        document.getElementById('emailErr').classList.remove('show');
    }

    // Üzenet
    if (uzenet.value.trim().length < 10) {
        uzenet.classList.add('invalid');
        document.getElementById('uzenetErr').classList.add('show');
        ok = false;
    } else {
        uzenet.classList.remove('invalid');
        document.getElementById('uzenetErr').classList.remove('show');
    }

    if (!ok) e.preventDefault();
});
</script>