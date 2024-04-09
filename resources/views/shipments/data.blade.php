<!-- <form id="updateStatusForm" method="POST" action="{{ route('update_selected_fields.update_selected_field.updateSelectedFields') }}" class="d-flex justify-content-end align-items-center mb-3">
    @csrf
    <div class="me-3">
        <label for="statusSelect" class="form-label">Select Status:</label>
        <select class="form-control form-select" id="statusSelect" name="status">
            <option value="new_status">New Status</option>
            <option value="another_status">Another Status</option>
           
        </select>
    </div>
    <input type="hidden" id="selectedShipmentIds" name="shipment_ids[]">
    <button class="btn btn-primary btn-sm">Update Selected Fields</button>
</form> -->



<table class="table table-striped " id="shipmentTable">
    <thead>
        <tr>
             <th>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAllCheckbox" onclick="toggleAllCheckboxes()">
                    <label class="form-check-label" for="selectAllCheckbox">Select All</label>
                </div>
            </th>
            <th>{{ trans('shipments.serial_number') }}</th>
            <th>{{ trans('shipments.account') }}</th>
            <th>{{ trans('shipments.loading_city_id') }}</th>
            <th>{{ trans('shipments.unloading_city_id') }}</th>
            <th>{{ trans('shipments.vehicle_type_id') }}</th>
            <th>{{ trans('shipments.vehicle_id') }}</th>
            <th>{{ trans('shipments.supervisor_user_id') }}</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($shipments as $shipment)
        <tr data-toggle="collapse" data-target="#shipmentDetails{{ $shipment->id }}" class="clickable">
            <td>
                <div class="form-check">
                    <input class="form-check-input shipmentCheckbox" type="checkbox" id="shipmentCheckbox{{ $shipment->id }}"  value="{{ $shipment->id }}">
                </div>
            </td>
            <td class="align-middle">{{ $shipment->serial_number }}</td>
            <td class="align-middle">{{ $shipment->getAccountName($shipment->account_id)->name_arabic }}</td>
            <td class="align-middle">{{ $shipment->getCityName($shipment->loading_city_id)->name_arabic }}</td>
            <td class="align-middle">{{ $shipment->getCityName($shipment->unloading_city_id)->name_arabic }}</td>
            <td class="align-middle">{{ optional($shipment->VehicleType)->name_arabic }}</td>
            <td class="align-middle">
                @if (!empty($shipment->shipmentDeliveryDetail->shipment_id))
                    {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->right_letter) }}
                    {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->middle_letter) }}
                    {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->left_letter) }}
                    {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->plate) }}
                @endif
            </td>
            <td class="align-middle">{{ optional($shipment->User)->name }}</td>
            <td class="text-end">
                                <form method="POST" action="{!! route('shipments.shipment.destroy', $shipment->id) !!}" accept-charset="UTF-8">
                                    <input name="_method" value="DELETE" type="hidden">
                                    {{ csrf_field() }}
                                    <div class="btn-group btn-group-sm" role="group">
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-secondary text-white dropdown-toggle hide-arrow" data-toggle="dropdown">
                                                <i class="fa-solid fa-table-list"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" id="test_hima" data-id="{{ $shipment->id }}">
                                                    <i data-feather="plus" class="mr-50"></i>
                                                    <span>{{ trans('main.add_carrir') }}</span>
                                                </a>
                                                <a class="dropdown-item" id="statuses_edit" data-id="{{ $shipment->id }}" data-toggle="modal" data-target="#modals-statuses-update">
                                                    <i data-feather="plus" class="mr-50"></i>
                                                    <span>{{ trans('statuses.edit') }}</span>
                                                </a>
                                                <a class="dropdown-item" href="{{ route('print_waybills.print_waybill.generateInvoice', $shipment->id) }}">
                                                    <i data-feather="plus" class="mr-50"></i>
                                                    <span>{{ trans('main.testprint') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                        <a class="btn btn-secondary"  data-toggle="collapse" href="#shipmentDetails{{ $shipment->id }}" role="button" aria-expanded="false" aria-controls="shipmentDetails{{ $shipment->id }}"">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a class="btn btn-secondary" href="{{ route('shipments.shipment.edit', $shipment->id) }}">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <button type="submit" class="btn btn-danger" title="{{ trans('shipments.delete') }}" onclick="return confirm(&quot;{{ trans('goods.confirm_delete') }}&quot;)">
                                            <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </form>
                            </td>
        </tr>
        <tr id="shipmentDetails{{ $shipment->id }}" class="collapse">
            <td colspan="8">
                <div class="shipment-details">
                <div class="row">
    <div class="col-md-4">
        <ul>
            <li><strong>{{ trans('shipments.user_id') }}:</strong> {{ $shipment->getAccountName($shipment->account_id)->name_arabic }}</li>
            <li><strong>{{ trans('shipments.loading_city_id') }}:</strong> {{ $shipment->getCityName($shipment->loading_city_id)->name_arabic }}</li>
            <li><strong>{{ trans('shipments.unloading_city_id') }}:</strong> {{ $shipment->getCityName($shipment->unloading_city_id)->name_arabic }}</li>
            <li><strong>{{ trans('shipments.vehicle_type_id') }}:</strong> {{ optional($shipment->VehicleType)->name_arabic }}</li>
            <li><strong>{{ trans('shipments.goods_id') }}:</strong> {{ optional($shipment->Goods)->name_arabic }}</li>
            <li><strong>{{ trans('shipments.status_id') }}:</strong> {{ optional($shipment->Status)->name_arabic }}</li>
            <li><strong>{{ trans('shipments.price') }}:</strong> {{ $shipment->price }}</li>
        </ul>
    </div>
    <div class="col-md-4">
                <ul>
                    <li><strong>{{ trans('shipments.carrir') }}:</strong>
                        @if (!empty($shipment->shipmentDeliveryDetail->shipment_id))
                            {{ ($shipment->getCarrir($shipment->id)->name_arabic) }}
                        @endif
                    </li>
                    <li><strong>{{ trans('shipments.carrier_price') }}:</strong> {{ $shipment->carrier_price }}</li>
                   
                    <li><strong>{{ trans('shipments.carrir') }}:</strong>
                        @if (!empty($shipment->shipmentDeliveryDetail->shipment_id))
                            {{ ($shipment->getCarrir($shipment->id)->name_arabic) }}
                        @endif
                    </li>
                    <li><strong>{{ trans('shipments.vehicle_id') }}:</strong>
                        @if (!empty($shipment->shipmentDeliveryDetail->shipment_id))
                            {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->right_letter) }}
                            {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->middle_letter) }}
                            {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->left_letter) }}
                            {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->plate) }}
                        @endif
                    </li>
                    <li><strong>{{ trans('shipments.driver') }}:</strong>
                        @if (!empty($shipment->shipmentDeliveryDetail->shipment_id))
                            {{ optional($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->driver)->name_arabic }}
                        @endif
                    </li>
                    <li><strong>{{ trans('shipments.phone') }}:</strong>
                        @if (!empty($shipment->shipmentDeliveryDetail->shipment_id))
                            {{ optional($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->driver)->phone }}
                        @endif
                    </li>
                </ul>
            </div>
            <div class="col-md-4">
                <ul>
                    <li><strong>{{ trans('shipments.supervisor_user_id') }}:</strong> {{ optional($shipment->User)->name }}</li>
                    <li><strong>{{ trans('shipments.identity_number') }}:</strong>
                        @if (!empty($shipment->shipmentDeliveryDetail->shipment_id))
                            {{ optional($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->driver)->identity_number }}
                        @endif
                    </li>
                    <li><strong>{{ trans('shipments.created_at') }}:</strong> {{ $shipment->created_at }}</li>
                    <li><strong>{{ trans('shipments.updated_at') }}:</strong> {{ $shipment->updated_at }}</li>
                </ul>
            </div>
        </div>

                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


