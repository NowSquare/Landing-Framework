<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Module
	|--------------------------------------------------------------------------
	*/

	"module_name" => "Eddystone",
	"module_name_plural" => "Eddystones",
	"module_name_plan" => "Eddystones",
	"module_desc" => "Broadcast Nearby Notifications to Android phones.",

	/*
	|--------------------------------------------------------------------------
	| Create
	|--------------------------------------------------------------------------
	*/

  "create_eddystone" => "Create Eddystone",
  "namespace_id" => "Namespace ID",
  "namespace_id_help" => "Eddystone UID beacons have a 10-byte (20 characters) namespace id. You can find this in the web dashboard or app of the beacon manufacturer. Also, make sure the UID protocol is enabled.",
  "instance_id" => "Instance ID",
  "instance_id_help" => "Eddystone UID beacons have a 6-byte (12 characters) namespace id. You can find this in the web dashboard or app of the beacon manufacturer. Also, make sure the UID protocol is enabled.",
  "" => "",

	/*
	|--------------------------------------------------------------------------
	| General
	|--------------------------------------------------------------------------
	*/

  "eddystone_explanation" => "About Eddystone beacons",
  "eddystone_explanation1" => "Eddystone beacons can broadcast <a href=\"https://developers.google.com/nearby/notifications/overview\" class=\"link\" target=\"_blank\">Nearby Notifications</a> to Android phones in range of the beacon. This technology is currently limited to phones with Android 4.4 and newer. Google is evolving integration with Android with every update, but the good part is that users don't need an app to receive notifications. Notifications will automatically show up in the notification shade when a phone is in range of an Eddystone beacon.",
  "eddystone_explanation2" => "You need Eddystone beacons that support the UID protocol in order to use this feature. A list of manufacturers can be found on this <a href=\"https://developers.google.com/beacons/eddystone\" target=\"_blank\">Eddystone format</a> page.",
  "eddystone_explanation3" => "Though physical web beacons can be detected by iOS devices with Chrome installed, this does not apply to Nearby Notifications. These are (currently) only supported by Android.",
  "id" => "ID",
  "notification" => "Notification",
  "notifications" => "Notifications",
  "notification_language_help" => "Users only see a notification if it's available in the language of their phone.",
  "notification_help" => "This text will show in the notification shade. Maximum of 40 characters.",
  "link" => "Link",
  "add_notification" => "Add notification",
  "" => "",
  "" => "",
);
