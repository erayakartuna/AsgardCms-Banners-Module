<?php namespace Modules\Banners\Http\Controllers\Admin;

use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Modules\Banners\Entities\Groups;
use Modules\Banners\Http\Requests\GroupRequest;
use Modules\Banners\Repositories\GroupsRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class GroupsController extends AdminBaseController
{
    /**
     * @var GroupsRepository
     */
    private $groups;

    public function __construct(GroupsRepository $groups)
    {
        parent::__construct();

        $this->groups = $groups;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $groups = $this->groups->all();

        return view('banners::admin.groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('banners::admin.groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  GroupRequest $request
     * @return Response
     */
    public function store(GroupRequest $request)
    {
        $this->groups->create($request->all());

        flash()->success(trans('core::core.messages.resource created', ['name' => trans('banners::groups.title.groups')]));

        return redirect()->route('admin.banners.groups.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Groups $groups
     * @return Response
     */
    public function edit(Groups $groups)
    {
        return view('banners::admin.groups.edit', compact('groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Groups $group
     * @param  GroupRequest $request
     * @return Response
     */
    public function update(Groups $group, GroupRequest $request)
    {
        $this->groups->update($group, $request->all());

        flash()->success(trans('core::core.messages.resource updated', ['name' => trans('banners::groups.title.groups')]));

        return redirect()->route('admin.banners.groups.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Groups $groups
     * @return Response
     */
    public function destroy(Groups $groups)
    {
        $this->groups->destroy($groups);

        flash()->success(trans('core::core.messages.resource deleted', ['name' => trans('banners::groups.title.groups')]));

        return redirect()->route('admin.banners.groups.index');
    }
}
