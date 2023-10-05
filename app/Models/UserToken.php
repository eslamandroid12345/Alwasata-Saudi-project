<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['tokenable_type', 'tokenable_id', 'token'];

    public function tokenable()
    {
        return $this->morphTo();
    }
}
