<?php
include 'config.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
        $message = "Registration successful! <a href='login.php' class='alert-link' style='color: #7c4dff;'>Login here</a>";
    } catch (PDOException $e) {
        $message = "Error: Email already exists.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Poemify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #09090b;
            color: #f4f4f5;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .bubbles-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: radial-gradient(circle at bottom, #111 0%, #09090b 100%);
        }

        .bubble {
            position: absolute;
            bottom: -100px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            animation: rise 15s infinite linear;
        }

        @keyframes rise {
            0% { bottom: -100px; transform: translateX(0); opacity: 0; }
            20% { opacity: 0.3; }
            80% { opacity: 0.3; }
            100% { bottom: 100vh; transform: translateX(50px); opacity: 0; }
        }

        .card {
            background: rgba(23, 23, 27, 0.8);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            width: 100%;
            max-width: 400px;
            margin: auto;
            padding: 40px;
            border-radius: 24px;
            color: #f4f4f5;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: #cbd5e1;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #7c4dff;
            color: #fff;
            box-shadow: 0 0 0 4px rgba(124, 77, 255, 0.15);
        }

        .btn-primary {
            background: #7c4dff;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 12px;
            margin-top: 10px;
            transition: 0.3s;
        }

        .btn-primary:hover { background: #6b3ee3; transform: translateY(-1px); }

        .text-muted { color: #a1a1aa !important; }
    </style>
</head>
<body>
    <div class="bubbles-container" id="bubbles"></div>

    <div class="card shadow-2xl">
        <h3 class="text-center mb-4 fw-bold">Create Account</h3>

        <?php if($message): ?>
            <div class="alert alert-info py-2" style="background: rgba(124, 77, 255, 0.1); border: 1px solid rgba(124, 77, 255, 0.2); color: #f4f4f5; font-size: 0.9rem;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="example@domain.com" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
        </form>
        <p class="mt-4 text-center text-muted small">Already have an account? <a href="login.php" class="text-decoration-none" style="color: #7c4dff;">Login</a></p>
    </div>

    <script>
        const container = document.getElementById('bubbles');
        for (let i = 0; i < 20; i++) {
            const bubble = document.createElement('div');
            bubble.className = 'bubble';
            const size = Math.random() * 12 + 4 + 'px';
            bubble.style.width = size;
            bubble.style.height = size;
            bubble.style.left = Math.random() * 100 + '%';
            bubble.style.animationDuration = Math.random() * 12 + 8 + 's';
            bubble.style.animationDelay = Math.random() * 8 + 's';
            container.appendChild(bubble);
        }
    </script>
</body>
</html>