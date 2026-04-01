<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Referral Received</title>
    <style>
        body { font-family: 'Inter', Helvetica, Arial, sans-serif; background-color: #f7f9fa; margin: 0; padding: 0; color: #333; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .accent-bar { height: 6px; background: linear-gradient(90deg, #97563D, #F8E0BB, #2E4B3C); }
        .header { padding: 40px 20px; text-align: center; }
        .logo { height: 60px; }
        .content { padding: 0 40px 40px; text-align: left; }
        h1 { color: #2E4B3C; font-size: 22px; margin-bottom: 16px; font-weight: 700; }
        p { font-size: 16px; line-height: 1.6; margin-bottom: 24px; color: #4B5563; }
        .referral-card { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; margin-bottom: 32px; }
        .label { font-weight: 600; color: #64748b; font-size: 13px; text-transform: uppercase; letter-spacing: 0.025em; }
        .value { color: #1e293b; font-size: 15px; font-weight: 700; display: block; margin-top: 2px; }
        .footer { background-color: #2E4B3C; color: #ffffff; padding: 30px 20px; text-align: center; font-size: 14px; }
        .footer a { color: #F8E0BB; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="accent-bar"></div>
        <div class="header">
            <?php echo $__env->make('emails.partials.logo', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <div class="content">
            <h1>New Client Referral</h1>
            <p>Hello <?php echo e($referral->referredTo->name); ?>,</p>
            <p>You have received a new client referral from your peer, <strong><?php echo e($referral->referredBy->name); ?></strong>.</p>

            <div class="referral-card">
                <div>
                    <span class="label">Client Name:</span>
                    <span class="value"><?php echo e($referral->user->name); ?></span>
                </div>
                <div style="margin-top: 15px; border-top: 1px solid #edf2f7; padding-top: 15px;">
                    <span class="label">Reference ID:</span>
                    <span class="value"><?php echo e($referral->referral_no); ?></span>
                </div>
            </div>

            <p>The client has been notified and sent a payment link to confirm the session. You will receive another notification once the payment is completed and the booking is officially confirmed.</p>
            
            <p>Thank you for being a part of our professional wellness network.</p>
        </div>

        <div class="footer">
            <p style="color: #F8E0BB; margin-bottom: 10px;">&copy; <?php echo e(date('Y')); ?> Zaya Wellness. All rights reserved.</p>
            <p style="color: #ffffff; font-weight: 500; opacity: 0.9;">Where Indian Wisdom Meets Modern Wellness</p>
            <p style="margin-top: 20px;"><a href="<?php echo e(config('app.url')); ?>" style="color: #F8E0BB; text-decoration: none; border: 1px solid #F8E0BB; padding: 8px 20px; border-radius: 99px; font-size: 12px;">Visit our Website</a></p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\wamp64\www\zaya\resources\views\emails\referral-received.blade.php ENDPATH**/ ?>