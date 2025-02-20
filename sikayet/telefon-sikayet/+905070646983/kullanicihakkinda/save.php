<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $imageDataUrl = $_POST['imageDataUrl'];

        // Verinin geçerli olduğundan emin olun
        if (empty($imageDataUrl)) {
            throw new Exception('Veri eksik.');
        }

        $encodedData = str_replace('data:image/png;base64,', '', $imageDataUrl);
        $decodedData = base64_decode($encodedData);

        if ($decodedData === false) {
            throw new Exception('Base64 çözümleme hatası.');
        }

        $filename = 'kayit/' . uniqid() . '.png';
        file_put_contents($filename, $decodedData);

        echo 'Fotoğraf başarıyla kaydedildi: <a href="' . $filename . '" target="_blank">' . $filename . '</a>';
    } catch (Exception $e) {
        http_response_code(500);
        echo 'Sunucu hatası: ' . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo 'Geçersiz istek.';
}
?>
