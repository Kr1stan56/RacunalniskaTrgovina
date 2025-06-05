<?php
require_once 'baza.php';
include_once 'session.php';

if (!isset($_SESSION['id_p']) || $_SESSION['id_p'] != 2) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['odstrani_id'])) {
    $id_odstrani = intval($_POST['odstrani_id']);
    if ($id_odstrani > 0) {
        $stmt = $conn->prepare("DELETE FROM uporabniki WHERE id_u = ?");
        $stmt->bind_param("i", $id_odstrani);
        $stmt->execute();
    }
}

$query = "SELECT u.id_u, u.ime, u.priimek, u.email, p.naziv AS privilegiji 
          FROM uporabniki u 
          INNER JOIN privilegiji p ON u.id_p = p.id_p";

$result = $conn->query($query);
$uporabniki = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Urejanje uporabnikov</title>
    <link rel="stylesheet" href="css/uredi_dodaj.css">
    <style>
        
    </style>
</head>
<body>
<main class="uredi-izdelek">
    <form action="index.php" method="get">
        <button type="submit">← Nazaj na nadzorno ploščo</button>
    </form>

    <h1>Upravljanje uporabnikov</h1>

    <table>
        <thead>
            <tr>
                <th>Ime</th>
                <th>Priimek</th>
                <th>Email</th>
                <th>Vloga</th>
                <th>Akcije</th>
				<th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($uporabniki as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['ime']) ?></td>
                    <td><?= htmlspecialchars($u['priimek']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['privilegiji']) ?></td>
                    <td class="button">
                        <form action="uredi_uporabnik.php" method="get">
                            <input type="hidden" name="id" value="<?= $u['id_u'] ?>">
                            <button type="submit">Uredi</button>
                        </form>
					</td>
					<td>
                        <form method="post" onsubmit="return confirm('Ali res želiš izbrisati tega uporabnika?');">
                            <input type="hidden" name="odstrani_id" value="<?= $u['id_u'] ?>">
                            <button type="submit" name="odstrani">Odstrani</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>
