<?php
    require "./event/connection_db.php";

    session_start();
    if (!isset($_SESSION['username']) || $_SESSION['role'] == 2) {
        header("Location: ?page=portal");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $serialCard = mysqli_real_escape_string($conn, $_POST['SerialCardAdd']);
        $ownerCard = mysqli_real_escape_string($conn, $_POST['ownercardAdd']);
    
        $timezone = new DateTimeZone('Asia/Jakarta');
        $timestamp = (new DateTime('', $timezone))->format('Y-m-d H:i:s');
    
        $insertUidCardQuery = "INSERT INTO uid_card (serial_number, id_owner, created_at, lastmodified_at) 
                               VALUES (?, ?, ?, ?)";
        
        try {
            $insertUidCardStmt = $conn->prepare($insertUidCardQuery);
            $insertUidCardStmt->bind_param("ssss", $serialCard, $ownerCard, $timestamp, $timestamp);

            if ($insertUidCardStmt->execute()) {
                $checkHistoryQuery = "SELECT COUNT(*) FROM history WHERE serial_number = ?";
                $checkStmt = $conn->prepare($checkHistoryQuery);
                $checkStmt->bind_param("s", $serialCard);
                $checkStmt->execute();
                $checkStmt->bind_result($historyCount);
                $checkStmt->fetch();
                $checkStmt->close();

                if ($historyCount > 0) {
                    $getOwnerIdQuery = "SELECT id FROM uid_card WHERE serial_number = ?";
                    $getOwnerIdStmt = $conn->prepare($getOwnerIdQuery);
                    $getOwnerIdStmt->bind_param("s", $serialCard);
                    $getOwnerIdStmt->execute();
                    $getOwnerIdStmt->bind_result($ownerId);
                    $getOwnerIdStmt->fetch();
                    $getOwnerIdStmt->close();

                    if ($ownerId !== null) {
                        $updateHistoryQuery = "UPDATE history SET id_uidCard = ? WHERE serial_number = ?";
                        $updateHistoryStmt = $conn->prepare($updateHistoryQuery);
                        $updateHistoryStmt->bind_param("ss", $ownerId, $serialCard);
                        $updateHistoryStmt->execute();
                        $updateHistoryStmt->close();
                    }
                }

                $_SESSION['statusRegister1'] = "success";
                $_SESSION['titleRegister1'] = "Yeah,";
                $_SESSION['msgRegister1'] = "Card added successfully!";
            } else {
                $_SESSION['statusRegister1'] = "error";
                $_SESSION['titleRegister1'] = "Oh no,";
                $_SESSION['msgRegister1'] = "An error occurred while adding the card. Please try again.";
            }

            $insertUidCardStmt->close();
            $conn->close();

            header("Location: ?page=cardManagement");
            exit();
        } catch (mysqli_sql_exception $e) {
            $_SESSION['statusRegister1'] = "error";
            $_SESSION['titleRegister1'] = "Oh no,";
            $_SESSION['msgRegister1'] = "Card with the same serial number already exists.";

            header("Location: ?page=cardManagement");
            exit();
        }
    }
?>
