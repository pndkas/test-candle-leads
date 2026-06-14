<?php
/**
 * db.php — Manages MySQL database connection
 *
 * Auto-setup: Automatically creates database and table on first run
 *
 * ⚠️ Update DB_USER and DB_PASS to match your MySQL setup before running
 */

define('DB_HOST',    '127.0.0.1');
define('DB_PORT',    '3306');
define('DB_NAME',    'candle_leads');
define('DB_USER',    'root');
define('DB_PASS',    'PanidaKhoei-arsa09');          // Insert your MySQL password here (if any)
define('DB_CHARSET', 'utf8mb4');

/**
 * Returns a PDO instance and automatically creates Database and Table
 */
function getDB(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        try {
            // 1. Connect to MySQL Server first
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';charset=' . DB_CHARSET,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );

            // 2. Automatically create 'candle_leads' database if it doesn't exist
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "`
                        CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            // 3. Select the database
            $pdo->exec("USE `" . DB_NAME . "`");

            // 4. Automatically create leads table if it doesn't exist
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS leads (
                    id         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
                    name       VARCHAR(100)  NOT NULL,
                    email      VARCHAR(254)  NOT NULL,
                    scent      ENUM('Vanilla','Lavender','Sandalwood','Citrus','Unsure') NOT NULL,
                    message    TEXT          NULL,
                    created_at DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (id),
                    INDEX idx_email      (email),
                    INDEX idx_created_at (created_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Cannot connect to MySQL: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    return $pdo;
}
