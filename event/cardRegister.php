<?php
    require "./event/connection_db.php";

    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] == 2){
        header("Location: ?page=portal");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $serialCard = mysqli_real_escape_string($conn, $_POST['SerialCardAdd']);
        $ownerCard = mysqli_real_escape_string($conn, $_POST['ownercardAdd']);
    
        $timezone = new DateTimeZone('Asia/Jakarta');
        $timestamp = (new DateTime('', $timezone))->format('Y-m-d H:i:s');
    
        $checkCardQuery = "SELECT COUNT(*) FROM uid_card WHERE serial_number = ?";
        $checkStmt = $conn->prepare($checkCardQuery);
        $checkStmt->bind_param("s", $serialCard);
        $checkStmt->execute();
        $checkStmt->bind_result($CardCount);
        $checkStmt->fetch();
        $checkStmt->close();
    
        if ($CardCount > 0) {
            $_SESSION['statusRegister1'] = "error";
            $_SESSION['titleRegister1'] = "Oh no,";
            $_SESSION['msgRegister1'] = "Card serial number already exists, please choose a different one.";
        } else {
            $insertSql = "INSERT INTO uid_card (serial_number, id_owner, created_at, lastmodified_at) 
                          VALUES (?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("ssss", $serialCard, $ownerCard, $timestamp, $timestamp);
    
            if ($insertStmt->execute()) {
                $_SESSION['statusRegister1'] = "success";
                $_SESSION['titleRegister1'] = "Yeah,";
                $_SESSION['msgRegister1'] = "Card added successfully!";
            } else {
                $_SESSION['statusRegister1'] = "error";
                $_SESSION['titleRegister1'] = "Oh no,";
                $_SESSION['msgRegister1'] = "An error occurred while adding the card. Please try again.";
            }
            $insertStmt->close();
        }
    
        header("Location: ?page=cardManagement");
        exit();
    }
?>
