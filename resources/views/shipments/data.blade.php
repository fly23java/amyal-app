<table class="table table-striped  zero-configuration2">
                    <thead>
                        <tr>
                            <th>{{ trans('shipments.serial_number') }}</th>
                            <th>{{ trans('shipments.user_id') }}</th>
                            <th>{{ trans('shipments.loading_city_id') }}</th>
                            <th>{{ trans('shipments.unloading_city_id') }}</th>
                            <th>{{ trans('shipments.vehicle_type_id') }}</th>
                         
                          
                            <th>{{ trans('shipments.carrir') }}</th>
                            <th>{{ trans('shipments.supervisor_user_id') }}</th>
                           

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
                            
                            <!-- <td class="align-middle badge badge-pill badge-light-warning  mt-1">{{ optional($shipment->Status)->name_arabic }}</td> -->
                            <td class="align-middle">
                            @if (!empty($shipment->shipmentDeliveryDetail->shipment_id))
                                {{ ($shipment->getCarrir($shipment->id)->name_arabic)  }}
                            @endif
                            </td>
                            <td class="align-middle">{{ optional($shipment->User)->name }}</td>
                            

                            <td class="text-end">

                                <form method="POST" action="{!! route('shipments.shipment.destroy', $shipment->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}
                                     <div class="btn-group btn-group-sm" role="group">
                                     
                                        <div class="dropdown">
                                                    <button type="button" class="btn btn-secondary  text-white dropdown-toggle hide-arrow" data-toggle="dropdown">
                                                         <i class="fa-solid fa-table-list"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                       
                                                         <a class="dropdown-item"  id="test_hima"  data-id="{{ $shipment->id  }}" data-toggle="modal" data-target="#modals-slide-in">
                                                            <i data-feather="plus" class="mr-50"></i>
                                                            <span>{{ trans('main.add_carrir') }}</span>
                                                        </a>
                                                         <a class="dropdown-item"   id="statuses_edit"  data-id="{{ $shipment->id  }}" data-toggle="modal" data-target="#modals-statuses-update">
                                                            <i data-feather="plus" class="mr-50"></i>
                                                            <span>{{ trans('statuses.edit') }}</span>
                                                        </a>
                                                         <a class="dropdown-item" href="{{ route('print_waybills.print_waybill.generateInvoice', $shipment->id ) }}">
                                                            <i data-feather="plus" class="mr-50"></i>
                                                            <span>{{ trans('main.testprint') }}</span>
                                                        </a>
                                                         <a class="dropdown-item"  id="shipment_delivery" data-id="{{ $shipment->id  }}" data-toggle="modal" data-target="#models-shipment-delivery" >
                                                            <i data-feather="plus" class="mr-50"></i>
                                                            <span>{{ trans('main.shipment_delivery') }}</span>
                                                        </a>
                                                       
                                                        
                                                         
                                                    </div>
                                        </div>

                                        <a class="btn btn-secondary" href="{{ route('shipments.shipment.show', $shipment->id ) }}">
                                        <i class="fa-solid fa-eye"></i>
                                          
                                        </a>
                                        <a class="btn btn-secondary" href="{{ route('shipments.shipment.edit', $shipment->id ) }}">
                                            <i class="fa-solid fa-edit"></i>
                                            
                                        </a>
                                    </div>
                                        

                                       
                                    </div>

                                </form>
                                
                            </td>
                           
                        </tr>
                      
                    @endforeach
                    </tbody>
                </table>