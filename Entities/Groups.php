<?php namespace Modules\Banners\Entities;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    protected $table = 'banners__groups';
    protected $fillable = [
        'title',
        'slug'
    ];
}
