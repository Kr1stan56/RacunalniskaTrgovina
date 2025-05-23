<?php
require_once 'baza.php';
require_once 'session.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subm'])) {
    try {
        $email = $_POST['mail'];
        $geslo = $_POST['geslo'];
        
        // Preveri prazne vrednosti
        if (empty($email) || empty($geslo)) {
            throw new Exception("Prosimo vnesite e-pošto in geslo");
        }

        // Pripravi poizvedbo
        $stmt = $conn->prepare("SELECT * FROM uporabniki WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
$query = "DELETE FROM predmeti WHERE id_p = '$id'";
$result = mysqli_query($link,$query);
        if ($user) {
            if (sha1($geslo) === $user['geslo']) {
                $_SESSION['name'] = $user['ime'];
                $_SESSION['surname'] = $user['priimek'];
                $_SESSION['idu'] = $user['id_l'];
                $_SESSION['log'] = true;
                
                $success = "Uspešna prijava! Pozdravljeni, " . $_SESSION['name'] . " " . $_SESSION['surname'];
                
                // Preusmeritev po 2 sekundah
                //header("Refresh: 2; URL=index.php");
            } else {
                throw new Exception("Napačno geslo");
            }
        } else {
            throw new Exception("Uporabnik s tem e-poštnim naslovom ne obstaja");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <?php include 'partials/header.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava | Računalniška Trgovina</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="login-page">
    <main class="login-container">
        <div class="login-box">
            <h1><i class=""></i> Prijava</h1>
            
            <?php if ($error): ?>
                <div class="error">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success">
                    <i class="succes"></i> <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="login.php">
                <div class="">
                    <label for="mail"><i class=""></i> E-pošta:</label>
                    <input type="email" id="mail" name="mail" required>
                </div>
                
                <div class="form-group">
                    <label for="geslo"><i class=""></i> Geslo:</label>
                    <input type="password" id="geslo" name="geslo" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" name="subm" class="btn-login">
                        <i class=""></i> Prijavi se
                    </button>
                </div>
            </form>
            
            <div class="">
                <a href="register.php"><i class=""></i> Še nimate računa? Registrirajte se</a>
                <a href="password_reset.php"><i class=""></i> Pozabljeno geslo?</a>
            </div>
        </div>
    </main>
</body>
</html>