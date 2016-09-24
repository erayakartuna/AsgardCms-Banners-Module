<?php namespace Modules\Banners\Repositories;

use Modules\Core\Repositories\BaseRepository;

interface BannersRepository extends BaseRepository
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */

    public function banners();
}
