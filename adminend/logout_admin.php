<?php
    session_start();
    session_destroy();
    header("Location: http://localhost/DoAnWeb2/webbandongho/adminend/login_admin.php?logout=success");
    exit();
?>