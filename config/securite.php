<?php
use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Charger les variables d'environnement depuis le fichier .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

define('JWT_SECRET', $_ENV['JWT_SECRET']);
define('JWT_ALGO', $_ENV['JWT_ALGO']);
define('JWT_EXPIRATION', (int) $_ENV['JWT_EXPIRATION']);

error_log("🔑 Clé JWT chargée depuis .env : " . JWT_SECRET);

/**
 * Génère un token JWT pour un utilisateur.
 */
function genererTokenJWT($utilisateur) {
    $payload = [
        'id' => (string) $utilisateur['_id'],
        'email' => $utilisateur['email'],
        'exp' => time() + JWT_EXPIRATION // Expiration du token
    ];

    return JWT::encode($payload, JWT_SECRET, JWT_ALGO);
}

/**
 * Vérifie un token JWT et retourne les données décodées.
 */
function verifierTokenJWT($token) {
    try {
        return JWT::decode($token, new Key(JWT_SECRET, JWT_ALGO));
    } catch (Exception $e) {
        error_log("❌ Erreur de décodage du token JWT : " . $e->getMessage());
        return null;
    }
}
