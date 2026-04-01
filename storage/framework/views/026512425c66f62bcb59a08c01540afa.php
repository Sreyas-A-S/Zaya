<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? config('app.name')); ?></title>
    <style>
        body {
            font-family: 'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #FFF7EF;
            margin: 0;
            padding: 0;
            color: #4B5563;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid #F8E0BB;
        }
        .header {
            background-color: #ffffff;
            padding: 40px 20px;
            text-align: center;
        }
        .logo {
            height: 72px;
            width: auto;
            display: block;
            margin: 6px auto;
        }
        .content {
            padding: 40px;
            text-align: center;
        }
        h1 {
            font-family: 'Playfair Display', Georgia, serif;
            color: #97563D;
            font-size: 28px;
            margin-bottom: 24px;
            font-weight: 700;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .otp-container {
            background: linear-gradient(135deg, #FFF7EF 0%, #F8E0BB 100%);
            padding: 30px;
            border-radius: 16px;
            margin: 32px 0;
            border: 1px solid #F8E0BB;
        }
        .otp-code {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: 8px;
            color: #97563D;
            margin: 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0 0;
            text-align: left;
        }
        .table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            color: #333;
        }
        .table td.label {
            font-weight: 700;
            width: 150px;
            color: #666;
        }
        .message-box {
            margin-top: 25px;
            padding: 20px;
            background-color: #fdfaf9;
            border-left: 4px solid #97563D;
            border-radius: 4px;
            text-align: left;
        }
        .message-label {
            font-weight: 700;
            display: block;
            margin-bottom: 10px;
            color: #97563D;
            text-transform: uppercase;
            font-size: 12px;
        }
        .message-text {
            white-space: pre-wrap;
            color: #444;
            line-height: 1.8;
        }
        .footer {
            background-color: #2E4B3C;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
            font-size: 14px;
        }
        .footer a {
            color: #F8E0BB;
            text-decoration: none;
        }
        .accent-bar {
            height: 4px;
            background: linear-gradient(90deg, #97563D, #F8E0BB, #2E4B3C);
        }
        .expiration {
            font-size: 14px;
            color: #6B7280;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="accent-bar"></div>
        <div class="header">
            <?php echo $__env->make('emails.partials.logo', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <div class="content">
            <h1><?php echo e($title ?? ''); ?></h1>
            <?php if(!empty($intro)): ?>
                <p><?php echo e($intro); ?></p>
            <?php endif; ?>

            <?php if(!empty($otp)): ?>
                <div class="otp-container">
                    <p style="margin-bottom: 10px; font-weight: 600; color: #2E4B3C; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Your Secure OTP</p>
                    <h2 class="otp-code"><?php echo e($otp); ?></h2>
                </div>
            <?php endif; ?>

            <?php if(!empty($expiration)): ?>
                <p class="expiration"><?php echo e($expiration); ?></p>
            <?php endif; ?>

        <?php if(!empty($messageData)): ?>
            <table class="table">
                    <tr>
                        <td class="label">Name:</td>
                        <td><?php echo e($messageData->first_name); ?> <?php echo e($messageData->last_name); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Email:</td>
                        <td><?php echo e($messageData->email); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Phone:</td>
                        <td><?php echo e($messageData->phone ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <td class="label">User Type:</td>
                        <td>
                            <?php if($messageData->user_type): ?>
                                <?php echo e(implode(', ', array_map('ucfirst', $messageData->user_type))); ?>

                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                    </tr>
            </table>

            <div class="message-box">
                <label class="message-label">Message:</label>
                <div class="message-text"><?php echo e($messageData->message); ?></div>
            </div>
        <?php endif; ?>

        <?php if(!empty($credentials) && (!empty($credentials['password']) || !empty($credentials['login_url']))): ?>
            <table class="table">
                <tr>
                    <td class="label">Login URL:</td>
                    <td><?php echo e($credentials['login_url'] ?? ''); ?></td>
                </tr>
                <tr>
                    <td class="label">Email:</td>
                    <td><?php echo e($credentials['email'] ?? ''); ?></td>
                </tr>
                <?php if(!empty($credentials['password'])): ?>
                <tr>
                    <td class="label">Password:</td>
                    <td><?php echo e($credentials['password'] ?? ''); ?></td>
                </tr>
                <?php endif; ?>
            </table>
        <?php endif; ?>

            <?php if(!empty($outro)): ?>
                <p><?php echo e($outro); ?></p>
            <?php endif; ?>
        </div>

        <div class="footer">
            <p>&copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. All rights reserved.</p>
            <p>Where Indian Wisdom Meets Modern Wellness</p>
            <p><a href="<?php echo e(config('app.url')); ?>">Visit our Website</a></p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\wamp64\www\zaya\resources\views\emails\default.blade.php ENDPATH**/ ?>