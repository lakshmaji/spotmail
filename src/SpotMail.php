<?php

/*
|--------------------------------------------------------------------------
| SpotMail class for implementing simple mail check with Laravel 
|--------------------------------------------------------------------------
|
*/

// define namespace 
namespace Lakshmajim\SpotMail;

// import required namespaces here
use Lakshmajim\SpotMail\Exception\SpotMailException;
// use Lakshmajim\SpotMail\Utility;


/**
 * SpotMail - A package for Detecteing whether the 
 * given email is valid or not by using MX records 
 * and configurable lookup domains. 
 *
 * @author     lakshmaji 
 * @package    Device
 * @version    1.0.0
 * @since      Class available since Release 1.0.0
 */
class SpotMail
{
	public function locate($email, $validate_email = false) 
	{
		// Set the status code 
		$http_code = 200;

		// Initialize array to hold the response
		$response = array();

		// format email 
		$email = strtolower($email);

		// email validation
		if ($validate_email) {
			// set email was not validated
			$is_valid_email = false;
			// validate email address
			$is_valid_email = $this->validateEmail($email);

			if (!$is_valid_email) {
				// the email address is not a valid format
				throw new SpotMailException("Not an valid email address", 400);
			}
		}

		// $user_mail_domain = $this->getDomainFromEmail($email);
		$user_mail_domain = $this->getDomain($email);

		$domains_configured = explode(",", str_replace(" ", "", config('spotmail.domains_list')));

		$email_validity = new Utility\EmailFactory($email, 'lakshmajim@yahoo.in');

		$mail_status = $email_validity->verify();

		if($mail_status) {
			$response['code'] = 13000;
			$response['info'] = "The email account exists";
		} else {

			if (in_array($user_mail_domain, $domains_configured)) {

				if ($mail_status) {
					$response['code'] =13000;
					$response['info'] = "The email account exists";
				} else {
					// email doesnot exists but the domain exists
					throw new SpotMailException($email." doesn't have account associated with ".$user_mail_domain, 400);
				}
			} else {

				// if domain is not in above lists then check for mx status only 
				$mail_response_array = $email_validity->get_debug();

				// domain not exists condition
				if (count($mail_response_array) < 5) {
					// MX entires were not found
					throw new SpotMailException("The MX records were not found (".$email." doesnot exists)");
				}

				// return true if domain is valid
				if( strtolower( str_replace(" ", "", $mail_response_array[7]) ) == "gota220response.sendinghelo...") {
					$http_code = 206;
					$response['code'] = 13001;
					$response['info'] = "The domain was found, but unable to fetch the account details";
				} else {
					// private domain and unable to find the email account
					throw new SpotMailException("Custom domain with no mail was found", 400);
				}
			}
		}

		return json_encode($response, $http_code);
	}


	public function getDomainFromEmail($email)
	{
		$domain = substr(strstr($email, "@"), 1);

		return $domain;
	}

	public function getDomain($email)
	{
		list($user_mail, $domain) = explode("@", $email);

		return $domain;
	}

	public function validateEmail($email)
	{
		$email = trim($email);
		$email = str_replace(" ", "", $email);

		if ( filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE ) {
			return FALSE;
		}
		if ( substr_count( $email, "@") > 1) {
			return FALSE;
		}
		if ( preg_match("#[\;\#\n\r\*\'\"<>&\%\!\(\)\{\}\[\]\?\\/\s]#", $email )) {
			return FALSE;
		}
		else if ( preg_match( "/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,4})(\]?)$/", $email) ) {
			return TRUE;
		}
		else {
			return FALSE;
		}
	}
}