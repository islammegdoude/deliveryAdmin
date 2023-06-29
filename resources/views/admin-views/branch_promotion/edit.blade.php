@extends('layouts.admin.app')

@section('title', translate('Promotional campaign'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
            <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                <img width="20" class="avatar-img" src="{{asset('public/assets/admin/img/icons/campaign.png')}}" alt="">
                <span class="page-header-title">
                    {{translate('promotion_update')}}
                </span>
            </h2>
        </div>
        <!-- End Page Header -->


        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.promotion.update',[$promotion['id']])}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">
                                    {{translate('Select Branch')}}
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="branch_id" class="form-control js-select2-custom" required>
                                    <option value="" selected>{{ translate('--select--') }}</option>
                                    @foreach($branches as $branch)
                                        <option value="{{$branch['id']}}" {{ $promotion->branch_id == $branch->id ? 'selected' : '' }}>{{$branch['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="input-label">
                                    {{translate('Select Banner Type')}}
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="banner_type" id="banner_type" class="form-control" required>
                                    <option value="" selected>{{ translate('--select--') }}</option>
                                    <option value="bottom_banner" {{ $promotion->promotion_type == 'bottom_banner' ? 'selected' : '' }}>{{ translate('Bottom Banner (1110*380 px)') }}</option>
                                    <option value="top_right_banner" {{ $promotion->promotion_type == 'top_right_banner' ? 'selected' : '' }}>{{ translate('Top Right Banner (280*450 px)') }}</option>
                                    <option value="bottom_right_banner" {{ $promotion->promotion_type == 'bottom_right_banner' ? 'selected' : '' }}>{{ translate('Bottom Right Banner (280*350 px)') }}</option>
                                    <option value="video" {{ $promotion->promotion_type == 'video' ? 'selected' : '' }}>{{ translate('Video') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                @if($promotion->promotion_type == 'video')
                                <div class="from_part_2 video_section" id="video_section">
                                    <label class="input-label" for="exampleFormControlSelect1">{{translate('youtube Video URL')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="video" value="{{$promotion->promotion_name}}" class="form-control" placeholder="{{ translate('ex : https://youtu.be/0sus46BflpU') }}">
                                </div>
                                @else
                                <div class="from_part_2 image_section" id="image_section">
                                    <label class="input-label">
                                        {{translate('Image')}}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="custom-file">
                                        <input type="file" name="image" id="customFileEg" class="custom-file-input"
                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                               oninvalid="document.getElementById('en-link').click()">
                                        <label class="custom-file-label" for="customFileEg">{{ translate('choose file') }}</label>
                                    </div>
                                    <div class="from_part_2 mt-3">
                                        <div class="form-group">
                                            <div class="text-center">
                                                <img width="180" class="rounded-10 border" id="viewer"
                                                     src="{{asset('storage/app/public/promotion')}}/{{$promotion['promotion_name']}}" alt="image"
                                                     onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                        <button type="submit" class="btn btn-primary">{{translate('Update')}}</button>
                    </div>
                </form>
            </div>
        </div>


    </div>
@endsection

@push('script_2')
    <script>
        $(function() {
            $('#banner_type').change(function(){
                if ($(this).val() === 'video'){
                    $('#video_section').show();
                    $('#image_section').hide();
                }else{
                    $('#video_section').hide();
                    $('#image_section').show();
                }
            });
        });

        function readURL(input, viewer_id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#'+viewer_id).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg").change(function () {
            readURL(this, 'viewer');
        });

    </script>
@endpush
