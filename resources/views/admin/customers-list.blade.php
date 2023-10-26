@extends('admin/layout')
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
                        <i data-feather="users"></i>
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
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>Phone No.</th>
                                            <th>Email ID</th>
                                            <th>Customer Status</th>
                                            <th>Wallet Balance</th>
                                            <th>Joining Date & Time</th>
                                            <th>Manage</th>
                                        </tr>
                                    </thead>
                                    @if ($data_list->isNotEmpty())
                                    <tbody>
                                        @foreach ($data_list as $key => $data)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data['user_id'] }}</td>
                                            <td>{{ $data['name'] }}</td>
                                            <td>{{ $data['phone'] ?? 'N/A' }}</td>
                                            <td>{{ $data['email'] ?? 'N/A' }}</td>
                                            <td>
                                                <select id="{{ $data['id'] }}" class="form-control select-status"
                                                    onchange="item_status({{ $data['id'] }}, value);">
                                                    <option value="1" style="font-weight:bold;" @if($data['status']=='1'
                                                        ) {{ 'selected' }} @endif>
                                                        Active</option>
                                                    <option value="0" style="font-weight:bold;" @if($data['status']=='0'
                                                        ) {{ 'selected' }} @endif>
                                                        Suspend</option>
                                                </select>
                                            </td>
                                            <td>₹ {{ $data['wallet'] }}</td>
                                            <td>
                                                {{ date('M j, Y - h:i:s a', strtotime($data['created_at'])) }}
                                            </td>
                                            <td>
                                                <a href="{{ url('/') }}/admin/user/edit/{{ $data['id'] }}"
                                                    class="btn btn-icon btn-primary btn-icon-style-1"
                                                    data-toggle="tooltip" title="Edit Customer"><i
                                                        class="btn-icon-wrap fa fa-edit"></i></a>

                                                <a href="{{ url('/') }}/admin/user/settings/{{ $data['id'] }}"
                                                    class="btn btn-icon btn-info btn-icon-style-1"
                                                    data-toggle="tooltip" title="Settings"><i
                                                        class="btn-icon-wrap fa fa-gear"></i></a>

                                                <button type="button" class="btn btn-icon btn-warning btn-icon-style-1"
                                                    data-toggle="tooltip" title="Generate Password"
                                                    onclick="item_status({{ $data['id'] }}, '5');"><i
                                                        class="btn-icon-wrap fa fa-key"></i></button>

                                                @if ($title != 'Removed Users')
                                                <button type="button" class="btn btn-icon btn-danger btn-icon-style-1"
                                                    data-toggle="tooltip" title="Remove Customer"
                                                    onclick="item_status({{ $data['id'] }}, '2');"><i
                                                        class="btn-icon-wrap fa fa-trash"></i></button>
                                                @else
                                                <button type="button" class="btn btn-icon btn-success btn-icon-style-1"
                                                    data-toggle="tooltip" title="Restore Customer"
                                                    onclick="item_status({{ $data['id'] }}, '3');"><i
                                                        class="btn-icon-wrap fa fa-undo"></i></button>
                                                <button type="button" class="btn btn-icon btn-danger btn-icon-style-1"
                                                    data-toggle="tooltip" title="Delete Customer"
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
        function item_status(id, status) {

                    if (status == '2') {
                        var textSms = 'Do you want to remove this user?';
                        var btn = true;
                    } else if (status == '3') {
                        var textSms = 'Do you want to restore this user?';
                        var btn = false;
                    } else if (status == '4') {
                        var textSms = 'Do you want to delete this user permanently?';
                        var btn = true;
                    } else if (status == '5') {
                        var textSms = 'Do you want to generate new password for this user?';
                        var btn = true;
                    } else {
                        var textSms = 'Do you want to change status for this user?';
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
                            const api_url = `{{ url('/') }}/admin/user/status/${id}/${status}`;
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