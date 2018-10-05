<div class="list-group">
  <a href="#/admin/settings/modules" class="list-group-item<?php if (request()->route()->getName() == 'modules') echo ' active'; ?>">{{ trans('global.modules') }}</a>
  <a href="#/admin/settings/google" class="list-group-item<?php if (request()->route()->getName() == 'google') echo ' active'; ?>">Google</a>
</div>