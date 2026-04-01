<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referral & Data Sharing Consent</title>
    <style>
        body { font-family: 'Inter', Helvetica, Arial, sans-serif; background-color: #f7f9fa; margin: 0; padding: 0; color: #333; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .accent-bar { height: 6px; background: linear-gradient(90deg, #97563D, #F8E0BB, #2E4B3C); }
        .header { padding: 40px 20px; text-align: center; }
        .logo { height: 60px; }
        .content { padding: 0 40px 40px; text-align: center; }
        h1 { color: #2E4B3C; font-size: 22px; margin-bottom: 16px; font-weight: 700; }
        p { font-size: 16px; line-height: 1.6; margin-bottom: 24px; color: #4B5563; }
        .pro-list { background-color: #f0fdf4; border: 1px solid #dcfce7; border-radius: 16px; padding: 20px; margin: 24px 0; text-align: left; }
        .pro-item { color: #166534; font-weight: 700; margin-bottom: 5px; font-size: 14px; }
        .otp-box { background-color: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 16px; padding: 30px; margin: 32px 0; }
        .otp-code { font-size: 42px; font-weight: 800; color: #2E4B3C; letter-spacing: 10px; margin: 0; }
        .footer { background-color: #2E4B3C; color: #ffffff; padding: 30px 20px; text-align: center; font-size: 14px; }
        .footer a { color: #F8E0BB; text-decoration: none; }
        .warning { font-size: 13px; color: #ef4444; margin-top: 20px; font-weight: 500; }
    </style>
</head>
<body>
    <div class="container">
        <div class="accent-bar"></div>
        <div class="header">
            <?php echo $__env->make('emails.partials.logo', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <div class="content">
            <h1>Referral & Data Sharing Consent</h1>
            <p>Your practitioner <strong><?php echo e($requesterName); ?></strong> wants to refer you to the following professional(s) and share your health data with them to ensure continuity of care:</p>
            
            <div class="pro-list">
                <?php $__currentLoopData = explode(', ', $professionalNames); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="pro-item">• <?php echo e($name); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <p>By providing the OTP below, you consent to sharing your profile and health history with these professionals.</p>

            <div class="otp-box">
                <h2 class="otp-code"><?php echo e($otp); ?></h2>
            </div>

            <p class="warning">If you did not expect this request, please ignore this email.</p>
            
            <p style="font-size: 14px; color: #94a3b8;">This OTP will expire in 15 minutes.</p>
        </div>

        <div class="footer">
            <p style="color: #F8E0BB; margin-bottom: 10px;">&copy; <?php echo e(date('Y')); ?> Zaya Wellness. All rights reserved.</p>
            <p style="color: #ffffff; font-weight: 500; opacity: 0.9;">Where Indian Wisdom Meets Modern Wellness</p>
            <p style="margin-top: 20px;"><a href="<?php echo e(config('app.url')); ?>" style="color: #F8E0BB; text-decoration: none; border: 1px solid #F8E0BB; padding: 8px 20px; border-radius: 99px; font-size: 12px;">Visit our Website</a></p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\wamp64\www\zaya\resources\views\emails\referral-otp.blade.php ENDPATH**/ ?>