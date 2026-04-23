<?php
$baseDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseUrl = defined('BASE_URL') ? BASE_URL : $baseDir;
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knihovna</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/style.css">
    <style>
        /* Moderní navigační panel a patička */
        .site-header {
            background-color: var(--primary);
            color: white;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            flex-wrap: wrap;
            gap: 1rem;
        }
        .site-header a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            margin-left: 1.5rem;
            transition: opacity 0.2s;
        }
        .site-header a:hover {
            opacity: 0.8;
        }
        .site-header .brand {
            font-size: 1.5rem;
            font-weight: 700;
            margin-left: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .site-footer {
            margin-top: 3rem;
            padding: 1.5rem;
            text-align: center;
            color: var(--text-muted);
            border-top: 1px solid var(--border);
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Hlavní navigace -->
        <header class="site-header">
            <a href="?url=book/index" class="brand">📚 Knihovna App</a>
            <nav style="display: flex; align-items: center;">
                <a href="<?= $baseUrl ?>/index.php">Přehled knih</a>
                <a href="<?= $baseUrl ?>/index.php?url=book/create">Přidat knihu</a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <span style="margin-left: 1.5rem; font-size: 0.9rem; color: #cbd5e1;"> 
                        Ahoj, <strong style="color: white; font-weight: 600;"><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></strong>
                    </span>
                    <a href="<?= $baseUrl ?>/index.php?url=auth/logout" style="color: #fca5a5; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.05em;">
                        Odhlásit
                    </a>

                <?php else: ?>
                    <a href="<?= $baseUrl ?>/index.php?url=auth/login">Přihlásit</a>
                    <a href="<?= $baseUrl ?>/index.php?url=auth/register">Registrace</a>
                <?php endif; ?>
            </nav>
        </header>

