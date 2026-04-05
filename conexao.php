<?php
date_default_timezone_set('UTC');
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$supaurl = $_ENV['SUPABASE_URL'];
$supakey = $_ENV['SUPABASE_KEY'];
$email_app = $_ENV['EMAIL_APP'];
$senha_app = $_ENV['SENHA_APP'];

function request($endPoint, $method = 'GET', $data = null)
{
    global $supaurl, $supakey;

    $url = $supaurl . '/rest/v1/' . $endPoint;
    $ch = curl_init($url);

    $headers = [
        'apikey: ' . $supakey,
        'Authorization: Bearer ' . $supakey,
        'Content-Type: application/json',
        'Prefer: return=representation'
    ];

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TCP_NODELAY, 1); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);

    return json_decode($response, true);
}