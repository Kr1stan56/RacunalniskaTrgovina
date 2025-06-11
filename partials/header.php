<?php include_once 'session.php'; ?>
<?php require_once 'baza.php'; ?>

<?php


$id_kosarice = $_SESSION['id_k'] ;
	

$stmt_st = mysqli_prepare($conn, "SELECT COUNT(*) AS stevilo FROM postavke_kosarice	 WHERE id_k = ?");
mysqli_stmt_bind_param($stmt_st,"i",$id_kosarice);
mysqli_stmt_execute($stmt_st);

mysqli_stmt_bind_result($stmt_st,$stevilo);
mysqli_stmt_fetch($stmt_st);
mysqli_stmt_close($stmt_st);

?>
<header class="header">
    <div class="glava-vsebina">
        <div class="logotip">
            <a href="index.php">
                <img src="images/logo.png" alt="Logotip trgovine" class="logotip-slika">
            </a>
        </div>

        <nav class="navigacija">
            <ul class="navigacijski-seznam">
                <li><a href="index.php">Domov</a></li>
                <li><a href="izdelki.php">Izdelki</a></li>

            </ul>
        </nav>

        <div class="uporabniske-akcije">
            <?php if (isset($_SESSION['prijavljen']) && $_SESSION['prijavljen']): ?>
                <span class="uporabnisko-ime"><?= htmlspecialchars($_SESSION['ime'] . ' ' . $_SESSION['priimek']) ?></span>
                <a href="odjava.php" class="gumb-odjava">Odjava</a>
                <?php if (isset($_SESSION['id_p']) && $_SESSION['id_p'] == 2): ?>
                    <a href="uredi_u.php" class="gumb-admin">Uporabniki</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="login.php" class="gumb-prijava">Prijava / Registracija</a>
            <?php endif; ?>

            <a href="kosarica.php" class="ikona-kosarica">
                <img src="images/kosarica.png" alt="Košarica" class="kosarica-ikona">
                <span class="stevec-kosarice"><?php= htmlspecialchars($stevilo)?></span>
            </a>
        </div>
    </div>


	<div class="iskalnik-vsebina">
		<form action="izdelki.php" method="get" class="iskalni-obrazec">
			<input type="text" name="query" placeholder="Kaj iščete?" value="<?php if (isset($_GET['query'])) 
																			{ 'value="' . htmlspecialchars($_GET['query']) . '"'; } 
																				else 
																			{  'value=""';}
																		?>" class="iskalno-polje">

			<select name="kategorija" class="kategorija" >
				<option value="" >Vse kategorije</option>
				<?php foreach ($kategorije as $kat): ?>
					<option value="<?= $kat['id_ka'] ?>" <?php if (isset($_GET['kategorija']) && $_GET['kategorija'] == $kat['id_ka']) 
														{ echo 'selected'; }?> > 
													<?=$kat['ime'] ?>
					</option>
				<?php endforeach; ?>
			</select>

			<button type="submit" class="gumb-iskanje">Najdi</button>
		</form>

	</div>

</header>
