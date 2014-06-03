@section('left-content')
	@if(isset($error))
		<div class="alert alert-danger">
			<p>{{$error}}</p>
		</div>	
	@else
		<h2>Games We Want</h2>
		@if(isset($games_data['want']))
			@foreach ($games_data['want'] as $game)
    			<article class="game">
    				<h3>{{{ $game->title }}}</h3>
    				<p>Current Votes: {{$game->votes}}</p>
    				<a class="btn btn-info btn-xs" href="{{ URL::action('GameController@ownGame', $game->id) }}">Got It</a>
    			</article>
    		@endforeach
			@if(isset($vote_error))
				<div class="alert alert-danger">
					<p>{{$vote_error}}</p>
				</div>
			@endif
		@else
			<p>There are currently no games to vote on in the system.</p>
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
    	
    	<a class="btn btn-info btn-xs" href="{{ URL::action('AdminController@clearGames') }}">Clear All Games</a>
	
	@endif
@stop