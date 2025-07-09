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

        // Send the OTP using PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ordanizatristan@gmail.com';
            $mail->Password   = 'orscfqsjydwucktp';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('howardclintforwork@gmail.com', 'USePass System');
            $mail->addAddress($request->email);
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body    = "<h3>Your OTP Code is: <strong>$otp</strong></h3>";

            $mail->send();
        } catch (Exception $e) {
            return back()->withErrors(['email' => 'Could not send OTP. Mailer Error: ' . $mail->ErrorInfo]);
        }

        return redirect()->route('otp.form');
    }

    public function showOtpForm()
    {
        return inertia('Auth/VerifyOtp');
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
