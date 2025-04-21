<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Đăng ký tài khoản</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Roboto', sans-serif;
    }

    body {
      margin: 0;
      background-color: #f4f4f4;
    }

    .container {
      position: relative;
      width: 100%;
      max-width: 400px;
      margin: 50px auto;
      padding: 30px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .container h2 {
      text-align: center;
      color: #222;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      color: #333;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    input[type="submit"] {
      width: 100%;
      background-color: #222;
      color: #fff;
      padding: 12px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }

    input[type="submit"]:hover {
      background-color: #444;
    }

    .login-link {
      text-align: center;
      margin-top: 15px;
    }

    .login-link a {
      color: #444;
      text-decoration: none;
    }

    .login-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="container">

    <h2>Đăng Nhập Tài Khoản</h2>
    <form action="login_process.php" method="POST">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Mật khẩu</label>
        <input type="password" id="password" name="password" required>
        <div style="margin:10px;">Quên mật khẩu?</div> 
      </div>
      <input type="submit" value="Đăng Nhập" onclick="return kTraDangNhap()">
    </form>

    <div class="login-link">
      Chưa có tài khoản? <a href="register.php">Đăng Ký</a>
    </div>
  </div>

  <script>
    function closeForm() {
      document.querySelector('.container').style.display = 'none';
    }
    function kTraDangNhap(){
            const eMail=document.getElementById("email");
            const matKhau=document.getElementById("password");
            if(eMail.value==""){
                alert("Email không được bỏ trống!");
                eMail.focus();
                return false;
            }
            if(matKhau.value==""){
                alert("Mật khẩu không được bỏ trống!");
                matKhau.focus();
                return false;
            }
            return true;
        }
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.get("error") === "wrongpass") {
                alert("Mật khẩu không đúng! Vui lòng thử lại.");
                document.getElementById("password").focus();
            }

            if (urlParams.get("error") === "notfound") {
                alert("Email không tồn tại!");
                document.getElementById("email").focus();
            }

            // Nếu có tên đăng nhập trước đó, điền lại vào ô để người dùng không cần nhập lại
            let eMail = urlParams.get("email");
            if (eMail) {
                document.getElementById("email").value = eMail;
            }
        };
  </script>

</body>
</html>

