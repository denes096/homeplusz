<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProjectService
{

    public function getById(int $id)
    {
        return Project::where('id', $id)->first();
    }

    public function getByCode(string $id)
    {
        return Project::where('project_code', $id)->first();
    }

    public function getAll(): Collection
    {
        return Project::all();
    }
}
