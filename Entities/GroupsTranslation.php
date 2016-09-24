<?php namespace Modules\Banners\Entities;

use Illuminate\Database\Eloquent\Model;

class GroupsTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'banners__groups_translations';
}
