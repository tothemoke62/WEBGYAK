<h1 class="page-title">Fiókkezelés</h1>
<p class="page-subtitle">Jelentkezzen be, vagy hozzon létre új fiókot</p>

<div class="auth-wrap">

    <!-- bejelentkezés -->
    <div class="form-card">
        <h2 class="section-title">Bejelentkezés</h2>

        <form action="index.php?page=belep" method="post" id="loginForm" novalidate>
            <div class="form-group">
                <label>Felhasználónév</label>
                <input type="text" name="felhasznalo" placeholder="pl. kovacs.janos">
                <span class="error-msg" id="loginErr">Kötelező mező!</span>
            </div>
            <div class="form-group">
                <label>Jelszó</label>
                <input type="password" name="jelszo" placeholder="••••••••">
                <span class="error-msg" id="passErr">Kötelező mező!</span>
            </div>
            <button type="submit" class="btn btn-primary">Bejelentkezés</button>
        </form>
    </div>

    <!-- regisztrálás -->
    <div class="form-card">
        <h2 class="section-title">Regisztráció</h2>

        <?php if(isset($uzenet)): ?>
            <div class="alert <?= isset($ujra) && $ujra ? 'alert-error' : 'alert-success' ?>">
                <?= htmlspecialchars($uzenet) ?>
            </div>
        <?php endif; ?>

        <form action="index.php?page=regisztral" method="post" id="regForm" novalidate>
            <div class="form-group">
                <label>Vezetéknév</label>
                <input type="text" name="vezeteknev" placeholder="Kovács">
                <span class="error-msg" id="vnErr">Kötelező mező!</span>
            </div>
            <div class="form-group">
                <label>Keresztnév</label>
                <input type="text" name="utonev" placeholder="János">
                <span class="error-msg" id="knErr">Kötelező mező!</span>
            </div>
            <div class="form-group">
                <label>Felhasználónév</label>
                <input type="text" name="felhasznalo" placeholder="kovacs.janos">
                <span class="error-msg" id="rlErr">Kötelező mező!</span>
            </div>
            <div class="form-group">
                <label>Jelszó (min. 6 karakter)</label>
                <input type="password" name="jelszo" placeholder="••••••••">
                <span class="error-msg" id="rp1Err">Legalább 6 karakter szükséges!</span>
            </div>
            <button type="submit" class="btn btn-primary">Regisztráció</button>
        </form>
    </div>

</div>

<script>
function validateLogin(e) {
    let ok = true;
    const l = document.querySelector('#loginForm input[name="felhasznalo"]');
    const p = document.querySelector('#loginForm input[name="jelszo"]');

    if (!l.value.trim()) {
        l.classList.add('invalid');
        document.getElementById('loginErr').classList.add('show');
        ok = false;
    } else {
        l.classList.remove('invalid');
        document.getElementById('loginErr').classList.remove('show');
    }
    if (!p.value) {
        p.classList.add('invalid');
        document.getElementById('passErr').classList.add('show');
        ok = false;
    } else {
        p.classList.remove('invalid');
        document.getElementById('passErr').classList.remove('show');
    }
    if (!ok) e.preventDefault();
}

function validateReg(e) {
    let ok = true;
    const fields = [
        ['vezeteknev', 'vnErr',  v => v.trim() !== ''],
        ['utonev',     'knErr',  v => v.trim() !== ''],
        ['felhasznalo','rlErr',  v => v.trim() !== ''],
        ['jelszo',     'rp1Err', v => v.length >= 6],
    ];
    fields.forEach(([name, errId, check]) => {
          const el  = document.querySelector('#regForm input[name="'+name+'"]');
        const err = document.getElementById(errId);
        if (!check(el.value)) {
            el.classList.add('invalid'); err.classList.add('show'); ok = false;
        } else {
            el.classList.remove('invalid'); err.classList.remove('show');
        }
    });
    if (!ok) e.preventDefault();
}

document.getElementById('loginForm').addEventListener('submit', validateLogin);
document.getElementById('regForm').addEventListener('submit', validateReg);
</script>





