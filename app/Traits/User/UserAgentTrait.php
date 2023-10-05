<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\User;

use App\user_collaborator;
use Illuminate\Database\Eloquent\Builder;

trait UserAgentTrait
{
    /**
     * Get active agent users only
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeAgentsOnly(Builder $builder): Builder
    {
        return $builder->where([
            'role'   => 0,
            'status' => 1,
        ]);
    }
    /**
     * Get active quality users only
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeQualitiesOnly(Builder $builder): Builder
    {
        return $builder->where([
            'role'   => 5,
            'status' => 1,
        ]);
    }

    /**
     * Get allowed received only
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeAllowedReceivedOnly(Builder $builder): Builder
    {
        return $builder->where([
            'allow_recived' => 1,
        ]);
    }

    /**
     * Get agents of Distribution of requests
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeForDistributionOnly(Builder $builder): Builder
    {
        return $builder->allowedReceivedOnly()->agentsOnly();
    }

    public function scopeForDistributionQualityOnly(Builder $builder): Builder
    {
        return $builder->allowedReceivedOnly()->QualitiesOnly();
    }

    /**
     * Get allowed received only
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeCollaboratorUsers(Builder $builder): Builder
    {
        $users = user_collaborator::where('collaborato_id',auth()->id())->pluck('user_id');
        return $builder->whereIn('id',$users);
    }
}
