<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Http\Requests\SendHelpFormRequest;
use App\Http\Requests\SendQuestionResponseRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuestionResponseMail;
use Illuminate\Support\Facades\Log;

class StaticPageController extends Controller
{
    public function showHelpPage()
    {
        return view('pages.help');
    }

    public function sendHelpForm(SendHelpFormRequest $request)
    {
        $request->merge([
            'is_unban' => $request->is_unban === 'true' ? true : false,
        ]);

        $validatedData = $request->validated();

        Question::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'message' => $validatedData['message'],
            'is_unban' => $validatedData['is_unban']
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

    public function sendQuestionResponse(SendQuestionResponseRequest $request, $id)
    {
        $question = Question::findOrFail($id);
        $response = $request->validated()['response'];

        Mail::to($question->email)->send(new QuestionResponseMail($response, $question));

        $question->delete();

        return redirect()->back()->with('success', 'Your response has been sent.');
    }
}