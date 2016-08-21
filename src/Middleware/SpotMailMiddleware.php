<?php

namespace Lakshmajim\SpotMail\Middleware;


use Lakshmajim\SpotMail\SpotMail;
use Lakshmajim\SpotMail\Exception\SpotMailException;


/**
 * SpotMail - SpotMailMiddleware class   
 *
 * @author     lakshmaji 
 * @package    SpotMail
 * @version    1.0.0
 * @since      Class available since Release 1.0.0
 */
class SpotMailMiddleware extends BaseMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        try
        {
            // $spotmail = new SpotMail;
            // $response=$spotmail->locate("lakshmaji@lakshmaji.tk",true); 

            // check email was set or not
            if(!$request->get('email')) {
                throw new SpotMailException("No email was present", 400);       
            }
            
            $email  = $request->get('email');
            $result = \SpotMail::locate($email, true);
            $result = json_decode($result, true);

            if(! ($result['code'] == 13000 || $result['code'] == 13001) ) {
                throw new SpotMailException("Error Processing Request", 400);
                
            } 

            // log the email response here if required
            // echo $result['info'];
        
        }
        catch(SpotMailException $e)
        {
            return $this->respond('email.validate', $e->getMessage(), 400, [$e]);
        }

        return $next($request);
    }
}
// end of class SpotMailMiddleware
// end of file SpotMailMiddleware.php