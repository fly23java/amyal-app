@extends('layouts.dashbord-layout')

@section('content')

    <div class="card text-bg-theme">

         <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">{{ trans('prices.create') }}</h4>
            <div>
                <a href="{{ route('prices.price.index') }}" class="btn btn-primary" title="{{ trans('prices.show_all') }}">
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

            <form method="POST" class="needs-validation" novalidate action="{{ route('prices.price.store') }}" accept-charset="UTF-8" id="create_price_form" name="create_price_form" >
            {{ csrf_field() }}
            @include ('prices.form', [
                                        'price' => null,
                                      ])

               <!-- price Details Fields (Dynamic) -->

               <div class="mb-3 row">
                <label for="receiver_id" class="col-form-label text-lg-end col-lg-2 col-xl-3">{{ trans('main.price_details') }}</label>
                <div class="col-lg-10 col-xl-9">
                
              
            <!-- price Details Fields (Dynamic) -->
            <div id="price-details-container">
         <!-- price Details Fields (Loop through details) -->
                @forelse(old('price_details', []) as $index => $detail)
                    <div class="price-detail row mb-3">
                        <div class="col-md-2">
                            <label for="price_details[{{ $index }}][vehicle_type_id]" class="form-label">Vehicle Type:</label>
                            <select name="price_details[{{ $index }}][vehicle_type_id]" class="form-control vehicle-type">
                                @foreach($vehicleTypes as $vehicleType)
                                    <option value="{{ $vehicleType->id }}" {{ old("price_details.{$index}.vehicle_type_id") == $vehicleType->id ? 'selected' : '' }}>{{ $vehicleType->name_arabic }}</option>
                                @endforeach
                            </select>
                            @error("price_details.{$index}.vehicle_type_id")
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label for="price_details[{{ $index }}][goods_id]" class="form-label">Goods:</label>
                            <select name="price_details[{{ $index }}][goods_id]" class="form-control goods">
                                <!-- Populate goods dynamically using AJAX -->
                            </select>
                            @error("price_details.{$index}.goods_id")
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label for="price_details[{{ $index }}][loading_city_id]" class="form-label">Loading City:</label>
                            <select name="price_details[{{ $index }}][loading_city_id]" class="form-control loading-city">
                                <!-- Populate loading cities dynamically using AJAX -->
                            </select>
                            @error("price_details.{$index}.loading_city_id")
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label for="price_details[{{ $index }}][dispersal_city_id]" class="form-label">Dispersal City:</label>
                            <select name="price_details[{{ $index }}][dispersal_city_id]" class="form-control dispersal-city">
                                <!-- Populate dispersal cities dynamically using AJAX -->
                            </select>
                            @error("price_details.{$index}.dispersal_city_id")
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label for="price_details[{{ $index }}][price]" class="form-label">Price:</label>
                            <input type="number" name="price_details[{{ $index }}][price]" class="form-control price" value="{{ old("price_details.{$index}.price") }}">
                            @error("price_details.{$index}.price")
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-detail">Remove</button>
                        </div>
                    </div>
                @empty
                    <!-- If no old price details, show at least one empty price detail -->
                    <div class="price-detail row mb-3">
                        <div class="col-md-2">
                            <label for="price_details[0][vehicle_type_id]" class="form-label">Vehicle Type:</label>
                            <select name="price_details[0][vehicle_type_id]" class="form-control vehicle-type">
                                @foreach($vehicleTypes as $vehicleType)
                                    <option value="{{ $vehicleType->id }}">{{ $vehicleType->name_arabic }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="price_details[0][goods_id]" class="form-label">Goods:</label>
                            <select name="price_details[0][goods_id]" class="form-control goods">
                                <!-- Populate goods dynamically using AJAX -->
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="price_details[0][loading_city_id]" class="form-label">Loading City:</label>
                            <select name="price_details[0][loading_city_id]" class="form-control loading-city">
                                <!-- Populate loading cities dynamically using AJAX -->
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="price_details[0][dispersal_city_id]" class="form-label">Dispersal City:</label>
                            <select name="price_details[0][dispersal_city_id]" class="form-control dispersal-city">
                                <!-- Populate dispersal cities dynamically using AJAX -->
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="price_details[0][price]" class="form-label">Price:</label>
                            <input type="number" name="price_details[0][price]" class="form-control price" value="{{ old('price_details.0.price') }}">
                        </div>

                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-detail">Remove</button>
                        </div>
                    </div>
                @endforelse
                
                
            </div>
            <button type="button" class="btn btn-success" id="add-detail">Add price Detail</button>


                <div class="col-lg-10 col-xl-9 offset-lg-2 offset-xl-3">
                    <input class="btn btn-primary" type="submit" value="{{ trans('prices.add') }}">
                </div>

            </form>

        </div>
    </div>

@endsection

@section('script')
<script>
    $(document).ready(function () {
        let detailCount = 1;

        // Fetch and populate vehicle types on page load for the first row
        fetchVehicleTypes($('#price-details-container .price-detail:first'));

        $('#add-detail').on('click', function () {
            if (validatepriceDetails()) {
                // Continue with adding a new detail
                const newDetail = createNewDetail();
                $('#price-details-container').append(newDetail);
                detailCount++;

                // Fetch and populate vehicle types for the new detail
                fetchVehicleTypes(newDetail);
            }
        });

        $('#price-details-container').on('click', '.remove-detail', function () {
            $(this).closest('.price-detail').remove();
        });

        // Handle change event on vehicle type dropdown
        $('#price-details-container').on('change', '.vehicle-type , #receiver_id', function () {
            // التحقق مما إذا كانت القيمة غير فارغة
            if ($(this).val() !== "") {
                const currentDetail = $(this).closest('.price-detail');
                const goodsDropdown = currentDetail.find('.goods');
                const selectedVehicleTypeId = $(this).val();
                const receiverId = $('#receiver_id').val();

                // Fetch and populate goods based on the selected vehicle type
                fetchGoods(selectedVehicleTypeId, goodsDropdown, currentDetail, receiverId);
            }
        });


        function createNewDetail() {
            const newDetail = $('#price-details-container .price-detail:first').clone();
            const uniqueId = Date.now(); // Generate a unique identifier
            newDetail.attr('id', 'price-detail-' + uniqueId);

            newDetail.find('input, select').each(function () {
                const name = $(this).attr('name').replace('[0]', '[' + detailCount + ']');
                $(this).attr('name', name);
                $(this).val(''); // Clear values for the new detail
            });

            // Remove any previous Select2 classes and initialize it
            newDetail.find('.select2').removeClass('select2-hidden-accessible').next().remove();
            newDetail.find('.select2').select2();

            return newDetail;
        }

        function fetchVehicleTypes(targetRow) {
            // Fetch vehicle types and populate the dropdown for the target row
            $.ajax({
                url: "{{ route('prices.price.vehicleTypes') }}",
                method: 'GET',
                success: function (response) {
                    const vehicleTypeDropdown = targetRow.find('.vehicle-type');
                    vehicleTypeDropdown.html('<option value="">Select value</option>'); // Add a default option
                    $.each(response.vehicleTypes, function (index, vehicleType) {
                        vehicleTypeDropdown.append($('<option>', {
                            value: vehicleType.id,
                            text: vehicleType.name_arabic
                        }));
                    });
                },
                error: function (error) {
                    console.log('Error fetching vehicle types:', error);
                }
            });
        }

        function fetchCity(currentDetail) {
            // Fetch cities and populate the dropdown
            $.ajax({
                url: "{{ route('prices.price.fetchCity') }}",
                method: 'GET',
                success: function (response) {
                    const loadingCity = currentDetail.find('.loading-city');
                    const dispersalCity = currentDetail.find('.dispersal-city');

                    loadingCity.html('<option value="">Select value</option>'); // Add a default option
                    dispersalCity.html('<option value="">Select value</option>'); // Add a default option

                    $.each(response.city, function (index, city) {
                        loadingCity.append($('<option>', {
                            value: city.id,
                            text: city.name_arabic
                        }));

                        dispersalCity.append($('<option>', {
                            value: city.id,
                            text: city.name_arabic
                        }));
                    });
                },
                error: function (error) {
                    console.log('Error fetching cities:', error);
                }
            });
        }

        function fetchGoods(vehicleTypeId, goodsDropdown, currentDetail,receiverId) {
            // Fetch goods based on the selected vehicle type and populate the dropdown
            $.ajax({
                url: "{{ route('prices.price.fetchGoods') }}",
                method: 'GET',
                data: { 
                        vehicle_type_id: vehicleTypeId,
                        receiver_id: receiverId
                     },
                success: function (response) {
                    goodsDropdown.html('<option value="">Select value</option>'); // Add a default option

                    $.each(response.goods, function (index, good) {
                        goodsDropdown.append($('<option>', {
                            value: good.id,
                            text: good.name_arabic
                        }));
                    });

                    // If goods are not empty, fetch and populate cities
                    if (response.goods.length > 0) {
                        fetchCity(currentDetail);
                    } else {
                        // If goods are empty, clear city options
                        currentDetail.find('.loading-city, .dispersal-city').html('<option value="">Select value</option>');
                    }
                },
                error: function (error) {
                    console.log('Error fetching goods:', error);
                }
            });
        }

        function validatepriceDetails() {
            // Add your client-side validation logic here
            // Example: check if the required fields are not empty

            let isValid = true;

            $('.price-detail').each(function () {
                const vehicleType = $(this).find('.vehicle-type');
                const goods = $(this).find('.goods');
                const price = $(this).find('.price');
                const dispersalCity = $(this).find('.dispersal-city');
                const loadingCity = $(this).find('.loading-city');
                // Add other fields as needed...

                if (vehicleType.val() === '' || goods.val() === '' || price.val() === '' || dispersalCity.val() === '' || loadingCity.val() === '') {
                    // alert('Please fill in all details for the price.');
                    // isValid = false;
                   

                    vehicleType.addClass('is-invalid');
                    goods.addClass('is-invalid');
                    price.addClass('is-invalid');
                    dispersalCity.addClass('is-invalid');
                    loadingCity.addClass('is-invalid');
                    // return false; // Stop the loop on the first invalid detail
                    isValid = false;
                }else{
                    vehicleType.removeClass('is-invalid');
                    goods.removeClass('is-invalid');
                    price.removeClass('is-invalid');
                    dispersalCity.removeClass('is-invalid');
                    loadingCity.removeClass('is-invalid');
                }
            });

            return isValid;
        }
    });
</script>

@endsection