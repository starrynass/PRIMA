<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$client = Gemini::client(config('services.gemini.api_key'));
$result = $client->generativeModel(model: 'gemini-3.8-flash')->generateContent('Halo, tes respon singkat!');
echo "Success: " . $result->text() . "\n";
