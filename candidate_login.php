

<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $student_id = $conn->real_escape_string(trim($_POST['student_id']));
    $password = trim($_POST['password']); // Don't escape passwords before verification

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT student_id, name, password FROM candidates WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $candidate = $result->fetch_assoc();
        
        // Verify password - make sure passwords are stored using password_hash()
        if (password_verify($password, $candidate['password'])) {
            // Password is correct, set session variables
            $_SESSION['candidate_id'] = $candidate['student_id'];
            $_SESSION['candidate_name'] = $candidate['name'];
            header("Location: candidate_dashboard.php");
            exit();
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "Candidate not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Candidate Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --primary: #3f51b5; --error: #f44336; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, rgb(29, 152, 245) 0%, rgb(125, 185, 234) 100%);
            display: grid; 
            place-items: center; 
            min-height: 100vh; 
            margin: 0;
            overflow: hidden;
            position: relative;
        }
        
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
        }
        
        .login-form {
            background: rgba(255, 255, 255, 0.95); 
            padding: 2.5rem; 
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
            width: 350px; 
            text-align: center;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: transform 0.3s ease;
        }
        
        .login-form:hover {
            transform: translateY(-5px);
        }
        
        .security-icon { 
            font-size: 3rem; 
            color: var(--primary); 
            margin-bottom: 1rem; 
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        h2 { 
            margin: 0 0 1.5rem; 
            color: #333; 
            font-weight: 600;
        }
        
        input {
            width: 100%; 
            padding: 12px; 
            margin-bottom: 1rem;
            border: 1px solid #ddd; 
            border-radius: 6px; 
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(63, 81, 181, 0.2);
            outline: none;
        }
        
        button {
            width: 100%; 
            padding: 12px; 
            background: var(--primary);
            color: white; 
            border: none; 
            border-radius: 6px; 
            font-size: 1rem;
            cursor: pointer; 
            margin: 1rem 0; 
            transition: all 0.3s;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        
        button:hover { 
            background: #303f9f; 
            transform: translateY(-2px); 
            box-shadow: 0 5px 15px rgba(63, 81, 181, 0.4);
        }
        
        .error-msg {
            color: white; 
            background: var(--error); 
            padding: 12px;
            border-radius: 6px; 
            margin-bottom: 1rem;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        
        .back-link {
            color: #666; 
            text-decoration: none; 
            display: block;
            margin-top: 1rem; 
            transition: color 0.3s;
            font-weight: 500;
        }
        
        .back-link:hover { 
            color: var(--primary); 
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div id="particles-js"></div>
    
    <form class="login-form" method="POST">
        <i class="bi bi-person-badge security-icon"></i>
        <h2>Candidate Login</h2>
        <?php if (isset($error)): ?><div class="error-msg"><?= $error ?></div><?php endif; ?>
        <input type="text" name="student_id" placeholder="Student ID" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <a href="index.php" class="back-link">← Back to Home</a>
    </form>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        // Initialize particles.js
        document.addEventListener('DOMContentLoaded', function() {
            particlesJS('particles-js', {
                "particles": {
                    "number": {
                        "value": 60,
                        "density": {
                            "enable": true,
                            "value_area": 800
                        }
                    },
                    "color": {
                        "value": "#ffffff"
                    },
                    "shape": {
                        "type": "circle",
                        "stroke": {
                            "width": 0,
                            "color": "#000000"
                        }
                    },
                    "opacity": {
                        "value": 0.5,
                        "random": true,
                        "anim": {
                            "enable": false,
                            "speed": 1,
                            "opacity_min": 0.1,
                            "sync": false
                        }
                    },
                    "size": {
                        "value": 3,
                        "random": true,
                        "anim": {
                            "enable": false,
                            "speed": 40,
                            "size_min": 0.1,
                            "sync": false
                        }
                    },
                    "line_linked": {
                        "enable": true,
                        "distance": 150,
                        "color": "#ffffff",
                        "opacity": 0.3,
                        "width": 1
                    },
                    "move": {
                        "enable": true,
                        "speed": 2,
                        "direction": "none",
                        "random": false,
                        "straight": false,
                        "out_mode": "out",
                        "bounce": false,
                        "attract": {
                            "enable": false,
                            "rotateX": 600,
                            "rotateY": 1200
                        }
                    }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": {
                        "onhover": {
                            "enable": true,
                            "mode": "grab"
                        },
                        "onclick": {
                            "enable": true,
                            "mode": "push"
                        },
                        "resize": true
                    },
                    "modes": {
                        "grab": {
                            "distance": 140,
                            "line_linked": {
                                "opacity": 0.8
                            }
                        },
                        "push": {
                            "particles_nb": 3
                        }
                    }
                },
                "retina_detect": true
            });
        });
    </script>
</body>
</html>