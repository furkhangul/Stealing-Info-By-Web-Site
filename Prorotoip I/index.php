<?php
// IP adresini al
$user_ip = $_SERVER['REMOTE_ADDR'];

// IP Geolocation API'sinden konum bilgisi al (örneğin, ip-api.com)
$geo_url = "http://ip-api.com/json/$user_ip";
$geo_info = json_decode(file_get_contents($geo_url), true);

// Konum bilgilerini al
$country = $geo_info['country'];
$region = $geo_info['regionName'];
$city = $geo_info['city'];

// Sunucu zaman dilimini ayarla (örneğin, İstanbul)
date_default_timezone_set('Europe/Istanbul');

// Sunucu tarih ve saat bilgisi
$server_date_time = date("Y-m-d H:i:s");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoş Geldiniz</title>
    <script>
        function sendData() {
            // JavaScript ile kullanıcıdan alınacak bilgiler
            var screenWidth = window.screen.width;
            var screenHeight = window.screen.height;
            var language = navigator.language || navigator.userLanguage;
            var userAgent = navigator.userAgent;
            var clientDateTime = new Date().toISOString(); // JavaScript ile tarih ve saat bilgisi

            // Çerezleri oku
            var cookies = document.cookie;

            // Pil durumu
            navigator.getBattery().then(function(battery) {
                var batteryLevel = battery.level * 100; // Pil yüzdesi
                var isCharging = battery.charging ? 'Evet' : 'Hayır';

                // Bilgileri PHP'ye gönder
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "save.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send("ip=<?php echo $user_ip; ?>&server_date_time=<?php echo $server_date_time; ?>&client_date_time=" + clientDateTime + "&country=<?php echo $country; ?>&region=<?php echo $region; ?>&city=<?php echo $city; ?>&screen_width=" + screenWidth + "&screen_height=" + screenHeight + "&language=" + language + "&user_agent=" + encodeURIComponent(userAgent) + "&battery_level=" + batteryLevel + "&is_charging=" + isCharging + "&cookies=" + encodeURIComponent(cookies));
            });
        }
    </script>
</head>
<body onload="sendData()">
    <h1>Hoş geldiniz! Bilgileriniz kaydedildi.</h1>
</body>
</html>
