<?php

/*
|--------------------------------------------------------------------------
| Games Collection Controller
|--------------------------------------------------------------------------
| Nerdery Code Challenge
| Todd LeMaster 
| 03/01/14
| Controller Description:
|
*/
class GameController extends BaseController 
{

	private $games;
	private $connected;
	protected $layout = 'master';


	/**
     * instantiate games model for the controller
     */
    public function __construct()
    {
        $this->games = new Game;
        $this->connected = $this->games->api_auth();
    }


	/*
    * home index method - route: get(/) - shows all games 
    */
	public function getIndex()
	{	
		//is api is connected?
		if ($this->connected == false) {
			$this->layout->content = View::make('index', array( 'error' => 'Could not connect to the Games API'));
		} else {
			
			//get the games data from model			
			$games_data = $this->games->get_games();

			//check to see if user has already participated or is the weekend
			$games_data['acted'] = $this->checkCookie();
			$this->layout->content = View::make('index', array('games_data' => $games_data));
		}
	}


	/**
    * post processing method - route: post(/) - handles processing for add game form
    */
	public function addGame()
	{	
		$data = Input::all();
		
		if ($this->connected == false) {
			$this->layout->content = View::make('index', array( 'error' => 'Could not connect to the Games API'));
		} else {	
			
			//set a couple arrays for laravel's built in validation
			$rules = array(
	        	'game-title' => array('required', 'min:5')
	    	);
	    	$messages = array(
	    		'required' => 'Game Title is required',
	    		'min' => 'Game Title cannot be less than 5 characters',
			);

			//register laravel validation and add our arrays
	    	$validator = Validator::make($data, $rules, $messages);

	    	if ($validator->passes()) {
	    		$added = $this->games->add_game(Input::get('game-title'));
	    		if($added !== true) {
	    			return Redirect::to('/')->withErrors($added['error']);
	    		}

	    		//calculate expire minutes for cookie
	    		$till_midnight = $this->calculateMidnight();
	    		return Redirect::to('/')->withCookie(Cookie::make('games_cookie', true, $till_midnight));
	    	}

	    	//redirect back to form with error message
	    	Input::flash();
	    	return Redirect::to('/')->withErrors($validator);
	    }
	}



	/**
    * get processing method - route:get(vote/$game_id) - handles voting for a game 
    */
	public function voteForGame($game_id)
	{
		//is api is connected?
		if ($this->connected == false) {
			$this->layout->content = View::make('index', array( 'error' => 'Could not connect to the Games API'));
		} else {
			
			//call the vote_for_games model
			$voted = $this->games = $this->games->vote_for_game($game_id);
			if($voted == false) {
	    		return Redirect::to('/')->with(array('vote_error' => 'Game Title could not be voted on at this time'));
	    	} else {
	    		$till_midnight = $this->calculateMidnight();
	    		return Redirect::to('/')->withCookie(Cookie::make('games_cookie', true, $till_midnight));
	    	}
		}	
	}


	/**
    * get processing method - route:get(own/$game_id) - handles setting a game status to gotit 
    */
	public function ownGame($game_id)
	{
		//is api is connected?
		if ($this->connected == false) {
			$this->layout->content = View::make('admin', array( 'error' => 'Could not connect to the Games API'));
		} else {
			//call the own_game function of the model
			$owned = $this->games = $this->games->own_game($game_id);
			if($owned == false) {
	    		return Redirect::to('/games-admin')->with(array('own_error' => 'Game Title could not marked as owned at this time'));
	    	} else {
	    		return Redirect::to('/games-admin');
	    	}
		}	
	}


	/**
    * calculate expire minutes - internal function 
    */
	public function calculateMidnight()
	{
		//set CST for timezone
		date_default_timezone_set('America/Chicago');
		
		$midnight = strtotime('tomorrow 00:00:00');
		$current_time = strtotime('now');
		
		//find time till midnight convert to minutes
		$minutes = round(abs($midnight - $current_time) /60,2);
		
		return $minutes;
	}

	/**
    * checks cookie to see if user has already participated - internal function 
    */
	public function checkCookie()
	{
		//get cookie value
		$cookie = Cookie::get('games_cookie');
		
		//get today's day
		$today = date('D');
		
		//is today sat or sun
		if ($today == 'Sat' || $today == 'Sun') {
			$cookie = true;
		}

		//does cookie exist
		if ($cookie == null) {
			return false;	
		}
		
		return $cookie;
	}	

}