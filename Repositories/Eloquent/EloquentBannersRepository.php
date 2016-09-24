<?php namespace Modules\Banners\Repositories\Eloquent;

use Modules\Banners\Repositories\BannersRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentBannersRepository extends EloquentBaseRepository implements BannersRepository
{
    /**
     * @return mixed
     */
    public function banners()
    {
        return $this->model->orderBy('sort_order')->get();
    }

    public function updateWithID($id,$data)
    {
        return $this->model->where('id',$id)->update($data);
    }
}
