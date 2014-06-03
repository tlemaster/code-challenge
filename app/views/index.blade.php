@section('left-content')
	@if(isset($error))
		<div class="alert alert-danger">
			<p>{{$error}}</p>
		</div>	
	@else
		<h2>Games We Want</h2>
		@if($games_data['acted'] == true)
			<p>Voting disabled! You have already participated or it's the weekend!</p>
		@endif
		@if(isset($games_data['want']))
			@foreach ($games_data['want'] as $game)
    			<article class="game">
    				<h3>{{{ $game->title }}}</h3>
    				<p>Current Votes: {{$game->votes}}</p>
    				@if($games_data['acted'] == false)
    					<a class="btn btn-info btn-xs" href="{{ URL::action('GameController@voteForGame', $game->id) }}">Vote for this Game</a>    					
    				@endif
    			</article>
    		@endforeach
			@if(isset($vote_error))
				<div class="alert alert-danger">
					<p>{{$vote_error}}</p>
				</div>
			@endif
		@else
			<p>There are currently no games to vote on in the system. Add one below!</p>
		@endif
	@endif
@stop
@section('right-content')
	@if(isset($error))
	
	@else
		<h2>Games We Own</h2>
		@if(isset($games_data['own']))
			@foreach ($games_data['own'] as $game)
				<article class="game">
					<h3>{{ $game->title }}</h3>
				</article>		
			@endforeach
			@if(isset($vote_error))
				<p>{{$vote_error}}</p>
			@endif
		@else
			<p>There are currently no games we own in the system.</p>
		@endif
    	
    	<h2>Add A New Game</h2>
		@if($games_data['acted'] == false)
			{{ Form::open(array('action' => 'GameController@addGame')) }}
				
				@foreach($errors->all('<p>:message</<p>') as $message)
	   				<div class="alert alert-warning">
	   					{{ $message }}
	   				</div>	
				@endforeach
	    	
				{{ Form::label('game-title', 'Game Title') . Form::text('game-title', Input::old('game-title')) }}
				{{ Form::submit('Submit Game!') }}
			{{ Form::close() }}
			
			@if(isset($add_error))
				<div class="alert alert-danger">
					<p>{{$add_error}}</p>
				</div>
			@endif
		@else
			Adding disabled! You have already participated or it's the weekend!
		@endif
	
	@endif
@stop