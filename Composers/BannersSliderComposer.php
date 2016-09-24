<?php namespace Modules\Banners\Composers;

use Illuminate\Contracts\View\View;
use Modules\Banners\Repositories\BannersRepository;
use Modules\Media\Repositories\FileRepository;


class BannersSliderComposer
{
    private $banners;
    private $file;

    public function __construct(BannersRepository $banners,FileRepository $fileRepository)
    {
        $this->banners = $banners;
        $this->file = $fileRepository;
    }

    public  function compose(View $view)
    {
        $return = '';
        $banners = $this->banners->banners();

        foreach($banners as $banner)
        {
            $image = $this->file->findFileByZoneForEntity('banner_image', $banner);
            if($image)
            {
                $return .='<li>';
                if($banner->url)
                {
                    $return .='<a href="'.$banner->url.'"';
                    if($banner->target)
                    {
                        $return .='target="_blank"';
                    }
                    $return .='>';
                }

                $return .='<img src="'.$image->path.'" alt="'.$banner->title.'" />';

                if($banner->url)
                {
                    $return .='</a>';
                }
                $return .='</li>';
            }
        }

        $view->with('banners', $return);
    }
}