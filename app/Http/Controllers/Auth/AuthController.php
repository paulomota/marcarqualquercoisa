<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\FacebookSession;
use Facebook\GraphUser;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\URL;

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
	
	//Dados do app de producao
	//const FACEBOOK_APP_ID = '670332853071736';
	//const FACEBOOK_APP_KEY = '31854c43fc9b1b8544620e3071dea6d2';
	
	//Dados do app de teste
	const FACEBOOK_APP_ID = '671882166250138';
	const FACEBOOK_APP_KEY = 'dd517df6535a74a189ed90f2dd84111d';
	
	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  Guard  $auth
	 * @param  Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
	}

	public function loginWithFacebook(){
		echo "login with facebook - ";
		
		$redirect_url = URL::to('/') . '/return-facebook-login';
		
		session_start();
		
		$helper = new FacebookRedirectLoginHelper($redirect_url, self::FACEBOOK_APP_ID, self::FACEBOOK_APP_KEY);
		
		$permissionsList = 'user_friends,email,user_birthday,user_likes,user_location,user_relationships';
		
		echo $helper->getLoginUrl().$permissionsList;
		
		echo '<a href="' . $helper->getLoginUrl().$permissionsList. '">Login with Facebook</a>';
	}

	public function returnOfFacebookLogin(){
		echo "chegou";
		
		$redirect_url = URL::to('/') . '/return-facebook-login';
		
		session_start();
		
		FacebookSession::setDefaultApplication(self::FACEBOOK_APP_ID, self::FACEBOOK_APP_KEY);
		$helper = new FacebookRedirectLoginHelper($redirect_url);
		
		try {
			$session = $helper->getSessionFromRedirect();
			
			if ($session) {
				echo 'tem sessao mano!';
				
				$user_profile = (new FacebookRequest($session, 'GET', '/me'))->execute()->getGraphObject(GraphUser::className());

				echo "Name: " . $user_profile->getName();
				
				echo "Email: " . $user_profile->getEmail();
				
				$user_profile->getBirthday();
				$user_profile->getFavoriteTeams();
				
//				first_name
//				last_name
//				gender
//				location
//				relationship_status
//				sports
//				timezone
//				verified

				
				var_dump($user_profile);
			}
		} catch(FacebookRequestException $ex) {
			echo $ex->getMessage();
		} catch(\Exception $ex) {
			echo $ex->getMessage();
		}
		
//		$session = new FacebookSession($acessToken);
//		
//		if($session) {
//			try {
//			  $user_profile = (new FacebookRequest($session, 'GET', '/me'))->execute()->getGraphObject(GraphUser::className());
//
//			  echo "Name: " . $user_profile->getName();
//
//			} catch(FacebookRequestException $e) {
//
//			  echo "Exception occured, code: " . $e->getCode();
//			  echo " with message: " . $e->getMessage();
//
//			}   
//		}
	}
}
