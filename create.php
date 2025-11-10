<?php
$flight = $_GET['flight'] ?? $_POST['flight'] ?? null;
if (!$flight) {
  http_response_code(400);
  echo "No flight provided.";
  exit;
}

// 1. Speichere JSON
$data = ['flight' => $flight, 'timestamp' => time()];
file_put_contents('scannedflight.json', json_encode($data));

// 2. Callback senden, falls vorhanden
if (file_exists('callback.url')) {
    $callback = file_get_contents('callback.url');
    $payload = json_encode(["scanned" => $flight]);

    $ch = curl_init($callback);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload)
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    $response = curl_exec($ch);
    curl_close($ch);

    echo "✔️ Flug $flight gespeichert und an CPEE übermittelt.";
} else {
    echo "⚠️ Kein Callback vorhanden.";
}
?>
