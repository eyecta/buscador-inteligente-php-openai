<?php
// Configuración de la API de OpenAI
const OPENAI_API_KEY = 'YOUR_API_KEY_HERE'; // Reemplazar con tu API key real
const OPENAI_API_URL = 'https://api.openai.com/v1/chat/completions';

// Configuración de la aplicación
const APP_NAME = 'Buscador Inteligente';
const APP_VERSION = '1.0.0';

// Configuración de errores
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Zona horaria
date_default_timezone_set('America/Mexico_City');

// Funciones de utilidad
function sanitize_output($output) {
    return htmlspecialchars($output, ENT_QUOTES, 'UTF-8');
}

function format_error($message) {
    return [
        'error' => true,
        'message' => $message
    ];
}

// Función para validar la consulta
function validate_query($query) {
    $query = trim($query);
    if (empty($query)) {
        return false;
    }
    // Limitar la longitud de la consulta
    if (strlen($query) > 500) {
        return false;
    }
    return $query;
}

// Función para registrar errores
function log_error($error) {
    $log_file = 'errors.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp] $error\n";
    error_log($log_message, 3, $log_file);
}
