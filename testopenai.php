<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

// สร้างตัวแปรสำหรับ Guzzle Client
$client = new Client();

// ตัวแปรสำหรับการ Retry
$maxRetries = 5;
$retryCount = 0;
$response = null; // กำหนดค่าเริ่มต้นให้กับ $response

while ($retryCount < $maxRetries) {
    try {
        // ตัวอย่างการเรียกใช้งาน OpenAI API
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => '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', // แทนที่ด้วย API Key ของคุณ
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo', // ใช้โมเดลที่รองรับ
                'messages' => [
                    ['role' => 'user', 'content' => 'Hello, world!']
                ],
                'max_tokens' => 5,
            ],
        ]);

        // ถ้าสำเร็จให้ออกจากลูป
        break;

    } catch (ClientException $e) {
        // ตรวจสอบว่าข้อผิดพลาดคือ 429 หรือไม่
        if ($e->getResponse()->getStatusCode() == 429) {
            sleep(1); // รอ 1 วินาทีก่อนทำการ retry
            $retryCount++;
        } else {
            // แสดงข้อความข้อผิดพลาดและรายละเอียด
            echo "Client error: " . $e->getMessage() . "\n";
            if ($e->hasResponse()) {
                echo "Response Status Code: " . $e->getResponse()->getStatusCode() . "\n";
                echo "Response Body: " . $e->getResponse()->getBody() . "\n";
            }
            exit; // ออกจากโปรแกรม
        }
    } catch (GuzzleException $e) {
        // ข้อผิดพลาดอื่นๆ ที่ไม่ใช่ ClientException
        echo "Guzzle error: " . $e->getMessage() . "\n";
        exit; // ออกจากโปรแกรม
    }
}

// แสดงผลลัพธ์ถ้า $response มีค่า
if ($response) {
    $data = json_decode($response->getBody(), true);
    if (isset($data['choices'][0]['message']['content'])) {
        echo $data['choices'][0]['message']['content'];
    } else {
        echo "No content in the response.";
    }
} else {
    echo "No response received.";
}
?>

