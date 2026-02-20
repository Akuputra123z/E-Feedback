<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SurveyResponse;
use Illuminate\Auth\Access\HandlesAuthorization;

class SurveyResponsePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SurveyResponse');
    }

    public function view(AuthUser $authUser, SurveyResponse $surveyResponse): bool
    {
        return $authUser->can('View:SurveyResponse');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SurveyResponse');
    }

    public function update(AuthUser $authUser, SurveyResponse $surveyResponse): bool
    {
        return $authUser->can('Update:SurveyResponse');
    }

    public function delete(AuthUser $authUser, SurveyResponse $surveyResponse): bool
    {
        return $authUser->can('Delete:SurveyResponse');
    }

    public function restore(AuthUser $authUser, SurveyResponse $surveyResponse): bool
    {
        return $authUser->can('Restore:SurveyResponse');
    }

    public function forceDelete(AuthUser $authUser, SurveyResponse $surveyResponse): bool
    {
        return $authUser->can('ForceDelete:SurveyResponse');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SurveyResponse');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SurveyResponse');
    }

    public function replicate(AuthUser $authUser, SurveyResponse $surveyResponse): bool
    {
        return $authUser->can('Replicate:SurveyResponse');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SurveyResponse');
    }

}