<?php
    $hostname = "localhost";//"sql111.infinityfree.com"; //
    $username = "root"; //"if0_34717110"; //
    $password = ""; //"PthDQZioBnyDx"; //"";
    $db = "doorlocksystem"; //"if0_34717110_mydramalist"; //"db_mdl";

    $conn = mysqli_connect($hostname, $username, $password, $db);
    
    if(!$conn){
        die("gagal: ".mysqli_connect_error());
    }
    $message = "Koneksi Berhasil";
?>