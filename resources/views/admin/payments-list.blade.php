@extends('admin/layout')
@section('content')

<div class="hk-pg-wrapper">
    <nav class="hk-breadcrumb" aria-label="breadcrumb" style="font-weight:bold;">
        <ol class="breadcrumb breadcrumb-light bg-transparent">
            <li class="breadcrumb-item">{{$page}}</li>
            <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
        </ol>
    </nav>
    <div class="container">
        <div class="hk-pg-header">
            <h4 class="hk-pg-title">
                <span class="pg-title-icon">
                    <span class="feather-icon">
                        <i data-feather="@if($page == 'Manage Invoices'){{'file-text'}}@else{{'credit-card'}}@endif"></i>
                    </span>
                </span>
                {{$title}}
            </h4>
        </div>

        @if($errors->any())
            @foreach($errors->all() as $error)
                <li class="flash-message">{{$error}}</li>
            @endforeach
            <br>
        @endif
        @if(session('message'))
            <li class="flash-message">{{session('message')}}</li>
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
                                            <th>Invoice ID</th>
                                            <th>Customer Info</th>
                                            <th>Invoice Type</th>
                                            <th>Total Amount</th>
                                            <th>Pay Amount</th>
                                            <th>Paid Amount</th>
                                            <th>Extra Paid</th>
                                            <th>Due Amount</th>
                                            <th>Due Date</th>
                                            <th>Late Payment IR (%)</th>
                                            <th>Last Payment On</th>
                                            <th>Payment Option</th>
                                            <th>Payment ID</th>
                                            <th>Transaction ID</th>
                                            <th>Payment Status</th>
                                            <th>Created On</th>
                                            <th>Manage</th>
                                        </tr>
                                    </thead>
                                    @if($data_list->isNotEmpty())
                                        <tbody>
                                            @foreach($data_list as $data)
                                                @php
                                                    $customer_data = $users->withTrashed()->where(['id' => $data['user_id'], 'type' => $data['user_type']])->first();
                                                    $customer_info = '<u>'.$customer_data->f_name.' '.$customer_data->l_name.'</u><br>Email ID: '.$customer_data->email.'<br>Phone No.: '.$customer_data->phone;
                                                @endphp
                                                <tr>
                                                    <td>{{$data['invoice_num']}}</td>
                                                    <td>{!!$customer_info!!}</td>
                                                    <td>@if($data['invoice_type'] == '0'){{'Down Payment Invoice'}}@elseif($data['invoice_type'] == '1'){{'Monthly Pay Invoice'}}@else{{'Custom Invoice'}}@endif</td>
                                                    <td>{{$settings_data->currency_symbol}} {{$data['total_amount']}}</td>
                                                    <td>{{$settings_data->currency_symbol}} {{$data['pay_amount']}}</td>
                                                    <td>{{$settings_data->currency_symbol}} {{$data['paid_amount']}}</td>
                                                    <td>{{$settings_data->currency_symbol}} {{$data['extra_amount']}}</td>
                                                    <td>{{$settings_data->currency_symbol}} {{$data['due_amount']}}</td>
                                                    <td>@if(!empty($data['due_date'])){{date('M j, Y', strtotime($data['due_date']))}}@else{{'N/A'}}@endif</td>
                                                    <td>@if(!empty($data['due_day_ir'])){{$data['due_day_ir'].'% Per Day'}}@else{{'N/A'}}@endif</td>
                                                    <td>@if(!empty($data['payment_time'])){{date('M j, Y - h:i:s a', strtotime($data['payment_time']))}}@else{{'N/A'}}@endif</td>
                                                    <td>@if(!empty($data['payment_option'])){{$data['payment_option']}}@else{{'N/A'}}@endif</td>
                                                    <td>@if(!empty($data['payment_id'])){{$data['payment_id']}}@else{{'N/A'}}@endif</td>
                                                    <td>@if(!empty($data['transaction_id'])){{$data['transaction_id']}}@else{{'N/A'}}@endif</td>
                                                    <td>
                                                        <select id="{{$data['invoice_num']}}" class="form-control select-status" onchange="item_status({{$data['invoice_num']}});">
                                                            <option value="2" style="font-weight:bold;" @if($data['status'] == '2'){{'selected'}}@endif>Pending</option>
                                                            <option value="1" style="font-weight:bold;" @if($data['status'] == '1'){{'selected'}}@endif>Completed</option>
                                                            <option value="0" style="font-weight:bold;" @if($data['status'] == '0'){{'selected'}}@endif>Cancelled</option>
                                                        </select>
                                                    </td>
                                                    <td>{{date('M j, Y - h:i:s a', strtotime($data['created_at']))}}</td>
                                                    <td>
                                                        <a href="{{url('/')}}/admin/update-invoice/{{$data['invoice_num']}}" class="btn btn-icon btn-primary btn-icon-style-1" data-toggle="tooltip" title="Update Invoice"><i class="btn-icon-wrap fa fa-edit"></i></a>
                                                        <button type="button" class="btn btn-icon btn-info btn-icon-style-1" data-toggle="tooltip" title="Update Payment" onclick="update_payment({{$data['invoice_num']}}, {{$data['pay_amount']}}, 0);"><i class="btn-icon-wrap fa fa-credit-card"></i></button>
                                                        <a href="{{url('/')}}/admin/payment-invoice/{{$data['invoice_num']}}" target="_blank" class="btn btn-icon btn-secondary btn-icon-style-1" data-toggle="tooltip" title="View Invoice"><i class="btn-icon-wrap fa fa-file-text"></i></a>
                                                        @if($page != 'Trash Can')
                                                            <button type="button" class="btn btn-icon btn-danger btn-icon-style-1" data-toggle="tooltip" title="Remove Payment" onclick="delete_item({{$data['invoice_num']}}, 1);"><i class="btn-icon-wrap fa fa-trash"></i></button>
                                                        @else
                                                            <button type="button" class="btn btn-icon btn-success btn-icon-style-1" data-toggle="tooltip" title="Restore Payment" onclick="restore_item({{$data['invoice_num']}});"><i class="btn-icon-wrap fa fa-undo"></i></button>
                                                            <button type="button" class="btn btn-icon btn-danger btn-icon-style-1" data-toggle="tooltip" title="Remove Payment" onclick="delete_item({{$data['invoice_num']}}, 0);"><i class="btn-icon-wrap fa fa-trash"></i></button>
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

    <div class="modal fade" id="payment-modal">
        <div class="modal-dialog" style="max-width: 400px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payment-title">Update Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="padding: 1.1rem;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="payment-form">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Paying Amount</label>
                            <input type="number" class="form-control" id="paying-amount" name="paying_amount" placeholder="Paying Amount ({{$settings_data->currency_word}})" min="0" step="any" value="" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" style="margin: 0;">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('page-scripts')
	<script>
        const modal = new bootstrap.Modal(document.getElementById(`payment-modal`));

        function update_payment(id, amount, type)
        {
            if(type === 0)
            {
                document.getElementById(`paying-amount`).value = amount;
                document.getElementById(`payment-title`).innerHTML = `Update Payment - ${id}`;
                document.getElementById(`payment-form`).setAttribute(`onsubmit`, `javascript: update_payment(${id}, ${amount}, 1);`);
                modal.show();
            }else{
                modal.hide();
                event.preventDefault();

                const api_url = `{{url('/')}}/admin/update-payment`;
                const form_data = {'invoice_num': id, 'paying_amount': event.target.elements.paying_amount.value};

                const options = {
					method: 'POST',
					headers: {'Content-Type': 'application/json'},
					body: JSON.stringify(form_data)
				};

                fetch(api_url, options).then(response =>
				{
					if((response.ok) && (response.status === 200))
					{
						return response.json();
					}else{
						return false;
					}
				}).then(data =>
				{
					if(data)
					{
                        if(data.status === 1)
                        {
                            swal(`Done!`, data.message, `success`).then(() =>
                            {
                                window.location.reload();
                            });
                        }else{
                            toastr.error(data.message);
                        }
                    }else{
						toastr.error('System Error!');
					}
				}).catch(() =>
				{
					toastr.error('Connection Error!');
				});
            }
        }

        function item_status(id)
        {
            const status = document.getElementById(id).value;
            const api_url = `{{url('/')}}/admin/payment-status/${id}/${status}`;

            fetch(api_url).then(response =>
            {
                if((response.ok) && (response.status === 200))
                {
                    return response.json();
                }else{
                    return false;
                }
            }).then(data =>
            {
                if(data)
                {
                    if(data.status === 1)
                    {
                        toastr.success(data.message);
                    }else{
                        toastr.error(data.message);
                    }
                }else{
                    toastr.error('System Error!');
                }
            }).catch(() =>
            {
                toastr.error('Connection Error!');
            });
        }

        function delete_item(id, type)
        {
            if(type === 1)
            {
                var message = `Confirm to move this invoice in trash? Invoice ID #${id}.`;
            }else{
                var message = `Confirm to remove this invoice permanently? Invoice ID #${id}.`;
            }

            swal({
                text: message,
                icon: "warning",
                buttons: true,
                dangerMode: false
            }).then((ok) =>
            {
                if(ok)
                {
                    window.location.replace(`{{url('/')}}/admin/delete-payment/${id}/${type}`);
                }
            });
        }

        function restore_item(id)
        {
            swal({
                text: `Confirm to restore back this invoice? Invoice ID #${id}.`,
                icon: "warning",
                buttons: true,
                dangerMode: false
            }).then((ok) =>
            {
                if(ok)
                {
                    window.location.replace(`{{url('/')}}/admin/restore-payment/${id}`);
                }
            });
        }
    </script>
@endpush

@endsection