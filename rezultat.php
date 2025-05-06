<?php
session_start();

if (!isset($_SESSION['users']) || !isset($_SESSION['num_dice']) || !isset($_SESSION['num_games'])) {
    header("Location: index.php");
    exit;
}

$users = $_SESSION['users'];
$numDice = $_SESSION['num_dice'];
$numGames = $_SESSION['num_games'];

$results = [];
$sums = [];

foreach ($users as $index => $user) {
    $dice = [];
    for ($i = 0; $i < $numDice; $i++) {
        $roll = rand(1, 6);
        $dice[] = $roll;
    }
    $results[$index] = $dice;
    $sums[$index] = array_sum($dice);
}

$max = max($sums);
$winners = array_keys($sums, $max);
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Rezultati</title>
    <link rel="stylesheet" href="rezultat.css">
    <link rel="icon" type="image/svg+xml" href="img/poker-chip-svgrepo-com.svg">
    <script>
        // Show final dice values after 2 seconds
        setTimeout(() => {
            document.querySelectorAll(".dice-result").forEach((container, index) => {
                const dice = JSON.parse(container.getAttribute('data-dice'));
                container.innerHTML = "";
                dice.forEach(roll => {
                    const img = document.createElement('img');
                    img.src = `img/dice${roll}.gif`;
                    img.alt = `Kocka ${roll}`;
                    container.appendChild(img);
                });
            });
            document.querySelectorAll(".sum").forEach(el => el.style.display = 'block');
        }, 2000);

        // Redirect back after 10 seconds
        setTimeout(() => {
            window.location.href = "index.php";
        }, 10000);
    </script>
</head>
<body>
    <h1>GAMBLING</h1>
    <div class="container">
        <div class="result-card">
            <div class="game-box">
                <?php foreach ($users as $i => $user): ?>
                    <div class="player-box">
                        <div class="player-name"><?= htmlspecialchars($user['ime']) ?></div>
                        <div class="dice-result" data-dice='<?= json_encode($results[$i]) ?>'>
                            <?php for ($j = 0; $j < $numDice; $j++): ?>
                                <img src="img/dice-anim.gif" alt="Kocke" />
                            <?php endfor; ?>
                        </div>
                        <div class="sum" style="display: none;">Seštevek kock: <?= $sums[$i] ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="info-box">
                Številka igre: 1/<?= $numGames ?><br><br>
                <button class="play-btn">Rezultati</button>
            </div>
        </div>
    </div>
</body>
</html>

<?php session_destroy(); ?>
