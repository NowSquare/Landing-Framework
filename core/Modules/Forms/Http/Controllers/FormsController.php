<?php

namespace Modules\Forms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;
use Modules\Forms\Http\Models;

class FormsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
      $items = [];

      $items[] = [
        "icon" => 'handshake.svg',
        "name" => trans('forms::global.contact_form'),
        "desc" => trans('forms::global.contact_form_desc'),
        "url" => "#/forms/contact"
      ];

      $items[] = [
        "icon" => 'attachmentadd.svg',
        "name" => trans('forms::global.download_form'),
        "desc" => trans('forms::global.download_form_desc'),
        "url" => "#/forms/contact"
      ];

      return view('forms::create', compact('items'));
    }

    /**
     * Forms backend main
     */
    public function index()
    {
      $forms = Models\Form::where('user_id', Core\Secure::userId())->orderBy('created_at', 'desc')->get();

      if (count($forms) == 0) {
        return $this->create();
      } else {
        return view('forms::index', compact('forms'));
      }
    }
}
