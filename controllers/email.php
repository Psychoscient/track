<?php
    require_once "../helper/send.php";

    $name = htmlspecialchars("Adrian");
    $email = filter_var("adrianjoseph.salim.cics@ust.edu.ph", FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars("yey");

    if (!$email) {
        die("Invalid gago");
    }

    // Outer card for layered effect
    $body = '<div style="background:#f3f4f6;padding:32px 0;min-height:100vh;">
                <div style="max-width:540px;margin:32px auto;padding:0 0 0 0;background:none;border-radius:18px;box-shadow:none;">
                    <div style="background:#fff;padding:28px 32px 28px 32px;border-radius:14px;box-shadow:0 4px 18px rgba(99,102,241,0.10);font-family:Segoe UI,Arial,sans-serif;color:#222;">
                        <div style="border-bottom:2px solid #eee;padding-bottom:16px;margin-bottom:22px;">
                            <h2 style="margin:0;color:#6366f1;font-size:1.6rem;letter-spacing:-1px;">UST Track - Contact Form Submission</h2>
                        </div>
                        <div style="margin-bottom:18px;">
                            <span style="display:inline-block;width:100px;font-weight:600;">Name:</span> Admin
                        </div>
                        <div style="margin-bottom:18px;">
                            <span style="display:inline-block;width:100px;font-weight:600;">Email:</span> ust.track@ust.com
                        </div>
                        <div style="margin-bottom:18px;">
                            <span style="display:inline-block;width:100px;font-weight:600;vertical-align:top;">Message:</span>
                            <span style="display:inline-block;white-space:pre-line;vertical-align:top;">Successfully Created Account!</span>
                        </div>
                        <div style="margin-top:28px;font-size:0.97em;color:#888;text-align:right;">
                            <em>This message was sent via the UST Track system.</em>
                        </div>
                        <div style="text-align:center;margin-top:18px;">
                            <div style="display:inline-block;background:#f3f4f6;padding:12px 16px;border-radius:12px;box-shadow:0 2px 8px rgba(99,102,241,0.10);">
                                <img src="https://media1.tenor.com/m/DwUvzOBtTEUAAAAd/speed-ishowspeed.gif" alt="Celebration" style="max-width:140px;border-radius:8px;display:block;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
            
    $result = sendEmail($email, $name, "Test Email from UST Track", $body);

    if ($result === true) {
        echo "Email sent successfully!";
    } else {
        echo "Failed to send email: " . $result;
    }
?>