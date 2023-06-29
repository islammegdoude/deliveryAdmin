@extends('layouts.admin.app')

@section('title', translate('Update product'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('public/assets/admin/css/tags-input.min.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
            <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                <img width="20" class="avatar-img" src="{{asset('public/assets/admin/img/icons/product.png')}}" alt="">
                <span class="page-header-title">
                    {{translate('Product_Update')}}
                </span>
            </h2>
        </div>
        <!-- End Page Header -->


        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="javascript:" method="post" id="product_form" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    @php($data = Helpers::get_business_settings('language'))
                                    @php($default_lang = Helpers::get_default_language())

                                    @if($data && array_key_exists('code', $data[0]))
                                        <ul class="nav nav-tabs w-fit-content mb-4">
                                            @foreach($data as $lang)
                                                <li class="nav-item">
                                                    <a class="nav-link lang_link {{$lang['code'] == 'en'? 'active':''}}" href="#" id="{{$lang['code']}}-link">{{Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')'}}</a>
                                                </li>
                                            @endforeach

                                        </ul>
                                        @foreach($data as $lang)
                                            <?php
                                            if(count($product['translations'])){
                                                $translate = [];
                                                foreach($product['translations'] as $t)
                                                {
                                                    if($t->locale == $lang['code'] && $t->key=="name"){
                                                        $translate[$lang['code']]['name'] = $t->value;
                                                    }
                                                    if($t->locale == $lang['code'] && $t->key=="description"){
                                                        $translate[$lang['code']]['description'] = $t->value;
                                                    }

                                                }
                                            }
                                            ?>
                                            <div class="{{$lang['code'] != 'en'? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                                <div class="form-group">
                                                    <label class="input-label" for="{{$lang['code']}}_name">{{translate('name')}} ({{strtoupper($lang['code'])}})</label>
                                                    <input type="text" {{$lang['status'] == true ? 'required':''}} name="name[]" id="{{$lang['code']}}_name" value="{{$translate[$lang['code']]['name']??$product['name']}}" class="form-control" placeholder="{{translate('New Product')}}" >
                                                </div>
                                                <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                                <div class="form-group">
                                                    <label class="input-label"
                                                        for="{{$lang['code']}}_description">{{translate('short')}} {{translate('description')}}  ({{strtoupper($lang['code'])}})</label>
                                                    {{--<div id="{{$lang}}_editor">{!! $translate[$lang]['description']??$product['description'] !!}</div>--}}
                                                    <textarea name="description[]" class="form-control textarea-h-100" id="{{$lang['code']}}_hiddenArea">{{$translate[$lang['code']]['description']??$product['description']}}</textarea>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="card p-4" id="english-form">
                                            <div class="form-group">
                                                <label class="input-label">{{translate('name')}} (EN)</label>
                                                <input type="text" name="name[]" value="{{$product['name']}}" class="form-control" placeholder="{{translate('New Product')}}" required>
                                            </div>
                                            <input type="hidden" name="lang[]" value="en">
                                            <div class="form-group pt-4">
                                                <label class="input-label">{{translate('short')}} {{translate('description')}} (EN)</label>
                                                {{--<div id="editor">{!! $product['description'] !!}</div>--}}
                                                <textarea name="description[]" class="form-control textarea-h-100" id="hiddenArea">{{ $product['description'] }}</textarea>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="font-weight-bold">{{translate('product')}} {{translate('image')}}</label>
                                        <small class="text-danger">* ( {{translate('ratio')}} 1:1 )</small>
                                        <!-- <div class="custom-file">
                                            <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label" for="customFileEg1">{{translate('choose')}} {{translate('file')}}</label>
                                        </div>

                                        <center style="display: block" id="image-viewer-section" class="pt-2">
                                            <img style="height: 200px;border: 1px solid; border-radius: 10px;" id="viewer"
                                                src="{{asset('storage/app/public/product')}}/{{$product['image']}}"
                                                onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'"
                                                alt="product image"/>
                                        </center> -->


                                        <div class="d-flex justify-content-center mt-4">
                                            <div class="upload-file">
                                                <input type="file" name="image" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" class="upload-file__input">
                                                <div class="upload-file__img_drag upload-file__img">
                                                    <img width="176" src="{{asset('storage/app/public/product')}}/{{$product['image']}}"
                                                        onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h4 class="mb-0 d-flex gap-2 align-items-center">
                                                <i class="tio-category"></i>
                                                {{translate('Category')}}
                                            </h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="input-label" for="exampleFormControlSelect1">{{translate('category')}}
                                                            <span class="text-danger">*</span></label>
                                                        <select name="category_id" id="category-id" class="form-control js-select2-custom"
                                                                onchange="getRequest('{{url('/')}}/admin/product/get-categories?parent_id='+this.value,'sub-categories')">
                                                            @foreach($categories as $category)
                                                                <option
                                                                    value="{{$category['id']}}" {{ $category->id==$product_category[0]->id ? 'selected' : ''}} >{{$category['name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="input-label" for="exampleFormControlSelect1">{{translate('sub_Category')}}<span
                                                                class="input-label-secondary"></span></label>
                                                        <select name="sub_category_id" id="sub-categories"
                                                                data-id="{{count($product_category)>=2?$product_category[1]->id:''}}"
                                                                class="form-control js-select2-custom"
                                                                onchange="getRequest('{{url('/')}}/admin/product/get-categories?parent_id='+this.value,'sub-sub-categories')">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="input-label" for="exampleFormControlInput1">{{translate('item_Type')}}
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <select name="item_type" class="form-control js-select2-custom">
                                                            <option value="0" {{$product['set_menu']==0?'selected':''}}>{{translate('product')}} {{translate('item')}}</option>
                                                            <option value="1" {{$product['set_menu']==1?'selected':''}}>{{translate('set_menu')}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="input-label" for="product_type">
                                                            {{translate('product_Type')}}
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <select name="product_type" class="form-control js-select2-custom" required>
                                                            <option value="veg" {{$product['product_type']=='veg'?'selected':''}}>{{translate('veg')}}</option>
                                                            <option value="non_veg" {{$product['product_type']=='non_veg'?'selected':''}}>{{translate('nonveg')}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h4 class="mb-0 d-flex gap-2 align-items-center">
                                                <i class="tio-dollar"></i>
                                                {{translate('Price_Information')}}
                                            </h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="input-label">{{translate('default_Price')}}
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="number" value="{{$product['price']}}" min="0" name="price"
                                                            class="form-control" step="0.01"
                                                            placeholder="{{translate('Ex : 100')}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="input-label">{{translate('discount_Type')}}
                                                            <span class="text-danger">*</span></label>
                                                        <select name="discount_type" class="form-control js-select2-custom">
                                                            <option value="percent" {{$product['discount_type']=='percent'?'selected':''}}>
                                                                {{translate('percent')}}
                                                            </option>
                                                            <option value="amount" {{$product['discount_type']=='amount'?'selected':''}}>
                                                                {{translate('amount')}}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="input-label">{{translate('discount')}}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="number" min="0" value="{{$product['discount']}}"
                                                            name="discount" class="form-control" required
                                                            placeholder="Ex : 100">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="input-label">{{translate('tax_Type')}}
                                                            <span class="text-danger">*</span></label>
                                                        <select name="tax_type" class="form-control js-select2-custom">
                                                            <option value="percent" {{$product['tax_type']=='percent'?'selected':''}}>{{translate('percentage')}}
                                                            </option>
                                                            <option value="amount" {{$product['tax_type']=='amount'?'selected':''}}>{{translate('amount')}}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="input-label" for="exampleFormControlInput1">{{translate('tax_Rate')}}
                                                            <span class="text-danger">*</span></label>
                                                        <input type="number" value="{{$product['tax']}}" min="0" name="tax"
                                                            class="form-control" step="0.01"
                                                            placeholder="{{translate('Ex : 7')}}" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between gap-3">
                                                <div class="text-dark">{{translate('turning visibility off will not show this product in the user app and website')}}</div>
                                                <div class="d-flex gap-3 align-items-center">
                                                    <h5>{{translate('Visibility')}}</h5>
                                                    <label class="switcher">
                                                        <input class="switcher_input" type="checkbox" name="status" {{$product->status == 1? 'checked' : ''}} >
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h4 class="mb-0 d-flex gap-2 align-items-center">
                                                <i class="tio-watches"></i>
                                                {{translate('Availability')}}
                                            </h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="input-label">{{translate('available_From')}}</label>
                                                        <input type="time" value="{{$product['available_time_starts']}}"
                                                            name="available_time_starts" class="form-control"
                                                            placeholder="{{translate('Ex : 10:30 am')}}" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="input-label">{{translate('available_Till')}}</label>
                                                        <input type="time" value="{{$product['available_time_ends']}}"
                                                            name="available_time_ends" class="form-control" placeholder="{{translate('5:45 pm')}}"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h4 class="mb-0 d-flex gap-2 align-items-center">
                                                <i class="tio-puzzle"></i>
                                                {{translate('Addons')}}
                                            </h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label class="input-label">{{translate('Select_Addons')}}</label>
                                                <select name="addon_ids[]" class="form-control" id="choose_addons" multiple="multiple">
                                                    @foreach(\App\Model\AddOn::orderBy('name')->get() as $addon)
                                                        <option
                                                            value="{{$addon['id']}}" {{in_array($addon->id,json_decode($product['add_ons'],true))?'selected':''}}>{{$addon['name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h4 class="mb-0 d-flex gap-2 align-items-center">
                                                <i class="tio-label"></i>
                                                {{translate('tags')}}
                                            </h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <div class="">
                                                        <label class="input-label">{{translate('search_tag')}}</label>
                                                        <input type="text" class="form-control" name="tags" placeholder="Enter tags" value="@foreach($product->tags as $c) {{$c->tag.','}} @endforeach" data-role="tagsinput">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card mt-3">
                            <div class="card-header">
                                <h4 class="mb-0 d-flex gap-2 align-items-center">
                                    <i class="tio-canvas-text"></i>
                                    {{ translate('Product_Variations') }}
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-12" >
                                        <div id="add_new_option">
                                            @if (isset($product->variations))
                                                @foreach (json_decode($product->variations,true) as $key_choice_options=>$item)
                                                    @if (isset($item["price"]))
                                                        @break
                                                    @else
                                                        @include('admin-views.product.partials._new_variations',['item'=>$item,'key'=>$key_choice_options+1])
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-outline-success" id="add_new_option_button">{{translate('Add_New_Variation')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                        <button type="submit" class="btn btn-primary">{{translate('update')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script')

@endpush

@push('script_2')
    <script src="{{asset('public/assets/admin/js/spartan-multi-image-picker.js')}}"></script>

    <script>
        //Select 2
        $("#choose_addons").select2({
            placeholder: "Select Addons",
            allowClear: true
        });
       /* $("#choice_attributes").select2({
            placeholder: "Select Attributes",
            allowClear: true
        });*/
    </script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
            $('#image-viewer-section').show(1000)
        });
    </script>

    <script>
        $(".lang_link").click(function(e){
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#"+lang+"-form").removeClass('d-none');
            if(lang == 'en')
            {
                $("#from_part_2").removeClass('d-none');
            }
            else
            {
                $("#from_part_2").addClass('d-none');
            }


        })
    </script>
    <script type="text/javascript">
        $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'images[]',
                maxCount: 4,
                rowHeight: '215px',
                groupClassName: 'col-3',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/admin/img/400x400/img2.jpg')}}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{translate("Please only input png or jpg type file")}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{translate("File size too big")}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>

    <script>
        function getRequest(route, id) {
            $.get({
                url: route,
                dataType: 'json',
                success: function (data) {
                    $('#' + id).empty().append(data.options);
                },
            });
        }

        $(document).ready(function () {
            setTimeout(function () {
                let category = $("#category-id").val();
                let sub_category = '{{count($product_category)>=2?$product_category[1]->id:''}}';
                let sub_sub_category = '{{count($product_category)>=3?$product_category[2]->id:''}}';
                getRequest('{{url('/')}}/admin/product/get-categories?parent_id=' + category + '&&sub_category=' + sub_category, 'sub-categories');
                getRequest('{{url('/')}}/admin/product/get-categories?parent_id=' + sub_category + '&&sub_category=' + sub_sub_category, 'sub-sub-categories');
            }, 1000)
        });
    </script>

    <script>
        $(document).on('ready', function () {
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script src="{{asset('public/assets/admin')}}/js/tags-input.min.js"></script>

    <script>
        function show_min_max(data){
            $('#min_max1_'+data).removeAttr("readonly");
            $('#min_max2_'+data).removeAttr("readonly");
            $('#min_max1_'+data).attr("required","true");
            $('#min_max2_'+data).attr("required","true");
        }
        function hide_min_max (data){
            $('#min_max1_'+data).val(null).trigger('change');
            $('#min_max2_'+data).val(null).trigger('change');
            $('#min_max1_'+data).attr("readonly","true");
            $('#min_max2_'+data).attr("readonly","true");
            $('#min_max1_'+data).attr("required","false");
            $('#min_max2_'+data).attr("required","false");
        }

        var count= {{isset($product->variations)?count(json_decode($product->variations,true)):0}};

        $(document).ready(function(){
            console.log(count);

            $("#add_new_option_button").click(function(e){
                count++;
                var add_option_view = `
        <div class="card view_new_option mb-2" >
            <div class="card-header">
                <label for="" id=new_option_name_`+count+`> {{  translate('add new variation')}}</label>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-lg-3 col-md-6">
                        <label for="">{{ translate('name')}}</label>
                        <input required name=options[`+count+`][name] class="form-control" type="text" onkeyup="new_option_name(this.value,`+count+`)">
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="form-group">
                            <label class="input-label text-capitalize d-flex alig-items-center"><span class="line--limit-1">{{ translate('messages.selcetion_type') }} </span>
                            </label>
                            <div class="resturant-type-group border">
                                <label class="form-check form--check mr-2 mr-md-4">
                                    <input class="form-check-input" type="radio" value="multi"
                                    name="options[`+count+`][type]" id="type`+count+`" checked onchange="show_min_max(`+count+`)"
                                    >
                                    <span class="form-check-label">
                                        {{ translate('Multiple') }}
                    </span>
                </label>

                <label class="form-check form--check mr-2 mr-md-4">
                    <input class="form-check-input" type="radio" value="single"
                    name="options[`+count+`][type]" id="type`+count+`" onchange="hide_min_max(`+count+`)"
                                    >
                                    <span class="form-check-label">
                                        {{ translate('Single') }}
                    </span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="row g-2">
            <div class="col-sm-6 col-md-4">
                <label for="">{{  translate('Min')}}</label>
                                <input id="min_max1_`+count+`" required name="options[`+count+`][min]" class="form-control" type="number" min="1">
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <label for="">{{  translate('Max')}}</label>
                                <input id="min_max2_`+count+`" required name="options[`+count+`][max]" class="form-control" type="number" min="1">
                            </div>

                            <div class="col-md-4">
                                <label class="d-md-block d-none">&nbsp;</label>
                                    <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <input id="options[`+count+`][required]" name="options[`+count+`][required]" type="checkbox">
                                        <label for="options[`+count+`][required]" class="m-0">{{  translate('Required')}}</label>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-danger btn-sm delete_input_button" onclick="removeOption(this)"
                                            title="{{  translate('Delete')}}">
                                            <i class="tio-add-to-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>





            <div id="option_price_` + count + `" >
                    <div class="border rounded p-3 pb-0 mt-3">
                        <div  id="option_price_view_` + count + `">
                            <div class="row g-3 add_new_view_row_class mb-3">
                                <div class="col-md-4 col-sm-6">
                                    <label for="">{{ translate('Option_name') }}</label>
                                    <input class="form-control" required type="text" name="options[` + count +`][values][0][label]" id="">
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <label for="">{{ translate('Additional_price') }}</label>
                                    <input class="form-control" required type="number" min="0" step="0.01" name="options[` + count + `][values][0][optionPrice]" id="">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 p-3 mr-1 d-flex "  id="add_new_button_` + count + `">
                            <button type="button" class="btn btn-outline-primary" onclick="add_new_row_button(` +
                    count + `)" >{{ translate('Add_New_Option') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
                $("#add_new_option").append(add_option_view);

            });

        });

        function new_option_name(value,data)
        {
            $("#new_option_name_"+data).empty();
            $("#new_option_name_"+data).text(value)
            console.log(value);
        }
        function removeOption(e)
        {
            element = $(e);
            element.parents('.view_new_option').remove();
        }
        function deleteRow(e)
        {
            element = $(e);
            element.parents('.add_new_view_row_class').remove();
        }


        function add_new_row_button(data)
        {
            count = data;
            countRow = 1 + $('#option_price_view_'+data).children('.add_new_view_row_class').length;
            var add_new_row_view = `
                <div class="row add_new_view_row_class mb-3 position-relative pt-3 pt-md-0">
                    <div class="col-md-4 col-sm-5">
                            <label for="">{{translate('Option_name')}}</label>
                            <input class="form-control" required type="text" name="options[`+count+`][values][`+countRow+`][label]" id="">
                        </div>
                        <div class="col-md-4 col-sm-5">
                            <label for="">{{translate('Additional_price')}}</label>
                            <input class="form-control"  required type="number" min="0" step="0.01" name="options[`+count+`][values][`+countRow+`][optionPrice]" id="">
                        </div>
                        <div class="col-sm-2 max-sm-absolute">
                            <label class="d-none d-md-block">&nbsp;</label>
                            <div class="mt-1">
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)"
                                    title="{{translate('Delete')}}">
                                    <i class="tio-add-to-trash"></i>
                                </button>
                            </div>
                    </div>
                </div>`;
            $('#option_price_view_'+data).append(add_new_row_view);

        }

    </script>

{{--    <script>--}}
{{--        $('#choice_attributes').on('change', function () {--}}
{{--            $('#customer_choice_options').html(null);--}}
{{--            $.each($("#choice_attributes option:selected"), function () {--}}
{{--                add_more_customer_choice_option($(this).val(), $(this).text());--}}
{{--            });--}}
{{--        });--}}

{{--        function add_more_customer_choice_option(i, name) {--}}
{{--            let n = name.split(' ').join('');--}}
{{--            $('#customer_choice_options').append('<div class="row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="' + i + '"><input type="text" class="form-control" name="choice[]" value="' + n + '" placeholder="Choice Title" readonly></div><div class="col-lg-9"><input type="text" class="form-control" name="choice_options_' + i + '[]" placeholder="Enter choice values" data-role="tagsinput" onchange="combination_update()"></div></div>');--}}
{{--            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();--}}
{{--        }--}}

{{--        function combination_update() {--}}
{{--            $.ajaxSetup({--}}
{{--                headers: {--}}
{{--                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
{{--                }--}}
{{--            });--}}

{{--            $.ajax({--}}
{{--                type: "POST",--}}
{{--                url: '{{route('admin.product.variant-combination')}}',--}}
{{--                data: $('#product_form').serialize(),--}}
{{--                success: function (data) {--}}
{{--                    $('#variant_combination').html(data.view);--}}
{{--                    if (data.length > 1) {--}}
{{--                        $('#quantity').hide();--}}
{{--                    } else {--}}
{{--                        $('#quantity').show();--}}
{{--                    }--}}
{{--                }--}}
{{--            });--}}
{{--        }--}}
{{--    </script>--}}


    <script>

        $('#product_form').on('submit', function () {
            var formData = new FormData(this);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.product.update',[$product['id']])}}',
                // data: $('#product_form').serialize(),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{translate("product updated successfully!")}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '{{route('admin.product.list')}}';
                        }, 2000);
                    }
                }
            });
        });
    </script>
@endpush
