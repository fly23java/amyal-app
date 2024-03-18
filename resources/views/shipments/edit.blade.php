@extends('layouts.dashbord-layout')

@section('content')

    <div class="card text-bg-theme">
  
         <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">{{ trans('shipments.edit') }}</h4>
            <div>
                <a href="{{ route('shipments.shipment.index') }}" class="btn btn-primary" title="{{ trans('shipments.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('shipments.shipment.create') }}" class="btn btn-secondary" title="{{ trans('shipments.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="list-unstyled mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" class="needs-validation" novalidate action="{{ route('shipments.shipment.update', $shipment->id) }}" id="edit_shipment_form" name="edit_shipment_form" accept-charset="UTF-8" >
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            @include ('shipments.form', [
                                        'shipment' => $shipment,
                                      ])

                <div class="col-lg-10 col-xl-9 offset-lg-2 offset-xl-3">
                    <input class="btn btn-primary" type="submit" value="{{ trans('shipments.update') }}">
                </div>
            </form>

        </div>
    </div>

@endsection


@section('script')
<script>
   $(document).ready(function() {
    // Store initial values for vehicle_type_id and account_id on page load
    var initialVehicleType = $('#vehicle_type_id').val();
    var initialAccountId = $('#account_id').val();

    // Execute initial value check
    checkInitialValues(initialVehicleType, initialAccountId);

    // Listen for changes in the vehicle type and account_id
    $('#vehicle_type_id, #account_id').on('change', function() {
        var selectedVehicleType = $('#vehicle_type_id').val();
        var account_id = $('#account_id').val();

        // Execute initial value check
        checkInitialValues(selectedVehicleType, account_id);
    });

    // Function to check initial values
    function checkInitialValues(selectedVehicleType, account_id) {
        var initialVehicleType = $('#vehicle_type_id').data('initial-value');
        var initialAccountId = $('#account_id').data('initial-value');

        // Check for changes in vehicle_type_id or account_id values
        if (selectedVehicleType !== initialVehicleType || account_id !== initialAccountId) {
            // If values have changed, perform required action
            // console.log("Values have changed:");
            // console.log("New vehicle_type_id: " + selectedVehicleType);
            // console.log("New account_id: " + account_id);

            // Here you can call the function to fetch goods or any other necessary action
            fetchGoodsByVehicleType(selectedVehicleType, account_id)
                .done(function(data) {
                    populateGoodsSelect(data.goods);
                })
                .fail(function(xhr, textStatus, errorThrown) {
                    console.error("Error fetching goods data:", errorThrown);
                });
        } else {
            console.log("Values are still the same.");
        }
    }

    // Function to fetch goods by vehicle type and populate the select list
    function fetchGoodsByVehicleType(selectedVehicleType, account_id) {
        return $.ajax({
            url: "{{ route('getGoodsByVehicleType', ['selectedVehicleType' => ':selectedVehicleType']) }}".replace(':selectedVehicleType', selectedVehicleType),
            type: 'GET',
            data: { account_id: account_id },
        });
    }

    // Function to populate the goods select list
    function populateGoodsSelect(goodsData) {
        // Empty the current goods list
        $('#goods_id').empty();

        if (goodsData.length > 0) {
            $('#goods_id').append($('<option>{{ trans("contract_details.goods_id__placeholder") }}</option>'));
            // Iterate through the goods data and append options to the select element
            $.each(goodsData, function(index, good) {
                $('#goods_id').append($('<option>', {
                    value: good.id,
                    text: good.name_arabic
                }));
            });

            // Select the pre-existing value if it matches any option
            var preSelectedValue = $('#old_goods_id').val() || '';
            $('#goods_id option[value="' + preSelectedValue + '"]').prop('selected', true);
        } else {
            // No goods found message
            $('#goods_id').append($('<option>', {
                value: '',
                text: "{{ trans('shipments.goods_not_found') }}"
            }));
        }
    }

    // Trigger change event for #vehicle_type_id if it has a pre-existing value
    var preSelectedVehicleType = $('#vehicle_type_id').val();
    if (preSelectedVehicleType) {
        $('#vehicle_type_id').trigger('change');
    }
});


</script>

@endsection