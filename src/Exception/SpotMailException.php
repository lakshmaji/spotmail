<?php

// namespace of this class
namespace Lakshmajim\SpotMail\Exception;

// import required namespaces here


/**
 * SpotMail - SpotMail Exception class
 *
 * @package SpotMail
 * @version 1.0.0
 * @since   Class available since release 1.0.0
 * @author  lakshmajim < lakshmajee88@gmail.com >
 */
class SpotMailException extends \Exception
{
    protected $status_code = 500;

    public function __construct($message = "An error ocured", $status_code = null)
    {
        parent::__construct($message);

        if (! is_null($status_code)) {
            $this->setStatusCode($status_code);
        }
    }

    /**
     *
     * @param int $status_code
     *
     */
    public function setStatusCode($status_code)
    {
        $this->status_code = $status_code;
    }

    /**
     *
     * @return int $status_code
     *
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }
}
// end of class SpotMail
// end of file SpotMail.php