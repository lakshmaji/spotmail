<?php

// namespace of this class
namespace Lakshmajim\SpotMail\Facade;

// import required namespaces here
use Illuminate\Support\Facades\Facade;


/**
 * SpotMail - Facade to support integration with Laravel Framework
 *
 * @package SpotMail
 * @version 1.0.0
 * @since   Class available since release 1.0.0
 * @author  lakshmajim < lakshmajee88@gmail.com >
 */
class SpotMail extends Facade 
{
	protected static function getFacadeAccessor() 
	{
		return 'spotmail';
	}
}
// end of class SpotMail
// end of file SpotMail.php