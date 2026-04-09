


<?php
session_start();
if (!isset($_SESSION['admin_name'])) header("Location: admin_login.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body { background: #f8f9fa; }
    .dashboard-card { transition: all 0.3s; border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    .dashboard-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .card-icon { font-size: 2.5rem; background: linear-gradient(135deg, #4361ee, #3a0ca3); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">College Voting System</a>
      <div class="d-flex align-items-center">
        <span class="text-white me-3">Welcome, <?= $_SESSION['admin_name'] ?></span><br/><br/>
        <a href="logout.php" class="btn btn-outline-light" color:red><i class="bi bi-box-arrow-right"></i> Logout</a>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="dashboard-card card text-center p-4">
          <i class="card-icon bi bi-person-badge-fill mb-3"></i>
          <h3>Candidate Manage</h3>
          <a href="candidate_manage.php" class="btn btn-primary mt-2">Go to Panel</a>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="dashboard-card card text-center p-4">
          <i class="card-icon bi bi-people-fill mb-3"></i>
          <h3>Voter Manage</h3>
          <a href="voter_manage.php" class="btn btn-primary mt-2">Go to Panel</a>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="dashboard-card card text-center p-4">
          <i class="card-icon bi bi-bar-chart-line-fill mb-3"></i>
          <h3>Results</h3>
          <a href="results.php" class="btn btn-primary mt-2">Go to Panel</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>