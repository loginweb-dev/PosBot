<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function chatbot()
    {
        $business_id = request()->session()->get('user.business_id');
        // $facturas = Transaction::where("business_id", $business_id)->where("type", "sell")->orderBy("created_at", "desc")->with("contact")->get();
        return view("chatbot.index", compact("business_id"));
    }
    
}
