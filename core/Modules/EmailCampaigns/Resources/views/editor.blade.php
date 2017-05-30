<div class="group-same-width">
  <div class="container">
    <div class="row">
      <div class="col-md-6" style="border-right: 1px solid #ddd;">

        <div class="input-group">
          <span class="input-group-addon">{{ trans('global.subject') }}</span>
          <input type="text" class="form-control" name="subject" id="subject" value="{{ $email->subject }}" required autocomplete="off">
        </div>

      </div>
      <div class="col-md-6">

        <div class="input-group">
          <span class="input-group-addon">{{ trans('global.to') }}</span>
          <span style="width: 100%">
<?php
if (count($forms) == 0) {
?>
            <input type="text" class="form-control" disabled value="{{ trans('emailcampaigns::global.no_forms_to_send_to') }}" required autocomplete="off">
<?php
} else {
  $form_id = $email->form_id;
  if ($form_id != '') $form_id = \Platform\Controllers\Core\Secure::staticHash($form_id, true);
  echo Former::select('mailto')
    ->addOption('&nbsp;')
    ->class('select2-required form-control')
    ->name('mailto')
    ->id('mailto')
    ->fromQuery($forms, 'name', 'local_domain')
    ->forceValue($form_id)
    ->label(false);
}
?>
          </span>
        </div>

      </div>

    </div>
  </div>

  <hr>

  <div class="container">
    <div class="row">

      <div class="col-md-6" style="border-right: 1px solid #ddd;">

        <div class="input-group">
          <span class="input-group-addon">{{ trans('global.from_name') }}</span>
          <input type="text" class="form-control" name="from_name" id="from_name" value="<?php echo ($email->emailCampaign->mail_from_name == '') ? auth()->user()->name : $email->emailCampaign->mail_from_name; ?>" required autocomplete="off">
        </div>

      </div>
      <div class="col-md-6">

        <div class="input-group">
          <span class="input-group-addon">{{ trans('global.from_email') }}</span>
          <input type="text" class="form-control" name="from_email" id="from_email" value="<?php echo ($email->emailCampaign->mail_from == '') ? auth()->user()->email : $email->emailCampaign->mail_from; ?>" required autocomplete="off">
        </div>

      </div>

    </div>
  </div>
</div>
<iframe id="editor_frame" class="desktop_mode" src="{{ $url }}" frameborder="0" allowtransparency="true" seamless></iframe>
<style type="text/css">
  .group-same-width {
    background-color: #fafafa;
    border-bottom: 1px solid #ddd;
  }
  
  .group-same-width hr {
    border-color: #ddd;
    margin: 0;
  }
  
  .group-same-width div.col-md-6 {
    padding: 10px;
  }
  
  .group-same-width .input-group-addon {
    width: 130px;
    text-align: left;
    background-color: #fafafa;
    border: 0;
    font-weight: bold;
    color: #999 !important;
    padding-top: 11px;
  }
  
  .group-same-width .input-group {
    width: 100%;
  }

  .group-same-width .form-control,
  .group-same-width .select2-container {
    border: 0 !important;
    color:
  }

  .group-same-width .select2-container {
    position: relative;
    top: 2px;
  }
  
  .group-same-width .form-control[disabled], .group-same-width .form-control[readonly],
  .group-same-width .input-group .form-control:focus,
  .group-same-width .select2-container.select2-container--open.select2-container--above,
  .group-same-width .select2-container.select2-container--open.select2-container--below,
  .group-same-width .select2-container.select2-container--focus .select2-selection,
  .group-same-width .select2-container.select2-container--focus .select2-selection__rendered,
  .group-same-width .select2-container.select2-container--open .select2-selection,
  .group-same-width .select2-container.select2-container--open .select2-selection__rendered {
    border: 0 !important;
    background-color: #fafafa !important;
  }

  #view,
  html {
    background-color: #21252b;
  }
  
  .topbar-main,
  .group-same-width {
    box-shadow: 0 6px 10px 0 rgba(0, 0, 0, 0.04), 0 1px 18px 0 rgba(0, 0, 0, 0.02), 0 3px 5px -1px rgba(0, 0, 0, 0.1);
  }
  
  #editor_frame {
    display: block;
    margin: 0 auto;
    transition: width 0.5s;
  }
  
  #editor_frame.desktop_mode {
    width: 1200px;
  }
  
  #editor_frame.tablet_mode {
    width: 800px;
  }
  
  #editor_frame.phone_mode {
    width: 450px;
  }
</style>
<script>
  $( '#generic_title a' ).text( "{{ str_replace('"
      ', ' & quot;
      ', $email->name) }}");

      $( '#generic_title a' ).on( 'click', function () {
        $( '#editor_frame' )[ 0 ].contentWindow.$( '.-x-el-fab-form-seo' ).trigger( 'click' );
      } );

      $( '#editor_frame' ).removeClass( 'desktop_mode tablet_mode phone_mode' ); $( '#editor_frame' ).addClass( $( '#device_selector li.active' ).attr( 'id' ) );

      $( '#device_selector a' ).on( 'click', function () {
        $( '#device_selector li' ).removeClass( 'active' );
        $( this ).parent( 'li' ).addClass( 'active' );
        var mode = $( this ).parent( 'li' ).attr( 'id' );
        $( '#editor_frame' ).removeClass( 'desktop_mode tablet_mode phone_mode' );
        $( '#editor_frame' ).addClass( mode );
      } );

      previewSiteResize();

      $( window ).resize( $.debounce( 100, previewSiteResize ) );

      function previewSiteResize() {
        $( '#editor_frame' ).css( {
          'height': ( parseInt( $( window ).outerHeight() ) - 172 ) + 'px'
        } );
      }
</script>