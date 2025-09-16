<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class ActivityLogService
{
    public function getRecentActivities(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Activity::with(['causer', 'subject'])
            ->whereNotNull('causer_id')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getActivityIcon(string $event): string
    {
        return match ($event) {
            'created' => '📤',
            'updated' => '✏️',
            'deleted' => '🗑️',
            default => '📝',
        };
    }

    public function formatActivityDescription(Activity $activity): string
    {
        $userName = $activity->causer ? $activity->causer->name : 'Ismeretlen';
        $description = $activity->description;

        // Ha van subject és property_code mezője
        if ($activity->subject && method_exists($activity->subject, 'property_code')) {
            $description = str_replace('Ingatlan hozzáadva:', "{$userName} hozzáadott egy ingatlant {$activity->subject->property_code}", $description);
            $description = str_replace('Ingatlan módosítva:', "{$userName} módosította az ingatlant {$activity->subject->property_code}", $description);
            $description = str_replace('Ingatlan törölve:', "{$userName} törölt egy ingatlant {$activity->subject->property_code}", $description);
        }

        // Ha van subject és name_0 mezője (customer)
        if ($activity->subject && method_exists($activity->subject, 'name_0')) {
            $description = str_replace('Új vevő rögzítve:', "{$userName} új vevőt rögzített", $description);
            $description = str_replace('Vevő módosítva:', "{$userName} módosította a vevőt", $description);
            $description = str_replace('Vevő törölve:', "{$userName} törölt egy vevőt", $description);
        }

        // Ha a description nem tartalmazza a felhasználó nevét, akkor hozzáadjuk
        if (! str_contains($description, $userName) && $userName !== 'Ismeretlen') {
            $description = "{$userName} - {$description}";
        }

        return $description;
    }

    public function getChangedFields(Activity $activity): ?string
    {
        if ($activity->event !== 'updated' || ! $activity->changes) {
            return null;
        }

        $changes = $activity->changes;
        $changedFields = [];

        if (isset($changes['attributes']) && isset($changes['old'])) {
            foreach ($changes['attributes'] as $field => $newValue) {
                $oldValue = $changes['old'][$field] ?? null;
                if ($oldValue !== $newValue) {
                    $changedFields[] = "{$field}: {$oldValue} → {$newValue}";
                }
            }
        }

        return ! empty($changedFields) ? implode(', ', $changedFields) : null;
    }

    public function logActivity($model, string $event, ?string $description = null): void
    {
        $user = Auth::user();

        if ($user) {
            activity()
                ->causedBy($user)
                ->performedOn($model)
                ->event($event)
                ->log($description ?? $this->getDefaultDescription($model, $event));
        }
    }

    private function getDefaultDescription($model, string $event): string
    {
        if (method_exists($model, 'property_code')) {
            return match ($event) {
                'created' => "Ingatlan hozzáadva: {$model->property_code}",
                'updated' => "Ingatlan módosítva: {$model->property_code}",
                'deleted' => "Ingatlan törölve: {$model->property_code}",
                default => "Ingatlan esemény: {$model->property_code}",
            };
        }

        if (method_exists($model, 'name_0')) {
            return match ($event) {
                'created' => "Új vevő rögzítve: {$model->name_0}",
                'updated' => "Vevő módosítva: {$model->name_0}",
                'deleted' => "Vevő törölve: {$model->name_0}",
                default => "Vevő esemény: {$model->name_0}",
            };
        }

        return "Esemény: {$event}";
    }
}
