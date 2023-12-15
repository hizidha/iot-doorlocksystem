<?php
    require "./event/connection_db.php";

    session_start();
    if (!isset($_SESSION['username']) || $_SESSION['role'] != 0) {
        header("Location: ?page=portal");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cardHeadEdit'])) {
        $idCard = htmlspecialchars($_POST['cardHeadEdit']);
        $serialNumber = htmlspecialchars($_POST['SerialCardEdit']);
        $ownerCard = htmlspecialchars($_POST['ownercardEdit']);

        $timezone = new DateTimeZone('Asia/Jakarta');
        $timestamp = (new DateTime('now', $timezone))->format('Y-m-d H:i:s');

        $updateSql = "UPDATE uid_card SET serial_number=?, id_owner=?, lastmodified_at=? WHERE id=?";
        $updateStmt = $conn->prepare($updateSql);
        
        $updateStmt->bind_param("sisi", $serialNumber, $ownerCard, $timestamp, $idCard);

        if ($updateStmt->execute()) {
            $_SESSION['statusUpdate1'] = "success";
            $_SESSION['titleUpdate1'] = "Yeah,";
            $_SESSION['msgUpdate1'] = "Card data is updated successfully!";
        } else {
            $_SESSION['statusUpdate1'] = "error";
            $_SESSION['titleUpdate1'] = "Oh no,";
            $_SESSION['msgUpdate1'] = "An error occurred while updating the card data. Please try again.";
        }

        $updateStmt->close();
        $conn->close();

        header("Location: ?page=cardManagement");
        exit();
    }
?>
