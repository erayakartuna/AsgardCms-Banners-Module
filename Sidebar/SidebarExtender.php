<?php namespace Modules\Banners\Sidebar;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Contracts\Authentication;

class SidebarExtender implements \Maatwebsite\Sidebar\SidebarExtender
{
    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * @param Authentication $auth
     *
     * @internal param Guard $guard
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param Menu $menu
     *
     * @return Menu
     */
    public function extendWithOLD(Menu $menu)
    {
        $menu->group(trans('core::sidebar.content'), function (Group $group) {
            $group->item(trans('banners::banners.title.banners'), function (Item $item) {
                $item->icon('fa fa-photo');
                $item->weight(10);
                $item->authorize(
                     /* append */
                );
                $item->item(trans('banners::banners.title.banners'), function (Item $item) {
                    $item->icon('fa fa-copy');
                    $item->weight(0);
                    $item->append('admin.banners.banners.create');
                    $item->route('admin.banners.banners.index');
                    $item->authorize(
                        $this->auth->hasAccess('banners.banners.index')
                    );
                });
                $item->item(trans('banners::groups.title.groups'), function (Item $item) {
                    $item->icon('fa fa-copy');
                    $item->weight(0);
                    $item->append('admin.banners.groups.create');
                    $item->route('admin.banners.groups.index');
                    $item->authorize(
                        $this->auth->hasAccess('banners.groups.index')
                    );
                });
// append


            });
        });

        return $menu;
    }

    public function extendWith(Menu $menu)
    {
        $menu->group(trans('core::sidebar.content'), function (Group $group) {
            $group->weight(90);
            $group->item(trans('banners::banners.title.banners'), function (Item $item) {
                $item->weight(3);
                $item->icon('fa fa-copy');
                $item->route('admin.banners.banners.index');
                $item->authorize(
                    $this->auth->hasAccess('banners.banners.index')
                );
            });
        });

        return $menu;
    }
}
