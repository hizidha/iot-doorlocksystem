<?php
    require "./event/connection_db.php";

    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: ?page=portal");
    }

    $errorMsgLogin = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['usernameLogin'];
        $password = $_POST['passwordLogin'];

        $stmt = $conn->prepare("SELECT id, username, password, role FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $row['password'])) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];

                $_SESSION['loginSuccess'] = "Congratulation, you have successfully logged in to your account.";
                header("Location: ?page=dashboard");
                exit();
            } else {
                $errorMsgLogin = "Invalid username or password, please try again.";
            }
        } else {
            $errorMsgLogin = "Username not found, please check your username.";
        }

        $stmt->close();
    }

    $_SESSION['loginError'] = $errorMsgLogin;
    $conn->close();

    header("Location: ?page=portal");
    exit();
?>
