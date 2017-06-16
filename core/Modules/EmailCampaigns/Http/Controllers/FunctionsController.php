<?php

namespace Modules\EmailCampaigns\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use \Platform\Controllers\Core;
use Modules\EmailCampaigns\Http\Models;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class FunctionsController extends Controller
{
  /**
   * Get all campaign categories
   */
  public static function getCampaignCategories()
  {
    //$items = [];

    $items['transactional_email'] = [
      'icon' => 'refresh1.svg',
      'category' => 'transactional_email',
      'name' => trans_choice('emailcampaigns::global.transactional_email', 1),
      'desc' => trans('emailcampaigns::global.transactional_email_desc')
    ];

    $items['marketing_email'] = [
      'icon' => 'letter.svg',
      'category' => 'marketing_email',
      'name' => trans_choice('emailcampaigns::global.marketing_email', 1),
      'desc' => trans('emailcampaigns::global.marketing_email_desc')
    ];
/*
    $items['drip_campaign'] = [
      'icon' => 'calendar.svg',
      'category' => 'drip_campaign',
      'name' => trans_choice('emailcampaigns::global.drip_campaign', 1),
      'desc' => trans('emailcampaigns::global.drip_campaign_desc')
    ];
*/
    return $items;
  }

  /**
   * Get all email categories
   */
  public static function getEmailCategories()
  {
    //$items = [];

    $items['opt_in'] = [
      'icon' => 'chatbubble.svg',
      'category' => 'opt_in',
      'name' => trans('emailcampaigns::global.opt_in'),
      'desc' => trans('emailcampaigns::global.opt_in_desc')
    ];

    $items['news'] = [
      'icon' => 'newsletter.svg',
      'category' => 'news',
      'name' => trans('emailcampaigns::global.news'),
      'desc' => trans('emailcampaigns::global.news_desc')
    ];

    $items['other'] = [
      'icon' => 'website.svg',
      'category' => 'other',
      'name' => trans('emailcampaigns::global.other'),
      'desc' => trans('emailcampaigns::global.other_desc')
    ];

    return $items;
  }

  /**
   * Get all form templates from a category
   */
  public static function getTemplatesByCategory($category)
  {
    $category_templates = [];

    $templates = array_sort(\File::directories(base_path('../templates/emails/')), function($dir) {
      if (\File::exists($dir . '/config.php')) {
        $config = include $dir . '/config.php';
        return $config['created_at'];
      } else {
        return $dir;
      }
    });

    foreach ($templates as $template) {
      if (\File::exists($template . '/config.php') && \File::exists($template . '/index.blade.php')) {
        $config = include $template . '/config.php';

        if ($config['active'] && in_array($category, $config['categories'])) {

          $dir = basename($template);

          // Create thumbnail for preview if not exists
          $preview01_path = base_path('../templates/emails/' . $dir . '/preview-01.png');
          $preview01_thumb = 'emails/template/' . $dir . '/preview/01-600.jpg';

          $exists = Storage::disk('public')->exists($preview01_thumb);

          if (! $exists) {
            $img = \Image::make($preview01_path);

            $img->resize(600, null, function ($constraint) {
              $constraint->aspectRatio();
            });

            $img_string = $img->encode('jpg', 60);

            Storage::disk('public')->put($preview01_thumb, $img_string->__toString());
            $preview01_url = Storage::disk('public')->url($preview01_thumb);
          } else {
            $preview01_url = Storage::disk('public')->url($preview01_thumb);
          }

          $category_templates[] = [
            'dir' => $dir,
            'created_at' => $config['created_at'],
            'updated_at' => $config['updated_at'],
            'preview01' => $preview01_url
          ];

        }
      }
    }

    return $category_templates;
  }

  /**
   * Create a email campaign
   */
  public static function createCampaign($name, $category, $user_id = null, $funnel_id = null)
  {
    if ($user_id == null) $user_id = Core\Secure::userId();
    if ($funnel_id == null) $funnel_id = Core\Secure::funnelId();

    $name = substr($name, 0, 200);

    // Check if category exists
    $hasItem = false;
    $categories = FunctionsController::getCampaignCategories();
    foreach($categories as $cat) {
      if ($cat['category'] == $category) {
        $hasItem = true;
        break;
      }
    }

    if ($name != '' && $hasItem) {
      // Create campaign
      $email_campaign = new Models\EmailCampaign;

      $email_campaign->user_id = $user_id;
      $email_campaign->funnel_id = $funnel_id;
      $email_campaign->name = $name;
      $email_campaign->type = $category;
      $email_campaign->language = auth()->user()->language;
      $email_campaign->timezone = auth()->user()->timezone;
      $email_campaign->save();

      $email_campaign_id = $email_campaign->id;

      return $email_campaign;
    } else {
      return false;
    }
  }

  /**
   * Create an email
   */
  public static function createEmail($email_campaign, $template, $name, $user_id = null, $funnel_id = null)
  {
    if ($user_id == null) $user_id = Core\Secure::userId();
    if ($funnel_id == null) $funnel_id = Core\Secure::funnelId();

    $name = substr($name, 0, 200);
    $template_path = base_path('../templates/emails/');

    if (\File::exists($template_path . $template . '/config.php') && \File::exists($template_path . $template . '/index.blade.php')) {
      $config = include $template_path . $template . '/config.php';

      // Create email for campaign
      $email = new Models\Email;

      $email->user_id = $user_id;
      $email->email_campaign_id = $email_campaign->id;
      $email->name = $name;
      $email->subject = trans('emailcampaigns::global.default_email_subject');
      $email->template = $template;
      $email->save();

      $local_domain = Core\Secure::staticHash($email->id, true);

      $email->local_domain = $local_domain;
      $email->save();

      // Finally, create directory with files
      $storage_root = 'emails/email/' . Core\Secure::staticHash($user_id) . '/' . Core\Secure::staticHash($email_campaign->id, true) . '/' . $local_domain;

      // Get template HTML and replace title
      $html = view('template.emails::' . $template . '.index')->render();

      //libxml_use_internal_errors(true);
      //$html = \phpQuery::newDocumentHTML($html);
      //\phpQuery::selectDocument($html);

      // Update page
      //pq('title')->text($name);

      // Beautify html
      $html = Core\Parser::beautifyHtml($html);

      $variant = 1;

      $storage_root_full = $storage_root . '/' . $variant;

      \Storage::disk('public')->makeDirectory($storage_root_full . '/' . date('Y-m-d-H-i-s'));
      \Storage::disk('public')->put($storage_root_full . '/' . date('Y-m-d-H-i-s') . '/index.blade.php', $html);
      \Storage::disk('public')->put($storage_root_full . '/index.blade.php', $html);

      return $email;
    } else {
      return false;
    }
  }

  /**
   * Save / publish email
   */
  public static function saveEmail($sl, $forms, $subject, $from_name, $from_email, $html, $publish = false, $user_id = null)
  {
    if ($user_id == null) $user_id = Core\Secure::userId();

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);

      $email_id = $qs['email_id'];
      $email = Models\Email::where('user_id', $user_id)->where('id', $email_id)->first();
      $email->subject = $subject;
      $email->save();

      // Link forms
      /*
      $forms_sync = [];
      if (! empty($forms)) {
        foreach ($forms as $form) {
          $forms_sync[] = Core\Secure::staticHashDecode($form, true);
        }
      }*/

      $email->forms()->sync($forms);

      $emailCampaign = Models\EmailCampaign::where('id', $email->email_campaign_id)->first();
      $emailCampaign->mail_from = $from_email;
      $emailCampaign->mail_from_name = $from_name;
      $emailCampaign->save();

      $variant = 1;

      // Update files
      $storage_root = 'emails/email/' . Core\Secure::staticHash($user_id) . '/' .  Core\Secure::staticHash($email->email_campaign_id, true) . '/' . Core\Secure::staticHash($email->id, true) . '/' . $variant;

      // Beautify html
      $html = Core\Parser::beautifyHtml($html);

      \Storage::disk('public')->makeDirectory($storage_root . '/' . date('Y-m-d-H-i-s'));
      \Storage::disk('public')->put($storage_root . '/' . date('Y-m-d-H-i-s') . '/index.blade.php', $html);
      \Storage::disk('public')->put($storage_root . '/index.blade.php', $html);

      if ($publish) {
        \Storage::disk('public')->put($storage_root . '/published/index.blade.php', $html);
      }

      // Limit history
      $limit = 11;
      $saves = \Storage::disk('public')->directories($storage_root);

      usort($saves, function ($dir1, $dir2) {
        return $dir2 <=> $dir1;
      });

      if (count($saves) > $limit) {
        for($i = $limit; $i < count($saves); $i++) {
          \Storage::disk('public')->deleteDirectory($saves[$i]);
        }
      }

      return true;
    } else {
      return false;
    }
  }

  /**
   * Parse email
   */
  public static function parseEmail($email_address, $view, $form = null)
  {
    $html = view($view)->render();

    $form_local_domain = '';
    $form_columns = [];
    $form_entry = [];

    if ($form != null) {
      $form_local_domain = '/' . $form->local_domain;

      // Get entry
    //  $tbl_name = 'x_form_entries_' . $form->user_id;
      //$Entry = new \Modules\Forms\Http\Models\Entry([]);
      //$Entry->setTable($tbl_name);

      $form_entry = \Modules\Forms\Http\Models\Entry::where('form_id', $form->id)->where('email', $email_address)->orderBy('created_at', 'desc')->first();

      if (! empty($form_entry)) {
        $form_columns = $form_entry->getColumns($form->id);
        $form_columns = (isset($form_columns['form'])) ? $form_columns['form'] : [];
        $form_local_domain .= '/' . Core\Secure::staticHash($form_entry->id, true);
        $form_entry = $form_entry->toArray();
      } else {
        $form_entry = [];
      }
    }

    $form_columns = array_merge(['personal_email'], $form_columns);

    preg_match_all("/--(.*)--/U", $html, $matches, PREG_PATTERN_ORDER);

    if (isset($matches[1])) {
      foreach ($matches[1] as $match) {
        if ($match == 'confirm') {
          $link = url('ec/confirm/' . $email_address . $form_local_domain);
          $html = str_replace('--confirm--', $link, $html);
        } elseif ($match == 'unsubscribe') {
          $link = url('ec/unsubscribe/' . $email_address . $form_local_domain);
          $html = str_replace('--unsubscribe--', $link, $html);
        } else {
          // Check for default value
          $variable = $match;
          $default_value = '';
          if (strpos($match, '=') !== false) {
            $default_value = explode('=', $match);
            if (isset($default_value[1]) && trim($default_value[1]) != '') {
              $variable = $default_value[0];
              $default_value = $default_value[1];
            }
          }
          if ($variable == 'personal_email') $variable = 'email';

          // Check if variable exists
          if (isset($form_entry[$variable]) && $form_entry[$variable] != '') {
            $html = str_replace('--' . $match . '--', $form_entry[$variable], $html);
          } elseif ($default_value != '') {
            $html = str_replace('--' . $match . '--', $default_value, $html);
          } elseif(isset($form_entry[$variable])) {
            $html = str_replace('--' . $match . '--', '', $html);
          }
        }
      }
    }

    // CSS to inline
    libxml_use_internal_errors(true);
    $dom = \phpQuery::newDocumentHTML($html);
    \phpQuery::selectDocument($dom);

    // Get CSS
    $html = pq('html')->html();
    $css = pq('html')->find('style[type=text/css]:first')->html();
    $html = str_replace($css, '', $html);

    if ($css != '') {
      $cssToInlineStyles = new CssToInlineStyles();

      // output
      $html = $cssToInlineStyles->convert(
          $html,
          $css
      );
    }

    return $html;
  }

  /**
   * Parse string
   */
  public static function parseString($email_address, $string, $form = null)
  {
    $html = $string;

    $form_local_domain = '';
    $form_columns = [];
    $form_entry = [];

    if ($form != null) {
      $form_local_domain = '/' . $form->local_domain;

      // Get entry
      //$tbl_name = 'x_form_entries_' . $form->user_id;
      //$Entry = new \Modules\Forms\Http\Models\Entry([]);
      //$Entry->setTable($tbl_name);

      $form_entry = \Modules\Forms\Http\Models\Entry::where('form_id', $form->id)->where('email', $email_address)->orderBy('created_at', 'desc')->first();

      if (! empty($form_entry)) {
        $form_columns = $form_entry->getColumns($form->id);
        $form_columns = (isset($form_columns['form'])) ? $form_columns['form'] : [];
        $form_local_domain .= '/' . Core\Secure::staticHash($form_entry->id, true);
        $form_entry = $form_entry->toArray();
      } else {
        $form_entry = [];
      }
    }

    $form_columns = array_merge(['personal_email'], $form_columns);

    preg_match_all("/--(.*)--/U", $html, $matches, PREG_PATTERN_ORDER);

    if (isset($matches[1])) {
      foreach ($matches[1] as $match) {
        if ($match == 'confirm') {
          $link = url('ec/confirm/' . $email_address . $form_local_domain);
          $html = str_replace('--confirm--', $link, $html);
        } elseif ($match == 'unsubscribe') {
          $link = url('ec/unsubscribe/' . $email_address . $form_local_domain);
          $html = str_replace('--unsubscribe--', $link, $html);
        } else {
          // Check for default value
          $variable = $match;
          $default_value = '';
          if (strpos($match, '=') !== false) {
            $default_value = explode('=', $match);
            if (isset($default_value[1]) && trim($default_value[1]) != '') {
              $variable = $default_value[0];
              $default_value = $default_value[1];
            }
          }
          if ($variable == 'personal_email') $variable = 'email';

          // Check if variable exists
          if (isset($form_entry[$variable]) && $form_entry[$variable] != '') {
            $html = str_replace('--' . $match . '--', $form_entry[$variable], $html);
          } elseif ($default_value != '') {
            $html = str_replace('--' . $match . '--', $default_value, $html);
          } elseif(isset($form_entry[$variable])) {
            $html = str_replace('--' . $match . '--', '', $html);
          }
        }
      }
    }

    return $html;
  }
}
