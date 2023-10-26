@extends('admin/layout')
@section('content')

<div class="hk-pg-wrapper">
    <div class="container mt-xl-50 mt-sm-30 mt-15">
        <div class="hk-pg-header align-items-top" style="@if(($errors->any()) || (session('message'))){{'margin-bottom: 0;'}}@endif">
            <div><h2 class="hk-pg-title font-weight-600">{{$title}}</h2></div>
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
                            <form action="" method="post">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>Principal Amount</label>
                                        <input type="number" class="form-control" name="principal_amount" placeholder="Principal Amount ({{$settings_data->currency_word}})" min="0" step="any" value="{{old('principal_amount', request()->input('principal_amount'))}}" required>
                                    </div>
                                    <div class="form-group col-md-3" style="margin-bottom: 0.5rem;">
                                        <label>Interest Rate</label>
                                        <input type="number" class="form-control" name="interest_rate" placeholder="Interest Rate (%)" min="0" step="any" value="{{old('interest_rate', request()->input('interest_rate'))}}" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label class="hidden-label">&nbsp;</label>
                                        <select name="tenure_ir_type" class="form-control" required>
                                            <option value="A" @if((old('tenure_ir_type') == 'A') || (request()->input('tenure_ir_type') == 'A')){{'selected'}}@endif>Annual Rate</option>
                                            <option value="M" @if((old('tenure_ir_type') == 'M') || (request()->input('tenure_ir_type') == 'M')){{'selected'}}@endif>Monthly Rate</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3" style="margin-bottom: 0.5rem;">
                                        <label>Payment Tenure</label>
                                        <input type="number" class="form-control" name="payment_tenure" placeholder="Payment Tenure (Yr./Mo.)" min="1" value="{{old('payment_tenure', request()->input('payment_tenure'))}}" required>
                                    </div>
                                    <div class="form-group col-md-1">
                                        <label class="hidden-label">&nbsp;</label>
                                        <select name="tenure_term_type" class="form-control" required>
                                            <option value="Y" @if((old('tenure_term_type') == 'Y') || (request()->input('tenure_term_type') == 'Y')){{'selected'}}@endif>Yr.</option>
                                            <option value="M" @if((old('tenure_term_type') == 'M') || (request()->input('tenure_term_type') == 'M')){{'selected'}}@endif>Mo.</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 m-b-25">
                            <div class="text-center box">
                                <h6>Monthly Pay Amount</h6>
                                <h3>{{$settings_data->currency_symbol}} @if(!empty($emi_data)){{number_format($emi_data['emi_amount'], 2)}}@else{{0}}@endif</h3>
                            </div>
                            <div class="text-center box">
                                <h6>Total Payable Interest</h6>
                                <h4>{{$settings_data->currency_symbol}} @if(!empty($emi_data)){{number_format($emi_data['interest_amount'], 2)}}@else{{0}}@endif</h4>
                            </div>
                            <div class="text-center box">
                                <h6>Total Payment Amount<br>(Principal + Interest)</h6>
                                <h4>{{$settings_data->currency_symbol}} @if(!empty($emi_data)){{number_format($emi_data['payment_amount'], 2)}}@else{{0}}@endif</h4>
                            </div>
                        </div>
                        <div class="col-md-6 m-b-25">
                            <div class="text-center">
                                <p class="text-black">Breakup of Total Payment</p>
                                <div class="pie-chart"><canvas id="pie-chart"></canvas></div>
                                <p class="text-black"><span class="principal"></span> Principal Amount - @if(!empty($emi_data)){{$emi_data['principal_percentage']}}@else{{0}}@endif%</p>
                                <p class="text-black"><span class="interest"></span> Total Interest - @if(!empty($emi_data)){{$emi_data['interest_percentage']}}@else{{0}}@endif%</p>
                            </div>
                        </div>
                    </div>
                    <hr style="margin-top: 0;">
                    <div class="container" style="padding: 0;">
                        <div class="col-md-12" style="padding: 0;">
                            <div class="panel panel-default">
                                <div class="panel-body table-responsive">
                                    <table class="table table-condensed table-striped text-center table-sm" style="margin-bottom: 0.5rem;">
                                        <tbody>
                                            <tr class="no-margin heding">
                                                <th>#</th>
                                                <th>Yr. / Mo.</th>
                                                <th>Principal<br>(A)</th>
                                                <th>Interest<br>(B)</th>
                                                <th>Total Amount<br>(A + B)</th>
                                                <th>Balance</th>
                                            </tr>
                                            @if(!empty($emi_data['emi_chart']))
                                                @foreach($emi_data['emi_chart'] as $year => $data)
                                                    <tr data-toggle="collapse" data-target="#Y-{{$year}}" class="accordion-toggle no-margin">
                                                        <td>
                                                            <button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-info-sign"></span></button>
                                                        </td>
                                                        <td>{{$year}}</td>
                                                        <td>{{$settings_data->currency_symbol}} {{$data['principal_amount']}}</td>
                                                        <td>{{$settings_data->currency_symbol}} {{$data['interest_amount']}}</td>
                                                        <td>{{$settings_data->currency_symbol}} {{$data['payment_amount']}}</td>
                                                        <td>{{$settings_data->currency_symbol}} {{$data['balance']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="12" class="hiddenRow">
                                                            <div class="accordian-body collapse table-responsive" id="Y-{{$year}}">
                                                                <table class="table table-striped mb-0">
                                                                    <tbody>
                                                                        @foreach($data['monthly_payments'] as $month => $value)
                                                                            <tr>
                                                                                <td>{{$value['month']}}</td>
                                                                                <td>{{$month}}</td>
                                                                                <td>{{$settings_data->currency_symbol}} {{$value['principal_amount']}}</td>
                                                                                <td>{{$settings_data->currency_symbol}} {{$value['interest_amount']}}</td>
                                                                                <td>{{$settings_data->currency_symbol}} {{$value['payment_amount']}}</td>
                                                                                <td>{{$settings_data->currency_symbol}} {{$value['balance']}}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

@push('page-scripts')
	<script>
        const canvas = document.getElementById("pie-chart");
        const pie_chart = canvas.getContext("2d");

        @if(empty($emi_data))
            const values = [50,50];
        @else
            const values = [{{$emi_data['principal_percentage']}},{{$emi_data['interest_percentage']}}];
        @endif

        const colors = ["#FFC154","#EC6B56"];

        let total = 0;
        let start_angle = 1.57;

        for(let index = 0; index < values.length; index++)
        {
            total += values[index];
        }

        for(let index = 0; index < values.length; index++)
        {
            let slice_angle = (2 * Math.PI * values[index] / total);

            pie_chart.fillStyle = colors[index];
            pie_chart.beginPath();
            pie_chart.moveTo(canvas.width / 2, canvas.height / 2);
            pie_chart.arc(canvas.width / 2, canvas.height / 2, canvas.height / 2, start_angle, start_angle + slice_angle);
            pie_chart.closePath();
            pie_chart.fill();

            start_angle += slice_angle;
        }
    </script>
@endpush

@endsection