
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "voting_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
require_once 'C:/xampp/htdocs/college cabinet voting/db_config.php';

$message = "";
$target_dir = "uploads/"; // Directory where images will be stored

// Create uploads directory if it doesn't exist
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

// Handle form submission
if (isset($_POST['add'])) {
    // Validate and sanitize inputs
    $student_id = $conn->real_escape_string(trim($_POST['student_id']));
    $name = $conn->real_escape_string(trim($_POST['name']));
    $position = $conn->real_escape_string(trim($_POST['position']));
    $semester = $conn->real_escape_string(trim($_POST['semester']));
    $division = $conn->real_escape_string(trim($_POST['division']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $image_path = "";

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $message = "File is not an image.";
        }
        // Check file size (max 2MB)
        elseif ($_FILES["image"]["size"] > 2000000) {
            $message = "Sorry, your file is too large (max 2MB).";
        }
        // Allow certain file formats
        elseif (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
        // Try to upload file
        else {
            // Create unique filename to prevent overwriting
            $new_filename = uniqid() . '.' . $imageFileType;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            } else {
                $message = "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Validate required fields
    if (empty($student_id) || empty($name) || empty($position) || empty($semester) || empty($division)) {
        $message = "All fields except image are required!";
    } elseif (empty($message)) { // Only proceed if no errors so far
        // Check if student already exists using prepared statement
        $stmt = $conn->prepare("SELECT student_id FROM candidates WHERE student_id = ?");
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $message = "Candidate with this ID already exists!";
        } else {
            // Insert new candidate using prepared statement
            $insert = $conn->prepare("INSERT INTO candidates (student_id, name, position, semester, division, password, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insert->bind_param("sssssss", $student_id, $name, $position, $semester, $division, $password, $image_path);
            
            if ($insert->execute()) {
                $message = "Candidate added successfully!";
            } else {
                $message = "Error adding candidate: " . $conn->error;
            }
            $insert->close();
        }
        $stmt->close();
    }
}

// Handle delete action
if (isset($_GET['delete'])) {
    $student_id = $conn->real_escape_string($_GET['delete']);
    
    // First get image path to delete the file
    $stmt = $conn->prepare("SELECT image_path FROM candidates WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row && !empty($row['image_path']))
    {
        // Delete the image file
        if (file_exists($row['image_path'])) {
            unlink($row['image_path']);
        }
    }
    
    // Now delete the record
    $stmt = $conn->prepare("DELETE FROM candidates WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    
    if ($stmt->execute()) {
        $message = "Candidate deleted successfully!";
    } else {
        $message = "Error deleting candidate: " . $conn->error;
    }
    $stmt->close();
}

// Get all candidates
$candidates = $conn->query("SELECT * FROM candidates ORDER BY position");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Candidates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f5f5; }
        .card { 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
            margin-bottom: 20px;
        }
        .password-toggle { 
            cursor: pointer; 
            background-color: #f8f9fa;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .alert {
            margin-top: 20px;
        }
        .candidate-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
        .preview-image {
            max-width: 100px;
            max-height: 100px;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary">Manage Candidates</h2>
            <div>
                <a href="admin_dashboard.php" class="btn btn-outline-secondary">Dashboard</a>
                <a href="logout.php" class="btn btn-danger ms-2">Logout</a>
            </div>
        </div>

        <?php if (!empty($message)): ?>
        <div class="alert alert-<?= strpos($message, 'successfully') !== false ? 'success' : 'danger' ?> alert-dismissible fade show">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-5">
                <div class="card p-3">
                    <h5 class="card-title">Add New Candidate</h5>
                    <form method="POST" autocomplete="off" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input type="text" class="form-control" name="student_id" placeholder="Student ID" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="name" placeholder="Full Name" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="position" placeholder="Position" required>
                        </div>
                        <div class="mb-3">
                            <select class="form-select" name="semester" required>
                                <option value="" selected disabled>Select Semester</option>
                                <option value="FIRST SEM">FIRST SEM</option>
                                <option value="SECOND SEM">SECOND SEM</option>
                                <option value="THIRD SEM">THIRD SEM</option>
                                <option value="FOURTH SEM">FOURTH SEM</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <select class="form-select" name="division" required>
                                <option value="" selected disabled>Select Division</option>
                                <option value="A Div">A Div</option>
                                <option value="B Div">B Div</option>
                                <option value="C Div">C Div</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Candidate Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Optional (JPG, PNG, GIF - max 2MB)</small>
                            <img id="imagePreview" src="#" alt="Preview" class="preview-image">
                        </div>
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required minlength="6">
                                <button class="btn btn-outline-secondary password-toggle" type="button" onclick="togglePassword()">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Minimum 6 characters</small>
                        </div>
                        <button type="submit" name="add" class="btn btn-primary w-100">Add Candidate</button>
                    </form>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card p-3">
                    <h5 class="card-title">Current Candidates</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Image</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Semester</th>
                                    <th>Division</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $candidates->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($row['image_path'])): ?>
                                            <img src="<?= htmlspecialchars($row['image_path']) ?>" class="candidate-image" alt="<?= htmlspecialchars($row['name']) ?>">
                                        <?php else: ?>
                                            <div class="candidate-image bg-secondary text-white d-flex align-items-center justify-content-center">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['student_id']) ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['position']) ?></td>
                                    <td><?= htmlspecialchars($row['semester']) ?></td>
                                    <td><?= htmlspecialchars($row['division']) ?></td>
                                    <td>
                                        <a href="?delete=<?= urlencode($row['student_id']) ?>" class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Are you sure you want to delete this candidate?')">
                                           <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.querySelector('.password-toggle i');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                pwd.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        // Image preview functionality
        document.getElementById('image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imagePreview');
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });
    </script>
</body>
</html>