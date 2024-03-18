@extends('layouts.dashbord-layout')

@section('content')

    <div class="card text-bg-theme">

         <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">{{ trans('shipments.create') }}</h4>
            <div>
                <a href="{{ route('shipments.shipment.index') }}" class="btn btn-primary" title="{{ trans('shipments.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
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

            <form method="POST" class="needs-validation" novalidate action="{{ route('shipments.shipment.store') }}" accept-charset="UTF-8" id="create_shipment_form" name="create_shipment_form" >
            {{ csrf_field() }}
            @include ('shipments.form', [
                                        'shipment' => null,
                                      ])

                <div class="col-lg-10 col-xl-9 offset-lg-2 offset-xl-3">
                    <input class="btn btn-primary" type="submit" value="{{ trans('shipments.add') }}">
                </div>

            </form>

        </div>
    </div>

@endsection



@section('script')
<script>
$(document).ready(function() {
    // تخزين القيم السابقة لكل عنصر
    var prevVehicleType = $('#vehicle_type_id').val();
    var prevAccountId = $('#account_id').val();

    // Listen for changes in the vehicle type
    $('#vehicle_type_id, #account_id').on('change', function() {
        var selectedVehicleType = $('#vehicle_type_id').val();
        var account_id = $('#account_id').val();

        // التحقق من تغيير في vehicle_type_id أو account_id
        if (selectedVehicleType !== prevVehicleType || account_id !== prevAccountId) {
            // تخزين القيم الجديدة كقيم سابقة للاستخدام في المرة القادمة
            prevVehicleType = selectedVehicleType;
            prevAccountId = account_id;

            // Empty the current goods list
            $('#goods_id').empty();
            if (selectedVehicleType && account_id) {
                $.ajax({
                    url: "{{ route('getGoodsByVehicleType', ['selectedVehicleType' => ':selectedVehicleType']) }}".replace(':selectedVehicleType', selectedVehicleType),
                    type: 'GET',
                    data: { account_id: account_id }, // Pass account_id as data
                    success: function(data) {
                        if (data && data.goods && data.goods.length > 0) {
                            $('#goods_id').append($('<option>{{ trans("contract_details.goods_id__placeholder") }}</option>'));
                            // Iterate through the goods data and append options to the select element
                            $.each(data.goods, function(index, good) {
                                $('#goods_id').append($('<option>', {
                                    value: good.id,
                                    text: good.name_arabic
                                }));
                            });
                        } else {
                            // No goods found message
                            $('#goods_id').append($('<option>', {
                                value: '',
                                text: "{{ trans('shipments.goods_not_found') }}"
                            }));
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.error("Error fetching goods data:", errorThrown);
                    }
                });
            }
        }
    });
});




</script>

@endsection

