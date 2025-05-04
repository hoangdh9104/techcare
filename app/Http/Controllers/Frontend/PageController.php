<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\Contact;
use App\Models\About;
use App\Models\EmailConfiguration;
use App\Models\GeneralSetting;
use App\Models\TermsAndCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function about()
    {
        $about = About::first();
        return view('frontend.pages.about', compact('about'));
    }

    public function termsAndCondition()
    {
        $terms = TermsAndCondition::first();
        return view('frontend.pages.terms-and-condition', compact('terms'));
    }

    public function contact()
    {
        return view('frontend.pages.contact');
    }

    public function handleContactForm(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => ['required', 'max:200'],
            'email' => ['required', 'email'],
            'subject' => ['required', 'max:200'],
            'message' => ['required', 'max:1000']
        ]);

        $setting = GeneralSetting::first();
        Mail::to($setting->contact_email)->send(new Contact($request->subject, $request->message, $request->email));
        return response(['status' => 'success', 'message' => 'Đã gửi mail thành công !']);
    }
}
