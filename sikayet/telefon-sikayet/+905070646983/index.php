<?php
// Kullanıcı IP'sini alma fonksiyonu
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// Kullanıcının tarayıcı ve cihaz bilgilerini alma fonksiyonu
function getUserDeviceInfo() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // Cihaz tipi belirleme
    $device_type = "Unknown";
    if (preg_match('/Android/i', $userAgent)) {
        $device_type = "Android";
    } elseif (preg_match('/iPhone|iPad/i', $userAgent)) {
        $device_type = "iOS";
    } elseif (preg_match('/Windows/i', $userAgent)) {
        $device_type = "PC (Windows)";
    } elseif (preg_match('/Macintosh|Mac OS X/i', $userAgent)) {
        $device_type = "PC (Mac)";
    }

    // Cihaz modeli ve detay bilgisi
    $device_model = "Unknown Device";
    if (preg_match('/Android ([\d\.]+); (.*?)(?= Build)/i', $userAgent, $matches)) {
        $device_model = "Android " . $matches[1] . " - " . $matches[2];
    } elseif (preg_match('/iPhone;.*?OS ([\d_]+)/i', $userAgent, $matches)) {
        $device_model = "iPhone - iOS " . str_replace('_', '.', $matches[1]);
    } elseif (preg_match('/iPad;.*?OS ([\d_]+)/i', $userAgent, $matches)) {
        $device_model = "iPad - iOS " . str_replace('_', '.', $matches[1]);
    } elseif (preg_match('/Windows NT ([\d\.]+)/i', $userAgent, $matches)) {
        $device_model = "Windows - Version " . $matches[1];
    } elseif (preg_match('/Macintosh;.*?Mac OS X ([\d_\.]+)/i', $userAgent, $matches)) {
        $device_model = "Mac - macOS " . str_replace('_', '.', $matches[1]);
    }

    // Tarayıcı bilgileri
    $browser = "Unknown Browser";
    if (preg_match('/Chrome\/([\d\.]+)/i', $userAgent, $matches)) {
        $browser = "Google Chrome - Version " . $matches[1];
    } elseif (preg_match('/Firefox\/([\d\.]+)/i', $userAgent, $matches)) {
        $browser = "Mozilla Firefox - Version " . $matches[1];
    } elseif (preg_match('/Safari\/([\d\.]+)/i', $userAgent) && !preg_match('/Chrome/i', $userAgent)) {
        $browser = "Safari - Version " . $matches[1];
    } elseif (preg_match('/Edge\/([\d\.]+)/i', $userAgent, $matches)) {
        $browser = "Microsoft Edge - Version " . $matches[1];
    } elseif (preg_match('/MSIE ([\d\.]+)/i', $userAgent, $matches)) {
        $browser = "Internet Explorer - Version " . $matches[1];
    }

    return [
        'device_type' => $device_type,
        'device_model' => $device_model,
        'browser' => $browser,
    ];
}

// Kullanıcının IP adresini al
$user_ip = getUserIP();

// IP konum bilgisi almak için bir API kullan (ipinfo.io)
$location_data = @file_get_contents("http://ip-api.com/json/{$user_ip}?fields=status,message,country,regionName,city,lat,lon,isp");
$location = "Unknown"; // Varsayılan konum
$provider_info = "Unknown"; // Varsayılan ISS bilgisi

if ($location_data) {
    $location_info = json_decode($location_data, true);
    if ($location_info['status'] === 'success') {
        $city = $location_info['city'] ?? 'Unknown City';
        $country = $location_info['country'] ?? 'Unknown Country';
        $latitude = $location_info['lat'] ?? 'Unknown Latitude';
        $longitude = $location_info['lon'] ?? 'Unknown Longitude';
        $location = "{$city}, {$country} (Lat: {$latitude}, Lon: {$longitude})";
        $provider_info = $location_info['isp'] ?? 'Unknown Provider';
    }
}

// Giriş tarihi ve saati
$entry_time = date('Y-m-d H:i:s');

// Tarayıcı ve cihaz bilgilerini al
$device_info = getUserDeviceInfo();
$device_type = $device_info['device_type'];
$device_model = $device_info['device_model'];
$browser = $device_info['browser'];

// Çerez bilgisi
$cookies = isset($_COOKIE) ? json_encode($_COOKIE) : 'No cookies';

// Kullanıcı hareketi: Sayfa tıklama verisi
$click_activity = "Page clicked at: " . $entry_time;

// Ekran çözünürlüğü
$screen_resolution = isset($_COOKIE['screen_resolution']) ? $_COOKIE['screen_resolution'] : 'Unknown Resolution';

// Tarayıcı dili
$browser_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'Unknown Language';

// Referer bilgisi
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'No Referer';

// Kayıt dosyasının adı
$file = 'kayitlar.txt';

// Kayıt içeriği
$log = "-------------------\n";
$log .= "IP Adresi         : {$user_ip}\n";
$log .= "Konum             : {$location}\n";
$log .= "ISS               : {$provider_info}\n";
$log .= "Giriş Tarihi      : {$entry_time}\n";
$log .= "Cihaz Türü        : {$device_type}\n";
$log .= "Cihaz Modeli      : {$device_model}\n";
$log .= "Tarayıcı          : {$browser}\n";
$log .= "Çerezler          : {$cookies}\n";
$log .= "Aktivite          : {$click_activity}\n";
$log .= "Ekran Çözünürlüğü : {$screen_resolution}\n";
$log .= "Tarayıcı Dili     : {$browser_language}\n";
$log .= "Referer           : {$referer}\n";

// Kayıtları dosyaya ekle
file_put_contents($file, $log, FILE_APPEND | LOCK_EX);
?>

<!DOCTYPE html>
<html lang="tr">
<img src="logo.svg" alt="Logo" class="logo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Müşteri Memnuniyetinin Adresi - Şikayetvar </title>
    <link rel="icon" href="images.png" type="image/png">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f4f8;
        }
        .logo {
            position: absolute;
            top: 0px;
            left: 25px; /* Sağdan mesafe */
            width: 150px; /* Boyut ayarı */
            height: 150px; /* Boyut ayarı */
        }

        .button-container {
            text-align: center;
        }

        .modern-button {
            background-color: #007bff; /* Mavi renk */
            color: white;
            font-size: 18px;
            font-weight: bold;
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modern-button:hover {
            background-color: #0056b3; /* Hoverda daha koyu mavi */
            transform: scale(1.1); /* Hoverda buton büyür */
        }

        .modern-button:active {
            transform: scale(0.98); /* Butona basıldığında küçülme */
        }

        .modern-button:focus {
            outline: none;
        }
    </style>
</head>
<body>
    <div class="button-container">
               <a href="http://sikayetvar.wuaze.com/sikayet/telefon-sikayet/+905070646983/kullanicihakkinda/" style="color: white;text-decoration: none;" class="modern-button">Şikayete Git</a>
    </div>
</body>
</html>
