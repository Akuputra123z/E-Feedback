<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SurveyAnswer;
use Illuminate\Auth\Access\HandlesAuthorization;

class SurveyAnswerPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SurveyAnswer');
    }

    public function view(AuthUser $authUser, SurveyAnswer $surveyAnswer): bool
    {
        return $authUser->can('View:SurveyAnswer');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SurveyAnswer');
    }

    public function update(AuthUser $authUser, SurveyAnswer $surveyAnswer): bool
    {
        return $authUser->can('Update:SurveyAnswer');
    }

    public function delete(AuthUser $authUser, SurveyAnswer $surveyAnswer): bool
    {
        return $authUser->can('Delete:SurveyAnswer');
    }

    public function restore(AuthUser $authUser, SurveyAnswer $surveyAnswer): bool
    {
        return $authUser->can('Restore:SurveyAnswer');
    }

    public function forceDelete(AuthUser $authUser, SurveyAnswer $surveyAnswer): bool
    {
        return $authUser->can('ForceDelete:SurveyAnswer');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SurveyAnswer');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SurveyAnswer');
    }

    public function replicate(AuthUser $authUser, SurveyAnswer $surveyAnswer): bool
    {
        return $authUser->can('Replicate:SurveyAnswer');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SurveyAnswer');
    }

}