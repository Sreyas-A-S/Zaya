<?php

namespace App\Traits;

use App\Services\ProfileCompletionService;

trait HasProfileCompletion
{
    /**
     * Get the profile completion status for the user.
     *
     * @return array
     */
    public function getProfileStatusAttribute(): array
    {
        return (new ProfileCompletionService())->getStatus($this);
    }

    /**
     * Check if the profile is incomplete.
     *
     * @return bool
     */
    public function isProfileIncomplete(): bool
    {
        return !$this->profile_status['is_complete'];
    }
}
