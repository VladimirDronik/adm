<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSettings extends Model
{
    protected $table = 'notifsettings';

    public $timestamps = false;

    protected $guarded = ['id'];
}
