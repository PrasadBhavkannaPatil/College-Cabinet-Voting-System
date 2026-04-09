
<?php
session_start();
require 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['voter_id'])) {
    header("Location: voter_login.php");
    exit();
}

// Initialize variables
$student_id = $_SESSION['voter_id'];
$voter_name = $_SESSION['voter_name'];
$message = $voted_candidate = null;

// Check voting status
$stmt = $conn->prepare("SELECT candidate_id FROM votes WHERE student_id = ?");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$voted_candidate = $stmt->get_result()->fetch_assoc()['candidate_id'] ?? null;

// Handle vote submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['candidate']) && !$voted_candidate) {
    $selected_candidate = $_POST['candidate'];
    
    $conn->begin_transaction();
    try {
        $conn->query("INSERT INTO votes (student_id, candidate_id) VALUES ('$student_id', '$selected_candidate')");
        $conn->query("UPDATE candidates SET votes = votes + 1 WHERE student_id = '$selected_candidate'");
        $conn->commit();
        $voted_candidate = $selected_candidate;
        $message = ['type' => 'success', 'text' => 'Vote cast successfully!'];
    } catch (Exception $e) {
        $conn->rollback();
        $message = ['type' => 'danger', 'text' => 'Failed to cast vote'];
    }
}

// Get candidates and winner
$candidates = $conn->query("SELECT * FROM candidates ORDER BY position, name");
$winner = $conn->query("SELECT name FROM candidates ORDER BY votes DESC LIMIT 1")->fetch_assoc();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #f8f9fc;
            --success: #1cc88a;
            --warning: #f6c23e;
        }
        body {
            background: linear-gradient(135deg, #f8f9fc 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .dashboard-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            max-width: 800px;
        }
        .voter-header {
            background: var(--primary);
            color: white;
            padding: 1.5rem;
        }
        .candidate-card {
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
            transition: all 0.3s;
            height: 100%;
        }
        .candidate-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        .candidate-info {
            padding-left: 1.5rem;
        }
        .winner-badge {
            background: var(--warning);
            color: #000;
        }
        .candidate-meta {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .candidate-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e3e6f0;
        }
        .img-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #e3e6f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            border: 2px solid #e3e6f0;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="dashboard-card mx-auto">
            <div class="voter-header text-center">
                <h2><i class="bi bi-person-badge"></i> Voter Dashboard</h2>
                <p class="mb-0">Welcome, <strong><?= htmlspecialchars($voter_name) ?></strong></p>
            </div>

            <div class="p-4">
                <?php if ($message): ?>
                    <div class="alert alert-<?= $message['type'] ?> d-flex align-items-center">
                        <i class="bi bi-<?= $message['type'] == 'success' ? 'check' : 'x' ?>-circle-fill me-2"></i>
                        <?= $message['text'] ?>
                    </div>
                <?php endif; ?>

                <?php if ($voted_candidate): ?>
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        You've voted for candidate ID: <strong><?= htmlspecialchars($voted_candidate) ?></strong>
                    </div>
                <?php else: ?>
                    <form method="POST" class="mb-4">
                        <h5 class="mb-3"><i class="bi bi-megaphone"></i> Cast Your Vote</h5>
                        <div class="row g-3">
                            <?php while ($candidate = $candidates->fetch_assoc()): ?>
                                <div class="col-md-6">
                                    <div class="candidate-card p-3 d-flex">
                                        <div class="form-check my-auto">
                                            <input class="form-check-input" type="radio" name="candidate" 
                                                   id="candidate<?= $candidate['student_id'] ?>" 
                                                   value="<?= $candidate['student_id'] ?>" required>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($candidate['image_path'])): ?>
                                                <img src="<?= htmlspecialchars($candidate['image_path']) ?>" 
                                                     class="candidate-img me-3" 
                                                     alt="<?= htmlspecialchars($candidate['name']) ?>">
                                            <?php else: ?>
                                                <div class="img-placeholder me-3">
                                                    <i class="bi bi-person" style="font-size: 1.5rem;"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div class="candidate-info">
                                                <label class="form-check-label" for="candidate<?= $candidate['student_id'] ?>">
                                                    <strong><?= htmlspecialchars($candidate['name']) ?></strong>
                                                    <span class="d-block"><?= htmlspecialchars($candidate['position']) ?></span>
                                                    <div class="candidate-meta mt-1">
                                                        <span class="d-block">
                                                            <i class="bi bi-book"></i> Semester: <?= htmlspecialchars($candidate['semester']) ?>
                                                        </span>
                                                        <span class="d-block">
                                                            <i class="bi bi-people"></i> Division: <?= htmlspecialchars($candidate['division']) ?>
                                                        </span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3 w-100">
                            <i class="bi bi-envelope-paper-heart"></i> Submit Vote
                        </button>
                    </form>
                <?php endif; ?>

                <a href="logout.php" class="btn btn-outline-danger w-100 mt-3">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>