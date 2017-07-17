<?php namespace Platform\Controllers\Core;

class Localization extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Localization Controller
   |--------------------------------------------------------------------------
   |
   | Localization related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Change language
   */
  public static function postSetLanguage()
  {
    return response()->json(['success' => true]);
  }

  /**
   * \Platform\Controllers\Core\Localization::getLanguagesArray();
   * Return array of languages in form of [LANGUAGE_CODE] => [LANGUAGE_NAME]
   */
  public static function getLanguagesArray()
  {
    $languages = array();
    $lang_path = base_path() . '/resources/lang/';
    $lang_dirs = \File::directories($lang_path);

    usort($lang_dirs, function ($dir1, $dir2) {
      return $dir1 <=> $dir2;
    });

    foreach($lang_dirs as $lang)
    {
      $language = include $lang . '/i18n.php';
      if (isset($language['language_is_active']) && $language['language_is_active']) {
        $languages[$language['language_code']] = $language['language_title'];
      }
    }

    return $languages;
  }

  /**
   * \Platform\Controllers\Core\Localization::getLanguages();
   * Get all languages
   */
  public static function getLanguages()
  {
    $current_language = \App::getLocale();
    $languages = \File::directories(base_path() . '/resources/lang/');

    usort($languages, function ($dir1, $dir2) {
      return $dir1 <=> $dir2;
    });

    $return = array();
    foreach($languages as $language)
    {
      if(\File::isFile($language . '/i18n.php'))
      {
        $i18n = include($language . '/i18n.php');
        if (isset($i18n['language_is_active']) && $i18n['language_is_active']) {
          $active = ($i18n['language_code'] == $current_language) ? true : false;
          $return[] = array('code' => $i18n['language_code'], 'title' => $i18n['language_title'], 'active' => $active);
        }
      }
    }

    $title = array();

    foreach ($return as $key => $row)
    {
      $title[$key] = $row['title'];
    }

    array_multisort($title, SORT_ASC, $return);

    return $return;
  }

  /**
   * \Platform\Controllers\Core\Localization::getAllLanguages();
   * Get all base languages
   */
  public static function getAllLanguages($two_char_only = true)
  {
    $current_language = (auth()->check()) ? auth()->user()->language : \App::getLocale();

    // Reads the language definitions from resources/language.
    $languageRepository = new \CommerceGuys\Intl\Language\LanguageRepository;
    $languages = $languageRepository->getAll($current_language);

    foreach ($languages as $language_code => $language) {

      if ($two_char_only && strlen($language_code) == 2) {
        $return[$language_code] = ['name' => $language->getName()];
      } elseif(! $two_char_only) {
        $return[$language_code] = ['name' => $language->getName()];
      }
    }
    return $return;
  }

  /**
   * \Platform\Controllers\Core\Localization::getAllCurrencies();
   * Get all currencies
   */
  public static function getAllCurrencies($flat_array = true)
  {
    $availableCurrencies = ['USD', 'EUR', 'GBP', 'RON', 'AUD', 'CAD', 'CHF', 'CZK', 'DKK', 'HUF', 'JPY', 'NOK', 'PLN', 'SEK', 'TRY', 'RUB', 'CNY', 'BGN', 'BRL', 'HKD', 'KRW', 'MXN', 'NZD', 'SGD', 'ZAR', 'MDL', 'AED', 'EGP', 'INR', 'RSD', 'UAH', 'TWD', 'ILS', 'SYP', 'QAR', 'SAR', 'ARS', 'CLP', 'BOB', 'COP', 'PYG', 'PEN', 'UYU', 'VEF', 'NGN', 'NAD', 'TND', 'DZD', 'KES', 'VND', 'BYN'];

    $current_language = (auth()->check()) ? auth()->user()->language : \App::getLocale();

    $currencyRepository = new \CommerceGuys\Intl\Currency\CurrencyRepository;

    $currencies = $currencyRepository->getAll($current_language);

    $return = [];

    foreach ($currencies as $currency_code => $currency) {

      if (in_array($currency_code, $availableCurrencies)) {
        if ($flat_array) {
          $return[$currency_code] = $currency->getName() . ' (' . $currency_code . ')';
        } else {
          $return[$currency_code] = ['name' => $currency->getName()];
        }
      }
    }
    return $return;
  }

  /**
   * Get language from browser
   */

  public static function getBrowserLocale()
  {
     // Parse the Accept-Language according to:
     // http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
     preg_match_all(
        '/([a-z]{1,8})' .       // M1 - First part of language e.g en
        '(-[a-z]{1,8})*\s*' .   // M2 -other parts of language e.g -us
        // Optional quality factor M3 ;q=, M4 - Quality Factor
        '(;\s*q\s*=\s*((1(\.0{0,3}))|(0(\.[0-9]{0,3}))))?/i',
        $_SERVER['HTTP_ACCEPT_LANGUAGE'],
        $langParse);

     $langs = $langParse[1]; // M1 - First part of language
     $quals = $langParse[4]; // M4 - Quality Factor

     $numLanguages = count($langs);
     $langArr = array();

     for ($num = 0; $num < $numLanguages; $num++)
     {
        $newLang = strtoupper($langs[$num]);
        $newQual = isset($quals[$num]) ?
           (empty($quals[$num]) ? 1.0 : floatval($quals[$num])) : 0.0;

        // Choose whether to upgrade or set the quality factor for the
        // primary language.
        $langArr[$newLang] = (isset($langArr[$newLang])) ?
           max($langArr[$newLang], $newQual) : $newQual;
     }

     // sort list based on value
     // langArr will now be an array like: array('EN' => 1, 'ES' => 0.5)
     arsort($langArr, SORT_NUMERIC);

     // The languages the client accepts in order of preference.
     $acceptedLanguages = array_keys($langArr);

     return $acceptedLanguages;
  }
}