<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Module
	|--------------------------------------------------------------------------
	*/

	"module_name" => "Nearby Notifications",
	"module_name_plural" => "Nearby Notifications",
	"module_name_plan" => "Nearby Notifications",
	"module_desc" => "Broadcast Nearby Notifications to Android phones.",

	/*
	|--------------------------------------------------------------------------
	| Create
	|--------------------------------------------------------------------------
	*/

  "eddystone_beacons" => "Eddystone beacons",
  "create_eddystone" => "Create Eddystone",
  "create_eddystone_help" => "You can add notifications when the Eddystone is created.",
  "namespace_id" => "Namespace ID",
  "namespace_id_help" => "Eddystone UID beacons have a 10-byte (20 characters) namespace id. You can find this in the web dashboard or app of the beacon manufacturer. Also, make sure the UID protocol is enabled.",
  "instance_id" => "Instance ID",
  "instance_id_help" => "Eddystone UID beacons have a 6-byte (12 characters) instance id. You can find this in the web dashboard or app of the beacon manufacturer. Also, make sure the UID protocol is enabled.",
  "" => "",

	/*
	|--------------------------------------------------------------------------
	| General
	|--------------------------------------------------------------------------
	*/

  "eddystone_visual" => url('assets/images/visuals/eddystones.jpg'),
  "eddystone_explanation" => "About Eddystone beacons",
  "eddystone_explanation1" => "Eddystone beacons can broadcast <a href=\"https://developers.google.com/nearby/notifications/overview\" class=\"link\" target=\"_blank\">Nearby Notifications</a> to Android phones in range of the beacon. This technology is currently limited to phones with Android 4.4 and newer. Google is evolving integration with Android with every update, and the good part is that users don't need an app to receive notifications. Notifications will show up in the notification shade as long as a phone is in range of the Eddystone beacon.",
  "eddystone_explanation2" => "You need Eddystone beacons that support the UID protocol. This <a href=\"https://developers.google.com/beacons/eddystone\" target=\"_blank\">Eddystone page</a> contains a list of manufacturers.",
  "eddystone_explanation3" => "Though <a href=\"https://google.github.io/physical-web/\" class=\"link\" target=\"_blank\">Physical Web</a> beacons can be detected by iOS devices with Chrome installed, this does not apply to Nearby Notifications. These are only supported by Android.",
  "id" => "ID",
  "notification" => "Notification",
  "notifications" => "Notifications",
  "notification_language_help" => "Users only see a notification if it's available in the language of their phone. Make sure you add notifications for every language you want to target.",
  "notification_help" => "This text will show in the notification shade. Maximum of 40 characters.",
  "link" => "Link",
  "custom_link" => "Custom link",
  "custom_link_help" => "Url must be SSL (https://).",
  "add_notification" => "Add notification",
  "when" => "When",
  "every_day" => "Every day",
  "days_of_week_short" => [
    "Mo",
    "Tu",
    "We",
    "Th",
    "Fr",
    "Sa",
    "Su"
  ],
);
