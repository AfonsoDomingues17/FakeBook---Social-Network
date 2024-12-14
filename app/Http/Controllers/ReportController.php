<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        return view('pages.reports', compact('report'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }

    public function showReports()
    {
        $reports = Report::all()->map(function ($report) {
            if ($report->target_user_id) {
                $report->type = 'user';
            } elseif ($report->post_id) {
                $report->type = 'post';
            } elseif ($report->comment_id) {
                $report->type = 'comment';
            } else {
                $report->type = 'unknown';
            }
            return $report;
        });

        $userReports = $reports->where('type', 'user')->groupBy('target_user_id')->sortByDesc(function ($group) {
            return count($group);
        });

        $postReports = $reports->where('type', 'post')->groupBy('post_id')->sortByDesc(function ($group) {
            return count($group);
        });

        $commentReports = $reports->where('type', 'comment')->groupBy('comment_id')->sortByDesc(function ($group) {
            return count($group);
        });

        return view('pages.reports', [
            'userReports' => $userReports,
            'postReports' => $postReports,
            'commentReports' => $commentReports
        ]);
    }

    public function reportUser(Request $request, $user_id)
    {
        if (Auth::check()) {
            $validatedData = $request->validate([
                'content' => 'required|string|max:250',
            ]);
        
            $report = Report::updateOrCreate(
                [
                    'author_id' => Auth::user()->id,
                    'target_user_id' => $user_id
                ],
                [
                    'content' => $validatedData['content']
                ]
            );
        }
        return redirect()->route('profile', ['user_id' => $user_id]);
    }

    public function reportPost(Request $request, $post_id)
    {
        if (Auth::check()) {
            $validatedData = $request->validate([
                'content' => 'required|string|max:250',
            ]);
        
            $report = Report::updateOrCreate(
                [
                    'author_id' => Auth::user()->id,
                    'post_id' => $post_id
                ],
                [
                    'content' => $validatedData['content']
                ]
            );
        }
        return redirect()->to(url('/'));
    }

    public function reportComment(Request $request, $comment_id)
    {
        
    }
}
