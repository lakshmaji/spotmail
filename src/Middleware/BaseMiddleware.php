<?php

namespace Lakshmajim\SpotMail\Middleware;


use Lakshmajim\SpotMail\SpotMail;
use Illuminate\Contracts\Events\Dispatcher;
use Lakshmajim\SpotMail\Exception\SpotMailException;
use Illuminate\Contracts\Routing\ResponseFactory;


/**
 * SpotMail - BaseMiddleware class   
 *
 * @author     lakshmaji 
 * @package    SpotMail
 * @version    1.0.0
 * @since      Class available since Release 1.0.0
 */
abstract class BaseMiddleware
{

    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $response;

    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * @var \Lakshmajim\SpotMail\SpotMail
     */
    protected $SpotMail;

    //-------------------------------------------------------------------------


    /**
     * Create a new BaseMiddleware instance.
     *
     * @param \Illuminate\Contracts\Routing\ResponseFactory  $response
     * @param \Illuminate\Contracts\Events\Dispatcher        $events
     * @param \Lakshmajim\SpotMail\SpotMail                      $SpotMail
     */
    public function __construct(ResponseFactory $response, Dispatcher $events, SpotMail $SpotMail)
    {
        $this->response = $response;
        $this->events   = $events;
        $this->SpotMail   = $SpotMail;
    }

    //-------------------------------------------------------------------------


    /**
     * Fire event and return the response.
     *
     * @param  string   $event
     * @param  string   $error
     * @param  int      $status
     * @param  array    $payload
     * @return mixed
     */
    protected function respond($event, $error, $status, $payload = [])
    {
        $response = $this->events->fire($event, $payload, true);

        return $response ?: $this->response->json(['status'=>$status,'data' => array('error' => $error)], $status);
    }
    
    //-------------------------------------------------------------------------

}
// end of class BaseMiddleware
// end of file BaseMiddleware.php
