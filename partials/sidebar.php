<?php
if (isset($_GET['toggle'])) {
    $toggle = $_GET['toggle'];
} else {
    $toggle = 1;
}

$stmt_kat = mysqli_prepare($conn, "SELECT * FROM kategorije ORDER BY ime ASC ");
mysqli_stmt_execute($stmt_kat);
$rezultat_kat = mysqli_stmt_get_result($stmt_kat);


$kategorije = [];
while ($vrstica = mysqli_fetch_assoc($rezultat_kat)) {
    $kategorije[] = $vrstica;
}
mysqli_stmt_close($stmt_kat);
?>

<?php
if ($toggle % 2 !== 0) { 
?>
<div class="sidebar-gumb">
	<div class="sidebar">
		
		

			<div class="kategorije-box">
			
			    <?php if (isset($_SESSION['id_p']) && $_SESSION['id_p'] == 2): ?>
				<a href="dodaj_kat.php" class="category-item " style="background-color:white;color:black;">+ Dodaj Kategorijo</a>
				<?php endif; ?>
				
				<a href="?" ><div class="category-item"s>
					Vse kategorije	
			</div>
				</a>
				<?php foreach ($kategorije as $kat): ?>
				<div>
					<a href="?kategorija=<?= $kat['id_ka'] ?>" >
						<div class="category-item"s>	
							<?= htmlspecialchars($kat['ime']) ?>
						</div>
					</a>
					<?php if (isset($_SESSION['id_p']) && $_SESSION['id_p'] == 2): ?>
						<a href="uredi_kat.php?id=<?= $kat['id_ka'] ?>" class="category-item" style="background-color: white; color: black;">
							<i >UREDI</i>
							
						</a>

					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>
		
	</div>
	<div class="zapri">
		<a href="?toggle=<?= $toggle + 1 ?>">
			<img src="images/left.webp" alt="Zapri sidebar">
		</a>
	</div>
</div>
<?php
} else 
{?>
<div class="sidebar-gumb zaprt ">
	<div class="odpri">
		<a href="?toggle=<?= $toggle -1 ?>"> 
            <img src="images/right.webp" alt="Odpri sidebar">
        </a>
	</div>
</div>
<?php
}?>
