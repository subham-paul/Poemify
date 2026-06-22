<?php
include 'config.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}

$googleLoginUrl = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
    'client_id' => $_ENV['GOOGLE_CLIENT_ID'],
    'redirect_uri' => $_ENV['GOOGLE_REDIRECT_URL'],
    'response_type' => 'code',
    'scope' => 'email profile',
    'access_type' => 'offline'
]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Poemify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #09090b;
            color: #f4f4f5; /* Off-white text */
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
            color: #f4f4f5; /* Ensure card text is off-white */
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 12px;
            border-radius: 12px;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #7c4dff;
            color: #fff;
            box-shadow: none;
        }

        .btn-primary {
            background: #7c4dff;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 12px;
            color: #fff;
        }

        .btn-google {
            background: #ffffff;
            color: #3c4043;
            border: 1px solid #dadce0;
            font-weight: 600;
            padding: 12px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-google:hover {
            background: #f8f9fa;
            color: #3c4043;
        }

        .btn-google img, .btn-google svg {
            width: 20px;
            margin-right: 12px;
        }

        .text-muted {
            color: #a1a1aa !important;
        }
    </style>
</head>
<body>
    <div class="bubbles-container" id="bubbles"></div>

    <div class="card shadow-2xl">
        <h3 class="text-center mb-4 fw-bold">Welcome Back</h3>
        <?php if($error): ?> <div class="alert alert-danger py-2"><?php echo $error; ?></div> <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            </div>
            <div class="mb-4">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
        </form>

        <div class="position-relative my-4 text-center">
            <hr class="text-muted">
            <span class="position-absolute top-50 start-50 translate-middle px-2 text-muted" style="background: #17171b !important;">OR</span>
        </div>

        <a href="<?php echo $googleLoginUrl; ?>" class="btn btn-google w-100">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48" class="me-2">
                <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20 20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"/>
                <path fill="#FF3D00" d="m6.306 14.691 6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4c-7.221 0-13.518 3.829-17.694 9.691z"/>
                <path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C10.505 39.756 16.708 44 24 44z"/>
                <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/>
            </svg>
            Continue with Google
        </a>
        <p class="mt-4 text-center text-muted small">Don't have an account? <a href="register.php" class="text-decoration-none" style="color: #7c4dff;">Register</a></p>
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