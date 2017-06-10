@extends('landingpages::layouts.modal')

@section('content')
<div class="container-fluid">
  <div class="editor-modal-header">
    <a href="javascript:void(0);" class="btn-close onClickClose"></a>
    <h1>{{ trans('landingpages::global.settings') }}</h1>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-10">

      <form class="ajax" id="frm" method="post" action="{{ url('forms/editor/settings') }}">
        <input type="hidden" name="sl" value="{{ $sl }}">
        {!! csrf_field() !!}

        <p class="lead">{{ trans('landingpages::global.form_settings_submit_text') }}</p>

        <div class="radio radio-primary">
            <input type="radio" name="after_submit" id="after_submit_message" value="message"<?php if ((isset($form->meta['after_submit']) && $form->meta['after_submit'] == 'message') || ! isset($form->meta['after_submit'])) echo ' checked'; ?>>
            <label for="after_submit_message">
              {{ trans('landingpages::global.show_message') }}
            </label>
        </div>

        <div class="form-group">
          <label for="text">{{ trans('landingpages::global.title') }}</label>
          <input type="text" class="form-control" id="title" name="title" autocomplete="off" value="{{ (isset($form->meta['title'])) ? $form->meta['title'] : trans('forms::global.thank_you_title') }}">
        </div>

        <div class="form-group">
          <label for="text">{{ trans('landingpages::global.text') }}</label>
          <textarea class="form-control" id="text" name="text" rows="3">{{ (isset($form->meta['text'])) ? $form->meta['text'] : trans('forms::global.thank_you_text') }}</textarea>
        </div>

        <h3 class="seperator"><span>{{ trans('global.or') }}</span></h3>

        <div class="radio radio-primary">
          <input type="radio" name="after_submit" id="after_submit_lp" value="lp"<?php if (isset($form->meta['after_submit']) && $form->meta['after_submit'] == 'lp') echo ' checked'; ?>>
          <label for="after_submit_lp">
            {{ trans('landingpages::global.redirect_to_landing_page') }}
          </label>
        </div>

        <div class="form-group">
<?php
$landing_page = (isset($form->meta['landing_page'])) ? $form->meta['landing_page'] : 0;

echo Former::select('landing_page')
->addOption('&nbsp;')
->class('select2-required form-control')
->name('landing_page')
->fromQuery($sites, 'name', 'id')
->forceValue($landing_page)
->label(false);
?>
        </div>

        <h3 class="seperator"><span>{{ trans('global.or') }}</span></h3>

        <div class="radio radio-primary">
          <input type="radio" name="after_submit" id="after_submit_url" value="url"<?php if (isset($form->meta['after_submit']) && $form->meta['after_submit'] == 'url') echo ' checked'; ?>>
          <label for="after_submit_url">
            {{ trans('landingpages::global.redirect_to_url') }}
          </label>
        </div>

        <div class="form-group">
          <input type="text" class="form-control" id="url" name="url" autocomplete="off" value="{{ (isset($form->meta['url'])) ? $form->meta['url'] : '' }}" placeholder="http://">
        </div>

        <div class="editor-modal-footer">
          <button type="button" class="btn btn-primary btn-material onClickClose">{{ trans('global.cancel') }}</button>
          <button type="submit" class="btn btn-primary btn-material ladda-button" data-style="zoom-in" data-spinner-color="#138dfa"><span class="ladda-label">{{ trans('global.update') }}</span></button>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
function formSaved() {
  // Changes detected
  window.parent.lfSetPageIsDirty();

  window.parent.lfCloseModal();
}
</script>
@endsection