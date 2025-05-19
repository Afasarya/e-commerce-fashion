<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    /**
     * Store a new newsletter subscription
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletters,email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Please enter a valid email address.')
                ->withInput();
        }

        Newsletter::create([
            'email' => $request->email,
        ]);
        
        return redirect()->back()->with('success', 'Thank you for subscribing to our newsletter!');
    }
} 