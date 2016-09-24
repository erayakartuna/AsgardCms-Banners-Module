<?php namespace Modules\Banners\Repositories\Cache;

use Modules\Banners\Repositories\BannersRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;


class CacheBannersDecorator extends BaseCacheDecorator implements BannersRepository
{
    public function __construct(BannersRepository $banners)
    {
        parent::__construct();
        $this->entityName = 'banners.banners';
        $this->repository = $banners;
    }

    public function banners()
    {
        return $this->cache
            ->tags($this->entityName, 'global')
            ->remember("{$this->locale}.{$this->entityName}.latest.", $this->cacheTime,
                function () {
                    return $this->repository->banners();
                }
            );

    }


}
