<?php
require 'vendor/autoload.php'; // Ensure Composer's autoload is included

// Load environment variables from .env file if it exists
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Retrieve API key from environment variables
$apiKey = getenv('API_KEY');
