<?php
require 'connect.php'; // เชื่อมต่อฐานข้อมูล
require 'vendor/autoload.php'; // โหลด Autoload ของ Composer

$openai = OpenAI::client('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'); // ใส่ API Key ของคุณที่นี่

function calculateSuitabilityWithAI($applicant, $job, $openai) {
    $prompt = "
        ผู้สมัครงานที่มีข้อมูลต่อไปนี้:
        - อายุ: {$applicant['Age_range']}
        - การศึกษา: {$applicant['education']}
        - ประสบการณ์: {$applicant['experience']}
        - ประเภทงานที่สนใจ: {$applicant['employment_type']}
        - เงินเดือนที่ต้องการ: {$applicant['expected_salary']}
        
        และงานที่มีข้อมูลดังนี้:
        - อายุขั้นต่ำ: {$job['job_oldmin']}
        - อายุสูงสุด: {$job['job_oldmax']}
        - คุณสมบัติที่ต้องการ: {$job['job_qualification']}
        - ประสบการณ์ที่ต้องการ: {$job['job_exp']}
        - ประเภทงาน: {$job['job_type']}
        - เงินเดือน: {$job['job_salary']}

        คำนวณและให้เปอร์เซ็นต์ความเหมาะสมของผู้สมัครนี้กับงานนี้ (เต็ม 100%):
    ";

    // เรียก OpenAI เพื่อคำนวณ
    $response = $openai->completions()->create([
        'model' => 'gpt-3.5-turbo', // หรือโมเดลที่คุณต้องการใช้
        'prompt' => $prompt,
        'max_tokens' => 100,
    ]);

    return trim($response['choices'][0]['text']); // ตัดช่องว่างหรือตัวอักษรพิเศษออกจากคำตอบ
}

// ตัวอย่างข้อมูลผู้สมัครและงาน
$applicant = [
    'Age_range' => 25,
    'education' => 'ปริญญาตรี',
    'experience' => 5,
    'employment_type' => 'เต็มเวลา',
    'expected_salary' => 30000
];

$job = [
    'job_oldmin' => 22,
    'job_oldmax' => 30,
    'job_qualification' => 'ปริญญาตรี',
    'job_exp' => 3,
    'job_type' => 'เต็มเวลา',
    'job_salary' => 35000
];

// คำนวณความเหมาะสมโดยใช้ AI
$suitability_percentage = calculateSuitabilityWithAI($applicant, $job, $openai);

echo "เปอร์เซ็นต์ความเหมาะสม: $suitability_percentage%";
?>
