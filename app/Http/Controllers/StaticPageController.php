<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuestionResponseMail;

class StaticPageController extends Controller
{
    public function showHelpPage()
    {
        return view('pages.help');
    }
    public function sendHelpForm(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|max:500'
        ]);

        Question::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message
        ]);
        return redirect()->back()->with('success', 'Your question has been submitted.');
    }
    public function showAboutPage()
    {
        return view('pages.about');
    }
    public function showSettingsPage()
    {
        return view('pages.settings');
    }

    public function sendQuestionResponse(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        $response = $request->response;

        Mail::to($question->email)->send(new QuestionResponseMail($response, $question));

        $question->delete();

        return redirect()->back()->with('success', 'Your response has been sent.');
    }
}
