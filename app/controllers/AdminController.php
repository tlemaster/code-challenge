<?php

/*
|--------------------------------------------------------------------------
|Admin Controller
|--------------------------------------------------------------------------
| Nerdery Code Challenge
| Todd LeMaster 
| 03/01/14
| Controller Description:
|
*/
class AdminController extends BaseController {

	private $games;
	private $connected;
	protected $layout = 'master';

	/**
     * instantiate games model
     */
    public function __construct()
    {
        $this->games = new Game;
        $this->connected = $this->games->api_auth();
    }


	/**
    * game admin index method - route:get(games-admin) - shows all games
    */
	public function getIndex()
	{
		//is api is connected?
		if ($this->connected == false) {
			$this->layout->content = View::make('admin', array( 'error' => 'Could not connect to the Games API'));
		} else {

			//receive games_data from model			
			$games_data = $this->games->get_games();
			$this->layout->content = View::make('admin', array('games_data' => $games_data));
		}
		
	}

	/**
    * game clear method - route:get(games-admin/clear) - clears all games
    */
	public function clearGames()
	{
		//is api is connected?
		if ($this->connected == false) {
			
			//handle not connected
			$this->layout->content = View::make('admin', array( 'error' => 'Could not connect to the Games API'));
		} else {

			//call clear games of model			
			$cleared = $this->games->clear_games();
			return Redirect::to('/games-admin');
		}
		
	}


}