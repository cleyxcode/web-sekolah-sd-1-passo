<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode OTP Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; color: #333;">
    <div style="max-width: 500px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h2 style="color: #2563eb; text-align: center;">Reset Kata Sandi Anda</h2>
        <p>Halo,</p>
        <p>Kami menerima permintaan untuk mereset kata sandi akun Portal Orang Tua Anda.</p>
        <p>Berikut adalah kode OTP Anda. Kode ini hanya berlaku selama 10 menit.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <span style="display: inline-block; padding: 15px 30px; font-size: 24px; font-weight: bold; background: #f8fafc; color: #0f172a; letter-spacing: 5px; border: 2px dashed #cbd5e1; border-radius: 8px;">
                {{ $otpCode }}
            </span>
        </div>

        <p>Jika Anda tidak meminta perubahan kata sandi, abaikan email ini. Akun Anda tetap aman.</p>
        <br>
        <p>Salam hangat,<br><strong>Tim SD Negeri 1 Passo</strong></p>
    </div>
</body>
</html>
