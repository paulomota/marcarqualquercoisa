<?php namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller {
	
	public function all(){
		$users = User::all();

   		//return View::make('users')->with('users', $users);
   		return View('users')->with('users', $users);
	}
}