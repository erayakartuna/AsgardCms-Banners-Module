<?php namespace Modules\Banners\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Support\Traits\MediaRelation;

class Banners extends Model
{
    use Translatable, MediaRelation;

    protected $table = 'banners__banners';
    public $translatedAttributes = [
        'title',
        'url',
        'target'
    ];
    protected $fillable = [

        'title',

        'url',

        'target'
    ];
}
