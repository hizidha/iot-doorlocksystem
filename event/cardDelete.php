<?php
    require "./event/connection_db.php";

    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] != 0){
        header("Location: ?page=portal");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uidcardHeadDel'])) {
        $cardToDelete = $_POST['uidcardHeadDel'];

        $deleteQuery = "DELETE FROM uid_card WHERE serial_number = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("s", $cardToDelete);

        if ($stmt->execute()) {
            $_SESSION['statusDelete1'] = "success";
            $_SESSION['titleDelete1'] = "Yeah,";
            $_SESSION['msgDelete1'] = "Card has been successfully deleted!";
        } else {
            $_SESSION['statusDelete1'] = "error";
            $_SESSION['titleDelete1'] = "Oh no,";
            $_SESSION['msgDelete1'] = "Failed to delete card.";
        }

        $stmt->close();
        $conn->close();

        header("Location: ?page=cardManagement");
        exit();
    }
?>
