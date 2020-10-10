<?php 
namespace Armincms\Arminpay\Http\Controllers\Web;

use Armin\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Core\User\Models\User;


class AccountCreateController extends Controller
{ 
	/**
	 * Store new product into database.
	 * 
	 * @param  Request $request 
	 * @return \Illuminate\Http\Response           
	 */
    public function __invoke(Request $request)
    {
    	$this->validate($request, $this->rules()); 

    	return $this->createUser($request); 
    }

    /**
     * Store new user in database
     * 
     * @param  \Illuminate\Http\Request $request 
     * @return \Illuminate\Database\Eloquent\Model           
     */
    public function createUser(Request $request)
    {  
        $user = tap(User::withTrashed()->firstOrNew(['email' => $request->email], []), function($user) {
            if(is_null($user->id)) {
                $user->fill($this->fetchUserDataFromRequest(request()))->save();
            }

            if(! is_null($user->deleted_at)) {
                $user->update(['deleted_at' => null]);
            } 
        }); 

        return [
            'id'    => $user->id,
            'user'  => $user->getMorphClass(),
        ];
    }

    /**
     * Retrive user data from request data.
     * 
     * @param  \Illuminate\Http\Request $request 
     * @return array           
     */
    public function fetchUserDataFromRequest(Request $request)
    {
        return [
            'username'  => $request->username ?: $request->email,
            'password'  => bcrypt(
                $request->password ?: $this->makeNewPassword($request->email)
            ),
            'firstname' => $request->firstname, 
            'lastname'  => $request->lastname,   
        ]; 
    } 

    public function makeNewPassword($email)
    {
        return tap(time(), function($password) use ($email) {
            $message = __("Your password is :password", compact('password'));
            $url = __("Your Login url is :url", [
                'url' => url('license/customer/order')
            ]); 

            \Mail::raw($message."\r\n".$url, function ($message) use ($email) {
                $message->from('aqoela@aqoela.com', 'Aqoela');
                $message->sender('aqoela@aqoela.com', 'Aqoela');
            
                $message->to($email, $email);
            
                // $message->cc($email, $email);
                // $message->bcc($email, $email);
            
                // $message->replyTo($email, $email);
            
                $message->subject(__(":name User Panel Password", [
                    'name' => request()->getHost()
                ]));
            
                // $message->priority(3); 
            }); 
        });
    }

    /**
     * User validation rules.
     * 
     * @return array
     */
    public function rules()
    {
    	return [
    		'email'   => 'required|email',
    		'username'=> 'alpha_dash',   
    	];
    }
}