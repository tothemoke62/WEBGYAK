<?php
$hiba  = '';
$siker = '';
if(isset($kepek_hiba))  $hiba  = $kepek_hiba;
if(isset($kepek_siker)) $siker = $kepek_siker;

$dbh = getDB();
$stmt = $dbh->query('SELECT * FROM kepek ORDER BY datum DESC');
$kepek = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="page-title">Képek <span>&</span> Galéria</h1>
<p class="page-subtitle">Pizzáink és éttermi hangulatunk képekben</p>

<?php if(isset($_SESSION['login'])): ?>
<div class="form-card" style="margin-bottom: 2rem;">
    <h2 class="section-title">Kép feltöltése</h2>

    <?php if($siker): ?>
        <div class="alert alert-success"><?= htmlspecialchars($siker) ?></div>
    <?php endif; ?>
    <?php if($hiba): ?>
        <div class="alert alert-error"><?= htmlspecialchars($hiba) ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?page=kepek" enctype="multipart/form-data">
        <div class="form-group">
            <label>Kép kiválasztása (JPG, PNG, GIF, WEBP – max 5MB)</label>
            <input type="file" name="kep" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Feltöltés</button>
    </form>
</div>
<?php else: ?>
    <div class="alert alert-info">A képek feltöltéséhez kérjük <a href="index.php?page=belepes">jelentkezzen be</a>!</div>
<?php endif; ?>

<?php if(empty($kepek)): ?>
    <div class="alert alert-info">Még nem töltöttek fel képet.</div>
<?php else: ?>
    <div class="gallery-grid">
        <?php foreach($kepek as $k): ?>
        <div class="gallery-item">
            <img src="./kepek/<?= htmlspecialchars($k['fajlnev']) ?>" alt="Pizza kép">
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>