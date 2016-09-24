<?php namespace Modules\Banners\Repositories\Cache;

use Modules\Banners\Repositories\GroupsRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheGroupsDecorator extends BaseCacheDecorator implements GroupsRepository
{
    public function __construct(GroupsRepository $groups)
    {
        parent::__construct();
        $this->entityName = 'banners.groups';
        $this->repository = $groups;
    }
}
