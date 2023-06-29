<div class="modal-header p-2">
    <h4 class="modal-title product-title">
    </h4>
    <button class="close call-when-done" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="d-flex flex-wrap gap-3">
        <!-- Product gallery-->
        <div class="d-flex align-items-center justify-content-center active">
            <img class="img-responsive rounded" width="160"
                 src="{{asset('storage/app/public/product')}}/{{$product['image']}}"
                 onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                 data-zoom="{{asset('storage/app/public/product')}}/{{$product['image']}}"
                 alt="Product image" width="">
            <div class="cz-image-zoom-pane"></div>
        </div>

        <?php
            $pb = json_decode($product->product_by_branch, true);
            $price = 0;
            $discount_data = [];
            if(isset($pb[0])){
                $price = $pb[0]['price'];
                $discount_data =[
                    'discount_type' => $pb[0]['discount_type'],
                    'discount' => $pb[0]['discount']
                ];
            }
        ?>

        <!-- Product details-->
        <div class="details">
            <div class="break-all">
                <a href="#" class="h3 mb-2 product-title">{{ Str::limit($product->name, 100) }}</a>
            </div>


            <div class="mb-2 text-dark d-flex align-items-baseline gap-2">
                <h3 class="font-weight-normal text-accent mb-0">
                    {{ \App\CentralLogics\Helpers::set_symbol(($price- \App\CentralLogics\Helpers::discount_calculate($discount_data, $price))) }}
                </h3>
                @if($discount_data['discount'] > 0)
                    <strike class="fz-12">
                        {{ \App\CentralLogics\Helpers::set_symbol($price) }}
                    </strike>
                @endif
            </div>

            @if($discount_data['discount'] > 0)
                <div class="mb-3 text-dark">
                    <strong>{{translate('Discount : ')}}</strong>
                    <strong
                        id="set-discount-amount">{{ \App\CentralLogics\Helpers::set_symbol(\App\CentralLogics\Helpers::discount_calculate($discount_data, $price)) }}</strong>
                </div>
            @endif
        <!-- Product panels-->
            {{--<div style="margin-left: -1%" class="sharethis-inline-share-buttons"></div>--}}
        </div>
    </div>
    <div class="row pt-2">
        <div class="col-12">
            <?php
            $cart = false;
            if (session()->has('cart')) {
                foreach (session()->get('cart') as $key => $cartItem) {
                    if (is_array($cartItem) && $cartItem['id'] == $product['id']) {
                        $cart = $cartItem;
                    }
                }
            }

            ?>
            <h3 class="mt-3">{{translate('description')}}</h3>
            <div class="d-block text-break text-dark __descripiton-txt __not-first-hidden">
                <div>
                    <p>
                        {!! $product->description !!}
                    </p>
                </div>
                <div class="show-more text-info text-center">
                    <span class="">See More</span>
                </div>
            </div>
            <form id="add-to-cart-form" class="mb-2">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">
                @if (isset($product->product_by_branch) && count($product->product_by_branch))
                    @foreach($product->product_by_branch as $branch_product)
                        @foreach ($branch_product->variations as $key => $choice)
                            @if (isset($choice->price) == false)
                                <div class="h3 p-0 pt-2">
                                    {{ $choice['name'] }}
                                    <small style="font-size: 12px" class="text-muted">
                                        ({{ ($choice['required'] == 'on')  ?  translate('Required') : translate('optional') }})
                                    </small>
                                </div>
                                @if ($choice['min'] != 0 && $choice['max'] != 0)
                                    <small class="d-block mb-3">
                                        {{ translate('You_need_to_select_minimum_ ') }} {{ $choice['min'] }} {{ translate('to_maximum_ ') }} {{ $choice['max'] }} {{ translate('options') }}
                                    </small>
                                @endif

                                <div>
                                    <input type="hidden"  name="variations[{{ $key }}][min]" value="{{ $choice['min'] }}" >
                                    <input type="hidden"  name="variations[{{ $key }}][max]" value="{{ $choice['max'] }}" >
                                    <input type="hidden"  name="variations[{{ $key }}][required]" value="{{ $choice['required'] }}" >
                                    <input type="hidden" name="variations[{{ $key }}][name]" value="{{ $choice['name'] }}">
                                    @foreach ($choice['values'] as $k => $option)
                                        <div class="form-check form--check d-flex pr-5 mr-6">
                                            <input class="form-check-input" type="{{ ($choice['type'] == "multi") ? "checkbox" : "radio"}}" id="choice-option-{{ $key }}-{{ $k }}"
                                                   name="variations[{{ $key }}][values][label][]" value="{{ $option['label'] }}" autocomplete="off">

                                            <label class="form-check-label"
                                                   for="choice-option-{{ $key }}-{{ $k }}">{{ Str::limit($option['label'], 20, '...') }}</label>
                                            <span class="ml-auto">{{ \App\CentralLogics\Helpers::set_symbol($option['optionPrice']) }}</span>
                                        </div>
                                    @endforeach
                                </div>

                            @endif
                        @endforeach
                    @endforeach
                @else
                    @foreach (json_decode($product->variations) as $key => $choice)

                        @if (isset($choice->price) == false)
                            <div class="h3 p-0 pt-2">
                                {{ $choice->name }}
                                <small style="font-size: 12px" class="text-muted">
                                    ({{ ($choice->required == 'on')  ?  translate('Required') : translate('optional') }})
                                </small>
                            </div>
                            @if ($choice->min != 0 && $choice->max != 0)
                                <small class="d-block mb-3">
                                    {{ translate('You_need_to_select_minimum_ ') }} {{ $choice->min }} {{ translate('to_maximum_ ') }} {{ $choice->max }} {{ translate('options') }}
                                </small>
                            @endif

                            <div>
                                <input type="hidden"  name="variations[{{ $key }}][min]" value="{{ $choice->min }}" >
                                <input type="hidden"  name="variations[{{ $key }}][max]" value="{{ $choice->max }}" >
                                <input type="hidden"  name="variations[{{ $key }}][required]" value="{{ $choice->required }}" >
                                <input type="hidden" name="variations[{{ $key }}][name]" value="{{ $choice->name }}">
                                @foreach ($choice->values as $k => $option)
                                    <div class="form-check form--check d-flex pr-5 mr-6">
                                        <input class="form-check-input" type="{{ ($choice->type == "multi") ? "checkbox" : "radio"}}" id="choice-option-{{ $key }}-{{ $k }}"
                                               name="variations[{{ $key }}][values][label][]" value="{{ $option->label }}" autocomplete="off">

                                        <label class="form-check-label"
                                               for="choice-option-{{ $key }}-{{ $k }}">{{ Str::limit($option->label, 20, '...') }}</label>
                                        <span class="ml-auto">{{ \App\CentralLogics\Helpers::set_symbol($option->optionPrice) }}</span>
                                    </div>
                                @endforeach
                            </div>

                        @endif
                    @endforeach
                @endif

                <!-- Quantity + Add to cart -->
                <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
                    <h3 class="product-description-label mt-2 mb-0">{{translate('Quantity')}}:</h3>

                    <div class="product-quantity d-flex align-items-center">
                        <div class="product-quantity-group d-flex align-items-center">
                            <button class="btn btn-number text-dark p-2" type="button"
                                    data-type="minus" data-field="quantity"
                                    disabled="disabled">
                                    <i class="tio-remove  font-weight-bold"></i>
                            </button>
                            <input type="text" name="quantity"
                                   class="form-control input-number text-center cart-qty-field"
                                   placeholder="1" value="1" min="1" max="100">
                            <button class="btn btn-number text-dark p-2" type="button" data-type="plus"
                                    data-field="quantity">
                                    <i class="tio-add  font-weight-bold"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @php($add_ons = json_decode($product->add_ons))
                @if(count($add_ons)>0)
                    <h3 class="pt-2">{{ translate('addon') }}</h3>

                    <div class="d-flex flex-wrap addon-wrap">
                        @foreach (\App\Model\AddOn::whereIn('id', $add_ons)->get() as $key => $add_on)
                            <div class="addon-item flex-column">
                                <input type="hidden" name="addon-price{{ $add_on->id }}" value="{{$add_on->price}}">
                                <input class="btn-check addon-chek" type="checkbox"
                                       id="addon{{ $key }}" onchange="addon_quantity_input_toggle(event)"
                                       name="addon_id[]" value="{{ $add_on->id }}"
                                       autocomplete="off">
                                <label class="d-flex align-items-center btn btn-sm check-label addon-input mb-0 h-100 break-all"
                                       for="addon{{ $key }}">{{ $add_on->name }}
                                    {{ \App\CentralLogics\Helpers::set_symbol($add_on->price) }}
                                </label>
                                <label class="input-group addon-quantity-input shadow bg-white rounded mb-0 d-flex align-items-center"
                                       for="addon{{ $key }}">
                                    <button class="btn btn-sm h-100 text-dark px-0" type="button"
                                            onclick="this.parentNode.querySelector('input[type=number]').stepDown(), getVariantPrice()">
                                        <i class="tio-remove  font-weight-bold"></i></button>
                                    <input type="number" name="addon-quantity{{ $add_on->id }}"
                                           class="text-center border-0 h-100"
                                           placeholder="1" value="1" min="1" max="100" readonly>
                                    <button class="btn btn-sm h-100 text-dark px-0" type="button"
                                            onclick="this.parentNode.querySelector('input[type=number]').stepUp(), getVariantPrice()">
                                        <i class="tio-add  font-weight-bold"></i></button>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="row no-gutters mt-4 text-dark" id="chosen_price_div">
                    <div class="col-2">
                        <div class="product-description-label">{{translate('Total_Price : ')}}</div>
                    </div>
                    <div class="col-10">
                        <div class="product-price">
                            <strong id="chosen_price"></strong>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center align-items-center gap-2 mt-2">
                    <button class="btn btn-primary px-md-5"
                            onclick="addToCart()"
                            type="button">
                        <i class="tio-shopping-cart"></i>
                        {{translate('add')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    cartQuantityInitialize();
    getVariantPrice();
    $('#add-to-cart-form input').on('change', function () {
        getVariantPrice();
    });
</script>

<script>
    $('.show-more span').on('click', function(){
        $('.__descripiton-txt').toggleClass('__not-first-hidden')
        if($(this).hasClass('active')) {
            $('.show-more span').text('{{translate('See More')}}')
            $(this).removeClass('active')
        }else {
            $('.show-more span').text('{{translate('See Less')}}')
            $(this).addClass('active')
        }
    })
</script>
