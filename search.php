<?php
// Incluir archivo de configuración
require_once 'config.php';

function searchWithAI($query, $isLucky = false) {
    if (!validate_query($query)) {
        throw new Exception('La consulta no es válida');
    }
    
    if (OPENAI_API_KEY === 'YOUR_API_KEY_HERE') {
        throw new Exception('Error en la API de OpenAI: Incorrect API key provided: YOUR_API*****HERE. You can find your API key at https://platform.openai.com/account/api-keys.');
    }

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . OPENAI_API_KEY
    ];

    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            [
                'role' => 'system',
                'content' => 'Eres un asistente de búsqueda preciso. Proporciona resultados relevantes y concisos.'
            ],
            [
                'role' => 'user',
                'content' => $query
            ]
        ],
        'temperature' => 0.7,
        'max_tokens' => 500
    ];

    $ch = curl_init(OPENAI_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        throw new Exception('Error en la conexión con OpenAI: ' . $error);
    }

    $result = json_decode($response, true);
    
    if (isset($result['error'])) {
        throw new Exception('Error en la API de OpenAI: ' . $result['error']['message']);
    }

    return $result['choices'][0]['message']['content'];
}

// Procesar la búsqueda
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$isLucky = isset($_GET['lucky']) && $_GET['lucky'] === '1';

if (empty($query)) {
    header('Location: index.php');
    exit;
}

$error = '';
$searchResults = '';

try {
    $aiResponse = searchWithAI($query, $isLucky);
    
    // Formatear la respuesta como resultados de búsqueda
    $results = explode("\n", $aiResponse);
    $searchResults = array_filter($results, function($line) {
        return !empty(trim($line));
    });
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($query); ?> - Resultados de búsqueda</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        .header {
            padding: 1rem;
            border-bottom: 1px solid #dfe1e5;
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .logo-small {
            font-size: 1.5rem;
            text-decoration: none;
        }

        .logo-small span:nth-child(1) { color: #4285F4; }
        .logo-small span:nth-child(2) { color: #EA4335; }
        .logo-small span:nth-child(3) { color: #FBBC05; }
        .logo-small span:nth-child(4) { color: #4285F4; }
        .logo-small span:nth-child(5) { color: #34A853; }
        .logo-small span:nth-child(6) { color: #EA4335; }

        .search-container-small {
            flex: 1;
            max-width: 692px;
        }

        .search-box-small {
            position: relative;
        }

        .search-input-small {
            width: 100%;
            height: 40px;
            padding: 0 40px;
            font-size: 16px;
            border: 1px solid #dfe1e5;
            border-radius: 24px;
            outline: none;
        }

        .search-input-small:hover,
        .search-input-small:focus {
            box-shadow: 0 1px 6px rgba(32,33,36,.28);
            border-color: rgba(223,225,229,0);
        }

        .results-container {
            max-width: 692px;
            margin: 0 auto;
            padding: 2rem;
        }

        .result-item {
            margin-bottom: 2rem;
        }

        .result-title {
            color: #1a0dab;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .result-description {
            color: #4d5156;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .error-message {
            color: #EA4335;
            text-align: center;
            padding: 2rem;
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="index.php" class="logo-small">
            <span>S</span><span>e</span><span>a</span><span>r</span><span>c</span><span>h</span>
        </a>
        <div class="search-container-small">
            <form action="search.php" method="GET">
                <div class="search-box-small">
                    <input type="text" name="q" value="<?php echo htmlspecialchars($query); ?>" class="search-input-small" required>
                </div>
            </form>
        </div>
    </header>

    <div class="results-container">
        <?php if ($error): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php else: ?>
            <?php foreach ($searchResults as $index => $result): ?>
                <div class="result-item">
                    <div class="result-title">Resultado <?php echo $index + 1; ?></div>
                    <div class="result-description"><?php echo htmlspecialchars($result); ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
