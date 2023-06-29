<!-- Header -->
<div class="card-header d-flex justify-content-between gap-10">
    <h5 class="mb-0">{{translate('Top_Selling_Products')}}</h5>
    <a href="{{route('admin.product.list')}}" class="btn-link">{{translate('View_All')}}</a>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    <div class="d-flex flex-column gap-3">
        @foreach($top_sell as $key=>$item)
            @if(isset($item->product))
                <a class="d-flex justify-content-between align-items-center text-dark" href='{{route('admin.product.view',[$item['product_id']])}}'">
                    <div class="media align-items-center gap-2">
                        <img class="rounded avatar avatar-lg" src="{{ asset('storage/app/public/product').'/'.$item->product->image  ?? '' }}" onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'" alt="{{$item->product->name}} image">
                        <span class="font-weight-semibold text-capitalize media-body">{{substr($item->product['name'],0,18)}} {{strlen($item->product['name'])>18?'...':''}}</span>
                    </div>
                    <span class="px-2 py-1 badge-soft-c1 font-weight-bold fz-12 rounded lh-1">{{translate('Sold :')}}{{$item['count']}}</span>
                </a>
            @endif
        @endforeach
    </div>
</div>
<!-- End Body -->
