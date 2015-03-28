<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookRedirectLoginHelper;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
	}

	public function loginWithFacebook(){
		FacebookSession::setDefaultApplication('670332853071736', '31854c43fc9b1b8544620e3071dea6d2');

		session_start();

		$helper = new FacebookRedirectLoginHelper('http://marcarqualquercoisa.com.br/return-facebook-login');

		$session = $helper->getSessionFromRedirect();

		$loginUrl = $helper->getLoginUrl();
		// Use the login url on a link or button to redirect to Facebook for authentication

	
		return View('auth/login')->with('facebookLoginUrl', $loginUrl);

		/*
		$helper = new FacebookRedirectLoginHelper();

		try {
		  $session = $helper->getSessionFromRedirect();
		} catch(FacebookRequestException $ex) {
		  // When Facebook returns an error
			echo "Facebook returns an error";
		} catch(\Exception $ex) {
		  // When validation fails or other local issues
			echo "validation fails or other local issues";
		}

		if ($session) {
		  echo "logado com sucesso";
		}

		echo "fim da autenticacao";
		*/
	}
}
