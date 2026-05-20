<?php
$siker = '';
$hiba  = '';

if(isset($kapcsolat_siker)) $siker = $kapcsolat_siker;
if(isset($kapcsolat_hiba))  $hiba  = $kapcsolat_hiba;
?>

<h1 class="page-title">Kapcsolat</h1>
<p class="page-subtitle">Írjon nekünk, hamarosan válaszolunk!</p>

<?php if($siker): ?>
    <div class="alert alert-success"><?= htmlspecialchars($siker) ?></div>
<?php endif; ?>
<?php if($hiba): ?>
    <div class="alert alert-error"><?= htmlspecialchars($hiba) ?></div>
<?php endif; ?>

<div class="form-card">
    <form method="POST" action="index.php?page=kapcsolat" id="kapcsolatForm" novalidate>
        <div class="form-group">
            <label>Teljes név</label>
            <input type="text" name="nev" placeholder="Kovács János">
            <span class="error-msg" id="nevErr">Kötelező mező!</span>
        </div>
        <div class="form-group">
            <label>E-mail cím</label>
            <input type="email" name="email" placeholder="pelda@email.hu">
            <span class="error-msg" id="emailErr">Érvényes e-mail cím szükséges!</span>
        </div>
        <div class="form-group">
            <label>Üzenet</label>
            <textarea name="uzenet" placeholder="Írja ide üzenetét..."></textarea>
            <span class="error-msg" id="uzenetErr">Az üzenet legalább 10 karakter kell legyen!</span>
        </div>
        <button type="submit" class="btn btn-primary">Üzenet küldése</button>
    </form>
</div>

<script>
document.getElementById('kapcsolatForm').addEventListener('submit', function(e) {
    let ok = true;

    const nev    = document.querySelector('input[name="nev"]');
    const email  = document.querySelector('input[name="email"]');
    const uzenet = document.querySelector('textarea[name="uzenet"]');

    if (!nev.value.trim()) {
        nev.classList.add('invalid');
        document.getElementById('nevErr').classList.add('show');
        ok = false;
    } else {
        nev.classList.remove('invalid');
        document.getElementById('nevErr').classList.remove('show');
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value)) {
        email.classList.add('invalid');
        document.getElementById('emailErr').classList.add('show');
        ok = false;
    } else {
        email.classList.remove('invalid');
        document.getElementById('emailErr').classList.remove('show');
    }

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