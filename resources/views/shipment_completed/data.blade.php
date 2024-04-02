<table class="table table-striped zero-configuration3">
    <thead>
        <tr>
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
        <tr>
            <td class="align-middle">{{ $shipment->serial_number }}</td>
            <td class="align-middle">{{ $shipment->getAccountName($shipment->account_id)->name_arabic }}</td>
            <td class="align-middle">{{ $shipment->getCityName($shipment->loading_city_id)->name_arabic }}</td>
            <td class="align-middle">{{ $shipment->getCityName($shipment->unloading_city_id)->name_arabic }}</td>
            <td class="align-middle">{{ optional($shipment->VehicleType)->name_arabic }}</td>
            <td class="align-middle">
                @empty ($shipment->shipmentDeliveryDetail)
                    {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->right_letter) }}
                    {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->middle_letter) }}
                    {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->left_letter) }}
                    {{ ($shipment->getVehicle($shipment->shipmentDeliveryDetail->vehicle_id)->plate) }}
                @endempty
            </td>
            <td class="align-middle">{{ optional($shipment->User)->name }}</td>
            <td class="text-end">
                <div class="btn-group btn-group-sm" role="group">
                    <div class="dropdown">
                        <button type="button" class="btn btn-secondary text-white dropdown-toggle hide-arrow" data-toggle="dropdown">
                            <i class="feather icon-table-list"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" id="statuses_edit" data-id="{{ $shipment->id }}" data-toggle="modal" data-target="#modals-statuses-update">
                                <i class="feather icon-plus mr-50"></i>
                                <span>{{ trans('statuses.edit') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
