
<?php
include 'db.php';

$positions = $conn->query("SELECT DISTINCT position FROM candidates ORDER BY position")->fetch_all(MYSQLI_ASSOC);
$winners = [];

foreach ($positions as $pos) {
    $winner = $conn->query("SELECT * FROM candidates WHERE position = '{$pos['position']}' ORDER BY votes DESC LIMIT 1")->fetch_assoc();
    if ($winner) $winners[] = $winner;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4e73df;
            --success: #1cc88a;
            --gold: #ffd700;
        }
        body {
            background: linear-gradient(135deg, #f8f9fc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }
        .results-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1);
        }
        .winner-card {
            background: linear-gradient(135deg, var(--gold) 0%, #ffc107 100%);
            border-radius: 0.5rem;
        }
        .position-card {
            border-left: 4px solid var(--primary);
            transition: transform 0.3s;
        }
        .position-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="py-4">
    <div class="container">
        <div class="results-card mx-auto overflow-hidden">
            <div class="p-4 text-center bg-primary text-white">
                <h1><i class="bi bi-trophy"></i> Election Results</h1>
            </div>
            
            <?php if (!empty($winners)): ?>
            <div class="p-4 bg-light">
                <h3 class="text-center mb-4"><i class="bi bi-stars"></i> Congratulations Winners!</h3>
                <div class="row g-3">
                    <?php foreach ($winners as $w): ?>
                    <div class="col-md-4">
                        <div class="winner-card p-3 text-center shadow">
                            <h5 class="mb-1"><?= htmlspecialchars($w['position']) ?></h5>
                            <h4 class="mb-1">🏆 <?= htmlspecialchars($w['name']) ?></h4>
                            <div class="text-dark fw-bold"><?= $w['votes'] ?> votes</div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="p-4">
                <a href="admin_dashboard.php" class="btn btn-outline-primary mb-4">
                    <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
                </a>

                <?php if (empty($positions)): ?>
                    <div class="alert alert-info">No election data found</div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($positions as $pos): 
                            $candidates = $conn->query("SELECT * FROM candidates WHERE position = '{$pos['position']}' ORDER BY votes DESC")->fetch_all(MYSQLI_ASSOC);
                        ?>
                        <div class="col-md-6">
                            <div class="position-card p-3 bg-white rounded shadow-sm">
                                <h4 class="text-primary mb-3"><?= htmlspecialchars($pos['position']) ?></h4>
                                <div class="list-group">
                                    <?php foreach ($candidates as $c): ?>
                                    <div class="list-group-item d-flex justify-content-between">
                                        <?= htmlspecialchars($c['name']) ?>
                                        <span class="badge bg-primary rounded-pill"><?= $c['votes'] ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>