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
	"module_desc" => "Verstuur Nearby Notifications naar Android telefoons.",

	/*
	|--------------------------------------------------------------------------
	| Create
	|--------------------------------------------------------------------------
	*/

  "create_eddystone" => "Nieuwe Eddystone",
  "create_eddystone_help" => "U kunt notificaties toevoegen als de Eddystone is gemaakt.",
  "namespace_id" => "Namespace ID",
  "namespace_id_help" => "Eddystone UID beacons hebben een namespace id van 10 bytes (20 karakters). U kunt dit vinden in het web dashboard of app van de Eddystone fabrikant. Zorg er ook voor dat het UID-protocol geactiveerd is.",
  "instance_id" => "Instance ID",
  "instance_id_help" => "Eddystone UID beacons hebben een instance id van 6 bytes (12 karakters). U kunt dit vinden in het web dashboard of app van de Eddystone fabrikant. Zorg er ook voor dat het UID-protocol geactiveerd is.",
  "" => "",

	/*
	|--------------------------------------------------------------------------
	| General
	|--------------------------------------------------------------------------
	*/

  "eddystone_explanation" => "Over Eddystone beacons",
  "eddystone_explanation1" => "Eddystone beacons kunnen <a href=\"https://developers.google.com/nearby/notifications/overview\" class=\"link\" target=\"_blank\">Nearby notificaties</a> naar Android toestellen uitzenden die in het bereik van het beacon zijn. Deze technologie is momenteel beperkt tot telefoons met Android 4.4 en nieuwer. Google ontwikkelt integratie met Android bij elke update, en het goede nieuws is dat gebruikers geen app nodig hebben om de notificaties te ontvangen. Notificaties worden weergegeven in het meldingenvenster zo lang een telefoon binnen het bereik van de Eddystone beacon is.",
  "eddystone_explanation2" => "U heeft Eddystone beacons nodig die het UID-protocol ondersteunen. Deze <a href=\"https://developers.google.com/beacons/eddystone\" target=\"_blank\">Eddystone pagina</a> bevat een lijst van fabrikanten.",
  "eddystone_explanation3" => "Hoewel <a href=\"https://google.github.io/physical-web/\" class=\"link\" target=\"_blank\">Physical Web</a> beacons kunnen worden gedetecteerd door iOS-apparaten met Chrome geïnstalleerd, is dit niet van toepassing op Nearby notificaties. Deze worden alleen ondersteund door Android.",
  "id" => "ID",
  "notification" => "Notificatie",
  "notifications" => "Notificaties",
  "notification_language_help" => "Gebruikers zien alleen notificaties als deze beschikbaar zijn in de taal van hun telefoon. Zorg ervoor dat u notificaties toevoegt voor elke taal die u wilt targeten.",
  "notification_help" => "Deze tekst wordt weergegeven in het meldingenvenster. Maximaal 40 tekens.",
  "link" => "Link",
  "custom_link" => "Aangepaste link",
  "custom_link_help" => "Url moet SSL zijn (https://).",
  "add_notification" => "Nieuwe notificatie toevoegen",
  "" => "",
  "" => "",
);
