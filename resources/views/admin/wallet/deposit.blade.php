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
                                                <th>User Details</th>
                                                <th>TXN Id.</th>
                                                <th>Amount</th>
                                                <th>TXN Number</th>
                                                <th>Date & Time</th>
                                                <th>Remarks</th>
                                                <th>Manage</th>
                                            </tr>
                                        </thead>
                                        @if (!empty($data_list))
                                            <tbody>
                                                @foreach ($data_list as $key => $data)
                                                    <tr id="row_{{ $data['id'] }}">
                                                        <td>{{ $key + 1 }}</td>
                                                        <th>
                                                            Name: {{ $data_list[0]['user']['name'] }}<br>
                                                            Phone: {{ $data_list[0]['user']['phone'] }}
                                                            <br>
                                                            Current Wallet: ₹ {{ $data_list[0]['user']['wallet'] }}
                                                        </th>
                                                        <td>{{ $data['txn_id'] ?? 'N/A' }}</td>
                                                        <td>{{ $site_data->currency_symbol }} {{ $data['amount'] }}</td>
                                                        <td>
                                                            {{ $data['txn_number'] ?? 'N/A' }}
                                                            @if ($data['txn_method'] == '1')
                                                                (GPay)
                                                            @elseif($data['txn_method'] == '2')
                                                                (Paytm)
                                                            @elseif($data['txn_method'] == '3')
                                                                (PhonePe)
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ date('M j, Y - h:i:s a', strtotime($data['created_at'])) }}
                                                        </td>
                                                        <td>{{ $data['remarks'] ?? 'N/A' }}</td>
                                                        <td>
                                                            @if ($data['status'] == '2')
                                                                <button class="btn btn-icon btn-success btn-icon-style-1"
                                                                    data-toggle="tooltip" title="Approve"
                                                                    onclick="changeStatus({{ $data['id'] }}, 1)"><i
                                                                        class="btn-icon-wrap fa fa-check"></i>
                                                                </button>
                                                                <button class="btn btn-icon btn-danger btn-icon-style-1"
                                                                    data-toggle="tooltip" title="Reject"
                                                                    onclick="changeStatus({{ $data['id'] }}, `0`)"><i
                                                                        class="btn-icon-wrap fa fa-remove"></i>
                                                                </button>
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

    @endsection

    @push('page-scripts')
        <script>
            function changeStatus(id, status) {
                swal({
                        title: "Are you sure?",
                        text: "Do you want to run change status?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: false,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            const api_url = `{{ url('/') }}/admin/wallet/deposit-status/${id}/${status}`;
                            fetch(api_url).then(res => res.json())
                                .then(data => {
                                    console.log(data);
                                    if (data.status) {
                                        toastr.success(data.message);
                                        document.getElementById(`row_${id}`).remove();
                                    } else {
                                        toastr.error(data.message);
                                    }
                                });
                        }
                    });
            }
        </script>
    @endpush
