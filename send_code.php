<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $code = rand(100000, 999999); // توليد رمز عشوائي
        $subject = "رمز التحقق - Africa City";
        $message = "رمز التحقق الخاص بك هو: $code";
        $headers = "From: no-reply@yourdomain.com"; // غيّره حسب استضافتك

        // إرسال الإيميل
        if (mail($email, $subject, $message, $headers)) {
            // ممكن تخزن الرمز في قاعدة بيانات أو Session لاحقاً
            echo "تم إرسال الرمز إلى بريدك الإلكتروني.";
        } else {
            echo "فشل في إرسال البريد. تأكد أن الاستضافة تدعم mail()";
        }
    } else {
        echo "البريد غير صالح.";
    }
} else {
    echo "طلب غير صالح.";
}
?>
