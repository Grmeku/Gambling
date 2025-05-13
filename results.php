<?php
session_start();

if (!isset($_SESSION['users'])) {
    header("Location: index.php");
    exit;
}

$users = $_SESSION['users'];
$totalScores = $_SESSION['total_scores'] ?? [0, 0, 0];

// Prepare players with scores
$players = [];
foreach ($users as $i => $user) {
    $players[] = [
        'ime' => $user['ime'],
        'score' => $totalScores[$i]
    ];
}

// Sort by score descending
usort($players, function($a, $b) {
    return $b['score'] <=> $a['score'];
});

// Destroy session now that results are shown
session_destroy();
?>

<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Rezultati</title>
    <link rel="stylesheet" href="result.css">
    <link rel="icon" href="img/poker-chip-svgrepo-com.svg" type="image/svg+xml">
    <style>
        body {
            background: url('img/background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Courier New', monospace;
            text-align: center;
            color: white;
        }

        h1 {
            margin-top: 40px;
            font-size: 60px;
            color: gold;
        }

        .podium {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            margin-top: 60px;
            gap: 40px;
        }

        .place {
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            padding: 10px;
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.7);
            width: 150px;
            font-size: 18px;
            font-weight: bold;
        }

        .first { height: 220px; background-color: gold; color: black; }
        .second { height: 160px; background-color: silver; color: black; }
        .third { height: 120px; background-color: #cd7f32; color: black; }

        .place span.score {
            margin-top: 10px;
            font-size: 16px;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        window.onload = function () {
            confetti({
                particleCount: 150,
                spread: 80,
                origin: { y: 0.6 }
            });

            setTimeout(() => {
                window.location.href = "index.php";
            }, 10000);
        };
    </script>
</head>
<body>
    <h1>PODIJ</h1>

    <div class="podium">
        <div class="place second">
            🥈 <?= htmlspecialchars($players[1]['ime']) ?><br>
            <span class="score"><?= $players[1]['score'] ?> točk</span>
        </div>

        <div class="place first">
            🥇 <?= htmlspecialchars($players[0]['ime']) ?><br>
            <span class="score"><?= $players[0]['score'] ?> točk</span>
        </div>

        <div class="place third">
            🥉 <?= htmlspecialchars($players[2]['ime']) ?><br>
            <span class="score"><?= $players[2]['score'] ?> točk</span>
        </div>
    </div>

    <p style="margin-top: 50px;">(Preusmeritev na začetek v 10 sekundah...)</p>
</body>
</html>
