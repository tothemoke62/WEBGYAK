<?php
include_once 'includes/db.php';
$db = getDB();

$hiba  = '';
$siker = '';

// töröl
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['nev'])) {
    $stmt = $db->prepare('DELETE FROM pizza WHERE nev = ?');
    $stmt->execute([$_GET['nev']]);
    $siker = 'Pizza sikeresen törölve!';
}

// hozzáad
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $nev           = trim($_POST['nev']           ?? '');
    $kategorianev  = trim($_POST['kategorianev']  ?? '');
    $vegetarianus  = isset($_POST['vegetarianus']) ? 1 : 0;

    if ($nev === '' || $kategorianev === '') {
        $hiba = 'A név és kategória megadása kötelező!';
    } else {
        $check = $db->prepare('SELECT nev FROM pizza WHERE nev = ?');
        $check->execute([$nev]);
        if ($check->fetch()) {
            $hiba = 'Ez a pizza már létezik!';
        } else {
            $ins = $db->prepare('INSERT INTO pizza (nev, kategorianev, vegetarianus) VALUES (?,?,?)');
            $ins->execute([$nev, $kategorianev, $vegetarianus]);
            $siker = 'Pizza sikeresen hozzáadva!';
        }
    }
}

// szerkeszt-ment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $regi_nev      = trim($_POST['regi_nev']      ?? '');
    $nev           = trim($_POST['nev']           ?? '');
    $kategorianev  = trim($_POST['kategorianev']  ?? '');
    $vegetarianus  = isset($_POST['vegetarianus']) ? 1 : 0;

    if ($nev === '' || $kategorianev === '') {
        $hiba = 'A név és kategória megadása kötelező!';
    } else {
        $upd = $db->prepare('UPDATE pizza SET nev=?, kategorianev=?, vegetarianus=? WHERE nev=?');
        $upd->execute([$nev, $kategorianev, $vegetarianus, $regi_nev]);
        $siker = 'Pizza sikeresen módosítva!';
    }
}

// lekérés
$kategoriak = $db->query('SELECT * FROM kategoria ORDER BY nev')->fetchAll(PDO::FETCH_ASSOC);

// Szerkesztés nyitás
$edit_pizza = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['nev'])) {
    $stmt = $db->prepare('SELECT * FROM pizza WHERE nev = ?');
    $stmt->execute([$_GET['nev']]);
    $edit_pizza = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Pizza lista
$pizzak = $db->query('SELECT p.*, k.ar FROM pizza p LEFT JOIN kategoria k ON p.kategorianev = k.nev ORDER BY p.nev')->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="page-title">Pizzáink</h1>
<p class="page-subtitle">Kínálatunk kezelése</p>

<?php if ($siker): ?>
    <div class="alert alert-success"><?= htmlspecialchars($siker) ?></div>
<?php endif; ?>
<?php if ($hiba): ?>
    <div class="alert alert-error"><?= htmlspecialchars($hiba) ?></div>
<?php endif; ?>

<!-- hozzáad + szerkeszt -->
<div class="form-card" style="margin-bottom: 2rem;">
    <h2 class="section-title"><?= $edit_pizza ? 'Pizza szerkesztése' : 'Új pizza hozzáadása' ?></h2>

    <form method="POST" action="index.php?page=crud">
        <input type="hidden" name="action" value="<?= $edit_pizza ? 'edit' : 'add' ?>">
        <?php if ($edit_pizza): ?>
            <input type="hidden" name="regi_nev" value="<?= htmlspecialchars($edit_pizza['nev']) ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="nev">Pizza neve</label>
            <input type="text" id="nev" name="nev" placeholder="pl. Margherita"
                value="<?= $edit_pizza ? htmlspecialchars($edit_pizza['nev']) : '' ?>">
        </div>
        <div class="form-group">
            <label for="kategorianev">Kategória</label>
            <select id="kategorianev" name="kategorianev">
                <?php foreach ($kategoriak as $k): ?>
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
            <?php if ($edit_pizza): ?>
                <a href="index.php?page=crud" class="btn btn-secondary">Mégse</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- lista -->
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
            <?php foreach ($pizzak as $p): ?>
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
                           onclick="return confirm('Biztosan törli a(z) <?= htmlspecialchars($p['nev']) ?> pizzát?')">Törlés</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>