<?php
$baseDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseUrl = defined('BASE_URL') ? BASE_URL : $baseDir;
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Aplikace pro správu oblíbených fotbalových týmů. Přidávejte, upravujte a spravujte své oblíbené kluby.">
    <title>⚽ Moje Fotbalové Kluby</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/style.css">
    <style>
        .site-header {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-dark) 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5), 0 0 20px rgba(56, 189, 248, 0.1);
            flex-wrap: wrap;
            gap: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
        }
        .site-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 60%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(221, 126, 58, 0.1) 0%, transparent 70%);
            pointer-events: none;
            transform: rotate(-15deg);
        }
        .site-header a {
            color: rgba(248, 250, 252, 0.8);
            text-decoration: none;
            font-weight: 500;
            margin-left: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            padding: 0.5rem 0;
        }
        .site-header nav a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s ease;
        }
        .site-header nav a:hover::after {
            width: 100%;
        }
        .site-header a:hover {
            color: #ffffff;
            transform: translateY(-1px);
        }
        .site-header .brand {
            font-size: 1.6rem;
            font-weight: 800;
            margin-left: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
            letter-spacing: -0.02em;
        }
        .site-header .brand img {
            height: 70px;
            width: auto;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.3));
        }
        .site-header .brand:hover {
            transform: scale(1.02);
        }
        .user-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-left: 1.5rem;
            font-size: 0.85rem;
            color: rgba(248, 250, 252, 0.7);
            background: rgba(255, 255, 255, 0.05);
            padding: 0.4rem 1rem;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .user-badge strong {
            color: var(--primary);
            font-weight: 700;
        }
        .logout-link {
            color: #fca5a5 !important;
            text-transform: uppercase;
            font-size: 0.75rem !important;
            letter-spacing: 0.1em;
            font-weight: 700 !important;
            border: 1px solid rgba(252, 165, 165, 0.2);
            padding: 0.4rem 0.8rem !important;
            border-radius: 8px;
            margin-left: 1rem !important;
        }
        .logout-link:hover {
            background: rgba(252, 165, 165, 0.1);
            color: #fecaca !important;
            border-color: rgba(252, 165, 165, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Hlavní navigace -->
        <header class="site-header">
            <a href="<?= $baseUrl ?>/index.php?url=team/index" class="brand">
                <img src="<?= $baseUrl ?>/img/logo.svg" alt="Logo">
                <span>My Teams</span>
            </a>
            <nav style="display: flex; align-items: center; flex-wrap: wrap;">
                <a href="<?= $baseUrl ?>/index.php?url=team/index">Moje týmy</a>
                <a href="<?= $baseUrl ?>/index.php?url=team/create">Přidat tým</a>
                <a href="<?= $baseUrl ?>/index.php?url=user/index">Seznam uživatelů</a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= $baseUrl ?>/index.php?url=user/show/<?= $_SESSION['user_id'] ?>" class="user-badge" style="text-decoration: none;">
                        👤 <strong><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></strong>
                        <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
                            <span style="background: var(--primary); color: white; font-size: 0.62rem; padding: 0.1rem 0.4rem; border-radius: 4px; font-weight: 700; letter-spacing: 0.05em; margin-left: 0.25rem;">ADMIN</span>
                        <?php endif; ?>
                    </a>
                    <a href="<?= $baseUrl ?>/index.php?url=auth/logout" class="logout-link">
                        Odhlásit
                    </a>

                <?php else: ?>
                    <a href="<?= $baseUrl ?>/index.php?url=auth/login">Přihlásit</a>
                    <a href="<?= $baseUrl ?>/index.php?url=auth/register">Registrace</a>
                <?php endif; ?>
            </nav>
        </header>

        <!-- Výpis systémových zpráv (success / error / notice) -->
        <?php if (!empty($_SESSION['messages']['success'])): ?>
            <div class="msg-success">
                <?php foreach ($_SESSION['messages']['success'] as $msg): ?>
                    <p style="margin: 0; font-weight: 500;">✓ <?= htmlspecialchars($msg) ?></p>
                <?php endforeach; ?>
                <?php unset($_SESSION['messages']['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['messages']['error'])): ?>
            <div class="msg-error">
                <?php foreach ($_SESSION['messages']['error'] as $error): ?>
                    <p style="margin: 0; font-weight: 500;">⚠ <?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
                <?php unset($_SESSION['messages']['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['messages']['notice'])): ?>
            <div class="msg-notice">
                <?php foreach ($_SESSION['messages']['notice'] as $notice): ?>
                    <p style="margin: 0; font-weight: 500;">ℹ <?= htmlspecialchars($notice) ?></p>
                <?php endforeach; ?>
                <?php unset($_SESSION['messages']['notice']); ?>
            </div>
        <?php endif; ?>

