<?php include_once 'session.php'; ?>


<header class="header">
    <div class="glava-vsebina">
        <div class="logotip">
            <a href="index.php">
				<img src="images/logo.png" alt="Logotip trgovine" class="logotip-slika">
			</a>
        </div>
        
        <nav class="navigacija">
            <ul class="navigacijski-seznam">   
                <li class="navigacijska-postavka"><a href="index.php">Domov</a></li>
                <li class="navigacijska-postavka"><a href="izdelki.php">Izdelki</a></li>
                <li class="navigacijska-postavka"><a href="o-nas.php">O nas</a></li>
                <li class="navigacijska-postavka"><a href="kontakt.php">Kontakt</a></li>
            </ul>
        </nav>
        
        <div class="uporabniske-akcije">
			<?php if (isset($_SESSION['prijavljen']) && $_SESSION['prijavljen']): ?>
				<span class="uporabnisko-ime"><?= htmlspecialchars($_SESSION['ime'] . ' ' . $_SESSION['priimek']) ?></span>
				<a href="odjava.php" class="gumb-odjava">Odjava</a>
				<a href="uredi_u.php" class="gumb-odjava" style="background-color: white;color: black;float:left;"><i>UPORABNIKI</i></a>
			<?php else: ?>
				<a href="login.php" class="gumb-prijava">Prijava / Registracija</a>
			<?php endif; ?>

			<a href="kosarica.php" class="ikona-kosarica">
				<img src="images/kosarica.png" alt="Košarica" class="kosarica-ikona">
				<span class="stevec-kosarice">0</span>
			</a>
		</div>

    </div>
</header>