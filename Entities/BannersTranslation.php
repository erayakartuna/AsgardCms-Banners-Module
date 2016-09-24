<?php namespace Modules\Banners\Entities;

use Illuminate\Database\Eloquent\Model;

class BannersTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'title',
        'url',
        'target'
    ];
    protected $table = 'banners__banners_translations';
}
