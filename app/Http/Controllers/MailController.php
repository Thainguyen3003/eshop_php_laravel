<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send_mail() {
        $to_name = "Nguyen Nang Thai";
        $to_email = "nguyennangthai@gmail.com";

        $data = array("name" => "Mail t? tài kho?n khách hàng", "body" => "Mail g?i v? v?n ?? hàng hóa");
        
        Mail::send('pages.mail.send_mail', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email)->subject('Test th? g?i mail google');
            $message->from($to_email, $to_name);
        });
        
        /* return redirect('/send-mail')->with('message', ''); */
    }


}
