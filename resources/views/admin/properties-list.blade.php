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
                        <i data-feather="home"></i>
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
                                            <th>ID</th>
                                            <th>Property Name</th>
                                            <th>Property Address</th>
                                            <th>Property Value</th>
                                            <th>Property Status</th>
                                            <th>Last Activated On</th>
                                            <th>Last Deactivated On</th>
                                            <th>Added On</th>
                                            <th>Manage</th>
                                        </tr>
                                    </thead>
                                    @if($data_list->isNotEmpty())
                                        <tbody>
                                            @foreach($data_list as $data)
                                                <tr>
                                                    <td>{{$data['id']}}</td>
                                                    <td>{{$data['name']}}</td>
                                                    <td>
                                                        @if(!empty($data['address']))
                                                            <div class="scrollable-area">{!!nl2br($data['address'])!!}</div>
                                                        @else
                                                            {{'N/A'}}
                                                        @endif
                                                    </td>
                                                    <td>{{$settings_data->currency_symbol}} {{$data['price']}}</td>
                                                    <td>
                                                        <select id="{{$data['id']}}" class="form-control select-status" onchange="item_status({{$data['id']}});">
                                                            <option value="1" style="font-weight:bold;" @if($data['status'] == '1'){{'selected'}}@endif>Active</option>
                                                            <option value="0" style="font-weight:bold;" @if($data['status'] == '0'){{'selected'}}@endif>Inactive</option>
                                                        </select>
                                                    </td>
                                                    <td id="enabled-id-{{$data['id']}}">
                                                        @if(!empty($data['enabled_on'])){{date('M j, Y - h:i:s a', strtotime($data['enabled_on']))}}@else{{'N/A'}}@endif
                                                    </td>
                                                    <td id="disabled-id-{{$data['id']}}">
                                                        @if(!empty($data['disabled_on'])){{date('M j, Y - h:i:s a', strtotime($data['disabled_on']))}}@else{{'N/A'}}@endif
                                                    </td>
                                                    <td>{{date('M j, Y - h:i:s a', strtotime($data['created_at']))}}</td>
                                                    <td>
                                                        <a href="{{url('/')}}/admin/edit-property/{{$data['id']}}" class="btn btn-icon btn-primary btn-icon-style-1" data-toggle="tooltip" title="Edit Property"><i class="btn-icon-wrap fa fa-edit"></i></a>
                                                        @if($page != 'Trash Can')
                                                            <button type="button" class="btn btn-icon btn-danger btn-icon-style-1" data-toggle="tooltip" title="Remove Property" onclick="delete_item({{$data['id']}}, 1);"><i class="btn-icon-wrap fa fa-trash"></i></button>
                                                        @else
                                                            <button type="button" class="btn btn-icon btn-success btn-icon-style-1" data-toggle="tooltip" title="Restore Property" onclick="restore_item({{$data['id']}});"><i class="btn-icon-wrap fa fa-undo"></i></button>
                                                            <button type="button" class="btn btn-icon btn-danger btn-icon-style-1" data-toggle="tooltip" title="Remove Property" onclick="delete_item({{$data['id']}}, 0);"><i class="btn-icon-wrap fa fa-trash"></i></button>
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
        function item_status(id)
        {
            const status = document.getElementById(id).value;
            const api_url = `{{url('/')}}/admin/property-status/${id}/${status}`;

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
                        document.getElementById(`enabled-id-${id}`).innerHTML = data.enabled_on;
                        document.getElementById(`disabled-id-${id}`).innerHTML = data.disabled_on;

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
                var message = `Confirm to move this property in trash? Property ID #${id}.`;
            }else{
                var message = `Confirm to remove this property permanently? Property ID #${id}.`;
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
                    window.location.replace(`{{url('/')}}/admin/delete-property/${id}/${type}`);
                }
            });
        }

        function restore_item(id)
        {
            swal({
                text: `Confirm to restore back this property? Property ID #${id}.`,
                icon: "warning",
                buttons: true,
                dangerMode: false
            }).then((ok) =>
            {
                if(ok)
                {
                    window.location.replace(`{{url('/')}}/admin/restore-property/${id}`);
                }
            });
        }
    </script>
@endpush

@endsection