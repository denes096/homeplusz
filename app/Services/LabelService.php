<?php

namespace App\Services;

use App\Models\Label;
use Illuminate\Database\Eloquent\Collection;

class LabelService
{

    public function getActiveLabels(): Collection
    {
        return Label::withCount('properties')->get();
    }

    public function setLabelToSession($label): void
    {
        session(['label' => $label]);
    }
}
