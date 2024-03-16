


<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم اناشاء شحنة جدية</title>
</head>
<body dir="rtl">
    <div style="font-family: Arial, sans-serif;">
        <div style="padding: 20px;">
            <h2>تفاصيل الشحنة</h2>
            <div class="card-body">
                <dl class="row">
                    <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.serial_number') }}</dt>
                    <dd class="col-lg-10 col-xl-9">{{ $serial_number }}</dd>
                    <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.account') }}</dt>
                    <dd class="col-lg-10 col-xl-9">{{ $accountId }}</dd>
                    <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.loading_city_id') }}</dt>
                    <dd class="col-lg-10 col-xl-9">{{ $loadingCityId }}</dd>
                    <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.unloading_city_id') }}</dt>
                    <dd class="col-lg-10 col-xl-9">{{ $unloadingCityId }}</dd>
                    <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.vehicle_type_id') }}</dt>
                    <dd class="col-lg-10 col-xl-9">{{ $vehicleTypeId }}</dd>
                    <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('shipments.goods_id') }}</dt>
                    <dd class="col-lg-10 col-xl-9">{{ $goodsId }}</dd>
                </dl>
            </div>
        </div>
    </div>
</body>
</html>
