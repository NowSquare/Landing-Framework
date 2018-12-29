<?php

return [
  // Only enabled modules are used within the system
  'enabled' => true,
  // If a module is 'creatable', it can be selected when creating a new item
  'creatable' => true,
  // If a module is 'in_plan', it can be turned on or off for a plan
  'in_plan' => true,
  // If a module is 'in_free_plan', it is enabled for default free plans
  'in_free_plan' => false,
  // The default allowable amount for a default free plan (if applicable)
  'in_free_plan_default_amount' => 0,
  // If 'in_plan_amount', a maximum amount can be configured for a plan
  'in_plan_amount' => true,
  // Numeric, the default amount for the system owner
  'in_plan_default_amount' => 10,
  // Extra boolean options for plan with default value - make sure to update global language file with $key
  'extra_plan_config_boolean' => [],
  // Extra string options for plan with default value - make sure to update global language file with $key
  'extra_plan_config_string' => [],
  // The order number is used for sorting purposes
  'order' => 45,
  // The icon must have a normal and active version, and it must exist in /assets/images/icons/
  'icon' => 'megaphone.svg',
  // Internal name, don't change
  'name' => 'Modals'
];