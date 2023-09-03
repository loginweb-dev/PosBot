<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function chatbot()
    {
        $business_id = request()->session()->get('user.business_id');
        return view("chatbot.index", compact("business_id"));
    }
    
    public function multimedia()
    {
        return view("chatbot.multimedia");
    }
}
