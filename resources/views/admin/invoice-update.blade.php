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
                        <i data-feather="file-text"></i>
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
                            <form action="" method="post">
                                @csrf
                                @if(!empty($customer_data))
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>Customer Info</label>
                                            <input type="text" class="form-control" style="color: black;" value="{{$customer_data->f_name}} {{$customer_data->l_name}} - ({{$customer_data->email}})" readonly disabled>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>Select Customer <strong class="text-danger">*</strong></label>
                                            <select name="user_id" class="form-control" required>
                                                @if($customers_list->isNotEmpty())
                                                    @foreach($customers_list as $customer)
                                                        <option value="{{$customer['id']}}" @if(old('user_id') == $customer['id']){{'selected'}}@endif>{{$customer['f_name']}} {{$customer['l_name']}} - ({{$customer['email']}})</option>
                                                    @endforeach
                                                @else
                                                    <option selected disabled>No Customer Available</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                @if((empty($invoice_data)) || ($invoice_data->invoice_type == '2'))
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>Invoice Title</label>
                                            <input type="text" class="form-control" name="invoice_title" placeholder="Invoice Title (Optional)" value="@if(!empty($invoice_data)){{$invoice_data->invoice_title}}@else{{old('invoice_title')}}@endif">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>Item & Description</label>
                                            <textarea class="form-control" name="invoice_items" rows="5" placeholder="Item & Description (Optional)">@if(!empty($invoice_data)){{$invoice_data->invoice_items}}@else{{old('invoice_items')}}@endif</textarea>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Item Value/Price ({{$settings_data->currency_symbol}})</label>
                                            <input type="number" class="form-control" name="items_price" placeholder="Item Value/Price (Optional)" min="0" step="any" value="@if(!empty($invoice_data)){{$invoice_data->items_price}}@else{{old('items_price')}}@endif">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Item Time/Quantity</label>
                                            <input type="text" class="form-control" name="items_quantity" placeholder="Item Time/Quantity (Optional)" value="@if(!empty($invoice_data)){{$invoice_data->items_quantity}}@else{{old('items_quantity')}}@endif">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Due Date (Optional)</label>
                                            <input type="date" class="form-control" name="due_date" placeholder="Invoice Due Date (Optional)" value="@if(!empty($invoice_data)){{$invoice_data->due_date}}@else{{old('due_date')}}@endif">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Subtotal Amount <strong class="text-danger">*</strong></label>
                                            <input type="number" class="form-control" name="subtotal_amount" placeholder="Subtotal Amount ({{$settings_data->currency_word}})" min="0" step="any" value="@if(!empty($invoice_data)){{$invoice_data->subtotal_amount}}@else{{old('subtotal_amount', 0)}}@endif" required>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Charge Amount <strong class="text-danger">*</strong></label>
                                        <input type="number" class="form-control" name="charge_amount" placeholder="Charge Amount ({{$settings_data->currency_word}})" min="0" step="any" value="@if(!empty($invoice_data)){{$invoice_data->charge_amount}}@else{{old('charge_amount', 0)}}@endif" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Discount Amount <strong class="text-danger">*</strong></label>
                                        <input type="number" class="form-control" name="discount_amount" placeholder="Discount Amount ({{$settings_data->currency_word}})" min="0" step="any" value="@if(!empty($invoice_data)){{$invoice_data->discount_amount}}@else{{old('discount_amount', 0)}}@endif" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Notes For Customer</label>
                                        <textarea class="form-control" name="notes" rows="5" placeholder="Notes For Customer (Optional)">@if(!empty($invoice_data)){{$invoice_data->notes}}@else{{old('notes')}}@endif</textarea>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Remarks For Customer</label>
                                        <textarea class="form-control" name="remarks" rows="5" placeholder="Remarks For Customer (Optional)">@if(!empty($invoice_data)){{$invoice_data->remarks}}@else{{old('remarks')}}@endif</textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

@endsection