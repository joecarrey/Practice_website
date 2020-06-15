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
        <div class="tab-pane fade show active" id="request">
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
                                            <td><a href="/file/{{$query->query_file_1}}/query_files" target="_blank">{{substr($query->query_file_1, 0, -37)}}</a></td>
                                            <td><a href="/file/{{$query->query_file_2}}/query_files" target="_blank">{{substr($query->query_file_2, 0, -37)}}</a></td>
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
                                        <label for="file_1_{{$query->id}}">File 1: <a href="/file/{{$query->query_file_1}}/query_files" target="_blank">{{substr($query->query_file_1, 0, -37)}}</a></label>
                                        <input id="file_1_{{$query->id}}" class="form-control" type="file" name="query_file_1" required>
                                        <label for="file_2_{{$query->id}}">File 2: <a href="/file/{{$query->query_file_2}}/query_files" target="_blank">{{substr($query->query_file_2, 0, -37)}}</a></label>
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
                                
                                <input class="form-control my-2" type="number" name="length" placeholder="Length (km)" required>

                                <select name="technology" class="form-control" required>
                                    <option value='' selected disabled>Choose Technology Type</option>
                                    <option value="4g">4g</option>
                                    <option value="3g">3g</option>
                                    <option value="2g">2g</option>
                                </select>

                                <select name="object_type" class="form-control" required>
                                    <option value='' selected disabled>Choose Object Type</option>
                                    <option value="Switch">Switch</option>
                                    <option value="Hub">Hub</option>
                                    <option value="MSAN">MSAN</option>
                                    <option value="ATS">ATS</option>
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
                                            <td><p style="height: 100px; overflow: auto;">{{$doc->queryabc->description}}</p></td>
                                            <td><a href="/file/{{$doc->doc_file_1}}/doc_files" target="_blank">{{substr($doc->doc_file_1, 0, -37)}}</a></td>
                                            <td><a href="/file/{{$doc->doc_file_2}}/doc_files" target="_blank">{{substr($doc->doc_file_2, 0, -37)}}</a></td>
                                            <td><a href="/file/{{$doc->doc_file_3}}/doc_files" target="_blank">{{substr($doc->doc_file_3, 0, -37)}}</a></td>
                                            <td>{{$doc->length}}</td>
                                            <td>{{$doc->technology}}</td>
                                            <td>{{$doc->object_type}}</td>
                                            <td>{{$doc->region}}</td>
                                            <td>    
                                                @switch($doc->status)
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
                                                        <form action="{{ url('/doc_status/' . $doc->id) }}" method="post">
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
                                                @if ($doc->doc_comment)
                                                    {{$doc->doc_comment->body}}
                                                @else
                                                         
                                                @endif
                                            </td>
                                            <td>
                                                @if (($doc->status != 2) and ($doc->status != 3))
                                                    <button type="button" class="btn btn-primary d-inline mb-2" data-toggle="modal" data-target="#editDocModal_{{$query->id}}">Edit</button>
                                                @else
                                                    <button type="button" class="btn btn-primary d-inline mb-2" disabled>Edit</button>
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
                {{-- Edit Modal --}}
                <div class="modal" id="editDocModal_{{$query->id}}" tabindex="-1" role="dialog" aria-labelledby="editDocModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editDocModalLabel"><h3>Edit</h3></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{url('/docs/' . $doc->id)}}" enctype="multipart/form-data" method="post">
                                    <div class="form-group">
                                    {{csrf_field()}}
                                        <p>Resubmit all your files please to change</p>
                                        <label for="doc_file_1_{{$doc->id}}" class="mt-2">File 1: <a href="/file/{{$doc->doc_file_1}}/doc_files" target="_blank">{{substr($doc->doc_file_1, 0, -37)}}</a></label>
                                        <input id="doc_file_1_{{$doc->id}}" class="form-control mb-2" type="file" name="doc_file_1" required>
                                        <label for="doc_file_2_{{$doc->id}}" class="mt-2">File 2: <a href="/file/{{$doc->doc_file_2}}/doc_files" target="_blank">{{substr($doc->doc_file_2, 0, -37)}}</a></label>
                                        <input id="doc_file_2_{{$doc->id}}" class="form-control mb-2" type="file" name="doc_file_2" required>
                                        <label for="doc_file_3_{{$doc->id}}" class="mt-2">File 3: <a href="/file/{{$doc->doc_file_3}}/doc_files" target="_blank">{{substr($doc->doc_file_3, 0, -37)}}</a></label>
                                        <input id="doc_file_3_{{$doc->id}}" class="form-control mb-2" type="file" name="doc_file_3" required>

                                        <label for="length_{{$doc->id}}" class="mt-2">Length</label>
                                        <input class="form-control mb-2" type="number" name="length" value="{{$doc->length}}" required>
                                        <label for="tech_{{$doc->id}}">Technology</label>
                                        
                                        <select name="technology" class="form-control" required>
                                            <option value="{{$doc->technology}}" selected>{{$doc->technology}}</option>
                                        <?php
                                            $tech_array = ['4g', '3g', '2g'];
                                            $key = array_search($doc->technology, $tech_array);
                                            unset($tech_array[$key]);
                                        ?>
                                            @foreach ($tech_array as $element)
                                                <option value="{{$element}}">{{$element}}</option>
                                            @endforeach
                                        </select>

                                        <label for="object_type_{{$doc->id}}">Object Type</label>
                                        <select name="object_type" class="form-control" required>
                                            <option value="{{$doc->object_type}}" selected>{{$doc->object_type}}</option>
                                        <?php
                                            $obj_array = ['Switch', 'Hub', 'MSAN', 'ATS'];
                                            $ind = array_search($doc->object_type, $obj_array);
                                            unset($obj_array[$ind]);
                                        ?>
                                            @foreach ($obj_array as $element)
                                                <option value="{{$element}}">{{$element}}</option>
                                            @endforeach
                                        </select>

                                        <label for="region_{{$doc->id}}">Region</label>
                                        <select name="region" class="form-control" required>
                                            <option value="{{$doc->region}}" selected>{{$doc->region}}</option>
                                        <?php
                                            $reg_array = ['Tashkent', 'Andijan', 'Namangan', 'Fergana', 'Fergana', 'Sirdarya', 'Jizzakh', 'Samarkand', 'Bukhara', 'Navai', 'Kashkadarya', 'Surkhandarya', 'Khorezm'];
                                            $index = array_search($doc->region, $reg_array);
                                            unset($reg_array[$index]);
                                        ?>
                                            @foreach ($reg_array as $element)
                                                <option value="{{$element}}">{{$element}}</option>
                                            @endforeach
                                        </select>

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
            @endforeach    
        </div>
    </div>
 
@endsection