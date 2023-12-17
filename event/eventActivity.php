<?php
    header("Content-Type: application/json");

    if(isset($_GET['apiKey'])){
        $apiKey = $_GET['apiKey'];
        $expectedApiKey = "123SayangSemuanya";

        if($apiKey === $expectedApiKey){
            $timezone = new DateTimeZone('Asia/Jakarta');
            $timestamp = (new DateTime('', $timezone))->format('Y-m-d H:i:s');
            
            if(isset($_GET['serial_number'])){
                $serialNumber = $_GET['serial_number'];
                $accessResult = checkAccess($serialNumber);

                if ($accessResult == 1) {
                    $response = ["status" => "success", "accessResult" => $accessResult];
                    $idOwner = getIdOwner($serialNumber);

                } else {
                    $response = ["status" => "invalid", "accessResult" => $accessResult];
                    $idOwner = 1;
                }
                $actionType_1 = 0;
                echo json_encode($response);

            } else if (isset($_GET['button_exit'])){
                $button = $_GET['button_exit'];
                $actionType_1 = 1;
            }

            require 'connection_db.php';
                
            $insertSql = "INSERT INTO history (serial_number, id_uidCard, action_type, timestamp) VALUES (?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("siis", $serialNumber, $idOwner, $actionType_1, $timestamp);
            $insertStmt->execute();

        } else {
            $response = ["status" => "error", "message" => "Invalid API Key"];
            echo json_encode($response);
        }
    } else {
        $response = ["status" => "error", "message" => "Invalid Request"];
        echo json_encode($response);
    }


    function getIdOwner($serialNumber) {
        require 'connection_db.php';

        $getIdOwnerQuery = "SELECT id FROM uid_card WHERE serial_number = ?";
        $getIdOwnerStmt = $conn->prepare($getIdOwnerQuery);
        $getIdOwnerStmt->bind_param("s", $serialNumber);
        $getIdOwnerStmt->execute();
        $getIdOwnerStmt->bind_result($idOwner);

        $success = $getIdOwnerStmt->fetch();
        $getIdOwnerStmt->close();

        if ($success) {
            return $idOwner;
        } else {
            return null;
        }
    }

    function checkAccess($serialNumber) {
        require 'connection_db.php';

        $checkSerialNumberQuery = "SELECT COUNT(*) as count FROM uid_card WHERE serial_number = ?";
        $checkStmt = $conn->prepare($checkSerialNumberQuery);
        $checkStmt->bind_param("s", $serialNumber);
        $checkStmt->execute();
        $checkStmt->bind_result($serialNumberCount);
        $checkStmt->fetch();
        $checkStmt->close();

        $conn->close();

        if ($serialNumberCount > 0) {
            return 1;
        } else {
            return 0;
        }
    }
?>
