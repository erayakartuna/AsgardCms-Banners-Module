<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/banners'], function (Router $router) {
    $router->bind('banners', function ($id) {
        return app('Modules\Banners\Repositories\BannersRepository')->find($id);
    });
    get('banners', ['as' => 'admin.banners.banners.index', 'uses' => 'BannersController@index']);
    get('banners/create', ['as' => 'admin.banners.banners.create', 'uses' => 'BannersController@create']);
    post('banners', ['as' => 'admin.banners.banners.store', 'uses' => 'BannersController@store']);
    get('banners/{banners}/edit', ['as' => 'admin.banners.banners.edit', 'uses' => 'BannersController@edit']);
    put('banners/{banners}/edit', ['as' => 'admin.banners.banners.update', 'uses' => 'BannersController@update']);
    post('banners/ajax_update_order', ['as' => 'admin.banners.banners.ajax_update_order', 'uses' => 'BannersController@ajax_update_order']);
    delete('banners/{banners}', ['as' => 'admin.banners.banners.destroy', 'uses' => 'BannersController@destroy']);
    post('banners/delete', ['as' => 'admin.banners.banners.destroy_all', 'uses' => 'BannersController@destroy_all']);
    $router->bind('groups', function ($id) {
        return app('Modules\Banners\Repositories\GroupsRepository')->find($id);
    });
    get('groups', ['as' => 'admin.banners.groups.index', 'uses' => 'GroupsController@index']);
    get('groups/create', ['as' => 'admin.banners.groups.create', 'uses' => 'GroupsController@create']);
    post('groups', ['as' => 'admin.banners.groups.store', 'uses' => 'GroupsController@store']);
    get('groups/{groups}/edit', ['as' => 'admin.banners.groups.edit', 'uses' => 'GroupsController@edit']);
    put('groups/{groups}/edit', ['as' => 'admin.banners.groups.update', 'uses' => 'GroupsController@update']);
    delete('groups/{groups}', ['as' => 'admin.banners.groups.destroy', 'uses' => 'GroupsController@destroy']);
// append


});
