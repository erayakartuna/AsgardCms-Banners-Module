<?php namespace Modules\Banners\Http\Controllers\Admin;

use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Modules\Banners\Entities\Banners;
use Modules\Banners\Entities\Groups;
use Modules\Banners\Http\Requests\CreateBannerRequest;
use Modules\Banners\Http\Requests\UpdateBannerRequest;
use Modules\Banners\Repositories\BannersRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Media\Repositories\FileRepository;

class BannersController extends AdminBaseController
{
    /**
     * @var BannersRepository
     */
    private $banners;

    public function __construct(BannersRepository $banners)
    {
        parent::__construct();

        $this->banners = $banners;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $groups = Groups::lists('id', 'title');

        $banners = $this->banners->banners();

        return view('banners::admin.banners.index', compact('banners','groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $groups = Groups::lists('id', 'title');
        return view('banners::admin.banners.create',compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateBannerRequest $request
     * @return Response
     */
    public function store(CreateBannerRequest $request)
    {
        $this->banners->create($request->all());

        flash()->success(trans('core::core.messages.resource created', ['name' => trans('banners::banners.title.banners')]));

        return redirect()->route('admin.banners.banners.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Banners $banners
     * @return Response
     */
    public function edit(Banners $banners,FileRepository $fileRepository)
    {
        $groups = Groups::lists('id', 'title');
        $banner_image = $fileRepository->findFileByZoneForEntity('banner_image',$banners);
        return view('banners::admin.banners.edit', compact('banners','banner_image','groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Banners $banners
     * @param  UpdateBannerRequest $request
     * @return Response
     */
    public function update(Banners $banners, UpdateBannerRequest $request)
    {
        $this->banners->update($banners, $request->all());

        flash()->success(trans('core::core.messages.resource updated', ['name' => trans('banners::banners.title.banners')]));

        return redirect()->route('admin.banners.banners.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Banners $banners
     * @return Response
     */
    public function destroy(Banners $banners)
    {
        $this->banners->destroy($banners);

        flash()->success(trans('core::core.messages.resource deleted', ['name' => trans('banners::banners.title.banners')]));

        return redirect()->route('admin.banners.banners.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy_all(Request $request)
    {
        $ids = $request->input('action_to');

        Banners::destroy($ids);

        flash()->success(trans('core::core.messages.resource deleted', ['name' => trans('banners::banners.title.banners')]));

        return redirect()->route('admin.banners.banners.index');
    }

    /**
     * @param Request $request
     */
    public function ajax_update_order(Request $request)
    {
        $order = explode(',',$request->input('order'));

        foreach($order as $sort=>$id)
        {
            $this->banners->updateWithID($id,['sort_order' => $sort]);
        }
    }


}
