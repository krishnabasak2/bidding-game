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
                                                <th>Manage</th>
                                            </tr>
                                        </thead>
                                        @if (!empty($data_list))
                                            <tbody>
                                                @foreach ($data_list as $key => $data)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $data->title}}</td>
                                                        <td>
                                                            <a href="{{ url('/') }}/admin/game/active/{{ $data['id'] }}"
                                                                class="btn btn-icon btn-primary btn-icon-style-1"
                                                                data-toggle="tooltip" title="View Sub-Games"><i
                                                                    class="btn-icon-wrap fa fa-play"></i></a>
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
                            text: "Once deleted, you will not be able to recover this imaginary file!",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                const api_url = `{{ url('/') }}/admin/game/distribute/${id}`;
                                fetch(api_url).then(res => res.json())
                                    .then(data => {
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
