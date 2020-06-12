@extends('layouts.app')
@section('content')

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#request">Step 1: Request</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#docs">Step 2: Documents</a>
        </li>
    </ul>

    <div id="myTabContent" class="tab-content">

        {{-- Request --}}
        <div class="tab-pane fade show active" id="users">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" style="display: inline;">Request</h4>
                            <button type="button" class="btn btn-primary d-inline ml-3" data-toggle="modal" data-target="#createModal">Send</button>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="text-primary">
                                        <th scope="col">ID</th>
                                        <th scope="col">Stations #</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">File 1</th>
                                        <th scope="col">File 2</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Comment</th>
                                    </thead>
                                    <tbody> 
                                    @foreach($queries as $query)
                                        <tr>
                                            <th scope="row">{{$query->id}}</th>
                                            <td>{{$query->number_of_stations}}</td>
                                            <td><p style="height: 100px; overflow: auto;">{{$query->description}}</p></td>
                                            <td>{{$query->query_file_1}}</td>
                                            <td>{{$query->query_file_2}}</td>
                                            <td>    
                                                @switch($query->status)
                                                    @case(1)
                                                        <button type="submit" class="btn btn-success" style="width: 125px;" disabled>SENT</button>
                                                        @break
                                                    @case(2)
                                                        <button type="submit" class="btn btn-success" style="width: 125px;" disabled>RECEIVED</button>
                                                        @break
                                                    @case(3)
                                                        <button type="submit" class="btn btn-success" style="width: 125px;" disabled>FINISHED</button>
                                                        @break
                                                    @case(-1)
                                                        <button class="btn btn-warning" style="width: 125px;" disabled>SENT TO PROCESS</button>
                                                        <form action="{{ url('/query_status/' . $query->id) }}" method="post">
                                                            {{csrf_field()}}
                                                            <input type="hidden" name="_method" value="PATCH">
                                                            <input type="hidden" name="status" value='-2'>
                                                            <button type="submit" class="btn btn-secondary my-2" style="width: 125px;">Start PROCESS</button>
                                                        </form>
                                                        @break
                                                    @case(-2)
                                                        <button class="btn btn-secondary" style="width: 125px;" disabled>PROCESSING</button>
                                                        @break            
                                                    @default
                                                        @break
                                                @endswitch
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
                                                @if (($query->status != 2) and ($query->status != 3))
                                                    <button type="button" class="btn btn-primary d-inline mb-2" data-toggle="modal" data-target="#queryModal_{{$query->id}}">Edit</button>
                                                @else
                                                    <button type="button" class="btn btn-primary d-inline mb-2" disabled>Edit</button>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($query->status == 3)
                                                    <button type="button" class="btn btn-success d-inline mb-2" data-toggle="modal" data-target="#docModal_{{$query->id}}" style="width: 80px;">Step 2</button>
                                                @else
                                                    <button type="button" class="btn btn-secondary d-inline mb-2" disabled style="width: 80px;">Step 2</button>
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
                {{-- Edit Modal --}}
                <div class="modal" id="queryModal_{{$query->id}}" tabindex="-1" role="dialog" aria-labelledby="queryModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="queryModalLabel"><h3>Edit</h3></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{url('/request/' . $query->id)}}" enctype="multipart/form-data" method="post">
                                    <div class="form-group">
                                    {{csrf_field()}}
                                        <input class="form-control my-2" type="number" name="number_of_stations" value="{{$query->number_of_stations}}" required>
                                        <input class="form-control my-2" type="text" name="description" value="{{$query->description}}" required>
                                        <hr><hr>
                                        <p>Resubmit all your files please to change</p>
                                        <label for="file_1_{{$query->id}}">File 1: {{$query->query_file_1}}</label>
                                        <input id="file_1_{{$query->id}}" class="form-control" type="file" name="query_file_1" required>
                                        <label for="file_2_{{$query->id}}">File 2: {{$query->query_file_2}}</label>
                                        <input id="file_2_{{$query->id}}" class="form-control" type="file" name="query_file_2" required>

                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="submit" class="btn btn-warning mt-2" value="Edit">
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="modal" id="docModal_{{$query->id}}" tabindex="-1" role="dialog" aria-labelledby="docModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="docModalLabel"><h3>Step 2</h3></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form action="{{url('/docs')}}" enctype="multipart/form-data" method="post">
                            <div class="form-group">
                            {{csrf_field()}}
                                <label for="file_11">File 1</label>
                                <input id="file_11" class="form-control my-2" type="file" name="doc_file_1" required>
                                <label for="file_22">File 2</label>
                                <input id="file_22" class="form-control my-2" type="file" name="doc_file_2" required>
                                <label for="file_33">File 3</label>
                                <input id="file_33" class="form-control my-2" type="file" name="doc_file_3" required>

                                <hr><hr>
                                
                                <input class="form-control my-2" type="number" name="length" placeholder="Length" required>

                                <select name="technology" class="form-control" required>
                                    <option value='' selected disabled>Choose Technology Type</option>
                                    <option value="4g">4g</option>
                                    <option value="3g">3g</option>
                                    <option value="2g">2g</option>
                                </select>

                                <select name="object_type" class="form-control" required>
                                    <option value='' selected disabled>Choose Object Type</option>
                                    <option value="Switch">Switch</option>
                                    <option value="Switch">Hub</option>
                                    <option value="Switch">MSAN</option>
                                    <option value="Switch">ATS</option>
                                </select>

                                <select name="region" class="form-control" required>
                                    <option value='' selected disabled>Choose Region</option>
                                    <option value="Tashkent">Tashkent</option>
                                    <option value="Andijan">Andijan</option>
                                    <option value="Namangan">Namangan</option>
                                    <option value="Fergana">Fergana</option>
                                    <option value="Sirdarya">Sirdarya</option>
                                    <option value="Jizzakh">Fergana</option>
                                    <option value="Samarkand">Samarkand</option>
                                    <option value="Bukhara">Bukhara</option>
                                    <option value="Navai">Navai</option>
                                    <option value="Kashkadarya">Kashkadarya</option>
                                    <option value="Surkhandarya">Surkhandarya</option>
                                    <option value="Khorezm">Khorezm</option>
                                </select>
                                <input type="hidden" name="query_id" value="{{$query->id}}">
                                <input type="submit" class="btn btn-primary mt-2" value="Send">
                            </div>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
            @endforeach
            {{-- CREATE MODAL --}}
            <div class="modal" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel"><h3>Send Request</h3></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form action="{{url('/request')}}" enctype="multipart/form-data" method="post">
                        <div class="form-group">
                        {{csrf_field()}}
                            <input class="form-control my-2" type="number" name="number_of_stations" placeholder="Number of stations" required>
                            <input class="form-control my-2" type="text" name="description" placeholder="Description" required>
                            
                            <label for="file_1">File 1</label>
                            <input id="file_1" class="form-control my-2" type="file" name="query_file_1" required>
                            <label for="file_2">File 2</label>
                            <input id="file_2" class="form-control my-2" type="file" name="query_file_2" required>

                            <input type="submit" class="btn btn-primary mt-2" value="Send">
                        </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
        </div>


    </div>
 
@endsection