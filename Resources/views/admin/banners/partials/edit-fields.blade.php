<div class="box-body">
    {!! Form::i18nInput('title',trans('banners::banners.form.title'),$errors,$lang,$banners) !!}
    {!! Form::i18nInput('url',trans('banners::banners.form.url'),$errors,$lang,$banners) !!}
    {!! Form::i18nCheckbox('target',trans('banners::banners.form.target'),$errors,$lang,$banners) !!}

</div>
