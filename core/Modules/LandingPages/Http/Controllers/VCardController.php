<?php

namespace Modules\LandingPages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Platform\Controllers\Core;

use Behat\Transliterator\Transliterator;
use JeroenDesloovere\VCard\VCard;

class VCardController extends Controller
{
    /**
     * Generate vCard
     */
    public function getVCard()
    {
      $vcard_data = request()->all();

			// define vcard
			$vcard = new VCard();

			// add personal data
			$vcard->addName($vcard_data['last_name'], $vcard_data['first_name'], '', (isset($vcard_data['prefix'])) ? $vcard_data['prefix'] : '', (isset($vcard_data['prefix'])) ? $vcard_data['suffix'] : '');

			// add work data
			$vcard->addCompany((isset($vcard_data['company'])) ? $vcard_data['company'] : '');
			$vcard->addJobtitle((isset($vcard_data['job_title'])) ? $vcard_data['job_title'] : '');
			$vcard->addEmail((isset($vcard_data['email'])) ? $vcard_data['email'] : '');
			$vcard->addPhoneNumber((isset($vcard_data['phone_work'])) ? $vcard_data['phone_work'] : '', 'PREF;WORK');
			$vcard->addAddress(null, null, (isset($vcard_data['business_street'])) ? $vcard_data['business_street'] : '', (isset($vcard_data['business_city'])) ? $vcard_data['business_city'] : '', (isset($vcard_data['business_state'])) ? $vcard_data['business_state'] : '', (isset($vcard_data['business_zip'])) ? $vcard_data['business_zip'] : '', (isset($vcard_data['business_country'])) ? $vcard_data['business_country'] : '', 'WORK;POSTAL');
			$vcard->addURL((isset($vcard_data['work_website'])) ? $vcard_data['work_website'] : '');

			// Personal data
			$vcard->addPhoneNumber((isset($vcard_data['phone_home'])) ? $vcard_data['phone_home'] : '', 'HOME');
			$vcard->addAddress(null, null, (isset($vcard_data['home_street'])) ? $vcard_data['home_street'] : '', (isset($vcard_data['home_city'])) ? $vcard_data['home_city'] : '', (isset($vcard_data['home_state'])) ? $vcard_data['home_state'] : '', (isset($vcard_data['home_zip'])) ? $vcard_data['home_zip'] : '', (isset($vcard_data['home_country'])) ? $vcard_data['home_country'] : '', 'HOME');
			$vcard->addURL((isset($vcard_data['personal_website'])) ? $vcard_data['personal_website'] : '');

			$photo = (isset($vcard_data['photo'])) ? $vcard_data['photo'] : '';

			if($photo != '') {
				$vcard->addPhoto(url($photo));
			}

			// return vcard as a string
			//return $vcard->getOutput();

			// return vcard as a download
			return $vcard->download();
    }
}
