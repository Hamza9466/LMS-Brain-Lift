<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
      public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required','string','max:100'],
            'email'   => ['required','email','max:150'],
            'message' => ['required','string','max:5000'],
        ]);

        $contact = Contact::create($data);

        // send thank-you email to the user
        Mail::to($contact->email)->send(new ContactMail($contact->name));

        return back()->with('success', 'Thanks! Your message has been sent.');
    }

    // GET /admin/contacts -> simple admin list
    public function index()
    {
        $contacts = Contact::latest()->paginate(20);
        return view('admin.pages.contact.index', compact('contacts'));
    }
}