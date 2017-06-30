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


      $list_beacons = $proximitybeaconService->beacons->listBeacons([
        /*'q' => 'description:"user_"',*/
        'q' => 'status:ACTIVE', // https://developers.google.com/beacons/proximity/reference/rest/v1beta1/beacons#Status
        'pageSize' => '1000'
      ]);

      echo 'Count: ' . $list_beacons->getTotalCount() . '<br>';
      echo '<hr>';

      $beacons = $list_beacons->getBeacons();

      foreach ($beacons as $beacon) {
        $beacon_name = $beacon->getBeaconName();
        echo 'Name: ' . $beacon_name . '<br>';
        echo 'Description: ' . $beacon->getDescription() . '<br>';
        echo 'Status: ' . $beacon->getStatus() . '<br>';
        echo '<hr>';

        // Attachments
        $attachments = $proximitybeaconService->beacons_attachments->listBeaconsAttachments($beacon_name, [
          'namespacedType' => 'com.google.nearby/*'
        ])->getAttachments();

        foreach ($attachments as $attachment) {
          $attachment_name = $attachment->getAttachmentName();
          echo 'Name: ' . $attachment_name . '<br>';
          echo 'Description: ' . $beacon->getCreationTimeMs() . '<br>';
          echo 'Status: ' . $beacon->getStatus() . '<br>';
          echo '<hr>';
        }
        //dd($attachments);
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
