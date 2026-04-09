
<?php
include 'db.php';

$message = ['type' => '', 'text' => ''];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = trim($_POST['student_id']);
    $name = trim($_POST['name']);
    $password = trim($_POST['password']);

    $check = $conn->prepare("SELECT * FROM voters WHERE student_id = ?");
    $check->bind_param("s", $student_id);
    $check->execute();
    
    if ($check->get_result()->num_rows > 0) {
        $message = ['type' => 'error', 'text' => 'Student ID already registered'];
    } else {
        $stmt = $conn->prepare("INSERT INTO voters (student_id, name, password, approved) VALUES (?, ?, ?, 0)");
        $stmt->bind_param("sss", $student_id, $name, $password);
        if ($stmt->execute()) {
            $message = ['type' => 'success', 'text' => 'Registration successful. Awaiting admin approval.'];
        } else {
            $message = ['type' => 'error', 'text' => 'Registration failed. Please try again.'];
        }
        $stmt->close();
    }

    $check->close();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Voter Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --primary: #4361ee; --success: #4caf50; --error: #f44336; }
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
            opacity: 0.8;
        }
        
        .reg-form {
            background: rgba(255, 255, 255, 0.96); 
            padding: 2.5rem; 
            border-radius: 12px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.15); 
            width: 350px; 
            text-align: center;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            transition: all 0.4s ease;
        }
        
        .reg-form:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .security-icon { 
            font-size: 3rem; 
            color: var(--primary); 
            margin-bottom: 1rem;
            animation: pulse 2s infinite ease-in-out;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
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
            letter-spacing: 1px;
            text-transform: uppercase;
            position: relative;
            overflow: hidden;
        }
        
        button:hover { 
            opacity: 1; 
            transform: translateY(-2px); 
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        }
        
        button:after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }
        
        button:focus:after {
            animation: ripple 1s ease-out;
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
        }
        
        .message {
            padding: 12px; 
            border-radius: 6px; 
            margin-bottom: 1rem;
            background: var(--<?= $message['type'] ?? '' ?>); 
            color: white;
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
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
    
    <form class="reg-form" method="POST">
        <i class="bi bi-person-plus security-icon"></i>
        <h2>Voter Registration</h2>
        <?php if (isset($message['text']) && $message['text']): ?>
            <div class="message"><?= $message['text'] ?></div>
        <?php endif; ?>
        <input type="text" name="student_id" placeholder="Student ID" required>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
        <a href="index.php" class="back-link">← Back to Home</a>
    </form>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        // Initialize particles.js
        document.addEventListener('DOMContentLoaded', function() {
            particlesJS('particles-js', {
                "particles": {
                    "number": {
                        "value": 70,
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
                            "mode": "bubble"
                        },
                        "onclick": {
                            "enable": true,
                            "mode": "push"
                        },
                        "resize": true
                    },
                    "modes": {
                        "bubble": {
                            "distance": 200,
                            "size": 6,
                            "duration": 2,
                            "opacity": 0.8,
                            "speed": 3
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