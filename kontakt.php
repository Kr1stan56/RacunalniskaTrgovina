
<?php
require_once 'baza.php';
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <?php include 'partials/header.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakt | Računalniška Trgovina</title>
    <link rel="stylesheet" href="css/style3.css">
</head>
<body>
    <main class="kontext">
        <section class="">
            <h1 class="glavni-naslovi">Kontaktirajte nas</h1>
            
            <div class="">
                <div class="kontakti">
                    <div class="">
                        <i class=""></i>
                        <h3>iz baze</h3>
                        <p>Tržaška cesta 123<br>1000 Ljubljana</p>
                    </div>
                    
                    <div class="info-box">
                        <i class="fas fa-phone"></i>
                        <h3>Telefon</h3>
                        <p>+386 40 123 456<br>+386 1 234 56 78</p>
                    </div>
                    
                    <div class="info-box">
                        <i class="fas fa-envelope"></i>
                        <h3>Email</h3>
                        <p>info@racunalniskatrgovina.si<br>podpora@racunalniskatrgovina.si</p>
                    </div>
                </div>
                
                <form class="form" action="pošlji.php" method="POST">
                    <div class="form">
                        <input type="text" name="ime" placeholder="Ime" required>
                        <input type="text" name="priimek" placeholder="Priimek" required>
                    </div>
                    
                    <div class="form">
                        <input type="email" name="email" placeholder="E-pošta" required>
                    </div>
                    
                    <div class="form">
                        <select name="vrsta_vprasanja" id="vrsta-vprasanja" required>
                            <option value="">Izberite vrsto vprašanja</option>
                            <option value="splosno">Splošno vprašanje</option>
                            <option value="vracilo">Vračilo blaga</option>
                            <option value="tehnicno">Tehnična podpora</option>
                            <option value="narocilo">Status naročila</option>
                        </select>
                    </div>
                    
                    <div class="form">
                        <textarea name="sporocilo" placeholder="Vaše sporočilo..." rows="5" required></textarea>
                    </div>
                    
                    <div class="form">
                        <label>
                            <input type="checkbox" name="newsletter"> Želim prejemati e-novice
                        </label>
                        <label>
                            <input type="checkbox" name="kopija"> Pošlji kopijo na moj e-mail
                        </label>
                    </div>
                    
                    <button type="submit" class="glavni">Pošlji sporočilo</button>
                </form>
            </div>
        </section>
    </main>

    <?php include 'partials/footer.php'; ?>
</body>
</html>