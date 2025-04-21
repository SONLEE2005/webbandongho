<?php
    session_start();
    session_destroy();
    header("Location: http://localhost/webbandongho-main/index.php?logout=success");
    exit();
?>
