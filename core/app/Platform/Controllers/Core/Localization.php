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
   * \Platform\Controllers\Core\Localization::getLanguagesArray();
   * Return array of languages in form of [LANGUAGE_CODE] => [LANGUAGE_NAME]
   */
  public static function getLanguagesArray()
  {
    $languages = array();
    $lang_path = base_path() . '/resources/lang/';
    $lang_dirs = \File::directories($lang_path);

    foreach($lang_dirs as $lang)
    {
      $language = include $lang . '/i18n.php';
      $languages[$language['language_code']] = $language['language_title'];
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

    $return = array();
    foreach($languages as $language)
    {
      if(\File::isFile($language . '/i18n.php'))
      {
        $i18n = include($language . '/i18n.php');
        $active = ($i18n['language_code'] == $current_language) ? true : false;
        $return[] = array('code' => $i18n['language_code'], 'title' => $i18n['language_title'], 'active' => $active);
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
}