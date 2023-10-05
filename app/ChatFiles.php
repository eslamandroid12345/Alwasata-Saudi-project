<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatFiles extends Model
{
    protected $table = 'chat_files';
    protected $fillable = [
         'file_name',
        'message_id',
        'customer_id',
        'user_id',
    ];
}
