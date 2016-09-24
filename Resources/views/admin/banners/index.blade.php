@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('banners::banners.title.banners') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('banners::banners.title.banners') }}</li>
    </ol>
@stop

@section('content')
    {!! Form::open(['route' => ['admin.banners.banners.destroy_all'], 'method' => 'post']) !!}
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
                    <a href="{{ route('admin.banners.banners.create') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-pencil"></i> {{ trans('banners::banners.button.create banners') }}
                    </a>
                    <button  class="btn btn-danger btn-flat"  style="padding: 4px 10px;margin-left:5px;">
                        <i class="fa fa-remove"></i> {{ trans('banners::banners.button.delete banners') }}
                    </button>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header">
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="data-table table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th width="100">{!! Form::checkbox('select_all', 0, false) !!}</th>
                            <th>{{ trans('banners::banners.form.title') }}</th>
                            <th>{{ trans('banners::banners.form.url') }}</th>
                            <th>{{ trans('core::core.table.created at') }}</th>
                            <th data-sortable="false">{{ trans('core::core.table.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($banners)): ?>
                        <?php foreach ($banners as $banner): ?>
                        <tr>
                            <td width="100">
                                {!! Form::checkbox('action_to[]', $banner->id, false) !!}
                             </td>

                             <td>
                                 {{ $banner->title }}
                             </td>
                             <td>
                                 {{ $banner->url }}
                             </td>
                             <td>
                                 <a href="{{ route('admin.banners.banners.edit', [$banner->id]) }}">
                                     {{ $banner->created_at }}
                                 </a>
                             </td>
                             <td>
                                 <div class="btn-group">
                                     <a href="{{ route('admin.banners.banners.edit', [$banner->id]) }}" class="btn btn-default btn-flat"><i class="fa fa-pencil"></i></a>
                                     <button class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-confirmation" data-action-target="{{ route('admin.banners.banners.destroy', [$banner->id]) }}"><i class="fa fa-trash"></i></button>
                                 </div>
                             </td>
                         </tr>
                         <?php endforeach; ?>
                         <?php endif; ?>
                         </tbody>

                     </table>
                     <!-- /.box-body -->
                 </div>
                 <!-- /.box -->
             </div>
         </div>
     </div>
     {!! Form::close() !!}
    @include('core::partials.delete-modal')
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>c</code></dt>
        <dd>{{ trans('banners::banners.title.create banners') }}</dd>
    </dl>
@stop

@section('scripts')
    <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'c', route: "<?= route('admin.banners.banners.create') ?>" }
                ]
            });
        });
    </script>
    <?php $locale = locale(); ?>
    <script type="text/javasczript">
        $(function () {
            $('.data-table').dataTable({
                "paginate": true,
                "lengthChange": true,
                "filter": true,
                "sort": true,
                "info": true,
                "autoWidth": true,
                "order": [[ 0, "desc" ]],
                "language": {
                    "url": '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(function(){
            $('table tbody').sortable({
                handle: 'td',
                update: function() {
                    order = new Array();
                    var i = 0;
                    $('tr', this).each(function(){
                        i++;
                        order.push( $(this).find('input[name="action_to[]"]').val() );
                    });
                    order = order.join(',');

                    $.ajax({
                        url: "<?= route('admin.banners.banners.ajax_update_order') ?>",
                        global: false,
                        type: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            order : order
                        }
                    });
                }

            }).disableSelection();

        });

    </script>
@stop
