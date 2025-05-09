<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use App\Services\PropertyService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(
        private ProjectService $projectService,
        private PropertyService $propertyService,
    )
    {
    }

    //
    public function list(int $id, Request $request)
    {
        $properties = $this->propertyService->getByProjectId($id);
        $project = $this->projectService->getById($id);

        return view('project.list', [
            'properties' => $properties,
            'project' => $project,
        ]);
    }

    public function getNextPropertyId(int $id, Request $request)
    {
        $project = $this->projectService->getById($id);
        $projectCode = $project->project_code;

        $propertiesCountNext = $this->propertyService->getByProjectId($id)->count() + 1;

        return response()->json(['code' => "{$projectCode}/{$propertiesCountNext}"]);
    }
}
