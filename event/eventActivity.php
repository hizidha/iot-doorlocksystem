<?php
    header("Content-Type: application/json");

    if (isset($_GET['apiKey']) && isset($_GET['serial_number'])) {
        $serialNumber = $_GET['serial_number'];
        $apiKey = $_GET['apiKey'];

        $expectedApiKey = "123SayangSemuanya";

        if ($apiKey === $expectedApiKey) {
            $accessResult = checkAccess($serialNumber);

            if($accessResult == 1){
                $response = ["status" => "success", "accessResult" => $accessResult];  
            } else {
                $response = ["status" => "invalid", "accessResult" => $accessResult];
            }

            echo json_encode($response);
        } else {
            $response = ["status" => "error", "message" => "Invalid API Key"];
            echo json_encode($response);        
        }
    } else {
        $response = ["status" => "error", "message" => "Invalid Request"];
        echo json_encode($response);
    }

    function checkAccess($serialNumber) {
        $allowedSerialNumber = "C678C08B";

        if ($serialNumber === $allowedSerialNumber) {
            return 1;
        } else {
            return 0;
        }
    }
?>
