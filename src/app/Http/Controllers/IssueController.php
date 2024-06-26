<?php

namespace App\Http\Controllers;

use App\Enums\IssueStateEnum;
use App\Models\Issue;
use App\Models\Project;
use App\Services\IssueService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class IssueController extends Controller
{
    public function index(Request $request, Project $project, IssueService $issueService)
    {
        $state = $request->query('state', IssueStateEnum::OPENED->value);

        if (! in_array($state, IssueStateEnum::values())) {
            throw new BadRequestException('Invalid issue status');
        }

        $issues = $issueService->getIssueList(
            state: $state,
            project: $project
        );

        return view('pages.issue.index')
            ->with('state', $state)
            ->with('project', $project)
            ->with('issues', $issues);
    }

    public function show(Project $project, Issue $issue)
    {
        $issue->load(['comments', 'comments.author']);
        $issue->loadSum('time_entries', 'time');

        return view('pages.issue.show')
            ->with('comments', $issue->comments)
            ->with('project', $project)
            ->with('issue', $issue);
    }
}
