<?php
session_start();

if (!isset($_SESSION['users'])) {
    header("Location: index.php");
    exit;
}

$users = $_SESSION['users'];
$numDice = $_SESSION['num_dice'];
$numGames = $_SESSION['num_games'];
$currentGame = $_SESSION['current_game'] ?? 1;
$totalScores = $_SESSION['total_scores'] ?? [0, 0, 0];
$roundResults = $_SESSION['round_results'] ?? [];

$isLastGame = $currentGame == $numGames;
$gameOver = $currentGame > $numGames;

$results = [];
$sums = [];

// ROLL DICE ONLY ON POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$gameOver) {
    foreach ($users as $i => $user) {
        $dice = [];
        for ($j = 0; $j < $numDice; $j++) {
            $dice[] = rand(1, 6);
        }
        $results[$i] = $dice;
        $sums[$i] = array_sum($dice);
        $totalScores[$i] += $sums[$i];
    }

    $_SESSION['total_scores'] = $totalScores;
    $roundResults[$currentGame] = [
        'rolls' => $results,
        'sums' => $sums
    ];
    $_SESSION['round_results'] = $roundResults;

    // Move to next round only after processing
    $_SESSION['current_game']++;
    header("Location: rezultat.php");
    exit;
}

// Load last stored round (the one just played)
$previousGame = $currentGame - 1;
$hasData = isset($roundResults[$previousGame]);

if ($hasData) {
    $results = $roundResults[$previousGame]['rolls'];
    $sums = $roundResults[$previousGame]['sums'];
}

$isFinalRoundDisplayed = $previousGame == $numGames;
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Rezultati</title>
    <link rel="stylesheet" href="result.css">
    <link rel="icon" href="img/poker-chip-svgrepo-com.svg" type="image/svg+xml">
    <script>
        const showWinner = <?= $isFinalRoundDisplayed ? 'true' : 'false' ?>;

        setTimeout(() => {
            document.querySelectorAll(".dice-result").forEach((el) => {
                const dice = JSON.parse(el.getAttribute("data-dice"));
                el.innerHTML = "";
                dice.forEach((num) => {
                    const img = document.createElement("img");
                    img.src = `img/dice${num}.gif`;
                    img.alt = `Kocka ${num}`;
                    el.appendChild(img);
                });
            });

            document.querySelectorAll(".sum").forEach(el => {
                el.style.display = "block";
            });

            if (showWinner) {
                document.getElementById("winner").style.display = "block";
                setTimeout(() => {
                    window.location.href = "index.php";
                }, 10000);
            } else {
                document.getElementById("next-btn").style.display = "inline-block";
            }
        }, 2000);
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
                        <?php if ($hasData && isset($results[$i])): ?>
                            <div class="dice-result" data-dice='<?= json_encode($results[$i]) ?>'>
                                <?php for ($j = 0; $j < $numDice; $j++): ?>
                                    <img src="img/dice-anim.gif" alt="Kocka">
                                <?php endfor; ?>
                            </div>
                            <div class="sum" style="display: none;">
                                Seštevek te igre: <?= $sums[$i] ?><br>
                                Skupaj: <?= $totalScores[$i] ?>
                            </div>
                        <?php else: ?>
                            <div class="sum">Čakamo na prvi met...</div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="info-box">
                <?php if (!$isFinalRoundDisplayed): ?>
                    Številka igre: <?= $previousGame ?>/<?= $numGames ?><br><br>
                    <form method="post">
                        <button type="submit" id="next-btn" class="play-btn" style="display: none;">Naslednja igra</button>
                    </form>
                <?php endif; ?>

                <div id="winner" style="display: none;">
                    <h2>Zmagovalec(i):</h2>
                    <?php
                        $max = max($totalScores);
                        foreach ($totalScores as $i => $score) {
                            if ($score === $max) {
                                echo "<p>" . htmlspecialchars($users[$i]['ime']) . " – {$score} točk</p>";
                            }
                        }
                    ?>
                    <p>(Preusmeritev v 10 sekundah...)</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Destroy session after final round shown
if ($isFinalRoundDisplayed) {
    session_destroy();
}
?>
