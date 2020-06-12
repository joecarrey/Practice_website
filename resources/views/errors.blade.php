@extends('layouts.app')
@section('content')
	@if ($errors->any())
	 	<div class="card-body" style="background-color: lightgrey; border-radius: 30px;">
			<div class="card my-2">
				<div class="card-body">
	 				<h4 class="card-body bg-danger text-white">{{$errors->first()}}</h4>
	 			</div>
	 		</div>		
	 	</div>
	@endif
@endsection