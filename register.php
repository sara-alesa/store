<?php
// الاتصال بقاعدة البيانات
include 'connection.php';
// التحقق من إرسال النموذج
if (isset($_POST['submit-btn'])) {

    // تنظيف البيانات من المستخدم
    $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $name = mysqli_real_escape_string($conn, $filter_name);
    
    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $email = mysqli_real_escape_string($conn, $filter_email);

    $filter_password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $password = mysqli_real_escape_string($conn, $filter_password);
    $filter_cpassword = filter_var($_POST['cpassword'], FILTER_SANITIZE_STRING);
    $cpassword = mysqli_real_escape_string($conn, $filter_cpassword);
    // التحقق من وجود المستخدم مسبقًا
    $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die("فشل الاستعلام");

    if (mysqli_num_rows($select_user) > 0) {
        $message[] = 'المستخدم موجود مسبقًا.';
    } else {
        // التحقق من تطابق كلمة المرور
        if ($password != $cpassword) {
            $message[] = 'كلمات المرور غير متطابقة.';
        } else {
            // إدخال المستخدم الجديد في قاعدة البيانات
            mysqli_query($conn, "INSERT INTO `users`(`name`, `email`, `password`) VALUES('$name', '$email', '$password')") 
            or die("فشل الاستعلام");

            $message[] = 'تم التسجيل بنجاح.';
            // تحويل المستخدم لصفحة تسجيل الدخول
            header('location:login.php');
            exit();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحة التسجيل</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '
            <div class="message">
                <span>' . $msg . '</span>
                <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
            </div>';
        }
    }
    ?>

    <section class="form-container">
        <form method="post">
            <h1>أنشئ حسابك</h1>
            <input type="text" name="name" placeholder="أدخل اسمك" required>
            <input type="email" name="email" placeholder="أدخل بريدك الإلكتروني" required>
            <input type="password" name="password" placeholder="أدخل كلمة المرور" required>
            <input type="password" name="cpassword" placeholder="أعد إدخال كلمة المرور" required>
            <input type="submit" name="submit-btn" value="سجّل الآن" class="btn">
            <p>هل لديك حساب؟ <a href="login.php">تسجيل الدخول</a></p>
        </form>
    </section>

</body>
</html>
