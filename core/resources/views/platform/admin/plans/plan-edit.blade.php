<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.admin') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand link" href="#/admin/plans">{{ trans('global.plans') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.edit_plan') }}</a>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <form class="ajax" id="frm" method="post" action="{{ url('platform/admin/plan/update') }}">
    <input type="hidden" name="sl" value="{{ $sl }}">

    <div class="row">
      {!! csrf_field() !!}
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.general') }}</h3>
          </div>
          <fieldset class="panel-body">

            <div class="form-group">
              <label for="name">{{ trans('global.name') }} <sup>*</sup></label>
              <input type="text" class="form-control" name="name" id="name" value="{{ $plan->name }}" required autocomplete="off"<?php if($plan->id == 1)echo ' disabled'; ?>>
            </div>

            <div class="form-group">
              <?php
                echo Former::select('currency')
                  ->class('select2-required form-control')
                  ->name('currency')
                  ->options($currencies)
                  ->forceValue($plan->currency)
                  ->label(trans('global.currency'));
                ?>
            </div>

            <div class="form-group" style="margin-top:20px">
              <div class="checkbox checkbox-primary">
                <input name="default" id="default" type="checkbox" value="1"<?php if($plan->default == 1) echo ' checked'; ?><?php if($plan->id == 1)echo ' disabled'; ?>>
                <label for="default"> {{ trans('global.default_plan') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.default_info') }}">&#xE887;</i></label>
              </div>
            </div>

            <div class="form-group" style="margin-top:20px">
              <div class="checkbox checkbox-primary">
                <input name="active" id="active" type="checkbox" value="1"<?php if($plan->active == 1) echo ' checked'; ?><?php if($plan->id == 1)echo ' disabled'; ?>>
                <label for="active"> {{ trans('global.active') }}</label>
              </div>
            </div>

          </fieldset>
        </div>

            <ul class="nav nav-tabs navtab-custom">
              <li class="active"><a href="#monthly" data-toggle="tab" aria-expanded="false">{{ trans('global.monthly') }}</a></li>
              <li><a href="#annual" data-toggle="tab" aria-expanded="false">{{ trans('global.annual') }}</a></li>
            </ul>

            <div class="tab-content" style="padding-bottom:10px">
              <div class="tab-pane active" id="monthly">

                <div class="form-group">
                  <label for="monthly_price">{{ trans('global.price') }} <sup>*</sup></label>
                  <input type="text" class="form-control" name="monthly_price" value="{{ $plan->monthly_price }}" required autocomplete="off" placeholder="11.25">
                </div>

                <div class="form-group">
                  <label for="monthly_remote_product_id">{{ trans('global.remote_product_id') }}</label>
                  <input type="text" class="form-control" name="monthly_remote_product_id" value="{{ $plan->monthly_remote_product_id }}" autocomplete="off" placeholder="">
                </div>

                <div class="form-group">
                  <label for="monthly_order_url">{{ trans('global.order_url') }}</label>
                  <input type="text" class="form-control" name="monthly_order_url" id="monthly_order_url" value="{{ $plan->monthly_order_url }}" autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="monthly_upgrade_url">{{ trans('global.upgrade_url') }}</label>
                  <input type="text" class="form-control" name="monthly_upgrade_url" id="monthly_upgrade_url" value="{{ $plan->monthly_upgrade_url }}" autocomplete="off">
                </div>

              </div>
              <div class="tab-pane" id="annual">

                <div class="form-group">
                  <label for="annual_price">{{ trans('global.price') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.annual_price_help') }}">&#xE887;</i></label>
                  <input type="text" class="form-control" name="annual_price" value="{{ $plan->annual_price }}" required autocomplete="off" placeholder="9">
                </div>

                <div class="form-group">
                  <label for="annual_remote_product_id">{{ trans('global.remote_product_id') }}</label>
                  <input type="text" class="form-control" name="annual_remote_product_id" value="{{ $plan->annual_remote_product_id }}" autocomplete="off" placeholder="">
                </div>


                <div class="form-group">
                  <label for="annual_order_url">{{ trans('global.order_url') }}</label>
                  <input type="text" class="form-control" name="annual_order_url" id="annual_order_url" value="{{ $plan->annual_order_url }}" autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="annual_upgrade_url">{{ trans('global.upgrade_url') }}</label>
                  <input type="text" class="form-control" name="annual_upgrade_url" id="annual_upgrade_url" value="{{ $plan->annual_upgrade_url }}" autocomplete="off">
                </div>

              </div>
            </div>

      </div>

      <div class="col-md-8">

            <ul class="nav nav-tabs navtab-custom">
              <li class="active"><a href="#create" data-toggle="tab" aria-expanded="false">{{ trans('global.create') }}</a></li>
              <li><a href="#media" data-toggle="tab" aria-expanded="false">{{ trans('global.media') }}</a></li>
              <li><a href="#account" data-toggle="tab" aria-expanded="false">{{ trans('global.account') }}</a></li>
            </ul>


            <div class="tab-content" style="padding-bottom:10px">
              <div class="tab-pane active" id="create">
<?php
foreach($items as $item) {
  if ($item['creatable']) {
?>
                <fieldset class="mdl-shadow--2dp" style="padding: 10px 10px 0 10px; margin-bottom: 20px;">

                  <div class="form-group">
                    <label for="limitations_{{ $item['namespace'] }}_visible">{{ $item['name'] }} <sup>*</sup></label>
                    <div class="checkbox checkbox-primary">
                      <input type="hidden" name="limitations[{{ $item['namespace'] }}][visible]" value="0">
                      <input name="limitations[{{ $item['namespace'] }}][visible]" id="limitations_{{ $item['namespace'] }}_visible" type="checkbox" value="1"<?php if($plan->limitations[$item['namespace']]['visible'] == 1) echo ' checked'; ?><?php if($plan->id == 1)echo ' disabled'; ?>>
                      <label for="limitations_{{ $item['namespace'] }}_visible">{{ trans('global.active') }}</label>
                    </div>
                  </div>
<?php if ($item['in_plan_amount']) { ?>

                  <div class="form-group">
                    <label for="limitations_{{ $item['namespace'] }}_max">{{ trans('global.maximum') }} <sup>*</sup></label>
                    <input type="number" class="form-control" name="limitations[{{ $item['namespace'] }}][max]" id="limitations_{{ $item['namespace'] }}_max" value="{{ $plan->limitations[$item['namespace']]['max'] }}" required autocomplete="off"<?php if($plan->id == 1)echo ' disabled'; ?>>
                  </div>
<?php } ?>
<?php 
if (isset($item['extra_plan_config_boolean']) && count($item['extra_plan_config_boolean']) > 0) { 
  foreach ($item['extra_plan_config_boolean'] as $config => $value) {
?>
                  <div class="form-group">
                    <label for="limitations_{{ $item['namespace'] }}_{{ $config }}">{{ trans($item['namespace'] . '::global.' . $config) }} <sup>*</sup></label>
                    <div class="checkbox checkbox-primary">
                      <input type="hidden" name="limitations[{{ $item['namespace'] }}][{{ $config }}]" value="0">
                      <input name="limitations[{{ $item['namespace'] }}][{{ $config }}]" id="limitations_{{ $item['namespace'] }}_{{ $config }}" type="checkbox" value="1"<?php if($plan->limitations[$item['namespace']][$config] == 1) echo ' checked'; ?><?php if($plan->id == 1)echo ' disabled'; ?>>
                      <label for="limitations_{{ $item['namespace'] }}_{{ $config }}">{{ trans('global.active') }}</label>
                    </div>
                  </div>
<?php 
  }
}
?>
<?php 
if (isset($item['extra_plan_config_string']) && count($item['extra_plan_config_string']) > 0) { 
  foreach ($item['extra_plan_config_string'] as $config => $value) {
?>
                  <div class="form-group">
                    <label for="limitations_{{ $item['namespace'] }}_{{ $config }}">{{ trans($item['namespace'] . '::global.' . $config) }} <sup>*</sup></label>
                    <input type="text" class="form-control" name="limitations[{{ $item['namespace'] }}][{{ $config }}]" id="limitations_{{ $item['namespace'] }}_{{ $config }}" value="{{ $plan->limitations[$item['namespace']][$config] }}" required autocomplete="off"<?php if($plan->id == 1)echo ' disabled'; ?>>
                  </div>
<?php 
  }
}
?>
                </fieldset>
<?php 
  } 
}
?>
              </div>          
 
              <div class="tab-pane" id="media">
                <fieldset>

                  <div class="form-group">
                    <div class="checkbox checkbox-primary">
                      <input type="hidden" name="limitations[media][visible]" value="0">
                      <input name="limitations[media][visible]" id="limitations_media_visible" type="checkbox" value="1"<?php if($plan->limitations['media']['visible'] == 1) echo ' checked'; ?><?php if($plan->id == 1)echo ' disabled'; ?>>
                      <label for="limitations_media_visible">{{ trans('global.media') }}</label>
                    </div>
                  </div>

                </fieldset>
              </div>
 
              <div class="tab-pane" id="account">
                <fieldset>

                  <div class="form-group">
                    <div class="checkbox checkbox-primary">
                      <input type="hidden" name="limitations[account][plan_visible]" value="0">
                      <input name="limitations[account][plan_visible]" id="limitations_account_plan_visible" type="checkbox" value="1"<?php if($plan->limitations['account']['plan_visible'] == 1) echo ' checked'; ?><?php if($plan->id == 1)echo ' disabled'; ?>>
                      <label for="limitations_account_plan_visible">{{ trans('global.plan') }}</label>
                    </div>
                  </div>

                </fieldset>
              </div>

            </div>

      </div>
      <!-- end col -->
      
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-inverse panel-border">
        <div class="panel-heading"></div>
        <div class="panel-body">
          <a href="#/admin/plans" class="btn btn-lg btn-default waves-effect waves-light w-md">{{ trans('global.back') }}</a>
          <button class="btn btn-lg btn-success waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.save_changes') }}</span></button>
        </div>
      </div>
    </div>
  </div>
</form>

  
</div>