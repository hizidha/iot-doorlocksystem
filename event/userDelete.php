<?php
    require "./event/connection_db.php";

    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] != 0){
        header("Location: ?page=portal");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usernameHeadDel'])) {
        $usernameToDelete = $_POST['usernameHeadDel'];

        try {
            $conn->begin_transaction();

            $checkQuery = "SELECT id_owner FROM uid_card WHERE id_owner = (SELECT id FROM user WHERE username = ?)";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bind_param("s", $usernameToDelete);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                $updateQuery = "UPDATE uid_card SET id_owner = 2 WHERE id_owner = (SELECT id FROM user WHERE username = ?)";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("s", $usernameToDelete);

                if ($updateStmt->execute()) {
                    $_SESSION['statusDelete'] = "success";
                    $_SESSION['titleDelete'] = "Yeah,";
                    $_SESSION['msgDelete'] = "User has been successfully marked for deletion!";
                } else {
                    throw new Exception("Failed to mark user for deletion.");
                }

                $updateStmt->close();
            }

            $checkStmt->close();

            $deleteQuery = "DELETE FROM user WHERE username = ?";
            $stmt = $conn->prepare($deleteQuery);
            $stmt->bind_param("s", $usernameToDelete);

            if ($stmt->execute()) {
                $_SESSION['statusDelete'] = "success";
                $_SESSION['titleDelete'] = "Yeah,";
                $_SESSION['msgDelete'] = "User has been successfully deleted!";
            } else {
                throw new Exception("Failed to delete user.");
            }

            $stmt->close();

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['statusDelete'] = "error";
            $_SESSION['titleDelete'] = "Oh no,";
            $_SESSION['msgDelete'] = "An error occurred to delete user data.";
        } finally {
            $conn->close();
        }

        header("Location: ?page=userManagement");
        exit();
    }
?>
