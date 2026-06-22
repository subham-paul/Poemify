<?php
include 'config.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM poems WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$poems = $stmt->fetchAll();

function get_word_count($text) {
    return str_word_count(strip_tags($text));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Poetry Collection - Poemify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #7c4dff;
            --bg-dark: #09090b;
            --card-bg: rgba(23, 23, 27, 0.9);
            --text-main: #f4f4f5;
            --text-muted: #a1a1aa;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
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
            background: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            animation: rise 15s infinite linear;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }

        @keyframes rise {
            0% { bottom: -100px; opacity: 0; }
            20% { opacity: 0.6; }
            80% { opacity: 0.6; }
            100% { bottom: 100vh; opacity: 0; }
        }

        .navbar {
            background: rgba(9, 9, 11, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            background: linear-gradient(45deg, var(--primary-color), #448aff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .user-dropdown .dropdown-toggle {
            color: #f4f4f5;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: 0.3s;
        }
        .dropdown-menu { background-color: #1a1a1d; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; margin-top: 10px; }
        .dropdown-item { color: #f4f4f5; padding: 10px 20px; }
        .dropdown-item:hover { background-color: var(--primary-color); color: #fff; }

        .container { padding-top: 40px; padding-bottom: 60px; }

        .btn-back {
            color: var(--text-muted) !important;
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            margin-bottom: 30px;
            transition: 0.3s;
        }

        h2 { font-family: 'Playfair Display', serif; font-weight: 700; font-size: 2.8rem; margin-bottom: 40px; }

        .poem-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 25px; /* Reduced padding */
            display: flex;
            flex-direction: column;
            height: 440px; /* Adjusted height for tighter look */
            transition: all 0.3s ease;
        }

        .poem-card:hover { transform: translateY(-8px); border-color: var(--primary-color); }

        .poem-title { color: #448aff; font-size: 1.15rem; font-weight: 600; margin-bottom: 5px; } /* Reduced margin */

        .poem-meta { font-size: 0.75rem; color: #a1a1aa; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; font-weight: 600; }

        .poem-content-wrapper {
            flex-grow: 1;
            overflow-y: auto;
            margin-bottom: 10px; /* Reduced margin */
            padding-right: 5px;
        }

        .poem-content-wrapper::-webkit-scrollbar { width: 3px; }
        .poem-content-wrapper::-webkit-scrollbar-thumb { background: var(--primary-color); border-radius: 10px; }

        .poem-content {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: 1.1rem;
            line-height: 1.4; /* Tightened line height */
            color: #e4e4e7;
            white-space: pre-wrap;
            border-left: 3px solid var(--primary-color);
            padding-left: 15px;
        }

        .btn-read-more {
            color: var(--primary-color);
            background: none;
            border: none;
            font-weight: 600;
            padding: 0;
            margin-bottom: 10px; /* Reduced margin */
            font-size: 0.85rem;
            text-decoration: underline;
        }

        .poem-footer {
            font-size: 0.8rem;
            color: #71717a;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 12px; /* Reduced padding */
            margin-top: auto;
        }

        .modal-content { background-color: #1a1a1d; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; color: #f4f4f5; }
        .modal-body .poem-content { line-height: 1.6; } /* Comfortable reading in modal */
    </style>
</head>
<body>

<div class="bubbles-container" id="bubbles"></div>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid px-5">
        <a class="navbar-brand fw-bold" href="index.php">Poemify</a>
        <div class="ms-auto">
            <?php if(isLoggedIn()): ?>
                <div class="dropdown user-dropdown">
                    <a class="dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-2"></i> Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="index.php"><i class="fas fa-magic me-2"></i> Generator</a></li>
                        <li><a class="dropdown-item" href="history.php"><i class="fas fa-history me-2"></i> My History</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">
    <a href="index.php" class="btn-back"><i class="fas fa-arrow-left me-2"></i> Return to Generator</a>
    <h2>My Collection</h2>

    <div class="row g-4">
        <?php foreach ($poems as $poem):
            $wordCount = get_word_count($poem['poem_text'] ?? '');
            $isLong = $wordCount > 20;
            // Limit characters for a cleaner preview
            $displayContent = $isLong ? mb_substr($poem['poem_text'], 0, 200) . '...' : ($poem['poem_text'] ?? 'No content');
        ?>
            <div class="col-md-6 col-lg-4">
                <div class="poem-card shadow-lg">
                    <div class="poem-title"><?php echo htmlspecialchars($poem['title'] ?? 'Untitled Poem'); ?></div>
                    <div class="poem-meta"><?php echo htmlspecialchars(($poem['type'] ?? 'N/A') . " (" . ($poem['style'] ?? 'N/A') . ")"); ?></div>

                    <div class="poem-content-wrapper">
                        <div class="poem-content"><?php echo nl2br(htmlspecialchars($displayContent)); ?></div>
                    </div>

                    <?php if($isLong): ?>
                        <button class="btn-read-more" data-bs-toggle="modal" data-bs-target="#poemModal<?php echo $poem['id']; ?>">Read Full Poem</button>
                    <?php endif; ?>

                    <div class="poem-footer d-flex justify-content-between align-items-center">
                        <span><i class="far fa-calendar-alt me-1"></i> <?php echo date('M d, Y', strtotime($poem['created_at'] ?? 'now')); ?></span>
                        <button class="btn btn-link btn-sm text-decoration-none text-muted p-0" onclick="copyText(`<?php echo addslashes($poem['poem_text'] ?? ''); ?>`)">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="poemModal<?php echo $poem['id']; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?php echo htmlspecialchars($poem['prompt'] ?? 'Full Poem'); ?></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-close="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="poem-content" style="border:none; padding:0;"><?php echo nl2br(htmlspecialchars($poem['poem_text'] ?? '')); ?></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary rounded-pill" data-bs-close="modal">Close</button>
                            <button class="btn btn-primary rounded-pill" onclick="copyText(`<?php echo addslashes($poem['poem_text'] ?? ''); ?>`)">
                                <i class="fas fa-copy me-1"></i> Copy Poem
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function copyText(text) {
        navigator.clipboard.writeText(text).then(() => alert("Poem copied to clipboard!"));
    }
    const container = document.getElementById('bubbles');
    for (let i = 0; i < 40; i++) {
        const bubble = document.createElement('div');
        bubble.className = 'bubble';
        const size = Math.random() * 15 + 5 + 'px';
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