<?php
    require "./event/connection_db.php";

    session_start();
    if (!isset($_SESSION['username']) || $_SESSION['role'] != 0) {
        header("Location: ?page=portal");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uidcardHeadDel'])) {
        $cardToDelete = $_POST['uidcardHeadDel'];

        try {
            $conn->begin_transaction();

            $checkHistoryQuery = "SELECT COUNT(*) FROM history WHERE serial_number = ?";
            $checkStmt = $conn->prepare($checkHistoryQuery);
            $checkStmt->bind_param("s", $cardToDelete);
            $checkStmt->execute();
            $checkStmt->bind_result($historyCount);
            $checkStmt->fetch();
            $checkStmt->close();

            if ($historyCount > 0) {
                $updateHistoryQuery = "UPDATE history SET id_uidCard = 1 WHERE serial_number = ?";
                $updateStmt = $conn->prepare($updateHistoryQuery);
                $updateStmt->bind_param("s", $cardToDelete);

                if (!$updateStmt->execute()) {
                    throw new Exception("Failed to update history.");
                }

                $updateStmt->close();
            }

            $deleteQuery = "DELETE FROM uid_card WHERE serial_number = ?";
            $stmt = $conn->prepare($deleteQuery);
            $stmt->bind_param("s", $cardToDelete);

            if ($stmt->execute()) {
                $_SESSION['statusDelete1'] = "success";
                $_SESSION['titleDelete1'] = "Yeah,";
                $_SESSION['msgDelete1'] = "Card has been successfully deleted!";
            } else {
                throw new Exception("Failed to delete card.");
            }

            $stmt->close();

            $conn->commit();
        } catch (Exception $e) {
            // Log the exception for debugging purposes
            error_log("Exception caught: " . $e->getMessage());

            $conn->rollback();
            $_SESSION['statusDelete1'] = "error";
            $_SESSION['titleDelete1'] = "Oh no,";
            $_SESSION['msgDelete1'] = "An error occurred while attempting to delete card data. Please contact support for assistance.";
        } finally {
            $conn->close();
        }

        header("Location: ?page=cardManagement");
        exit();
    }
?>
