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
                    <div class="row">
                        <div class="col-sm">
                            <div class="table-wrap table-format">
                                <table id="data-table-1" class="table table-hover table-responsive display">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>#</th>
                                            <th>Game Title</th>
                                            <th>Game Time</th>
                                            <th>Date</th>
                                            <th>Bid Amount</th>
                                            <th>Won Amount</th>
                                            <th>Single Win Value</th>
                                            <th>Patti Win Value</th>
                                            <th>Jodi Win Value</th>
                                            <th>Manage</th>
                                        </tr>
                                    </thead>
                                    @if (!empty($data_list))
                                    <tbody>
                                        @foreach ($data_list as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data['getTime']['title'] }}</td>
                                            <td>{{ date('h:i a', strtotime($data['getTime']['start_time'])) }}
                                                -
                                                {{ date('h:i a', strtotime($data['getTime']['stop_time'])) }}
                                            </td>
                                            <td>
                                                {{ date('d-m-Y', strtotime($data['created_at'])) }}
                                            </td>
                                            <td>
                                                {{$data->bid->sum('bid_amount')}}
                                            </td>
                                            <td>
                                                {{$data->bid->sum('won_amount')}}
                                            </td>
                                            <td>{{ isset($data['single_win_value']) ? $data['single_win_value'] : 'N/A'
                                                }}
                                            </td>
                                            <td>{{ isset($data['patti_win_value']) ? $data['patti_win_value'] : 'N/A' }}
                                            </td>
                                            <td>{{ isset($data['jodi_win_value']) ? $data['jodi_win_value'] : 'N/A' }}
                                            </td>
                                            <td>
                                                <a href="{{ url('/') }}/admin/game/result/{{ $data['id'] }}"
                                                    class="btn btn-icon btn-primary btn-icon-style-1"
                                                    data-toggle="tooltip" title="Result"><i
                                                        class="btn-icon-wrap fa fa-trophy"></i></a>

                                                @if ($data['status'] == '0' && ($data['patti_win_value'] ||
                                                $data['single_win_value']))
                                                <button id="button_{{ $data['id'] }}"
                                                    class="btn btn-icon btn-warning btn-icon-style-1"
                                                    data-toggle="tooltip" title="Distribute"
                                                    onclick="distribute({{ $data['id'] }})"><i
                                                        class="btn-icon-wrap fa fa-share"></i>
                                                </button>
                                                @endif
                                                <a href="{{ url('/') }}/admin/game/history/bid/{{ $data['id'] }}"
                                                    class="btn btn-icon btn-info btn-icon-style-1"
                                                    data-toggle="tooltip" title="Bid List"><i
                                                        class="btn-icon-wrap fa fa-list"></i></a>
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
        function item_status(id, status) {

                    if (status == '2') {
                        var textSms = 'Do you want to remove this game?';
                        var btn = true;
                    } else if (status == '3') {
                        var textSms = 'Do you want to restore this game?';
                        var btn = false;
                    } else if (status == '4') {
                        var textSms = 'Do you want to delete this game permanently?';
                        var btn = true;
                    } else {
                        var textSms = 'Do you want to change status for this game?';
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
                            const api_url = `{{ url('/') }}/admin/game/status/${id}/${status}`;
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

                function distribute(id) {
                    swal({
                            title: "Are you sure?",
                            text: "Do you want to run distribution process?",
                            icon: "warning",
                            buttons: true,
                            dangerMode: false,
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                const api_url = `{{ url('/') }}/admin/game/distribute/${id}`;
                                fetch(api_url).then(res => res.json())
                                    .then(data => {
                                        console.log(data);
                                        if (data.status) {
                                            toastr.success(data.message);
                                            document.getElementById(`button_${id}`).style.display = 'none';
                                        } else {
                                            toastr.error(data.message);
                                        }
                                    });
                            }
                        });
                }
    </script>
    @endpush
    @endsection