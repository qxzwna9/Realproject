<?php
// profile.php

// เริ่ม session
session_start();

// --- ส่วนป้องกันหน้า ---
// ตรวจสอบว่ามี session 'user_id' อยู่หรือไม่
if (!isset($_SESSION['user_id'])) {
    // ถ้าไม่มี (ยังไม่ได้ล็อกอิน) ให้ส่งกลับไปหน้า login
    header("Location: login.php");
    exit(); // หยุดการทำงานของสคริปต์ทันที
}

// ดึงข้อมูลจาก session มาใช้
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_type = $_SESSION['user_type'];

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>โปรไฟล์ของฉัน</title>
    <style>
        body { font-family: sans-serif; }
        .container { width: 500px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <div class="container">
        <h1>สวัสดี, <?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?>!</h1>
        
        <p>รหัสสมาชิกของคุณ: <?php echo $user_id; ?></p>
        <p>ประเภทบัญชี: <?php echo htmlspecialchars($user_type, ENT_QUOTES, 'UTF-8'); ?></p>
        
        <?php if ($user_type === 'admin'): ?>
            <p><a href="/admin_dashboard.php">ไปที่หน้าจัดการระบบ</a></p>
        <?php endif; ?>

        <p><a href="logout.php">ออกจากระบบ</a></p>
    </div>
</body>
</html>