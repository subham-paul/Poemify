<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poemify - AI Poetry Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #7c4dff;
            --secondary-color: #448aff;
            --bg-dark: #09090b;
            --card-bg: rgba(23, 23, 27, 0.8);
            --text-main: #f4f4f5;
            --text-muted: #cbd5e1;

            /* Button specific variables */
            --btn-color: #000;
            --text-color: #448aff;
            --shadow-color: rgba(68, 138, 255, 0.4);
            --content: 'CREATE POEM';
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
            margin: 0;
        }

        .bubbles-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
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
            0% { bottom: -100px; transform: translateX(0); opacity: 0; }
            20% { opacity: 0.6; }
            80% { opacity: 0.6; }
            100% { bottom: 100vh; transform: translateX(-50px); opacity: 0; }
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
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* User Dropdown Styles */
        .user-dropdown .dropdown-toggle {
            color: #f4f4f5;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: 0.3s;
        }
        .user-dropdown .dropdown-toggle:hover { background: rgba(255, 255, 255, 0.1); }
        .dropdown-menu {
            background-color: #1a1a1d;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            margin-top: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .dropdown-item {
            color: #f4f4f5;
            padding: 10px 20px;
            transition: 0.2s;
        }
        .dropdown-item:hover { background-color: var(--primary-color); color: #fff; }
        .dropdown-divider { border-top: 1px solid rgba(255, 255, 255, 0.05); }

        .hero-section {
            padding: 40px 0 30px;
            text-align: center;
        }

        .hero-section h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            color: #f4f4f5 !important;
            font-size: 1.1rem;
            font-weight: 400;
            max-width: 600px;
            margin: 0 auto;
        }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-muted);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 12px 16px;
            border-radius: 12px;
            appearance: none;
            -webkit-appearance: none;
        }

        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23cbd5e1' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px 12px;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(124, 77, 255, 0.15);
            color: #fff;
        }

        .form-select option {
            background-color: #1a1a1d;
            color: #fff;
        }

        #generate-btn {
            position: relative;
            width: 100%;
            padding: 24px 20px;
            border: none;
            background: none;
            cursor: pointer;
            font-family: "Inter", sans-serif;
            font-weight: 900;
            text-transform: uppercase;
            font-size: 20px;
            color: var(--text-color);
            background-color: var(--btn-color);
            box-shadow: var(--shadow-color) 0px 4px 15px;
            border-radius: 16px;
            z-index: 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(68, 138, 255, 0.2);
        }

        #generate-btn:hover {
            box-shadow: var(--text-color) 0px 0px 30px,
                        rgba(124, 77, 255, 0.3) 0px 0px 50px;
            border-color: var(--text-color);
            transform: translateY(-2px);
        }

        #generate-btn::after {
            content: var(--content);
            display: block;
            position: absolute;
            white-space: nowrap;
            padding: 40px 40px;
            pointer-events: none;
            font-weight: 200;
            top: -18px;
            left: 0;
            right: 0;
            opacity: 0.1;
        }

        .btn-magic-icon {
            margin-right: 12px;
            position: relative;
            z-index: 2;
            font-size: 24px;
        }

        #generate-btn .right, #generate-btn .left {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            pointer-events: none;
        }
        #generate-btn .right { left: 66%; }
        #generate-btn .left { right: 66%; }

        #generate-btn .right::after {
            content: var(--content);
            display: block;
            position: absolute;
            white-space: nowrap;
            padding: 40px 40px;
            pointer-events: none;
            top: -18px;
            left: calc(-66% - 20px);
            background-color: #000;
            color: transparent;
            transition: transform .4s ease-out;
            transform: translate(0, -90%) rotate(0deg);
        }

        #generate-btn:hover .right::after {
            transform: translate(0, -47%) rotate(0deg);
        }

        #generate-btn::before {
            content: '';
            pointer-events: none;
            opacity: .6;
            background:
                radial-gradient(circle at 20% 35%, transparent 0, transparent 2px, var(--text-color) 3px, var(--text-color) 4px, transparent 4px),
                radial-gradient(circle at 75% 44%, transparent 0, transparent 2px, var(--text-color) 3px, var(--text-color) 4px, transparent 4px),
                radial-gradient(circle at 46% 52%, transparent 0, transparent 4px, var(--text-color) 5px, var(--text-color) 6px, transparent 6px);
            width: 100%;
            height: 300%;
            top: 0;
            left: 0;
            position: absolute;
            animation: btn-bubbles 5s linear infinite both;
        }

        @keyframes btn-bubbles {
            from { transform: translate(0, 0); }
            to { transform: translate(0, -66.666%); }
        }

        .btn-text {
            position: relative;
            z-index: 2;
        }

        .btn-sample {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-muted);
            border: 1px dashed rgba(255, 255, 255, 0.2);
            font-size: 0.8rem;
            padding: 4px 12px;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-sample:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        #poem-output {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            line-height: 1.8;
            color: #e4e4e7;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 16px;
            padding: 40px;
            border-left: 4px solid var(--primary-color);
        }

        .loader {
            display: none;
            width: 48px;
            height: 48px;
            border: 5px solid #FFF;
            border-bottom-color: var(--primary-color);
            border-radius: 50%;
            margin: 30px auto;
            animation: rotation 1s linear infinite;
        }

        @keyframes rotation { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    </style>
</head>
<body>

<div class="bubbles-container" id="bubbles"></div>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Poemify</a>
        <div class="ms-auto">
            <?php if(isLoggedIn()): ?>
                <div class="dropdown user-dropdown">
                    <a class="dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-2"></i> Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="history.php"><i class="fas fa-history me-2"></i> My History</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary btn-sm rounded-pill px-4">Sign In</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">
    <div class="hero-section">
        <h1>Echoes of <span style="color: var(--primary-color);">Thought</span></h1>
        <p class="hero-subtitle">Transform your emotions into timeless poetic verses.</p>
    </div>

    <div class="row justify-content-center pb-5">
        <div class="col-md-10 col-lg-9">
            <div class="card p-4 p-md-5">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label">What's Your Poem About?</label>
                        <span class="btn-sample" onclick="getSamplePrompt()">
                            <i class="fas fa-lightbulb me-1"></i> Sample Topic
                        </span>
                    </div>
                    <textarea id="prompt" class="form-control" rows="3" placeholder="A lonely lighthouse in a storm, the feeling of nostalgia..."></textarea>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Poem Type</label>
                        <select id="type" class="form-select">
                            <option value="Narrative">Narrative</option>
                            <option value="Lyric">Lyric</option>
                            <option value="Ode">Ode</option>
                            <option value="Elegy">Elegy</option>
                            <option value="Sonnet">Sonnet</option>
                            <option value="Free Verse">Free Verse</option>
                            <option value="Ballad">Ballad</option>
                            <option value="Haiku">Haiku</option>
                            <option value="Villanelle">Villanelle</option>
                            <option value="Limerick">Limerick</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Style</label>
                        <select id="style" class="form-select">
                            <option value="Contemporary">Contemporary</option>
                            <option value="Classical">Classical</option>
                            <option value="Shakespearean">Shakespearean</option>
                            <option value="Victorian Gothic">Victorian Gothic</option>
                            <option value="Abstract">Abstract</option>
                            <option value="Romantic">Romantic</option>
                            <option value="Modern Slam">Modern Slam</option>
                            <option value="Cyberpunk">Cyberpunk</option>
                            <option value="Minimalist">Minimalist</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Length</label>
                        <select id="length" class="form-select">
                            <option value="standard">Standard</option>
                            <option value="short">Short (max 4 lines)</option>
                            <option value="medium">Medium (around 12-16 lines)</option>
                            <option value="long">Long (extensive verses)</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button id="generate-btn">
                            <span class="left"></span>
                            <i class="fas fa-magic btn-magic-icon"></i>
                            <span class="btn-text">Create Poem</span>
                            <span class="right"></span>
                        </button>
                    </div>
                </div>

                <div class="loader" id="loader"></div>

                <div id="result-container" style="display:none;">
                    <hr class="my-5 opacity-10">
                    <div id="poem-output" class="mb-4"></div>
                    <div class="controls d-flex flex-wrap justify-content-center gap-3">
                        <button class="btn btn-outline-light px-4" id="tts-btn" onclick="toggleTTS()">
                            <i class="fas fa-volume-up me-2"></i> Listen
                        </button>
                        <button class="btn btn-outline-light px-4" onclick="copyPoem()">
                            <i class="fas fa-copy me-2"></i> Copy
                        </button>
                        <button class="btn btn-outline-light px-4" onclick="sharePoem()">
                            <i class="fas fa-share-alt me-2"></i> Share
                        </button>
                        <?php if(isLoggedIn()): ?>
                            <button id="save-btn" class="btn btn-outline-primary px-4" onclick="savePoem()">
                                <i class="fas fa-save me-2"></i> Save to Collection
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
<script src="script.js"></script>
</body>
</html>