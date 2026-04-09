

<?php
session_start();
include 'db.php';

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("SELECT * FROM voters WHERE student_id = ?");
    $stmt->bind_param("s", $_POST['student_id']);
    $stmt->execute();
    $voter = $stmt->get_result()->fetch_assoc();

    if (!$voter) $error = "Voter not found";
    elseif ($_POST['password'] !== $voter['password']) $error = "Invalid password";
    elseif (!$voter['approved']) $error = "Account not approved yet";
    else {
        $_SESSION['voter_id'] = $voter['student_id'];
        $_SESSION['voter_name'] = $voter['name'];
        header("Location: voter_dashboard.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Voter Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --primary: #4361ee; --error: #e63946; }
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
            opacity: 0.7;
        }
        
        .login-form {
            background: rgba(255, 255, 255, 0.95); 
            padding: 2.5rem; 
            border-radius: 12px; 
            box-shadow: 0 15px 30px rgba(0,0,0,0.15); 
            width: 350px; 
            text-align: center;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .login-form:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
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
            letter-spacing: 0.5px;
        }
        
        input { 
            width: 100%; 
            padding: 12px; 
            margin-bottom: 1rem; 
            border: 1px solid #ddd; 
            border-radius: 6px; 
            font-size: 1rem;
            transition: all 0.3s;
            background: rgba(255,255,255,0.8);
        }
        
        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            outline: none;
            background: white;
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
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        button:hover { 
            opacity: 1; 
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        }
        
        button:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
            z-index: -1;
        }
        
        button:hover:before {
            left: 100%;
        }
        
        .error-msg { 
            color: var(--error); 
            background: #ffebee; 
            padding: 10px; 
            border-radius: 5px; 
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
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .back-link:hover { 
            color: var(--primary); 
            text-decoration: underline;
            transform: translateX(-3px);
        }
    </style>
</head>
<body>
    <div id="particles-js"></div>
    
    <form class="login-form" method="POST">
        <i class="bi bi-shield-lock security-icon"></i>
        <h2>Voter Login</h2>
        <?php if (isset($error) && $error): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>
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
                        "value": 80,
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
                            "enable": true,
                            "speed": 1,
                            "opacity_min": 0.1,
                            "sync": false
                        }
                    },
                    "size": {
                        "value": 3,
                        "random": true,
                        "anim": {
                            "enable": true,
                            "speed": 2,
                            "size_min": 1,
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
                        "speed": 1.5,
                        "direction": "none",
                        "random": true,
                        "straight": false,
                        "out_mode": "out",
                        "bounce": false,
                        "attract": {
                            "enable": true,
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
                            "mode": "repulse"
                        },
                        "onclick": {
                            "enable": true,
                            "mode": "push"
                        },
                        "resize": true
                    },
                    "modes": {
                        "repulse": {
                            "distance": 100,
                            "duration": 0.4
                        },
                        "push": {
                            "particles_nb": 4
                        }
                    }
                },
                "retina_detect": true
            });
        });
    </script>
</body>
</html>