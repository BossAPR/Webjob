<?php
include 'connectdb.php';

if (isset($_SESSION['account_id'])) {
    $account_id = $_SESSION['account_id'];
    
    $query = "SELECT applicant_id FROM applicant WHERE account_id = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("i", $account_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $suitability_scores = []; 
        
        while ($row = $result->fetch_assoc()) {
            $applicant_id = $row['applicant_id'];

            if ($applicant_id) {
                $url = "http://127.0.0.1:5000/predict/" . $applicant_id;
                $response = file_get_contents($url);
                $scores = json_decode($response, true);

                if (isset($scores['error'])) {
                    echo "Error for applicant ID $applicant_id: " . $scores['error'] . "<br>";
                } else {
                    $suitability_scores[$applicant_id] = $scores; 
                }
            } else {
                echo "No applicant ID found.<br>";
            }
        }
        
        $_SESSION['suitability_scores'] = $suitability_scores; 
    } else {
        echo "No applicants found for account ID: $account_id.<br>";
    }
} else {
    echo "Please log in to view your suitability scores.";
}
?>
