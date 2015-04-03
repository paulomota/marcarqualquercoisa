<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\FacebookSession;
use Facebook\GraphUser;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class AuthController extends Controller {

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
	public function __construct(Guard $auth, Registrar $registrar){
		$this->auth = $auth;
		$this->registrar = $registrar;

		$this->middleware('guest', ['except' => 'getLogout']);
	}

	public function loginWithFacebook(){
		$redirect_url = URL::to('/') . '/return-fb-login';
		
		session_start();
		
		$helper = new FacebookRedirectLoginHelper($redirect_url, self::FACEBOOK_APP_ID, self::FACEBOOK_APP_KEY);
		
		$permissionsList = 'user_friends,email,user_birthday,user_likes,user_location,user_relationships';
		
		$params = array('url_login_fb' => $helper->getLoginUrl().$permissionsList);
		
		return View('auth/login')->with($params);
	}

	public function returnOfFacebookLogin(){
		$redirect_url = URL::to('/') . '/return-fb-login';
		
		session_start();
		
		FacebookSession::setDefaultApplication(self::FACEBOOK_APP_ID, self::FACEBOOK_APP_KEY);
		$helper = new FacebookRedirectLoginHelper($redirect_url);
		
		try {
			$session = $helper->getSessionFromRedirect();
			
			if ($session) {
				$user_profile = (new FacebookRequest($session, 'GET', '/me'))->execute()->getGraphObject(GraphUser::className());

				$userRetrieved = User::where('email', '=', $user_profile->getEmail())->first();
				
				if($userRetrieved != null){
					echo 'usuario ja existe - ';
					return self::authenticate($userRetrieved);
				}
				
				$user = new User();
				$user->name = $user_profile->getName();
				$user->email = $user_profile->getEmail();
				$user->password = bcrypt($user_profile->getEmail());
				$user->dt_nascimento = $user_profile->getBirthday();
				$user->sexo = $user_profile->getGender();
				//$user->location = $user_profile->getLocation()->name;
				//$user->relationshipStatus = $user_profile->getRelationshipStatus();
				
				$user->save();
				
				return self::authenticate($user);
				
			}
		} catch(FacebookRequestException $ex) {
			echo $ex->getMessage();
		} catch(\Exception $ex) {
			echo $ex->getMessage();
		}
		
	}
	
	public function authenticate(User $user){
		if (Auth::attempt(['email' => $user->email, 'password' => $user->email])){
			return redirect()->intended('/');
		}
	}
}
