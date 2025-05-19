@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="bg-gray-50 py-12">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">Contact Us</h1>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
                Have questions? We're here to help and would love to hear from you.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contact Information -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="px-6 py-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Contact Information</h3>
                        
                        <div class="flex items-start mb-6">
                            <div class="flex-shrink-0 bg-yellow-100 rounded-md p-2 mr-4">
                                <i class="ri-map-pin-line text-yellow-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-1">Our Store Location</h4>
                                <p class="text-gray-600">
                                    123 Fashion Street<br>
                                    Jakarta, Indonesia 12345
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start mb-6">
                            <div class="flex-shrink-0 bg-yellow-100 rounded-md p-2 mr-4">
                                <i class="ri-phone-line text-yellow-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-1">Phone Number</h4>
                                <p class="text-gray-600">+62 123 456 7890</p>
                                <p class="text-gray-600">Monday - Friday, 9am - 6pm</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start mb-6">
                            <div class="flex-shrink-0 bg-yellow-100 rounded-md p-2 mr-4">
                                <i class="ri-mail-line text-yellow-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-1">Email Address</h4>
                                <p class="text-gray-600">hello@fashionstore.com</p>
                                <p class="text-gray-600">support@fashionstore.com</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-yellow-100 rounded-md p-2 mr-4">
                                <i class="ri-time-line text-yellow-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-1">Opening Hours</h4>
                                <p class="text-gray-600">
                                    Monday - Saturday: 10:00 AM - 9:00 PM<br>
                                    Sunday: 11:00 AM - 6:00 PM
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Follow Us</h4>
                        <div class="flex space-x-3">
                            <a href="#" class="text-gray-500 hover:text-yellow-500">
                                <i class="ri-facebook-fill text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-yellow-500">
                                <i class="ri-instagram-line text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-yellow-500">
                                <i class="ri-twitter-fill text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-yellow-500">
                                <i class="ri-linkedin-fill text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="px-6 py-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Send Us a Message</h3>
                        
                        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input type="text" name="name" id="name" class="shadow-sm focus:ring-yellow-500 focus:border-yellow-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                    @error('name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                    <input type="email" name="email" id="email" class="shadow-sm focus:ring-yellow-500 focus:border-yellow-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                <input type="text" name="subject" id="subject" class="shadow-sm focus:ring-yellow-500 focus:border-yellow-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                @error('subject')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                <textarea name="message" id="message" rows="5" class="shadow-sm focus:ring-yellow-500 focus:border-yellow-500 block w-full sm:text-sm border-gray-300 rounded-md" required></textarea>
                                @error('message')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="privacy" id="privacy" class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded" required>
                                <label for="privacy" class="ml-2 block text-sm text-gray-700">
                                    I agree to the <a href="#" class="text-yellow-600 hover:text-yellow-500">Privacy Policy</a>
                                </label>
                            </div>

                            <div>
                                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    <i class="ri-send-plane-fill mr-2"></i> Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Find Us</h3>
                
                <div class="h-96 w-full rounded-lg overflow-hidden">
                    <iframe 
                        class="w-full h-full"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253840.65295081925!2d106.68942549003518!3d-6.229386528146494!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e945e34b9d%3A0x5371bf0fdad786a2!2sJakarta%2C%20Indonesia!5e0!3m2!1sen!2s!4v1652834800000!5m2!1sen!2s" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Frequently Asked Questions</h2>
            <p class="mt-4 text-gray-500">Have questions? We're here to help.</p>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="divide-y divide-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">What are your shipping options?</h3>
                    <p class="mt-2 text-gray-600">We offer standard shipping (3-5 business days), express shipping (1-2 business days), and same-day delivery for select areas. Shipping costs vary based on location and chosen method.</p>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">How can I return an item?</h3>
                    <p class="mt-2 text-gray-600">Returns can be initiated within 14 days of receiving your order. Please visit your order history page to start the return process, or contact our customer service team for assistance.</p>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">Do you ship internationally?</h3>
                    <p class="mt-2 text-gray-600">Yes, we ship to most countries worldwide. International shipping typically takes 7-14 business days depending on the destination country.</p>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">How can I track my order?</h3>
                    <p class="mt-2 text-gray-600">Once your order ships, you'll receive a tracking number via email. You can also view your order status in your account dashboard under "Order History".</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection