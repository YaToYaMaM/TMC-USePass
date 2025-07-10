<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CustomForgotPasswordController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $otp = rand(100000, 999999);
        Session::put('otp', $otp);
        Session::put('otp_email', $request->email);
        Session::put('otp_start_time', now()); // ← This line tracks when timer began

        // Send the OTP using PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'usepasstmc.system@gmail.com';
            $mail->Password   = 'rhkwujluyfwnaxpy';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('usepasstmc.system@gmail.com', 'USePass System');
            $mail->addAddress($request->email);
            $mail->isHTML(true);
            $mail->Subject = 'USePass OTP Code';
            $mail->Body = '
                    <div style="font-family: system-ui, sans-serif; font-size: 15px; color: #222; padding: 20px;">
                        <p>Hi there,</p>

                        <p>Here is your One-Time Password (OTP) to continue with your action:</p>

                        <p style="font-size: 24px; font-weight: bold; letter-spacing: 4px; margin: 20px 0;">' . $otp . '</p>

                        <p>This code will expire in 2 minutes. Please do not share it with anyone for your security.</p>

                        <br>

                        <p>Regards,<br>
                        USePass Support Team</p>
                    </div>';

            $mail->send();
        } catch (Exception $e) {
            return back()->withErrors(['email' => 'Could not send OTP. Mailer Error: ' . $mail->ErrorInfo]);
        }

        return redirect()->route('otp.form');
    }

    public function showOtpForm()
    {
        return inertia('Auth/VerifyOtp', [
            'otp_start_time' => Session::get('otp_start_time')
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        if ($request->otp == Session::get('otp')) {
            // Proceed to reset password
            return redirect()->route('password.reset', ['token' => 'dummy-token']); // or custom logic
        }

        return back()->withErrors(['otp' => 'Invalid OTP.']);
    }
}
