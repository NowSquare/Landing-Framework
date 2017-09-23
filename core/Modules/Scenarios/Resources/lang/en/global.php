<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Module
	|--------------------------------------------------------------------------
	*/

	"module_group" => "Proximity App",
	"module_name" => "Scenario",
	"module_name_plural" => "Scenarios",
	"module_name_plan" => "Scenarios",
	"module_desc" => "Push notifications and content triggered by beacons and geo-fences, based on certain conditions.",

	/*
	|--------------------------------------------------------------------------
	| General
	|--------------------------------------------------------------------------
	*/

  "scenario" => "Scenario",
  "scenarios" => "Scenarios",
  "add_scenario" => "Add scenario",
  "edit_scenarios" => "Edit scenarios",
  "trigger" => "Trigger",
  "reset" => "Reset",
  "submit" => "Submit",
  "scenario_warning_message" => "Make sure you enter a <strong>notification title</strong> and <strong>text</strong>, and select an <strong>app image</strong>. Without those, a scenario won't be triggered.",

  "qr" => "QR code",
  "api" => "API",
  "no_beacons_geofences_found" => "No beacons or geofences found",

  "notifications" => "Notifications",
  "notification" => "Notification",
  "push_notification_title" => "Notification title",
  "push_notification_text" => "Notification text",
  "notification_help" => "<strong>Required.</strong> This push notification will be sent.",
  "select_image" => "Select image",
  "remove_image" => "Remove image",

  "enter" => "Enter",
  "exit" => "Exit",
  "leave" => "Leave",
  "far" => "Far",
  "near" => "Near",
  "immediate" => "Immediate",

  "if_someone" => "If someone",
  "if" => "If",
  "where" => "Where",
  "then" => "Then",
  "when" => "When",
  "days" => "Days",
  "time" => "Time",

  "do_nothing" => "do nothing",

  "available_app_close" => "App is closed",
  "available_app_open" => "App open (beacons)",
  "enters_region_of" => "enters region of",
  "exits_region_of" => "exits region of",
  "is_far_from" => "is far from",
  "is_near" => "is near",
  "is_very_near" => "is very close to",
  "waits_for" => "waits for", // as in: "he *waits for* 10 minutes"
  "returns_to" => "returns to",
  "frequency" => "Frequency",
  "frequency_info" => "Amount of seconds that must pass in order to retrigger this scenario.",
  "delay" => "Delay",
  "delay_info" => "Amount of seconds before scenario is triggered, given the region hasn't changed.",

  "image" => "Image",
  "template" => "Template",
  "only_for_analytics" => "only use for analytics",
  "show_template" => "show template",
  "url" => "URL",
  "open_url" => "open URL",
  "show_image" => "show image",

  "start_date" => "Start date",
  "end_date" => "End date",
  "all_the_time" => "all the time",
  "between_two_times" => "time range",
  "date_range" => "Date range",
  "time_range" => "Time range",
  "timing" => "Timing",

  "app_image" => "App image",
  "app_image_help" => "<strong>Required.</strong> This image will be displayed <br> in the app.",

  "day" => "Day",
  "every_day" => "every day",
  "between_two_dates" => "date range",
  "recurring_date" => "recurring date",
  "saturday_and_sunday" => "Sat - Sun",
  "friday_and_saturday" => "Fri - Sat",
  "monday_to_friday" => "Mon - Fri",
  "sunday_to_thursday" => "Sun - Thu",

  "range" => "Range",
  "sunday" => "Sunday",
  "monday" => "Monday",
  "tuesday" => "Tuesday",
  "wednesday" => "Wednesday",
  "thursday" => "Thursday",
  "friday" => "Friday",
  "saturday" => "Saturday",

  "days_of_week_short" => [
    "mo" => "Mo",
    "tu" => "Tu",
    "we" => "We",
    "th" => "Th",
    "fr" => "Fr",
    "sa" => "Sa",
    "su" => "Su"
  ],

  /*
   |--------------------------------------------------------------------------
   | Analytics
   |--------------------------------------------------------------------------
   */

  "analytics" => "Analytics",
  "view_analytics" => "View analytics",
  "beacons" => "Beacons",
  "geofences" => "Geofences",
  "platforms" => "Platforms",
  "models" => "Models",
  "views" => "Views",
  "interactions" => "Interactions",
  "device" => "Device",
  "map" => "Map",
  "heatmap" => "Heatmap",
  "hits" => "Hits",
  "browsers" => "Browsers",
  "os" => "OS",
  "no_data_found" => "No data found.",

  /*
   |--------------------------------------------------------------------------
   | App
   |--------------------------------------------------------------------------
   */

	"proximity_app" => "Proximity App",
	"dont_show_again" => "Don't show this message again",
	"app_p1" => "Have your own proximity app to push notifications with Bluetooth beacons and geofences. Available for iOS and Android, branded for your business.",
	"app_p2" => "The app collects cards, triggered by a beacon or geofence. You define the cards as images with a link to a web page.",
	"app_p3" => "Please <a href=\":mailto\">contact us</a> if you are interested.",
	"mailto_subject" => "Information about Proximity App",
	"mailto_body" => "I am interested in the proximity app. Do you have a demo?",

);