
<?php
session_start();
include 'db.php';

if (!isset($_SESSION['candidate_id'])) {
    header("Location: candidate_login.php");
    exit();
}

$candidate_id = $_SESSION['candidate_id'];
$candidate_name = $_SESSION['candidate_name'];

// Fetch all candidates and votes
$candidates_result = $conn->query("SELECT * FROM candidates ORDER BY position, votes DESC");

// Fetch own vote count
$own_result = $conn->prepare("SELECT votes FROM candidates WHERE student_id = ?");
$own_result->bind_param("s", $candidate_id);
$own_result->execute();
$own_votes = $own_result->get_result()->fetch_assoc()['votes'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Candidate Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding-top: 2rem;
        }
        .dashboard-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .vote-count {
            font-size: 1.5rem;
            font-weight: bold;
            color: #4e73df;
        }
        .position-header {
            color: #4e73df;
            border-bottom: 2px solid #eee;
            padding-bottom: 0.5rem;
            margin-top: 1.5rem;
        }
        .candidate-item {
            padding: 1rem;
            margin: 0.5rem 0;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .candidate-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }
        .logout-btn {
            background: #e74a3b;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
            transition: all 0.3s;
        }
        .logout-btn:hover {
            background: #be2617;
            transform: translateY(-2px);
        }
        .candidate-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #dee2e6;
            margin-right: 1rem;
        }
        .candidate-details {
            flex: 1;
        }
        .candidate-meta {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .vote-badge {
            min-width: 80px;
            text-align: center;
        }
        .img-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-person-badge me-2"></i>Welcome, <?php echo htmlspecialchars($candidate_name); ?></h2>
                <div class="vote-count">
                    <i class="bi bi-bar-chart-fill me-2"></i><?php echo $own_votes; ?> votes
                </div>
            </div>

            <h3 class="mb-4">Election Results</h3>
            
            <?php
            $current_position = "";
            while ($row = $candidates_result->fetch_assoc()) {
                if ($row['position'] !== $current_position) {
                    if ($current_position !== "") echo "</div>";
                    $current_position = $row['position'];
                    echo '<h5 class="position-header">'.htmlspecialchars($current_position).'</h5><div class="mb-4">';
                }
                echo '<div class="candidate-item d-flex align-items-center">
                        <div class="d-flex align-items-center">';
                
                // Display candidate image or placeholder
                if (!empty($row['image_path'])) {
                    echo '<img src="'.htmlspecialchars($row['image_path']).'" class="candidate-img" alt="'.htmlspecialchars($row['name']).'">';
                } else {
                    echo '<div class="img-placeholder">
                            <i class="bi bi-person" style="font-size: 1.5rem;"></i>
                          </div>';
                }
                
                echo '</div>
                      <div class="candidate-details">
                          <div class="d-flex justify-content-between align-items-center">
                              <div>
                                  <strong>'.htmlspecialchars($row['name']).'</strong> ('.htmlspecialchars($row['student_id']).')
                                  <div class="candidate-meta mt-1">
                                      <span class="d-block">
                                          <i class="bi bi-book me-1"></i>Semester: '.htmlspecialchars($row['semester']).'
                                      </span>
                                      <span class="d-block">
                                          <i class="bi bi-people me-1"></i>Division: '.htmlspecialchars($row['division']).'
                                      </span>
                                  </div>
                              </div>
                              <span class="badge bg-primary rounded-pill vote-badge">'.$row['votes'].' votes</span>
                          </div>
                      </div>
                    </div>';
            }
            if ($current_position !== "") echo "</div>";
            ?>

            <div class="text-center mt-4">
                <a href="logout.php" class="logout-btn"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>