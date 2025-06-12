<?php
require_once 'baza.php';
include_once 'session.php';

if (!isset($_SESSION['id_p']) || $_SESSION['id_p'] != 2) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['odstrani_id'])) {
    $id_odstrani = $_POST['odstrani_id'];
    if ($id_odstrani > 0) {
        $stmt_ids = mysqli_prepare($conn, "DELETE FROM postavke_narocila WHERE id_n IN (SELECT id_n FROM narocila WHERE id_u = ?)");
        mysqli_stmt_bind_param($stmt_ids, "i", $id_odstrani);
        mysqli_stmt_execute($stmt_ids);
		mysqli_stmt_close($stmt_ids);


        $stmt_del_narocila = mysqli_prepare($conn, "DELETE FROM narocila WHERE id_u = ?");
        mysqli_stmt_bind_param($stmt_del_narocila, "i", $id_odstrani);
        mysqli_stmt_execute($stmt_del_narocila);
        mysqli_stmt_close($stmt_del_narocila);

        $stmt_del_uporabnik = mysqli_prepare($conn, "DELETE FROM uporabniki WHERE id_u = ?");
        mysqli_stmt_bind_param($stmt_del_uporabnik, "i", $id_odstrani);
        mysqli_stmt_execute($stmt_del_uporabnik);
        mysqli_stmt_close($stmt_del_uporabnik);
    }
}

$query = "SELECT u.id_u, u.ime, u.priimek, u.email, p.naziv AS privilegiji 
          FROM uporabniki u 
          INNER JOIN privilegiji p ON u.id_p = p.id_p";

$result = mysqli_query($conn, $query);

$uporabniki = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $uporabniki[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Urejanje uporabnikov</title>
    <link rel="stylesheet" href="css/uredi_dodaj.css?v=1.1">
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
