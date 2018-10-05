<?php
namespace Platform\Controllers\Core;

/**
 * Thumbnail class
 *
 */

class Thumb extends \App\Http\Controllers\Controller {

  /**
   * Create thumbnail
   */
  public function getNail() {
    $return = request()->get('return', 'img');
    $img = request()->get('img', '');
    $target = request()->get('target', ''); // If empty elFinder .tmb path is used
    $w = request()->get('w', 0);
    $h = request()->get('h', 0);
    $t = request()->get('t', 'crop');

    $img_part = pathinfo($img);

    $root = substr(url('/'), strpos(url('/'), \Request::server('HTTP_HOST')));
    $abs_path_prefix = url('/');

    $img_part['dirname'] = str_replace($abs_path_prefix, '', $img_part['dirname']);
    $img = public_path() . str_replace($abs_path_prefix, '', $img);

    if($target == '') {
      $target = $img_part['dirname'] . '/.tmb/' . $img_part['filename'] . '-' . $w . 'x' . $h . '-' . $t . '.' . $img_part['extension'];
    }

    if($w == 0) $w = NULL;
    if($h == 0) $h = NULL;

    if(! \File::exists(public_path() . $target)) {
      // Create dir
      if(! \File::isDirectory(public_path() . $img_part['dirname'] . '/.tmb/')) {
        \File::makeDirectory(public_path() . $img_part['dirname'] . '/.tmb/');
      }

      if ($t == 'crop') {
        $img = \Image::make($img)->fit($w, $h, function ($constraint) use($t) {
          //$constraint->aspectRatio();
        })->save(public_path() . $target);
      } elseif($t == 'fit') {
        $img = \Image::make($img)->crop($w, $h, function ($constraint) use($t) {
          //$constraint->aspectRatio();
        })->save(public_path() . $target);
      } elseif($t == 'resize') {
        $img = \Image::make($img)->resize($w, $h, function ($constraint) use($t) {
          $constraint->aspectRatio();
        })->save(public_path() . $target);
      } elseif($t == 'resize-ratio') {
        $img = \Image::make($img)->resize($w, $h, function ($constraint) use($t) {
          $constraint->aspectRatio();
        })->save(public_path() . $target);
      }
    }

    if($return == 'img') {
      $type = 'image/' . $img_part['extension'];

      \Response::make('', 200, 
        array(
          'Content-Type' => $type,
          'Content-Transfer-Encoding' => 'binary',
          'Content-Disposition' => 'inline',
          'Expires' => 0,
          'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
          'Pragma' => 'public'
        )
      );

      readfile(public_path() . $target);
    } elseif($return == 'path') {
      return $target;
    }
  }

  /**
   * Create thumbnail
   * Core\Thumb::nail('/path/to/img', '/path/to/thumb/dir', 64, 64, 'crop');
   */
  public static function nail($img, $dir, $w = 0, $h = 0, $t = 'crop') {
    $img_part = pathinfo($img);

    $root = substr(url('/'), strpos(url('/'), \URL::current()));
    $abs_path_prefix = $root;

    $img_part['dirname'] = str_replace($abs_path_prefix, '', $img_part['dirname']);
    $img = public_path() . str_replace($abs_path_prefix, '', $img);

    $target = $dir . '/' . $img_part['filename'] . '-' . $w . 'x' . $h . '-' . $t . '.' . $img_part['extension'];

    if($w == 0) $w = NULL;
    if($h == 0) $h = NULL;

    if(! \File::exists(public_path() . $target)) {
      // Create dir
      if(! \File::isDirectory(public_path() . $dir)) {
        \File::makeDirectory(public_path() . $dir);
      }

      if ($t == 'crop') {
        $img = \Image::make($img)->fit($w, $h, function ($constraint) use($t) {
          //$constraint->aspectRatio();
        })->save(public_path() . $target);
      } elseif($t == 'fit') {
        $img = \Image::make($img)->crop($w, $h, function ($constraint) use($t) {
          //$constraint->aspectRatio();
        })->save(public_path() . $target);
      } elseif($t == 'resize') {
        $img = \Image::make($img)->resize($w, $h, function ($constraint) use($t) {
          $constraint->aspectRatio();
        })->save(public_path() . $target);
      } elseif($t == 'resize-ratio') {
        $img = \Image::make($img)->resize($w, $h, function ($constraint) use($t) {
          $constraint->aspectRatio();
        })->save(public_path() . $target);
      }
    }

    return $target;
  }
}