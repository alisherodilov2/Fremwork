<?php
namespace App\Database\Migrations;

use PDO;

class Migration {
    protected $pdo;

    public function __construct() {
        $config = require __DIR__ . '/../../../../config/database.php';
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']}";
        $this->pdo = new PDO($dsn, $config['user'], $config['password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Run a migration
    public function up() {

    }

    // Rollback a migration
    public function down() {

    }
}