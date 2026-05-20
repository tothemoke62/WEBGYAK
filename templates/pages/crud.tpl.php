<?php
$hiba  = '';
$siker = '';
if(isset($crud_hiba))  $hiba  = $crud_hiba;
if(isset($crud_siker)) $siker = $crud_siker;

$dbh = getDB();
$kategoriak = $dbh->query('SELECT * FROM kategoria ORDER BY nev')->fetchAll(PDO::FETCH_ASSOC);

$edit_pizza = null;
if(isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['nev'])) {
    $stmt = $dbh->prepare('SELECT * FROM pizza WHERE nev = ?');
    $stmt->execute([$_GET['nev']]);
    $edit_pizza = $stmt->fetch(PDO::FETCH_ASSOC);
}

$pizzak = $dbh->query('SELECT p.*, k.ar FROM pizza p LEFT JOIN kategoria k ON p.kategorianev = k.nev ORDER BY p.nev')->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="page-title">Pizzáink</h1>
<p class="page-subtitle">Kínálatunk kezelése</p>

<?php if($siker): ?>
    <div class="alert alert-success"><?= htmlspecialchars($siker) ?></div>
<?php endif; ?>
<?php if($hiba): ?>
    <div class="alert alert-error"><?= htmlspecialchars($hiba) ?></div>
<?php endif; ?>

<div class="form-card" style="margin-bottom: 2rem;">
    <h2 class="section-title"><?= $edit_pizza ? 'Pizza szerkesztése' : 'Új pizza hozzáadása' ?></h2>

    <form method="POST" action="index.php?page=crud">
        <input type="hidden" name="action" value="<?= $edit_pizza ? 'edit' : 'add' ?>">
        <?php if($edit_pizza): ?>
            <input type="hidden" name="regi_nev" value="<?= htmlspecialchars($edit_pizza['nev']) ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Pizza neve</label>
            <input type="text" name="nev" placeholder="pl. Margherita"
                value="<?= $edit_pizza ? htmlspecialchars($edit_pizza['nev']) : '' ?>">
        </div>
        <div class="form-group">
            <label>Kategória</label>
            <select name="kategorianev">
                <?php foreach($kategoriak as $k): ?>
                    <option value="<?= htmlspecialchars($k['nev']) ?>"
                        <?= ($edit_pizza && $edit_pizza['kategorianev'] === $k['nev']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($k['nev']) ?> (<?= $k['ar'] ?> Ft)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="display:flex; align-items:center; gap:0.8rem;">
            <input type="checkbox" id="vegetarianus" name="vegetarianus" style="width:auto;"
                <?= ($edit_pizza && $edit_pizza['vegetarianus']) ? 'checked' : '' ?>>
            <label for="vegetarianus" style="margin:0; text-transform:none; font-size:0.95rem;">Vegetáriánus</label>
        </div>
        <div style="display:flex; gap:1rem;">
            <button type="submit" class="btn btn-primary">
                <?= $edit_pizza ? 'Mentés' : 'Hozzáadás' ?>
            </button>
            <?php if($edit_pizza): ?>
                <a href="index.php?page=crud" class="btn btn-secondary">Mégse</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Pizza neve</th>
                <th>Kategória</th>
                <th>Ár</th>
                <th>Vegetáriánus</th>
                <th>Műveletek</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pizzak as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nev']) ?></td>
                <td><?= htmlspecialchars($p['kategorianev']) ?></td>
                <td><?= $p['ar'] ?> Ft</td>
                <td><?= $p['vegetarianus'] ? '🌿 Igen' : '🍖 Nem' ?></td>
                <td>
                    <div class="action-btns">
                        <a href="index.php?page=crud&action=edit&nev=<?= urlencode($p['nev']) ?>"
                           class="btn btn-secondary btn-sm">Szerkesztés</a>
                        <a href="index.php?page=crud&action=delete&nev=<?= urlencode($p['nev']) ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Biztosan törli?')">Törlés</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>