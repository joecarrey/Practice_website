@extends('layouts.admin_app')
@section('content')

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#request">Step 1: Requests</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#docs">Step 2: Documents</a>
        </li>
    </ul>

    <div id="myTabContent" class="tab-content">

        {{-- Request --}}
        <div class="tab-pane fade show active" id="request">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" style="display: inline;">Requests</h4>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="text-prim ary">
                                        <th scope="col">ID</th>
                                        <th scope="col">User</th>
                                        <th scope="col">Stations #</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">File 1</th>
                                        <th scope="col">File 2</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Comment</th>
                                        <th scope="col">Order</th>
                                    </thead>
                                    <tbody>
                                    @foreach($queries as $query)
                                        <tr>
                                            <th scope="row">{{$query->id}}</th>
                                            <td>{{$query->user->name}}</td>
                                            <td>{{$query->number_of_stations}}</td>
                                            <td><p style="height: 100px; overflow: auto;">{{$query->description}}</p></td>
                                            <td>{{substr($query->query_file_1, 0, -37)}}</td>
                                            <td>{{substr($query->query_file_2, 0, -37)}}</td>
                                            <td style="width: 250px;">    
                                                @switch($query->status)
                                                    @case(1)
                                                        <button class="btn btn-warning" style="width: 125px;" disabled>SENT</button>
                                                        @break
                                                    @case(2)
                                                        <button class="btn btn-primary" style="width: 125px;" disabled>RECEIVED</button>
                                                        @break
                                                    @case(3)
                                                        <button class="btn btn-success" style="width: 125px;" disabled>FINISHED</button>
                                                        @break
                                                    @case(-1)
                                                        <button class="btn btn-danger" style="width: 125px;" disabled>SENT TO PROCESS</button>
                                                        @break
                                                    @case(-2)
                                                        <button class="btn btn-secondary" style="width: 125px;" disabled>PROCESSING</button>
                                                        @break            
                                                    @default
                                                        @break
                                                @endswitch
                                                <form action="{{ url('/admin/query_status/' . $query->id) }}" method="post">
                                                    {{csrf_field()}}
                                                    <select name="status" class="form-control" style="width: 220px;" required>
					                                    <option value='' selected disabled>Choose Status to Change</option>
					                                    <option value="2">RECEIVED</option>
					                                    <option value="3">FINISHED</option>
					                                    <option value="-1">SENT TO PROCESS</option>
					                                </select>
                                                    <input type="hidden" name="_method" value="PATCH">
                                                    <button type="submit" class="btn btn-outline-primary" style="width: 125px;">Change</button>
                                                </form>
                                            </td>
                                            <td>
                                                @php
                                                    $comment = $query->query_comment->first();
                                                    if($comment){
                                                        foreach($query->query_comment as $com){
                                                            $last = $com->body ? $com->body : null;
                                                        }
                                                    }
                                                    else $last = null;    
                                                @endphp
                                                {{$last}}
                                            </td>
                                            <td>
                                            	@php
				                                    $order = $query->order->first();
				                                    if($order){
				                                        foreach($query->order as $ord){
				                                            $x_order = $ord->order_file ? $ord->order_file : null;
				                                        }
				                                    }
				                                    else $x_order = null;    
				                                @endphp
				                                {{substr($x_order, 0, -37)}}
                                            </td>
                                            <td>
                                            	@if ($comment)
                                            		<button type="button" class="btn btn-primary d-inline mb-2" data-toggle="modal" data-target="#editComModal_{{$query->id}}">Comment</button>
                                            	@else
                                            		<button type="button" class="btn btn-primary d-inline mb-2" data-toggle="modal" data-target="#addComModal_{{$query->id}}">Comment</button>
                                            	@endif
                                            </td>
                                            <td>
                                            	@if ($query->order->first())
                                            		<button type="button" class="btn btn-primary d-inline mb-2" data-toggle="modal" data-target="#editOrderModal_{{$query->id}}">Order</button>
                                            	@else
                                            		<button type="button" class="btn btn-primary d-inline mb-2" data-toggle="modal" data-target="#addOrderModal_{{$query->id}}">Order</button>
                                            	@endif
                                            </td>	
                                        </tr>
                                    @endforeach 
                                    </tbody>
                                </table>
                            </div>
                        </div>  
                    </div>
                </div>  
            </div>
            @foreach($queries as $query)
            	@if ($query->query_comment->first())
            		{{-- Edit Comment --}}
	                <div class="modal" id="editComModal_{{$query->id}}" tabindex="-1" role="dialog" aria-labelledby="editComModalLabel" aria-hidden="true">
	                    <div class="modal-dialog" role="document">
	                        <div class="modal-content">
	                            <div class="modal-header">
	                                <h5 class="modal-title" id="editComModalLabel"><h3>Edit Comment</h3></h5>
	                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                                <span aria-hidden="true">&times;</span>
	                                </button>
	                            </div>
	                            <div class="modal-body">
	                            	@php
                                        $comment = $query->query_comment->first();
                                        if($comment){
                                            foreach($query->query_comment as $com){
                                            	$com_id = $com->id ? $com->id : null;
                                                $last = $com->body ? $com->body : null;
                                            }
                                        }
                                        else $last = null;    
                                    @endphp
	                                <form action="{{url('/admin/comment_query/' . $com_id)}}" enctype="multipart/form-data" method="post">
	                                    <div class="form-group">
	                                    {{csrf_field()}}
	                                    	
	                                        <input class="form-control my-2" type="text" name="body" value="{{$last}}" required>
	                                        <input type="hidden" name="_method" value="PATCH">
	                                        <input type="submit" class="btn btn-warning mt-2" value="Edit">
	                                    </div>
	                                </form>
	                                <form onsubmit="return confirm('Are you sure to delete?');" action="{{ url('/admin/comment/' . $com_id . '/0') }}" method="post">
										{{csrf_field()}}
										<input type="hidden" name="_method" value="DELETE">
										<button type="submit" class="btn btn-danger">Delete</button>
									</form>
	                            </div>
	                            <div class="modal-footer">
	                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            @else
	            	{{-- Create Comment --}}
	                <div class="modal" id="addComModal_{{$query->id}}" tabindex="-1" role="dialog" aria-labelledby="addComModalLabel" aria-hidden="true">
	                    <div class="modal-dialog" role="document">
	                        <div class="modal-content">
	                            <div class="modal-header">
	                                <h5 class="modal-title" id="addComModalLabel"><h3>Add Comment</h3></h5>
	                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                                <span aria-hidden="true">&times;</span>
	                                </button>
	                            </div>
	                            <div class="modal-body">
	                                <form action="{{ url('/admin/comment_query/' . $query->id) }}" enctype="multipart/form-data" method="post">
	                                    <div class="form-group">
	                                    {{csrf_field()}}
	                                        <input class="form-control my-2" type="text" name="body" placeholder="Add Comment" required>
	                                        <input type="submit" class="btn btn-primary mt-2" value="Add">
	                                    </div>
	                                </form>
	                            </div>
	                            <div class="modal-footer">
	                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	                            </div>
	                        </div>
	                    </div>
	                </div>    
            	@endif
            	@if ($query->order->first())
	            	{{-- Edit Order --}}
	                <div class="modal" id="editOrderModal_{{$query->id}}" tabindex="-1" role="dialog" aria-labelledby="editOrderModalLabel" aria-hidden="true">
	                    <div class="modal-dialog" role="document">
	                        <div class="modal-content">
	                            <div class="modal-header">
	                                <h5 class="modal-title" id="editOrderModalLabel"><h3>Edit Order</h3></h5>
	                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                                <span aria-hidden="true">&times;</span>
	                                </button>
	                            </div>
	                            <div class="modal-body">
	                            	@php
	                                    $order = $query->order->first();
	                                    if($order){
	                                        foreach($query->order as $ord){
	                                            $x_order = $ord->order_file ? $ord->order_file : null;
	                                            $order_id = $ord->id ? $ord->id : null;
	                                        }
	                                    }
	                                    else $x_order = null;    
	                                @endphp
	                                <form action="{{url('/admin/order/' . $order_id)}}" enctype="multipart/form-data" method="post">
	                                    <div class="form-group">
	                                    {{csrf_field()}}
	                                        <label for="order_file_11_{{$query->id}}" class="mt-2">Order File: {{substr($x_order, 0, -37)}}</label>
	                                        <input id="doc_file_11_{{$query->id}}" class="form-control mb-2" type="file" name="order_file" required>
	                                        <input type="hidden" name="_method" value="PATCH">
	                                        <input type="submit" class="btn btn-warning mt-2" value="Edit">
	                                    </div>
	                                </form>
	                                <form onsubmit="return confirm('Are you sure to delete?');" action="{{ url('/admin/order/' . $order_id) }}" method="post">
										{{csrf_field()}}
										<input type="hidden" name="_method" value="DELETE">
										<button type="submit" class="btn btn-danger">Delete</button>
									</form>
	                            </div>
	                            <div class="modal-footer">
	                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            @else
	            	{{-- Create Order --}}
	                <div class="modal" id="addOrderModal_{{$query->id}}" tabindex="-1" role="dialog" aria-labelledby="addOrderModalLabel" aria-hidden="true">
	                    <div class="modal-dialog" role="document">
	                        <div class="modal-content">
	                            <div class="modal-header">
	                                <h5 class="modal-title" id="addOrderModalLabel"><h3>Add Order</h3></h5>
	                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                                <span aria-hidden="true">&times;</span>
	                                </button>
	                            </div>
	                            <div class="modal-body">
	                                <form action="{{url('/admin/order/' . $query->id)}}" enctype="multipart/form-data" method="post">
	                                    <div class="form-group">
	                                    {{csrf_field()}}
	                                        <label for="order_file_22_{{$query->id}}" class="mt-2">Order File</label>
	                                        <input id="doc_file_22_{{$query->id}}" class="form-control mb-2" type="file" name="order_file" required>
	                                        <input type="submit" class="btn btn-primary mt-2" value="Add">
	                                    </div>
	                                </form>
	                            </div>
	                            <div class="modal-footer">
	                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            @endif    
            @endforeach
        </div>

        {{-- STEP 2 DOCUMENTS --}}
        <div class="tab-pane fade" id="docs">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" style="display: inline;">STEP 2</h4>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="text-primary">
                                        <th scope="col">ID</th>
                                        <th scope="col">User</th>
                                        <th scope="col">Request</th>
                                        <th scope="col">File 1</th>
                                        <th scope="col">File 2</th>
                                        <th scope="col">File 3</th>
                                        <th scope="col">Length (km)</th>
                                        <th scope="col">Technology</th>
                                        <th scope="col">Object Type</th>
                                        <th scope="col">Region</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Comment</th>
                                    </thead>
                                    <tbody> 
                                    @foreach($documents as $doc)
                                        <tr>
                                            <th scope="row">{{$doc->id}}</th>
                                            <td>{{$doc->user->name}}</td>
                                            <td><p style="height: 100px; overflow: auto;">{{$doc->queryabc->description}}</p></td>
                                            <td>{{substr($doc->doc_file_1, 0, -37)}}</td>
                                            <td>{{substr($doc->doc_file_2, 0, -37)}}</td>
                                            <td>{{substr($doc->doc_file_3, 0, -37)}}</td>
                                            <td>{{$doc->length}}</td>
                                            <td>{{$doc->technology}}</td>
                                            <td>{{$doc->object_type}}</td>
                                            <td>{{$doc->region}}</td>
                                            <td style="width: 250px;">    
                                                @switch($doc->status)
                                                    @case(1)
                                                        <button class="btn btn-warning" style="width: 125px;" disabled>SENT</button>
                                                        @break
                                                    @case(2)
                                                        <button class="btn btn-primary" style="width: 125px;" disabled>RECEIVED</button>
                                                        @break
                                                    @case(3)
                                                        <button class="btn btn-success" style="width: 125px;" disabled>FINISHED</button>
                                                        @break
                                                    @case(-1)
                                                        <button class="btn btn-danger" style="width: 125px;" disabled>SENT TO PROCESS</button>
                                                        @break
                                                    @case(-2)
                                                        <button class="btn btn-secondary" style="width: 125px;" disabled>PROCESSING</button>
                                                        @break            
                                                    @default
                                                        @break
                                                @endswitch
                                                <form action="{{ url('/admin/doc_status/' . $doc->id) }}" method="post">
                                                    {{csrf_field()}}
                                                    <select name="status" class="form-control" style="width: 220px;" required>
					                                    <option value='' selected disabled>Choose Status to Change</option>
					                                    <option value="2">RECEIVED</option>
					                                    <option value="3">FINISHED</option>
					                                    <option value="-1">SENT TO PROCESS</option>
					                                </select>
                                                    <input type="hidden" name="_method" value="PATCH">
                                                    <button type="submit" class="btn btn-outline-primary" style="width: 125px;">Change</button>
                                                </form>
                                            </td>
                                            <td>
                                                @if ($doc->doc_comment)
                                                    {{$doc->doc_comment->body}}
                                                @else
                                                         
                                                @endif
                                            </td>
                                            <td>
                                            	@if ($doc->doc_comment)
                                            		<button type="button" class="btn btn-primary d-inline mb-2" data-toggle="modal" data-target="#editComDoc_{{$doc->id}}">Comment</button>
                                            	@else
                                            		<button type="button" class="btn btn-primary d-inline mb-2" data-toggle="modal" data-target="#addComDoc_{{$doc->id}}">Comment</button>
                                            	@endif
                                            </td>
                                        </tr>
                                    @endforeach 
                                    </tbody>
                                </table>
                            </div>
                        </div>  
                    </div>
                </div>  
            </div>
            @foreach($documents as $doc)
            	@if ($doc->doc_comment)
            		{{-- Edit Comment --}}
	                <div class="modal" id="editComDoc_{{$doc->id}}" tabindex="-1" role="dialog" aria-labelledby="editComDocLabel" aria-hidden="true">
	                    <div class="modal-dialog" role="document">
	                        <div class="modal-content">
	                            <div class="modal-header">
	                                <h5 class="modal-title" id="editComDocLabel"><h3>Edit Comment</h3></h5>
	                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                                <span aria-hidden="true">&times;</span>
	                                </button>
	                            </div>
	                            <div class="modal-body">
	                                <form action="{{url('/admin/comment_doc/' . $doc->doc_comment->id)}}" enctype="multipart/form-data" method="post">
	                                    <div class="form-group">
	                                    {{csrf_field()}}
	                                    	
	                                        <input class="form-control my-2" type="text" name="body" value="{{$doc->doc_comment->body}}" required>
	                                        <input type="hidden" name="_method" value="PATCH">
	                                        <input type="submit" class="btn btn-warning mt-2" value="Edit">
	                                    </div>
	                                </form>
	                                <form onsubmit="return confirm('Are you sure to delete?');" action="{{ url('/admin/comment/' . $doc->doc_comment->id . '/1') }}" method="post">
										{{csrf_field()}}
										<input type="hidden" name="_method" value="DELETE">
										<button type="submit" class="btn btn-danger">Delete</button>
									</form>
	                            </div>
	                            <div class="modal-footer">
	                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            @else
	            	{{-- Create Comment --}}
	                <div class="modal" id="addComDoc_{{$doc->id}}" tabindex="-1" role="dialog" aria-labelledby="addComDocLabel" aria-hidden="true">
	                    <div class="modal-dialog" role="document">
	                        <div class="modal-content">
	                            <div class="modal-header">
	                                <h5 class="modal-title" id="addComDocLabel"><h3>Add Comment</h3></h5>
	                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                                <span aria-hidden="true">&times;</span>
	                                </button>
	                            </div>
	                            <div class="modal-body">
	                                <form action="{{ url('/admin/comment_doc/' . $doc->id) }}" enctype="multipart/form-data" method="post">
	                                    <div class="form-group">
	                                    {{csrf_field()}}
	                                        <input class="form-control my-2" type="text" name="body" placeholder="Add Comment" required>
	                                        <input type="submit" class="btn btn-primary mt-2" value="Add">
	                                    </div>
	                                </form>
	                            </div>
	                            <div class="modal-footer">
	                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	                            </div>
	                        </div>
	                    </div>
	                </div>    
            	@endif
            @endforeach	   
        </div>

    </div>
@endsection