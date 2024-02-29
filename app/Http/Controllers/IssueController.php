<?php

namespace App\Http\Controllers;

use App\Enums\IssueStateEnum;
use App\Models\Issue;
use App\Models\Project;

class IssueController extends Controller
{
    public function index(Project $project)
    {
        $issues = Issue::query()
            ->whereState(IssueStateEnum::OPENED)
            ->with('assigned')
            ->where('project_id', $project->project_id)
            ->latest('updated_at')
            ->paginate(10);

        return view('pages.issue.index')
            ->with('issues', $issues);
    }
}