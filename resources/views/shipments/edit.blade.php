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
    function fetchGoodsByVehicleType(selectedVehicleType) {
        return $.ajax({
            url: "{{ url('getGoodsByVehicleType')}}/" + selectedVehicleType,
            type: 'GET'
        });
    }

    function populateGoodsSelect(goodsData) {
        // Empty the current goods list
        $('#goods_id').empty();

        if (goodsData.length > 0) {
            // Iterate through the goods data and append options to the select element
            $.each(goodsData, function(index, good) {
                $('#goods_id').append($('<option>', {
                    value: good.id,
                    text: good.name_arabic
                }));
            });

           
            // Select the pre-existing value if it matches any option
                var preSelectedValue = $('#goods_id').data('pre-selected') || '';
                $('#goods_id option[value="' + preSelectedValue + '"]').prop('selected', true);

        } else {
            // No goods found message
            $('#goods_id').append($('<option>', {
                value: '',
                text: "{{ trans('shipments.goods_not_found') }}"
            }));
        }
    }

    // Listen for changes in the vehicle type
    $('#vehicle_type_id').on('change', function() {
        var selectedVehicleType = $(this).val();

        // Fetch goods data and handle it with a promise
        var goodsPromise = fetchGoodsByVehicleType(selectedVehicleType);

        $.when(goodsPromise)
            .done(function(data) {
                populateGoodsSelect(data.goods);
            })
            .fail(function(xhr, textStatus, errorThrown) {
                console.error("Error fetching goods data:", errorThrown);
            });
    });

    // Trigger change event for #vehicle_type_id if it has a pre-existing value
    var preSelectedVehicleType = $('#vehicle_type_id').val();
    if (preSelectedVehicleType) {
        $('#vehicle_type_id').trigger('change');
    }
});

</script>

@endsection