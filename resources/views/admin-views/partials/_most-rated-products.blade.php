<!-- Header -->
<div class="card-header d-flex justify-content-between gap-10">
    <h5 class="mb-0">{{translate('Most_Rated_Products')}}</h5>
    <a href="{{route('admin.reviews.list')}}" class="btn-link">{{translate('View_All')}}</a>
</div>
<!-- End Header -->

<!-- Body -->
<div class="card-body">
    <div class="grid-item-wrap">
        @foreach($most_rated_products as $key=>$item)
            @php($product=\App\Model\Product::find($item['product_id']))
            @if(isset($product))
                <a class="grid-item text-dark" href='{{route('admin.product.view',[$item['product_id']])}}'>
                    <div class="d-flex align-items-center gap-2">
                        <img class="rounded avatar"
                                src="{{asset('storage/app/public/product')}}/{{ $item->product->image ?? '' }}"
                                onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                                alt="{{$product->name}} image">
                        <span class=" font-weight-semibold text-capitalize media-body">
                            {{isset($product)?substr($product->name,0,18) . (strlen($product->name)>18?'...':''):'not exists'}}
                        </span>
                    </div>
                    <div class="">
                        <span class="rating text-primary"><i class="tio-star"></i></span>
                        <span>{{ $avg_rating = count($product->rating)>0?number_format($product->rating[0]->average, 2, '.', ' '):0 }} </span>
                        ({{$item['total']}})
                    </div>
                </a>
            @endif
        @endforeach
    </div>
</div>
<!-- End Body -->
