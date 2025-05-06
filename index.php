<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['users'] = [
        ['ime' => $_POST['ime1']],
        ['ime' => $_POST['ime2']],
        ['ime' => $_POST['ime3']]
    ];
    $_SESSION['num_dice'] = $_POST['num_dice'];
    $_SESSION['num_games'] = $_POST['num_games'];
    header("Location: rezultat.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Gambling</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/svg+xml" href="img/poker-chip-svgrepo-com.svg">
</head>
<body>
    <h1>GAMBLING</h1>
    <form method="POST" action="">
        <div class="container">
            <div class="user-group">
                <div class="user-input">
                    <h3>UPORABNIK 1</h3>
                    <label>Ime:</label>
                    <input type="text" name="ime1" required>
                </div>
                <div class="user-input">
                    <h3>UPORABNIK 2</h3>
                    <label>Ime:</label>
                    <input type="text" name="ime2" required>
                </div>
                <div class="user-input">
                    <h3>UPORABNIK 3</h3>
                    <label>Ime:</label>
                    <input type="text" name="ime3" required>
                </div>
            </div>

            <div class="dice-settings">
                <label>Število kock:
                    <select name="num_dice">
                        <?php for ($i = 1; $i <= 3; $i++) echo "<option>$i</option>"; ?>
                    </select>
                </label>
                &nbsp;&nbsp;&nbsp;
                <label>Število iger:
                    <select name="num_games">
                        <?php for ($i = 1; $i <= 5; $i++) echo "<option>$i</option>"; ?>
                    </select>
                </label>
            </div>

            <button class="play-btn" type="submit">Igraj</button>
        </div>
    </form>
</body>
</html>
