<?php
require_once 'baza.php';
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <?php include 'partials/header.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O nas | Računalniška Trgovina</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    
    <main class="main-content">
	
        <section class="about-section">
            <h1 class="page-title">O našem podjetju</h1>
            <div class="team-section">
                <h2>Naš tim</h2>
                <div class="team-grid">
                    <div class="team-member">
                        <img src="images/team/1.jpg" alt="Janez Novak">
                        <h4>Janez Novak</h4>
                        <p>Tehnični direktor</p>
                    </div>
                    <div class="team-member">
                        <img src="images/team/2.jpg" alt="Maja Kovač">
                        <h4>Maja Kovač</h4>
                        <p>Vodja prodaje</p>
                    </div>
                </div>
            </div>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-year">2005</div>
                    <div class="timeline-content">
                        <h3>Začetki podjetja</h3>
                        <p>Prva maloprodajna lokacija v Ljubljani s 3 zaposlenimi</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-year">2012</div>
                    <div class="timeline-content">
                        <h3>Širitev ponudbe</h3>
                        <p>Začetek sodelovanja z vodilnimi svetovnimi znamkami</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-year">2020</div>
                    <div class="timeline-content">
                        <h3>Digitalna preobrazba</h3>
                        <p>Lansiranje spletne trgovine in mobilne aplikacije</p>
                    </div>
                </div>
            </div>
            
            
        </section>
    </main>

    <?php include 'partials/footer.php'; ?>
</body>
</html>