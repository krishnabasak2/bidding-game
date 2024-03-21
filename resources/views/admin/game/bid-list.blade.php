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
                                                <th>User Name</th>
                                                <th>Gamge Type</th>
                                                <th>Bid Number</th>
                                                <th>Bid Amount</th>
                                                <th>Win/Loss</th>
                                                <th>Date & Time</th>
                                                <th>Manage</th>
                                            </tr>
                                        </thead>
                                        @if (!empty($data_list))
                                            <tbody>
                                                @foreach ($data_list as $key => $item)
                                                    <tr id="button_{{ $item->id }}">
                                                        <td>{{ $key++ }}</td>
                                                        <td>{{ $item->getUser->name }}</td>
                                                        <td>
                                                            @if ($item->game_type == '1')
                                                                Single
                                                            @elseif ($item->game_type == '2')
                                                                Jodi
                                                            @elseif ($item->game_type == '3')
                                                                Patti
                                                            @endif
                                                        </td>
                                                        <td>{{ $item->bid_number }}</td>
                                                        <td>{{ $site_data->currency_symbol }} {{ $item->bid_amount }}

                                                            @if ($site_data->currency_value > 1)
                                                                <small>
                                                                    (₹
                                                                    {{ round($item->bid_amount / $site_data->currency_value, 2) }})
                                                                </small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($item->status == '1')
                                                                Win ({{ $site_data->currency_symbol }}
                                                                {{ $item->won_amount }})

                                                                @if ($site_data->currency_value > 1)
                                                                    <small>
                                                                        (₹
                                                                        {{ round($item->won_amount / $site_data->currency_value, 2) }})
                                                                    </small>
                                                                @endif
                                                            @elseif ($item->status == '2')
                                                                Pending
                                                            @elseif ($item->status == '0')
                                                                Loss
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ date('M j, Y - h:i:s a', strtotime($item['created_at'])) }}
                                                        </td>
                                                        <td>
                                                            {{-- @php
                                                                $current_time = date('H:i');
                                                            @endphp

                                                            @if ($item->time->stop_time > $current_time && $item->result->status == '0') --}}
                                                            @if ($item->result->status == '0')
                                                                <button class="btn btn-icon btn-danger btn-icon-style-1"
                                                                    data-toggle="tooltip" title="Delete Bid"><i
                                                                        class="btn-icon-wrap fa fa-trash"
                                                                        onclick="bidDelete({{ $item->id }})"></i></button>
                                                            @else
                                                                N/A
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
                function bidDelete(id) {
                    swal({
                            title: "Are you sure?",
                            text: "Do you want to delete this bid?",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                const api_url = `{{ url('/') }}/admin/game/history/bid-delete/${id}`;
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
