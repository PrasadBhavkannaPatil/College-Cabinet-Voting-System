<?php
session_start();
include 'db.php';

// Redirect if not logged in as admin
if (!isset($_SESSION['admin_name'])) {
    header("Location: admin_login.php");
    exit();
}

$message = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    // Delete voter
    $student_id = $conn->real_escape_string($_GET['delete']);
    $result = $conn->query("DELETE FROM voters WHERE student_id = '$student_id'");
    if ($result) {
        $message = "Voter deleted successfully.";
    } else {
        $message = "Error deleting voter: " . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_selected'])) {
    // Approve selected voters
    if (!empty($_POST['approve'])) {
        $selected = array_map(function($id) use ($conn) {
            return "'" . $conn->real_escape_string($id) . "'";
        }, $_POST['approve']);
        
        $ids = implode(',', $selected);
        $result = $conn->query("UPDATE voters SET approved = 1 WHERE student_id IN ($ids) AND approved = 0");
        
        if ($result) {
            $count = $conn->affected_rows;
            $message = "$count voter(s) approved successfully.";
        } else {
            $message = "Error approving voters: " . $conn->error;
        }
    } else {
        $message = "No voters selected for approval.";
    }
}

// Fetch all voters
$voters = $conn->query("SELECT * FROM voters ORDER BY approved ASC, name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Voters</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #4e73df;
            --success: #1cc88a;
            --danger: #e74a3b;
            --warning: #f6c23e;
        }
        .management-card {
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
        .header-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        .btn-dashboard {
            background: lightgrey;
            border: 1px solid #ddd;
        }
        .btn-dashboard:hover {
            background: #f8f9fa;
        }
        .btn-logout {
            background: var(--danger);
            color: white;
        }
        .btn-logout:hover {
            background: #c82333;
            color: white;
        }
        .status-approved {
            color: var(--success);
            font-weight: 500;
        }
        .status-pending {
            color: var(--warning);
            font-weight: 500;
        }
        .badge-approved {
            background-color: var(--success);
        }
        .badge-pending {
            background-color: var(--warning);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="header-actions">
            <a href="admin_dashboard.php" class="btn btn-dashboard"><i class="bi bi-arrow-left-circle me-1"></i>Dashboard</a>
            <a href="logout.php" class="btn btn-logout"><i class="bi bi-box-arrow-right me-1"></i>Logout</a>
        </div>

        <div class="management-card card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h3 class="card-title mb-0"><i class="bi bi-people-fill me-2"></i>Voter Management</h3>
            </div>
            <div class="card-body">
                <?php if ($message): ?>
                    <div class="alert <?= strpos($message, 'Error') !== false ? 'alert-danger' : 'alert-success' ?> alert-dismissible fade show">
                        <?= htmlspecialchars($message) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" id="voterForm">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%"><input type="checkbox" id="selectAll"></th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Password</th>
                                    <th>Status</th>
                                    <th width="5%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($voters->num_rows > 0): ?>
                                    <?php while ($voter = $voters->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <?php if (!$voter['approved']): ?>
                                                    <input type="checkbox" name="approve[]" value="<?= htmlspecialchars($voter['student_id']) ?>" class="voter-checkbox">
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($voter['student_id']) ?></td>
                                            <td><?= htmlspecialchars($voter['name']) ?></td>
                                            <td>••••••</td>
                                            <td>
                                                <span class="badge rounded-pill <?= $voter['approved'] ? 'badge-approved' : 'badge-pending' ?>">
                                                    <?= $voter['approved'] ? 'Approved' : 'Pending' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="?delete=<?= urlencode($voter['student_id']) ?>" class="text-danger action-btn" onclick="return confirm('Are you sure you want to delete this voter?')">
                                                    <i class="bi bi-trash-fill"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No voters found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if ($voters->num_rows > 0): ?>
                        <button type="submit" name="approve_selected" class="btn btn-success mt-3" id="approveBtn">
                            <i class="bi bi-check-circle-fill me-1"></i>Approve Selected
                        </button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all checkboxes
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.voter-checkbox');
            const approveBtn = document.getElementById('approveBtn');
            
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
                toggleApproveButton();
            });
            
            // Toggle checkboxes individually
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (!this.checked) {
                        selectAll.checked = false;
                    } else {
                        // Check if all checkboxes are checked
                        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                        selectAll.checked = allChecked;
                    }
                    toggleApproveButton();
                });
            });
            
            // Enable/disable approve button based on selections
            function toggleApproveButton() {
                if (approveBtn) {
                    const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                    approveBtn.disabled = !anyChecked;
                }
            }
            
            // Initialize button state
            toggleApproveButton();
        });
    </script>
</body>
</html>