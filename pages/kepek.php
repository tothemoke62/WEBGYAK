<?php
include_once 'includes/db.php';

$hiba  = '';
$siker = '';

// Bejelentkezett felhasználó ONLY!
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    if (isset($_FILES['kep']) && $_FILES['kep']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size      = 5 * 1024 * 1024; // 5MB

        if (!in_array($_FILES['kep']['type'], $allowed_types)) {
            $hiba = 'Csak JPG, PNG, GIF vagy WEBP fájl tölthető fel!';
        } elseif ($_FILES['kep']['size'] > $max_size) {
            $hiba = 'A fájl mérete maximum 5MB lehet!';
        } else {
            $kiterjesztes = pathinfo($_FILES['kep']['name'], PATHINFO_EXTENSION);
            $uj_nev       = uniqid() . '.' . $kiterjesztes;
            $cel          = 'kepek/' . $uj_nev;

            if (move_uploaded_file($_FILES['kep']['tmp_name'], $cel)) {
                $db  = getDB();
                $ins = $db->prepare('INSERT INTO kepek (fajlnev, feltolto_id, datum) VALUES (?,?,NOW())');
                $ins->execute([$uj_nev, $_SESSION['user']['id']]);
                $siker = 'Kép sikeresen feltöltve!';
            } else {
                $hiba = 'Feltöltés sikertelen, próbálja újra!';
            }
        }
    } else {
        $hiba = 'Kérjük válasszon ki egy képet!';
    }
}

// Képek lekérése
$db   = getDB();
$stmt = $db->query('SELECT * FROM kepek ORDER BY datum DESC');
$kepek = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="page-title">Képek <span>&</span> Galéria</h1>
<p class="page-subtitle">Pizzáink és éttermi hangulatunk képekben</p>

<?php if (isset($_SESSION['user'])): ?>
<div class="form-card" style="margin-bottom: 2rem;">
    <h2 class="section-title">Kép feltöltése</h2>

    <?php if ($siker): ?>
        <div class="alert alert-success"><?= htmlspecialchars($siker) ?></div>
    <?php endif; ?>
    <?php if ($hiba): ?>
        <div class="alert alert-error"><?= htmlspecialchars($hiba) ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?page=kepek" enctype="multipart/form-data">
        <div class="form-group">
            <label for="kep">Kép kiválasztása (JPG, PNG, GIF, WEBP – max 5MB)</label>
            <input type="file" id="kep" name="kep" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Feltöltés</button>
    </form>
</div>
<?php else: ?>
    <div class="alert alert-info">A képek feltöltéséhez kérjük <a href="index.php?page=belepes">jelentkezzen be</a>!</div>
<?php endif; ?>

<?php if (empty($kepek)): ?>
    <div class="alert alert-info">Még nem töltöttek fel képet.</div>
<?php else: ?>
    <div class="gallery-grid">
        <?php foreach ($kepek as $k): ?>
        <div class="gallery-item">
            <img src="kepek/<?= htmlspecialchars($k['fajlnev']) ?>" alt="Pizza kép">
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>