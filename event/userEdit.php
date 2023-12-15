<?php
    require "./event/connection_db.php";

    session_start();
    if(!isset($_SESSION['username']) || $_SESSION['role'] != 0) {
        header("Location: ?page=portal");
        exit();
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['usernameEdit'])) {
        $username = htmlspecialchars($_POST['usernameEdit']);
        $fullName = htmlspecialchars($_POST['fullNameEditUser']);
        $division = htmlspecialchars($_POST['divisionEditUser']);
        $department = htmlspecialchars($_POST['departmentEditUser']);
        $position = htmlspecialchars($_POST['positionEditUser']);
        $role = $_POST['roleEditUser'];
        
        $timezone = new DateTimeZone('Asia/Jakarta');
        $timestamp = (new DateTime('now', $timezone))->format('Y-m-d H:i:s');

        $updateSql = "UPDATE user SET full_name=?, division=?, department=?, position=?, role=?, lastmodified_at=?";

        $password = !empty($_POST['passwordEditUser']) ? password_hash($_POST['passwordEditUser'], PASSWORD_DEFAULT) : null;
        if ($password !== null) {
            $updateSql .= ", password=?";
        }

        $updateSql .= " WHERE username=?";
        $updateStmt = $conn->prepare($updateSql);

        if ($password !== null) {
            $updateStmt->bind_param("ssssisss", $fullName, $division, $department, $position, $role, $password, $username, $timestamp);
        } else {
            $updateStmt->bind_param("ssssiss", $fullName, $division, $department, $position, $role, $timestamp, $username);
        }

        if ($updateStmt->execute()) {
            $_SESSION['statusUpdate'] = "success";
            $_SESSION['titleUpdate'] = "Yeah,";
            $_SESSION['msgUpdate'] = "User data is updated successfully!";
        } else {
            $_SESSION['statusUpdate'] = "error";
            $_SESSION['titleUpdate'] = "Oh no,";
            $_SESSION['msgUpdate'] = "An error occurred while updating the user. Please try again.";
        }

        $updateStmt->close();
        $conn->close();

        header("Location: ?page=userManagement");
        exit();
    }
?>
