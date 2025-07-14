<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\User;

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
            $user = User::where('email', Session::get('otp_email'))->first();
            if (!$user) {
                return back()->withErrors(['otp' => 'No user found for this email.']);
            }

            $token = Password::getRepository()->create($user);

            return redirect()->route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ]);
        }

        return back()->withErrors(['otp' => 'Invalid OTP.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
