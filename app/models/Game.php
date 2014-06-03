<?php 

/*
|--------------------------------------------------------------------------
| Game Model
|--------------------------------------------------------------------------
| Nerdery Code Challenge
| Todd LeMaster 
| 03/01/14
| Model Description:
|
*/
class Game extends Eloquent {

	private $api_key = '269397e1aa014e0ea5107d14d982ed91';
	private $api_url = 'http://xbox.sierrabravo.net/v2/xbox.wsdl';
	private $gamesApi;

    public function __construct()
    {
    	$this->gamesApi = new SoapClient($this->api_url);	    
    }

    
    public function api_auth()
    {
        return $this->gamesApi->checkKey($this->api_key);	  	
    }

    
    public function get_games()
    {
        $sorted_games = $this->gamesApi->getGames($this->api_key);
        foreach ($sorted_games as $game) {
            if ($game->status == 'wantit') {
                $games_want[] = $game;
            } else {
                $games_own[] = $game;
            }
        }
       
       if (isset($games_want) and count($games_want) > 0) {
            usort( $games_want, function($a, $b) { return strcmp($b->votes, $a->votes); } );
            $sorted_games['want'] = $games_want;
       }

       if (isset($games_own) and count($games_own) > 0) {
            usort( $games_own, function($a, $b) { return strcmp($a->title, $b->title); } );
            $sorted_games['own'] = $games_own;
       }

       return $sorted_games;
    }

    
    /*
    * checks submitted game title against current game titles
    */
    public function check_for_dups($submitted_title) 
    {   
    	$game_titles = $this->gamesApi->getGames($this->api_key);
        foreach ($game_titles as $game) {
            if(strtolower($game->title) == strtolower($submitted_title)){
                return false;
            }
        }

        return true;
    }


    /*
    * adds submitted game title to the api
    */
    public function add_game($title)
    {
		$dup_validation = $this->check_for_dups($title);
        if($dup_validation == false) {
            return array('error' => 'Game Title already exists');
        }

        $created = $this->gamesApi->addGame($this->api_key, $title );
        if($created == false) {
            return array('error' => 'Game Title cant be added at this time');
        }

        return true;	  	
    }


    /*
    * votes for a game
    */
    public function vote_for_game($game_id)
    {
		return $this->gamesApi->addVote($this->api_key, $game_id );	  	
    }


    /*
    * changes status of a game to ownit
    */
    public function own_game($game_id)
    {
        return $this->gamesApi->setGotIt($this->api_key, $game_id);      
    }


    /*
    * changes status of a game to ownit
    */
    public function clear_games()
    {
        return $this->gamesApi->clearGames($this->api_key);      
    }
    



}	