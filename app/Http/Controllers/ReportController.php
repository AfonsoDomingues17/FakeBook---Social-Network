<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Requests\ReportUserRequest;
use App\Http\Requests\ReportPostRequest;
use App\Http\Requests\ReportCommentRequest;
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

        $userReports = $reports->where('type', 'user')->groupBy('target_user_id')->filter(function ($group) {
            return count($group) >= 5;
        })->sortByDesc(function ($group) {
            return count($group);
        });

        $postReports = $reports->where('type', 'post')->groupBy('post_id')->filter(function ($group) {
            return count($group) >= 5;
        })->sortByDesc(function ($group) {
            return count($group);
        });

        $commentReports = $reports->where('type', 'comment')->groupBy('comment_id')->filter(function ($group) {
            return count($group) >= 5;
        })->sortByDesc(function ($group) {
            return count($group);
        });

        Log::info('User Reports: ', $userReports->toArray());
        Log::info('Post Reports: ', $postReports->toArray());
        Log::info('Comment Reports: ', $commentReports->toArray());

        return view('pages.reports', [
            'userReports' => $userReports,
            'postReports' => $postReports,
            'commentReports' => $commentReports
        ]);
    }
    public function reportUser(ReportUserRequest $request, $user_id)
    {
        if (Auth::check()) {
            $validatedData = $request->validated();
        
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

    public function reportPost(ReportPostRequest $request, $post_id)
    {
        if (Auth::check()) {
            $validatedData = $request->validated();
        
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

        public function getUserReports($user_id)
    {
        $reports = Report::where('target_user_id', $user_id)->get();
        return response()->json($reports);
    }

    public function getPostReports($post_id)
    {
        $reports = Report::where('post_id', $post_id)->get();
        return response()->json($reports);
    }
        public function getCommentReports($comment_id)
    {
        $reports = Report::where('comment_id', $comment_id)->get();
        return response()->json($reports);
    }
    public function reportComment(ReportCommentRequest $request, $comment_id)
    {
        if (Auth::check()) {
            $validatedData = $request->validated();
        
            $report = Report::updateOrCreate(
                [
                    'author_id' => Auth::user()->id,
                    'comment_id' => $comment_id
                ],
                [
                    'content' => $validatedData['content']
                ]
            );
        }
        return redirect()->to(url('/'));
    }
}
