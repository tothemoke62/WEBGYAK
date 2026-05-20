<?php if(!isset($_SESSION['login'])): ?>
    <div class="alert alert-error">Ez az oldal csak bejelentkezett felhasználók számára elérhető!</div>
<?php else: ?>

<h1 class="page-title">Üzenetek</h1>
<p class="page-subtitle">Beérkezett kapcsolatfelvételek</p>

<?php
$dbh = getDB();
$stmt = $dbh->query('SELECT * FROM uzenetek ORDER BY datum DESC');
$uzenetek = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if(empty($uzenetek)): ?>
    <div class="alert alert-info">Még nem érkezett egyetlen üzenet sem.</div>
<?php else: ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Név</th>
                    <th>E-mail</th>
                    <th>Üzenet</th>
                    <th>Dátum</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($uzenetek as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['nev']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['uzenet']) ?></td>
                    <td><?= $u['datum'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php endif; ?>