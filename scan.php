<?php
// Minimal: Header lesen (wie vorher), ohne das restliche Verhalten zu ändern
$headers = function_exists('getallheaders') ? getallheaders() : [];

// 1) CPEE-Callback speichern (unverändert)
if (isset($headers['Cpee-Callback'])) {
    file_put_contents('callback.url', $headers['Cpee-Callback']);
    header('CPEE-CALLBACK: true');
    exit;
}

// 2) Parameter auslesen
$flight = $_GET['flight'] ?? null;
$back   = isset($_GET['back']);

// 3) Einheitliche "scanned"-Nutzlast bestimmen
$scanned = null;
if ($back) {
    $scanned = 'back';
} elseif (!empty($flight)) {
    $scanned = $flight;
}

// 4) Wenn etwas gescannt wurde: lokal speichern + an CPEE pushen
if ($scanned !== null) {
    // 4.1 lokal speichern (wie bisher in deiner Logik, nur um 'scanned' erweitert)
    file_put_contents("scannedflight.json", json_encode([
        "scanned"   => $scanned,        // "back" oder Flugnummer
        "flight"    => ($scanned === 'back' ? null : $scanned),
        "timestamp" => time()
    ]));

    // 4.2 an CPEE senden, falls callback.url existiert (PUT mit {"scanned": ...})
    if (file_exists('callback.url')) {
        $callback = @file_get_contents('callback.url');
        $data = json_encode(["scanned" => $scanned]);

        $opts = [
            'http' => [
                'method'  => 'PUT',
                'header'  => "Content-Type: application/json\r\n",
                'content' => $data,
                'timeout' => 5,
            ]
        ];
        $context  = stream_context_create($opts);
        $response = @file_get_contents($callback, false, $context);

        if ($response !== false) {
            if ($scanned === 'back') {
                echo "✅ BACK gesendet & an CPEE geschickt";
            } else {
                echo "✅ Flug $scanned gespeichert & an CPEE geschickt";
            }
        } else {
            if ($scanned === 'back') {
                echo "❌ BACK gespeichert, aber Fehler beim Senden an CPEE";
            } else {
                echo "❌ Gespeichert, aber Fehler beim Senden an CPEE";
            }
        }
    } else {
        if ($scanned === 'back') {
            echo "ℹ️ BACK gespeichert, aber kein Callback vorhanden.";
        } else {
            echo "ℹ️ Flug gespeichert ($scanned), aber kein Callback vorhanden.";
        }
    }
    exit;
}

// 5) Kein Parameter → warten (wie vorher)
echo "⏳ Warte auf Scan mit ?flight=... oder ?back=1";
