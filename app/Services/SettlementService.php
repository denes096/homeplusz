<?php

namespace App\Services;

use App\Models\Label;
use App\Models\Settlement;
use App\Models\SettlementGroup;
use Illuminate\Database\Eloquent\Collection;

class SettlementService
{

    public function getSettlementGroups(): Collection
    {
        return SettlementGroup::with('settlement', 'settlements')->get();
    }

    public function getSettlements(): Collection
    {
        return Settlement::get();
    }

    public function getSettlementGroupById($settlementGroupId): ?SettlementGroup
    {
        return SettlementGroup::find($settlementGroupId);
    }
}
