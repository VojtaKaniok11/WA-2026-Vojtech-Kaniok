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
            padding: 1rem 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
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
            <nav>
                <a href="?url=book/index">Přehled knih</a>
                <a href="?url=book/create">Přidat knihu</a>
            </nav>
        </header>

