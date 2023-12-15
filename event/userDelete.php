<?php
    require "./event/connection_db.php";

    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] != 0){
        header("Location: ?page=portal");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usernameHeadDel'])) {
        $usernameToDelete = $_POST['usernameHeadDel'];

        $deleteQuery = "DELETE FROM user WHERE username = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("s", $usernameToDelete);

        if ($stmt->execute()) {
            $_SESSION['statusDelete'] = "success";
            $_SESSION['titleDelete'] = "Yeah,";
            $_SESSION['msgDelete'] = "User has been successfully deleted!";
        } else {
            $_SESSION['statusDelete'] = "error";
            $_SESSION['titleDelete'] = "Oh no,";
            $_SESSION['msgDelete'] = "Failed to delete user.";
        }

        $stmt->close();
        $conn->close();

        header("Location: ?page=userManagement");
        exit();
    }
?>
