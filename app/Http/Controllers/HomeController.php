<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Display the home page with featured products
     */
    public function index()
    {
        $featuredProducts = Product::with(['category'])
            ->where('active', true)
            ->where('featured', true)
            ->latest()
            ->take(8)
            ->get();
            
        $categories = Category::where('active', true)->get();
        
        return view('home', compact('featuredProducts', 'categories'));
    }
    
    /**
     * Display the about page
     */
    public function about()
    {
        return view('about');
    }
    
    /**
     * Display the contact page
     */
    public function contact()
    {
        return view('contact');
    }
    
    /**
     * Handle submission of the contact form
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'privacy' => 'accepted',
        ]);
        
        // Save contact message to database
        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);
        
        // Optionally send email notification to admin
        // Mail::to('admin@example.com')->send(new NewContactMessage($validated));
        
        // Redirect back with success message
        return redirect()->route('contact')->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
