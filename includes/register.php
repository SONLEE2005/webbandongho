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
    <h2>Đăng Ký Tài Khoản</h2>
    <form id="registerForm">
      <div class="form-group">
        <label for="username">Tên người dùng</label>
        <input type="text" id="username" name="username" required>
      </div>

      <div class="form-group">
        <label for="phone">Số điện thoại</label>
        <input type="text" id="sdt" name="sdt" required>
      </div>

      <div class="form-group">
        <label for="address">Địa chỉ</label>
        <input type="text" id="diachi" name="diachi" required>
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="password">Mật khẩu</label>
        <input type="password" id="password" name="password" required>
      </div>

      <div class="form-group">
        <label for="confirm_password">Nhập lại mật khẩu</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
      </div>

      <input type="submit" value="Đăng ký" onclick="return kTraDangKy()">
    </form>
    <!-- Hiển thị thông báo -->
    <div id="message" style="text-align: center; margin:10px"></div>

    <div class="login-link">
      Đã có tài khoản? <a href="login.php">Đăng nhập</a>
    </div>
  </div>
</body>
<script>
  function closeForm() {
    document.querySelector('.container').style.display = 'none';
  }
  function kTraDangKy(){
          const tenNgDung= document.getElementById("username");
          const diaChi=document.getElementById("diachi");
          const soDT=document.getElementById("sdt");
          const eMail= document.getElementById("email");
          const matKhau1=document.getElementById("password");
          const matKhau2=document.getElementById("confirm_password");
          if(tenNgDung.value==""){
              alert("Tên người dùng không được bỏ trống!");
              tenNgDung.focus();
              return false;
          }
          if(soDT.value==""){
              alert("Số điện thọai không được bỏ trống!");
              soDT.focus();
              return false;
          }
          if(!/^0\d{9}$/.test(soDT.value)){
              alert("Số điện thọai không hợp lệ!");
              soDT.focus();
              return false;
          }
          if(diaChi.value==""){
              alert("Địa chỉ không được bỏ trống!");
              diaChi.focus();
              return false;
          }
          if(eMail.value==""){
            alert("Email không được bỏ trống!");
            eMail.focus();
            return false;
          }
          if(!/(\.com|\.vn)$/i.test(eMail.value)){
              alert("Đuôi email phải kết thúc với '.com' hoặc '.vn'");
              eMail.focus();
              return false;
          }
          if(matKhau1.value==""){
              alert("Mật khẩu không được bỏ trống!");
              matKhau1.focus();
              return false;
          }if(matKhau2.value==""){
              alert("Mật khẩu xác nhận không được bỏ trống!");
              matKhau2.focus();
              return false;
          }
          if(matKhau1.value!=matKhau2.value){
              alert("Mật khẩu xác nhận lại không khớp!");
              matKhau2.focus();
              return false;
          }
          return true;
      }
      window.onload = function() {
          const urlParams = new URLSearchParams(window.location.search);
          if (urlParams.get("success") === "1") {
              alert("Đăng ký thành công!"); // Hiển thị thông báo
              history.replaceState(null, "", "register.php"); // Xóa tham số trên URL để tránh hiển thị lại khi tải lại trang
          }
          
          // Hiển thị thông báo lỗi nếu tên đăng nhập đã tồn tại
          if (urlParams.get("error") === "exists") {
              alert("Email đã tồn tại! Vui lòng chọn email khác.");
              document.getElementById("email").focus(); // Đưa con trỏ vào ô tên đăng nhập
          }
          let eMail = urlParams.get("email");
          let tenNgDung = urlParams.get("username");
          let soDT = urlParams.get("sdt");
          let diaChi = urlParams.get("diachi");
          if (eMail) {
              document.getElementById("email").value = eMail;
          }
          if (tenNgDung) {
              document.getElementById("username").value = tenNgDung;
          }
          if (soDT) {
              document.getElementById("sdt").value = soDT;
          }
          if (diaChi) {
              document.getElementById("diachi").value = diaChi;
          }
      };
</script>
<script>
document.getElementById("registerForm").addEventListener("submit", async function (e) {
    e.preventDefault(); // Ngăn gửi form theo cách truyền thống

    const form = e.target;
    const formData = new FormData(form);

    const response = await fetch("register_process.php", {
        method: "POST",
        body: formData
    });

    const result = await response.json(); // Phản hồi là JSON

    const messageDiv = document.getElementById("message");
    messageDiv.style.color = result.success ? "green" : "red";
    messageDiv.textContent = result.message;

    if (result.success) {
        form.reset(); // Xóa form nếu đăng ký thành công
    }
});
</script>

</html>

