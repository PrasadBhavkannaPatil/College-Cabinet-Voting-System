
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Cabinet Voting System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4cc9f0;
            --warning: #f72585;
            --card-bg: rgba(255, 255, 255, 0.9);
            --glass-effect: rgba(255, 255, 255, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #4361ee, #3f37c9, #4895ef);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            color: var(--dark);
            min-height: 100vh;
            overflow-x: hidden;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            padding: 5px 5%;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header .brand {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(to right, #ff8a00, #e52e71, #b24592, #f15f79, #ff8a00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            text-align: center;
            width: 100%;
            background-size: 300% 300%;
            animation: gradient 5s ease infinite;
        }

        @keyframes gradient {
             0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }




        .main-section {
            padding: 5% 5%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 80px);
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 3rem;
            color: white;
        }

        .welcome-text h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .welcome-text p {
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
            opacity: 0.9;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
            width: 100%;
            max-width: 1200px;
        }

        .card {
            background: var(--card-bg);
            padding: 2.5rem 2rem;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-align: center;
            backdrop-filter: blur(5px);
            border: 1px solid var(--glass-effect);
            position: relative;
            overflow: hidden;
            z-index: 1;
            flex: 1;
            min-width: 250px;
            max-width: 280px;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, var(--glass-effect), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
            z-index: -1;
        }

        .card:hover::before {
            transform: translateX(100%);
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .card i {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(to right, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: transform 0.3s ease;
        }

        .card:hover i {
            transform: scale(1.1);
        }

        .card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .card p {
            color: #666;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .card a {
            display: inline-block;
            padding: 0.8rem 2rem;
            background: linear-gradient(to right, var(--primary), var(--accent));
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        .card a:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
        }

        .footer {
            text-align: center;
            padding: 1.5rem;
            color: white;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            font-size: 0.9rem;
        }

        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }

        @media (max-width: 1200px) {
            .card {
                min-width: 220px;
            }
        }

        @media (max-width: 992px) {
            .card-container {
                flex-wrap: wrap;
            }
            .card {
                min-width: 200px;
                max-width: 45%;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                padding: 1.5rem;
            }

            .header .brand {
                margin-bottom: 1rem;
                font-size: 1.5rem;
            }

            .welcome-text h1 {
                font-size: 2rem;
            }

            .welcome-text p {
                font-size: 1rem;
            }

            .card {
                padding: 2rem 1.5rem;
                max-width: 100%;
                width: 100%;
            }
        }

        /* Animation classes */
        .fade-in {
            animation: fadeIn 1s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }
        .delay-4 { animation-delay: 0.8s; }
    </style>
</head>
<body>
    <div class="particles" id="particles-js"></div>

    <div class="header">
    <div class="brand"><a href="index.php">College Cabinet Voting System</a></div>
</div>

    <div class="main-section">
        <div class="welcome-text fade-in">
            <h1>Welcome to GSS BCA College Elections</h1>
            <p>Cast your vote for the future leaders of our college. Your voice matters in shaping our community.</p>
        </div>

        <div class="card-container">
            <div class="card fade-in delay-1">
                <i class="bi bi-shield-lock-fill"></i>
                <h3>Admin Portal</h3>
                <p>Manage elections, candidates, and voting process with administrative privileges.</p>
                <a href="admin_login.php">Admin Login</a>
            </div>
            
            <div class="card fade-in delay-2">
                <i class="bi bi-person-badge-fill"></i>
                <h3>Candidate Portal</h3>
                <p>Register as a candidate and manage your campaign profile.</p>
                <a href="candidate_login.php">Candidate Login</a>
            </div>
            
            <div class="card fade-in delay-3">
                <i class="bi bi-person-plus-fill"></i>
                <h3>Voter Registration</h3>
                <p>Register to participate in the upcoming elections as a voter.</p>
                <a href="voter_register.php">Register Now</a>
            </div>
            
            <div class="card fade-in delay-4">
                <i class="bi bi-box-arrow-in-right"></i>
                <h3>Voter Login</h3>
                <p>Already registered? Login to cast your vote.</p>
                <a href="voter_login.php">Vote Now</a>
            </div>
        </div>
    </div>

    <div class="footer">
        &copy; 2025 College Cabinet Voting System. All rights reserved.
    </div>

    <!-- Particles.js for background animation -->
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
                        },
                        "polygon": {
                            "nb_sides": 5
                        }
                    },
                    "opacity": {
                        "value": 0.3,
                        "random": false,
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
                        "opacity": 0.2,
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
                                "opacity": 1
                            }
                        },
                        "bubble": {
                            "distance": 400,
                            "size": 40,
                            "duration": 2,
                            "opacity": 8,
                            "speed": 3
                        },
                        "repulse": {
                            "distance": 200,
                            "duration": 0.4
                        },
                        "push": {
                            "particles_nb": 4
                        },
                        "remove": {
                            "particles_nb": 2
                        }
                    }
                },
                "retina_detect": true
            });
        });
    </script>
</body>
</html>