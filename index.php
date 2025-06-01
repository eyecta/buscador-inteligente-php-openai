<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador Inteligente</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #fff;
        }

        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .logo {
            font-size: 4rem;
            margin-bottom: 2rem;
        }

        .logo span:nth-child(1) { color: #4285F4; }
        .logo span:nth-child(2) { color: #EA4335; }
        .logo span:nth-child(3) { color: #FBBC05; }
        .logo span:nth-child(4) { color: #4285F4; }
        .logo span:nth-child(5) { color: #34A853; }
        .logo span:nth-child(6) { color: #EA4335; }

        .search-container {
            width: 100%;
            max-width: 584px;
            margin-bottom: 2rem;
        }

        .search-box {
            width: 100%;
            position: relative;
        }

        .search-input {
            width: 100%;
            height: 44px;
            padding: 0 50px;
            font-size: 16px;
            border: 1px solid #dfe1e5;
            border-radius: 24px;
            outline: none;
            transition: all 0.3s;
        }

        .search-input:hover,
        .search-input:focus {
            box-shadow: 0 1px 6px rgba(32,33,36,.28);
            border-color: rgba(223,225,229,0);
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa0a6;
        }

        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 1rem;
        }

        .button {
            background-color: #f8f9fa;
            border: 1px solid #f8f9fa;
            border-radius: 4px;
            color: #3c4043;
            font-size: 14px;
            padding: 0 16px;
            height: 36px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .button:hover {
            box-shadow: 0 1px 1px rgba(0,0,0,.1);
            background-color: #f8f9fa;
            border: 1px solid #dadce0;
            color: #202124;
        }

        .footer {
            background-color: #f2f2f2;
            padding: 1rem;
            margin-top: auto;
        }

        .results {
            width: 100%;
            max-width: 700px;
            padding: 20px;
        }

        .result-item {
            margin-bottom: 2rem;
        }

        .result-title {
            color: #1a0dab;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            text-decoration: none;
        }

        .result-title:hover {
            text-decoration: underline;
        }

        .result-url {
            color: #006621;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .result-description {
            color: #545454;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .error {
            color: #EA4335;
            margin-top: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (OPENAI_API_KEY === 'YOUR_API_KEY_HERE'): ?>
        <div class="error-message" style="background-color: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.375rem; margin-bottom: 2rem; text-align: center;">
            <strong>Configuración requerida:</strong> Es necesario configurar tu API key de OpenAI en el archivo config.php.<br>
            Obtén tu API key en <a href="https://platform.openai.com/account/api-keys" style="color: #991b1b; text-decoration: underline;">https://platform.openai.com/account/api-keys</a>
        </div>
        <?php endif; ?>
        
        <div class="logo">
            <span>S</span><span>e</span><span>a</span><span>r</span><span>c</span><span>h</span>
        </div>
        
        <div class="search-container">
            <form action="search.php" method="GET">
                <div class="search-box">
                    <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" name="q" class="search-input" placeholder="Escribe tu búsqueda inteligente" required>
                </div>
                <div class="buttons">
                    <button type="submit" class="button">Buscar</button>
                    <button type="button" class="button" onclick="feelingLucky()">Me siento con suerte</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="footer">
        <div style="text-align: center; color: #70757a; font-size: 14px;">
            Búsqueda inteligente potenciada por OpenAI
        </div>
    </footer>

    <script>
        function feelingLucky() {
            const input = document.querySelector('input[name="q"]');
            if (input.value.trim()) {
                window.location.href = `search.php?q=${encodeURIComponent(input.value)}&lucky=1`;
            }
        }
    </script>
</body>
</html>
