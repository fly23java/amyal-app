@extends('layouts.dashbord-layout')

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {!! session('success_message') !!}

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card text-bg-theme">

        <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">{{ trans('main.shipments') }}</h4>
            <div>
                <a href="{{ route('shipments.shipment.create') }}" class="btn btn-secondary" title="{{ trans('shipments.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        
        @if(count($shipments) == 0)
            <div class="card-body text-center">
                <h4>{{ trans('shipments.none_available') }}</h4>
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped  zero-configuration">
                    <thead>
                        <tr>
                            <th>{{ trans('shipments.serial_number') }}</th>
                            <th>{{ trans('shipments.user_id') }}</th>
                            <th>{{ trans('shipments.loading_city_id') }}</th>
                            <th>{{ trans('shipments.unloading_city_id') }}</th>
                            <th>{{ trans('shipments.vehicle_type_id') }}</th>
                         
                            <th>{{ trans('shipments.status_id') }}</th>
                            <th>{{ trans('shipments.price') }}</th>
                           

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($shipments as $shipment)
                        <tr>
                            <td class="align-middle">{{ $shipment->serial_number }}</td>
                            <td class="align-middle">{{ $shipment->getAUserName($shipment->user_id)->name }}</td>
                            <td class="align-middle">{{ $shipment->getCityName($shipment->loading_city_id)->name_arabic }}</td>
                            <td class="align-middle">{{  $shipment->getCityName($shipment->unloading_city_id)->name_arabic  }}</td>
                            <td class="align-middle">{{ optional($shipment->VehicleType)->name_arabic }}</td>
                            
                            <td class="align-middle badge badge-pill badge-light-warning  mt-1">{{ optional($shipment->Status)->name_arabic }}</td>
                            <td class="align-middle">{{ $shipment->price }}</td>
                            

                            <td class="text-end">

                                <form method="POST" action="{!! route('shipments.shipment.destroy', $shipment->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                <div class="dropdown">
                                                    <button type="button" class="btn btn-secondary  text-white dropdown-toggle hide-arrow" data-toggle="dropdown">
                                                         <i class="fa-solid fa-table-list"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ route('shipments.shipment.show', $shipment->id ) }}">
                                                            <i data-feather="edit-2" class="mr-50"></i>
                                                            <span>{{ trans('shipments.show') }}</span>
                                                        </a>
                                                        <a class="dropdown-item" href="{{ route('shipments.shipment.edit', $shipment->id ) }}">
                                                            <i data-feather="trash" class="mr-50"></i>
                                                            <span>{{ trans('shipments.edit') }}</span>
                                                        </a>
                                                         <a class="dropdown-item"  id="test_hima"  data-id="{{ $shipment->id  }}" data-toggle="modal" data-target="#modals-slide-in">
                                                            <i data-feather="plus" class="mr-50"></i>
                                                            <span>{{ trans('main.add_carrir') }}</span>
                                                        </a>
                                                        
                                                         
                                                    </div>
                                                </div>

                                       
                                    </div>

                                </form>
                                
                            </td>
                           
                        </tr>
                      
                    @endforeach
                    </tbody>
                </table>

            </div>

        
        </div>
        
        @endif


                        <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                            <div class="modal-dialog">
                                <form class="add-new-user modal-content pt-0"  method="get" action="{{ route('shipments.shipment.shipmentDetails') }}" id="create_shipment_details_form">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                    <div class="modal-header mb-1">
                                        <h5 class="modal-title" id="exampleModalLabel">
                                             {{ trans('shipments.edit') }}
                                        </h5>
                                    </div>
                                    <div class="modal-body flex-grow-1">
                                    <input  name="shipment_delivery_detail_id" type="hidden" id="shipment_delivery_detail_id" >
                                    <input  name="supervisor_user_id" type="hidden"  value=" {{ Auth::user()->id }} " >
                                    <input  name="shipment_id" type="hidden" id="shipment_id" >
                                        <div class="form-group">
                                            <label class="form-label" for="vehicle_id">{{ trans('shipment_delivery_details.vehicle_id') }}</label>
                                            <select class="form-select form-control{{ $errors->has('vehicle_id') ? ' is-invalid' : '' }}" id="vehicle_id" name="vehicle_id" required="true" placeholder="">
                                                  
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-body flex-grow-1">
                                        <div class="form-group">
                                            <label class="form-label" for="basic-icon-default-fullname">{{ trans('shipments.carrier_price') }}</label>
                                            <input class="form-control{{ $errors->has('loading_time') ? ' is-invalid' : '' }}" name="carrier_price" type="number" id="carrier_price"  placeholder="{{ trans('shipments.carrier_price__placeholder') }}"  value="@if ($shipment->carrier_price ){{$shipment->carrier_price }} @endif" >
                                        </div>
                                    </div>
                                   
                                   
                                    <div class="modal-body flex-grow-1" >
                                        <button type="submit" class="btn btn-primary mr-1 data-submit">Submit</button>
                                        <button type="reset" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
    
    </div>




@endsection