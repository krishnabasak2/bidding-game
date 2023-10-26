@extends('admin.layout')
@section('content')
<div class="hk-pg-wrapper">
    <nav class="hk-breadcrumb" aria-label="breadcrumb" style="font-weight:bold;">
        <ol class="breadcrumb breadcrumb-light bg-transparent">
            <li class="breadcrumb-item">{{ $page }}</li>
            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
        </ol>
    </nav>
    <div class="container">
        <div class="hk-pg-header">
            <h4 class="hk-pg-title">
                <span class="pg-title-icon">
                    <span class="feather-icon">
                        <i data-feather="box"></i>
                    </span>
                </span>
                {{ $title }}
            </h4>
            {{-- <div>
                <button class="btn btn-primary mt-3 mx-2">Create</button>
                <button class="btn btn-primary mt-3 mx-2">List</button>
            </div> --}}
        </div>

        @if ($errors->any())
        @foreach ($errors->all() as $error)
        <li class="flash-message">{{ $error }}</li>
        @endforeach
        <br>
        @endif
        @if (session('message'))
        <li class="flash-message">{{ session('message') }}</li>
        <br>
        @endif

        <div class="row">
            <div class="col-xl-12">
                <section class="hk-sec-wrapper">
                    {{-- <h4 class="pb-3">{{ $heading }}</h4> --}}
                    <div class="row">
                        <div class="col-sm">
                            <form action="" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Sub-game Title<strong class="text-danger">*</strong></label>
                                            <input type="text" placeholder="Subgame Title" class="form-control"
                                                name="title"
                                                value="{{ old('title', $time_data ? $time_data->title : '') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Game Days<strong class="text-danger">*</strong></label>
                                            <br>

                                            <a class="form-control dropdown-toggle col-md-12"
                                                data-toggle="dropdown">Select Days</a>

                                            @php
                                            if ($time_data) {
                                            $game_days = json_decode($time_data->game_days, true);
                                            } else {
                                            $game_days = [];
                                            }
                                            @endphp
                                            <ul class="dropdown-menu dropdown_day p-3">
                                                <li class="p-2"><a href="#" class="small" data-value="option1"
                                                        tabIndex="-1"><input type="checkbox" name="game_days[]"
                                                            value="0" {{ in_array('0', $game_days) ? 'checked' : ''
                                                            }} />Sunday</a>
                                                </li>
                                                <li class="p-2"><a href="#" class="small" data-value="option2"
                                                        tabIndex="-1"><input type="checkbox" name="game_days[]"
                                                            value="1" {{ in_array('1', $game_days) ? 'checked' : ''
                                                            }} />Monday</a>
                                                </li>
                                                <li class="p-2"><a href="#" class="small" data-value="option3"
                                                        tabIndex="-1"><input type="checkbox" name="game_days[]"
                                                            value="2" {{ in_array('2', $game_days) ? 'checked' : ''
                                                            }} />Tuesday</a>
                                                </li>
                                                <li class="p-2"><a href="#" class="small" data-value="option4"
                                                        tabIndex="-1"><input type="checkbox" name="game_days[]"
                                                            value="3" {{ in_array('3', $game_days) ? 'checked' : ''
                                                            }} />Wednesday</a>
                                                </li>
                                                <li class="p-2"><a href="#" class="small" data-value="option5"
                                                        tabIndex="-1"><input type="checkbox" name="game_days[]"
                                                            value="4" {{ in_array('4', $game_days) ? 'checked' : ''
                                                            }} />Thursday</a>
                                                </li>
                                                <li class="p-2"><a href="#" class="small" data-value="option6"
                                                        tabIndex="-1"><input type="checkbox" name="game_days[]"
                                                            value="5" {{ in_array('5', $game_days) ? 'checked' : ''
                                                            }} />Friday</a>
                                                </li>
                                                <li class="p-2"><a href="#" class="small" data-value="option6"
                                                        tabIndex="-1"><input type="checkbox" name="game_days[]"
                                                            value="6" {{ in_array('6', $game_days) ? 'checked' : ''
                                                            }} />Saturday</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Start Time<strong class="text-danger">*</strong></label>
                                            <input type="time" class="form-control" name="start_time"
                                                value="{{ old('title', $time_data ? $time_data->start_time : '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Stop Time<strong class="text-danger">*</strong></label>
                                            <input type="time" class="form-control" name="stop_time"
                                                value="{{ old('title', $time_data ? $time_data->stop_time : '') }}">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <section class="hk-sec-wrapper">
                    <div class="row">
                        <div class="col-sm">
                            <div class="table-wrap table-format">
                                <table id="data-table-1" class="table table-hover table-responsive display">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Game Days</th>
                                            <th>Start Time</th>
                                            <th>Stop Time</th>
                                            <th>Adding Date & Time</th>
                                            <th>Status</th>
                                            <th>Manage</th>
                                        </tr>
                                    </thead>
                                    @if ($data_list->isNotEmpty())
                                    <tbody>
                                        @foreach ($data_list as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data['title'] }}</td>
                                            <td>
                                                @php
                                                $days = '';
                                                $game_days = json_decode($data['game_days'], true);

                                                foreach ($game_days as $key => $value) {
                                                if ($value == '0') {
                                                $days = $days . ' Sunday, ';
                                                } elseif ($value == '1') {
                                                $days = $days . ' Monday, ';
                                                } elseif ($value == '2') {
                                                $days = $days . ' Tuesday, ';
                                                } elseif ($value == '3') {
                                                $days = $days . ' Wednesday, ';
                                                } elseif ($value == '4') {
                                                $days = $days . ' Thursday, ';
                                                } elseif ($value == '5') {
                                                $days = $days . ' Friday, ';
                                                } elseif ($value == '6') {
                                                $days = $days . ' Saturday, ';
                                                } else {
                                                $days = $days . ' ...';
                                                }
                                                }
                                                @endphp
                                                {{ $days }}
                                            </td>
                                            <td>{{ date('h:i a', strtotime($data['start_time'])) }}
                                            </td>
                                            <td>{{ date('h:i a', strtotime($data['stop_time'])) }}</td>
                                            <td>{{ date('M j, Y - h:i:s a', strtotime($data['created_at'])) }}
                                            </td>
                                            <td>
                                                <select id="{{ $data['id'] }}" class="form-control select-status"
                                                    onchange="item_status({{ $data['id'] }}, value);">
                                                    <option value="1" style="font-weight:bold;" @if($data['status']=='1'
                                                        ) {{ 'selected' }} @endif>
                                                        Active</option>
                                                    <option value="0" style="font-weight:bold;" @if($data['status']=='0'
                                                        ) {{ 'selected' }} @endif>
                                                        Inactive</option>
                                                </select>
                                            </td>
                                            <td>
                                                <a href="{{ url('/') }}/admin/game/time/{{ $data['game_id'] }}/{{ $data['id'] }}"
                                                    class="btn btn-icon btn-primary btn-icon-style-1"
                                                    data-toggle="tooltip" title="Edit Sub-Game"><i
                                                        class="btn-icon-wrap fa fa-edit"></i></a>

                                                @if (!$data['deleted_at'])
                                                <button type="button" class="btn btn-icon btn-danger btn-icon-style-1"
                                                    data-toggle="tooltip" title="Remove Sub-Game"
                                                    onclick="item_status({{ $data['id'] }}, '2');"><i
                                                        class="btn-icon-wrap fa fa-trash"></i></button>
                                                @else
                                                <button type="button" class="btn btn-icon btn-success btn-icon-style-1"
                                                    data-toggle="tooltip" title="Restore Sub-Game"
                                                    onclick="item_status({{ $data['id'] }}, '3');"><i
                                                        class="btn-icon-wrap fa fa-undo"></i></button>
                                                <button type="button" class="btn btn-icon btn-danger btn-icon-style-1"
                                                    data-toggle="tooltip" title="Delete Sub-Game"
                                                    onclick="item_status({{ $data['id'] }}, '4');"><i
                                                        class="btn-icon-wrap fa fa-trash"></i></button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    @push('page-scripts')
    <script>
        var options = [];

                $('.dropdown_day a').on('click', function(event) {

                    var $target = $(event.currentTarget),
                        val = $target.attr('data-value'),
                        $inp = $target.find('input'),
                        idx;

                    if ((idx = options.indexOf(val)) > -1) {
                        options.splice(idx, 1);
                        setTimeout(function() {
                            $inp.prop('checked', false)
                        }, 0);
                    } else {
                        options.push(val);
                        setTimeout(function() {
                            $inp.prop('checked', true)
                        }, 0);
                    }

                    $(event.target).blur();

                    // console.log(options);
                    return false;
                });
    </script>

    <script>
        function item_status(id, status) {

                    if (status == '2') {
                        var textSms = 'Do you want to remove this sub-game?';
                        var btn = true;
                    } else if (status == '3') {
                        var textSms = 'Do you want to restore this sub-game?';
                        var btn = false;
                    } else if (status == '4') {
                        var textSms = 'Do you want to delete this sub-game permanently?';
                        var btn = true;
                    } else {
                        var textSms = 'Do you want to change status for this sub-game?';
                        var btn = false;
                    }

                    swal({
                        title: "Are you sure?",
                        text: textSms,
                        icon: "warning",
                        buttons: true,
                        dangerMode: btn,
                    }).then((willDelete) => {
                        if (willDelete) {
                            const api_url = `{{ url('/') }}/admin/game/time/status/${id}/${status}`;
                            fetch(api_url).then(response => {
                                if ((response.ok) && (response.status === 200)) {
                                    return response.json();
                                } else {
                                    return false;
                                }
                            }).then(data => {
                                if (data) {
                                    if (data.status === true) {
                                        swal("Done", data.message, "success").then(() => {
                                            if (data.reload) {
                                                window.location.reload();
                                            }
                                        });
                                    } else {
                                        toastr.error(data.message);
                                    }
                                } else {
                                    toastr.error('System Error!');
                                }
                            }).catch(() => {
                                toastr.error('Connection Error!');
                            });
                        }
                    });
                }
    </script>
    @endpush
    @endsection