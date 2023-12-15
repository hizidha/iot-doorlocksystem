<?php
    require "./event/connection_db.php";

    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] == 2){
        header("Location: ?page=portal");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fullName = htmlspecialchars($_POST['fullNameAddUser']);
        $username = htmlspecialchars($_POST['usernameAddUser']);
        $password = password_hash($_POST['passwordAddUser'], PASSWORD_DEFAULT);
        $departement = htmlspecialchars($_POST['departmentAddUser']);
        $division = htmlspecialchars($_POST['divisionAddUser']);
        $position = htmlspecialchars($_POST['positionAddUser']);
        $role = $_POST['roleAddUser'];
        
        $timezone = new DateTimeZone('Asia/Jakarta');
        $timestamp = (new DateTime('', $timezone))->format('Y-m-d H:i:s');

        // Check already exists
        $checkUsernameQuery = "SELECT COUNT(*) FROM user WHERE username = ?";
        $checkStmt = $conn->prepare($checkUsernameQuery);
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();
        $checkStmt->bind_result($usernameCount);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($usernameCount > 0) {
            $_SESSION['statusRegister'] = "error";
            $_SESSION['titleRegister'] = "Oh no,";
            $_SESSION['msgRegister'] = "Username already exists, please choose a different one.";
        } else {
            $insertSql = "INSERT INTO user (full_name, username, password, department, division, position, role, created_at, lastmodified_at) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("ssssssiss", $fullName, $username, $password, $departement, $division, $position, $role, $timestamp, $timestamp);

            if ($insertStmt->execute()) {
                $_SESSION['statusRegister'] = "success";
                $_SESSION['titleRegister'] = "Yeah,";
                $_SESSION['msgRegister'] = "User added successfully!";
            } else {
                $_SESSION['statusRegister'] = "error";
                $_SESSION['titleRegister'] = "Oh no,";
                $_SESSION['msgRegister'] = "An error occurred while adding the user. Please try again.";
            }
            $insertStmt->close();
        }

        header("Location: ?page=userManagement");
        exit();
    }
?>
