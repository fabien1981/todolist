<?php
namespace App\Middlewares;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

require_once __DIR__ . '/../config/securite.php';

class AuthMiddleware {
    public static function verifierAuthentification() {
        error_log("👀 AuthMiddleware - Début de la vérification du JWT...");

        $headers = getallheaders();
        error_log("📡 Headers reçus : " . json_encode($headers));
        
        if (!isset($headers['Authorization'])) {
            error_log("❌ Aucun header Authorization reçu !");
            return false;
        }
        
        $authHeader = $headers['Authorization'];
        
        if (!str_starts_with($authHeader, 'Bearer ')) {
            error_log("❌ Format d'Authorization invalide !");
            return false;
        }
        
        $token = substr($authHeader, 7);
        error_log("🔑 Token extrait : " . $token);
        
        try {
            $decoded = JWT::decode($token, new Key(JWT_SECRET, JWT_ALGO));
            error_log("✅ Token JWT décodé avec succès : " . json_encode($decoded));
            return (array) $decoded;
        } catch (Exception $e) {
            error_log("❌ Erreur lors du décodage JWT : " . $e->getMessage());
            return false;
        }
    }        
}
