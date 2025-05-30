<?php
require_once 'baza.php';
include_once 'session.php';

if (!isset($_SESSION['id_p']) || $_SESSION['id_p'] != 2) {
    header("Location: index.php");
    exit;
}

$query = "SELECT u.id_u, u.ime, u.priimek, u.email,p.naziv AS privilegiji 
          FROM uporabniki u INNER JOIN privilegiji p ON u.id_p = p.id_p";

$result = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['odstrani_id'])) {
    $id_odstrani = intval($_POST['odstrani_id']);
    if ($id_odstrani > 0) {
        $stmt = $conn->prepare("DELETE FROM uporabnik WHERE id_u = ?");
        $stmt->bind_param("i", $id_odstrani);
        $stmt->execute();
        exit;
    }
}
?>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Ime</th>
            <th>Priimek</th>
            <th>Email</th>
            <th>Pravice</th>
            <th>Akcije</th>
			<th>Akcije</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($uporabnik = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($uporabnik['id_u']) ?></td>
                <td><?= htmlspecialchars($uporabnik['ime']) ?></td>
                <td><?= htmlspecialchars($uporabnik['priimek']) ?></td>
                <td><?= htmlspecialchars($uporabnik['email']) ?></td>
                <td><?= htmlspecialchars($uporabnik['privilegiji']) ?></td>
                <td>
                    <a href="uredi_uporabnika.php?id=<?= $uporabnik['id_u'] ?>">Uredi</a>
                </td>
				<td>
                    <form method="post">
                        <input type="hidden" name="odstrani_id" value="<?= $uporabnik['id_u'] ?>">
                        <button type="submit" class="odstrani">Izbriši</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
