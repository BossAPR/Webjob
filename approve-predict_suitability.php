<?php
include 'connectdb.php';

function calculateSuitabilityScore($job_id, $applicant_id) {
    $url = "http://127.0.0.1:5000/predict/" . ($applicant_id + 1);  // การเพิ่ม 1 ต้องอยู่ในวงเล็บ
    
    // ดึงข้อมูลจาก API
    $response = @file_get_contents($url);
    
    // แสดงผลการตอบกลับจาก API
    echo "Response from API for applicant ID $applicant_id: " . $response . "<br>";

    if ($response === false) {
        echo "Error: ไม่สามารถเรียก API สำหรับ applicant ID: $applicant_id<br>";
        return null; // คืนค่า null เมื่อไม่สามารถเรียก API
    }

    $scores = json_decode($response, true);
    
    if (isset($scores['error'])) {
        echo "Error for applicant ID $applicant_id: " . $scores['error'] . "<br>";
        return null; // คืนค่า null หากพบข้อผิดพลาดจาก API
    }

    if (!isset($scores['suitability_score'])) {
        echo "Error: API ไม่ส่งค่าความเหมาะสมกลับมาที่ applicant ID: $applicant_id<br>";
        return null; // คืนค่า null หากไม่มี `suitability_score`
    }

    return $scores['suitability_score']; // คืนค่าความเหมาะสมที่ได้รับ
}
?>
