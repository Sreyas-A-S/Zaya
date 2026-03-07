<!DOCTYPE html>
<html>
<head>
    <title>New Contact Us Message</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0;">
    <div style="background-color: #f9f9f9; padding: 20px;">
        <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #e0e0e0;">
            <div style="text-align: center; margin-bottom: 30px;">
                <img src="https://demo.zayawellness.com/frontend/assets/zaya-logo.svg" alt="Zaya Wellness Logo" style="width: 150px; height: auto;">
            </div>
            
            <h2 style="color: #97563D; border-bottom: 2px solid #97563D; padding-bottom: 10px; margin-bottom: 20px;">New Contact Message Received</h2>
            <p>You have received a new inquiry from the <strong>Zaya Wellness</strong> contact form.</p>
            
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold; width: 150px; color: #666;">Name:</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; color: #333;">{{ $messageData->first_name }} {{ $messageData->last_name }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666;">Email:</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; color: #333;">{{ $messageData->email }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666;">Phone:</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; color: #333;">{{ $messageData->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; font-weight: bold; color: #666;">User Type:</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee; color: #333;">
                        @if($messageData->user_type)
                            {{ implode(', ', array_map('ucfirst', $messageData->user_type)) }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            </table>

            <div style="margin-top: 25px; padding: 20px; background-color: #fdfaf9; border-left: 4px solid #97563D; border-radius: 4px;">
                <label style="font-weight: bold; display: block; margin-bottom: 10px; color: #97563D; text-transform: uppercase; font-size: 12px;">Message:</label>
                <div style="white-space: pre-wrap; color: #444; line-height: 1.8;">{{ $messageData->message }}</div>
            </div>
            
            <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; font-size: 12px; color: #999;">
                <p>This is an automated notification from the Zaya Wellness Platform.</p>
                <p>&copy; {{ date('Y') }} Zaya Wellness. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
