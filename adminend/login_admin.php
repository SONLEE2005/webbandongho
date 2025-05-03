<?php

?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng Nhập Quản Trị</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f3f3f3;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .container {
      background-color: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 400px;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      box-sizing: border-box;
    }
    input[type="submit"] {
      width: 100%;
      padding: 10px;
      background-color: #007BFF;
      color: white;
      border: none;
      cursor: pointer;
      font-weight: bold;
    }
    input[type="submit"]:hover {
      background-color: #0056b3;
    }
    .login-link {
      text-align: center;
      margin-top: 15px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Đăng Nhập Người Quản Trị</h2>
    <form action="login_admin_process.php" method="POST" onsubmit="return kTraDangNhap()">
      <div class="form-group">
        <label for="email">Email: </label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Mật khẩu: </label>
        <input type="password" id="password" name="password" required>
        <div style="margin-top: 8px;"><a href="#">Quên mật khẩu?</a></div> 
      </div>
      <input type="submit" value="Đăng Nhập">
    </form>

    <div class="login-link">
      Quay về <a href="../public/index.php">Trang người dùng</a>
    </div>
  </div>

  <script>
    function kTraDangNhap() {
      const email = document.getElementById('email').value.trim();
      const pass = document.getElementById('password').value.trim();
      if (email === "" || pass === "") {
        alert("Vui lòng nhập đầy đủ thông tin!");
        return false;
      }
      return true;
    }
    window.onload = function(){
      const urlParams = new URLSearchParams(window.location.search);

      if(urlParams.get("error") === "wrongpass"){
        alert("Mật khẩu không đúng! Vui lòng thử lại.");
        document.getElementById("password").focus();
      }

      if(urlParams.get("error") === "notfound"){
        alert("Email khong ton tai");
        document.getElementById("email").focus();
      }

      let email = urlParams.get("email");
      if(email){
        document.getElementById("email").value = email;
      }
    };
  </script>
</body>
</html>
