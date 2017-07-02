<?php

namespace Modules\Properties\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class WebsiteController extends Controller
{
    /**
     * Website homepage
     */
    public function home()
    {
      // Define scopes
      $scopes = ['https://www.googleapis.com/auth/userlocation.beacon.registry'];

      // Register client
      $client = new \Google_Client();
      $client->setAuthConfig(storage_path('app/google_keys/Mobile Site Builder Pro-c7613e6b52be.json'));
      $client->setScopes($scopes);

      $proximitybeaconService = new \Google_Service_Proximitybeacon($client);

      define('TAB', '&nbsp;&nbsp;&nbsp;');

      // Delete beacon
      /*
      $beacon_namespace = 'edd1ebeac04e5defa017';
      $beacon_instance = 'abe0e03f6da9';
      $beacon_name = 'beacons/3!' . $beacon_namespace . $beacon_instance;
      $error = '';

      try {
        $result = $proximitybeaconService->beacons->delete($beacon_name);
      } catch (\Exception $e) {
        $response = json_decode($e->getMessage());

        if (isset($response->error->message)) {
          $error = $response->error->message;
        } else {
          $error = 'Unknown error.';
        }
      }

      if ($error != '') {
        echo $error;
      }*/


      // Add beacon
      /*
      $beacon_namespace = 'edd1ebeac04e5defa017';
      $beacon_instance = 'abe0e03f6da9';
      $beacon_name = 'beacons/3!' . $beacon_namespace . $beacon_instance;
      $error = '';

      $new_beacon = new \Google_Service_Proximitybeacon_Beacon;

      $advertisedId = new \Google_Service_Proximitybeacon_AdvertisedId;
      $beacon_advertise_id = base64_encode(hex2bin($beacon_namespace . $beacon_instance));

      $advertisedId->setType("EDDYSTONE");
      $advertisedId->setId($beacon_advertise_id);

      $new_beacon->setAdvertisedId($advertisedId);

      $new_beacon->description = 'Gele beacon';
      $new_beacon->status = 'ACTIVE';

      // Add user property
      $new_beacon->setProperties(['user_id' => '312']);

      try {
        $result = $proximitybeaconService->beacons->register($new_beacon);
      } catch (\Exception $e) {
        $response = json_decode($e->getMessage());
        if (isset($response->error->status) && $response->error->status == 'ALREADY_EXISTS') {
          $error = 'Beacon already exists.';
        } elseif (isset($response->error->message)) {
          $error = $response->error->message;
        } else {
          $error = 'Unknown error.';
        }
      }

      if ($error != '') {
        echo $error;
      } else {
        // Add user property

      }
      //dd($result);
      */

      // Get beacon by name
/*
      $beacon_namespace = 'edd1ebeac04e5defa017';
      $beacon_instance = 'abe0e03f6da9';

      $beacon_name = 'beacons/3!' . $beacon_namespace . $beacon_instance;

      try {
        $beacon = $proximitybeaconService->beacons->get($beacon_name);
      } catch (\Exception $e) {
        $response = json_decode($e->getMessage());

        if (isset($response->error->message)) {
          $error = $response->error->message;
        } else {
          $error = 'Unknown error.';
        }
      }

      if ($error != '') {
        echo $error;
      }
*/

      // Add Nearby Notification
      /*
      $beacon_namespace = 'edd1ebeac04e5defa017';
      $beacon_instance = 'abe0e03f6da9';
      $language = 'nl';
      $title = 'Deze notificatie is automatisch toegevoegd!'; // LESS THAN 40 chars!
      $url = 'https://s3m.nl';
      $startDate = '2017-06-22';
      $endDate = '2017-07-12';
      $startTimeOfDay = '14:40';
      $endTimeOfDay = '14:40';
      $anyOfDaysOfWeek = [1,2,3,4,5,6,7];
      $error = '';

      $beacon_name = 'beacons/3!' . $beacon_namespace . $beacon_instance;
      $data = base64_encode(json_encode([
        'title' => $title, 
        'url' => $url, 
        'targeting' => [
          'startDate' => '2017-06-22',
          'endDate' => '2017-07-12',
          'startTimeOfDay' => '14:40',
          'endTimeOfDay' => '17:40',
          'anyOfDaysOfWeek' => [1,2,3,4,5,6,7]
        ]
      ]));


      // New attachment
      $attachment = new \Google_Service_Proximitybeacon_BeaconAttachment;
      $attachment->setNamespacedType('com.google.nearby/' . $language);
      $attachment->setData($data);

      // Add attachment to beacon
      try {
        $attachment = $proximitybeaconService->beacons_attachments->create($beacon_name, $attachment);
      } catch (\Exception $e) {
        $response = json_decode($e->getMessage());

        if (isset($response->error->message)) {
          $error = $response->error->message;
        } else {
          $error = 'Unknown error.';
        }
      }

      if ($error != '') {
        echo $error;
      }

      //dd($attachment);
*/

      // Delete attachment
      /*
      $attachment_name = 'beacons/3!edd1ebeac04e5defa01719a802602c06/attachments/a5020a96-8808-428b-8770-a41b9a1d6d5c';
      $error = '';

      try {
        $attachment = $proximitybeaconService->beacons_attachments->delete($attachment_name);
      } catch (\Exception $e) {
        $response = json_decode($e->getMessage());

        if (isset($response->error->message)) {
          $error = $response->error->message;
        } else {
          $error = 'Unknown error.';
        }
      }

      if ($error != '') {
        echo $error;
      }*/
      //die();
      $list_beacons = $proximitybeaconService->beacons->listBeacons([
        /*'q' => 'description:"user_"',
        property:"user_id=312" // https://labs.ribot.co.uk/exploring-google-eddystone-with-the-proximity-beacon-api-bc9256c97e05
        */
        'q' => 'status:ACTIVE property:"user_id=312" property:"funnel_id=21"', // https://developers.google.com/beacons/proximity/reference/rest/v1beta1/beacons#Status
        //'q' => 'status:ACTIVE', // https://developers.google.com/beacons/proximity/reference/rest/v1beta1/beacons#Status
        'pageSize' => '1000'
      ]);

      echo 'Count: ' . $list_beacons->getTotalCount() . '<br>';
      echo '<hr>';

      $beacons = $list_beacons->getBeacons();

      foreach ($beacons as $beacon) {
        //dd($beacon);
        $beacon_name = $beacon->getBeaconName();
        echo 'Beacon name: ' . $beacon_name . '<br>';
        echo 'Description: ' . $beacon->getDescription() . '<br>';
        echo 'Status: ' . $beacon->getStatus() . '<br>';
        echo '---' . '<br>';

        // Attachments (filter Nearby Notifications with 'namespacedType')
        $attachments = $proximitybeaconService->beacons_attachments->listBeaconsAttachments($beacon_name, [
          'namespacedType' => 'com.google.nearby/*'
        ])->getAttachments();

        foreach ($attachments as $attachment) {
          $attachment_name = $attachment->getAttachmentName();
          $created_at = \Carbon\Carbon::parse($attachment->getCreationTimeMs())->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s');
          $data = json_decode(base64_decode($attachment->getData()));
          $language = $attachment->getNamespacedType();
          $language = explode('/', $language)[1];

          echo TAB . 'Attachment name: ' . $attachment_name . '<br>';
          echo TAB . 'Created at: ' . $created_at . '<br>';
          echo TAB . 'Language: ' . $language . '<br>';
          //echo TAB . 'Name space: ' . $attachment->getNamespacedType() . '<br>';
          echo TAB . 'Data: ' . base64_decode($attachment->getData()) . '<br>';
          echo TAB . 'Data title: ' . $data->title . '<br>';
          echo TAB . 'Data url: ' . $data->url . '<br>';
          echo TAB . '---' . '<br>';
/*
          if ($attachment_name == 'beacons/3!edd1ebeac04e5defa01719a802602c06/attachments/4f38361e-f7c0-4e7c-87cc-a3a00a3e48e7') {
            echo 'DELETE';
            $proximitybeaconService->beacons_attachments->delete($attachment_name);
          }
          */
        }
        //dd($attachments);
        echo '<hr>';
      }

      die();
      /*
      $browser_language = new \Sinergi\BrowserDetector\Language();
      $language = $browser_language->getLanguage();
      $language_locale = str_replace('_', '-', $browser_language->getLanguageLocale());

      // Numbers
      $currencyRepository = new \CommerceGuys\Intl\Currency\CurrencyRepository;
      $numberFormatRepository = new \CommerceGuys\Intl\NumberFormat\NumberFormatRepository;

      $currency = $currencyRepository->get('USD');
      $numberFormat = $numberFormatRepository->get($language_locale);

      $decimalFormatter = new \CommerceGuys\Intl\Formatter\NumberFormatter($numberFormat);
      echo $decimalFormatter->format('1234000.99'); // 123,456.99
      echo '<br>';

      $percentFormatter = new \CommerceGuys\Intl\Formatter\NumberFormatter($numberFormat, \CommerceGuys\Intl\Formatter\NumberFormatter::PERCENT);
      echo $percentFormatter->format('0.75'); // 75%
      echo '<br>';

      $currencyFormatter = new \CommerceGuys\Intl\Formatter\NumberFormatter($numberFormat, \CommerceGuys\Intl\Formatter\NumberFormatter::CURRENCY);
      echo $currencyFormatter->formatCurrency('2.99', $currency); // $2.99
      echo '<br>';

      // The accounting pattern shows negative numbers differently and is used
      // primarily for amounts shown on invoices.
      $invoiceCurrencyFormatter = new \CommerceGuys\Intl\Formatter\NumberFormatter($numberFormat, \CommerceGuys\Intl\Formatter\NumberFormatter::CURRENCY_ACCOUNTING);
      echo $invoiceCurrencyFormatter->formatCurrency('-2.99', $currency); // (2.99$)
      echo '<br>';

      // Arabic, Arabic extended, Bengali, Devanagari digits are supported as expected.
      $currency = $currencyRepository->get('USD', 'ar');
      $numberFormat = $numberFormatRepository->get('ar');
      $currencyFormatter = new \CommerceGuys\Intl\Formatter\NumberFormatter($numberFormat, \CommerceGuys\Intl\Formatter\NumberFormatter::CURRENCY);
      echo $currencyFormatter->formatCurrency('1230.99', $currency); // US$ ١٬٢٣٠٫٩٩
      echo '<br>';

      // Parse formatted values into numeric values.
      echo $currencyFormatter->parseCurrency('US$ ١٬٢٣٠٫٩٩', $currency); // 1230.99
      echo '<br>';

      $allCurrencies = $currencyRepository->getAll($language_locale);
      //dd($allCurrencies);

      // Reads the language definitions from resources/language.
      $languageRepository = new \CommerceGuys\Intl\Language\LanguageRepository;

      // Get the german language using the default locale (en).
      $language = $languageRepository->get($language_locale);
      echo $language->getLanguageCode(); // de
      echo '<br>';
      echo $language->getName(); // German
      echo '<br>';

      // Get the german language using the fr-FR locale.
      $language = $languageRepository->get('de', 'fr-FR');
      echo $language->getName(); // allemand
      echo '<br>';

      $allLanguages = $languageRepository->getAll($language_locale);
      dd($allLanguages);

      // Date
      \Date::setLocale($language);
      echo \Date::now()->format('l j F Y H:i:s'); // zondag 28 april 2013 21:58:16
      echo \Date::parse('-1 day')->diffForHumans(); // 1 dag geleden

      // Reads the country definitions from resources/country.
      $countryRepository = new \CommerceGuys\Intl\Country\CountryRepository;

      // Get the US country using the default locale (en).
      $country = $countryRepository->get('US', $language_locale);
      echo $country->getCountryCode(); // US
      echo $country->getName(); // United States
      echo $country->getCurrencyCode(); // USD

      // Get the US country using the fr-FR locale.
      //$country = $countryRepository->get('US', 'fr-FR');
      //echo $country->getName(); // États-Unis

      $allCountries = $countryRepository->getAll($language_locale);
      //dd($allCountries);
      */
    }

}
