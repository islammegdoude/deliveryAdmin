<div id="headerMain" class="d-none">
    <header id="header" class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-flush navbar-container navbar-bordered">
        <div class="navbar-nav-wrap">
            <div class="navbar-brand-wrapper">
                <!-- Logo -->
                @php($restaurant_logo=\App\Model\BusinessSetting::where(['key'=>'logo'])->first()->value)
                <a class="navbar-brand" href="{{route('branch.dashboard')}}" aria-label="">
                    <img class="navbar-brand-logo" style="object-fit: contain;"
                         onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                         src="{{asset('storage/app/public/restaurant/'.$restaurant_logo)}}" alt="Logo">
                    <img class="navbar-brand-logo-mini" style="object-fit: contain;"
                         onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                         src="{{asset('storage/app/public/restaurant/'.$restaurant_logo)}}"
                         alt="Logo">
                </a>
                <!-- End Logo -->
            </div>

            <div class="navbar-nav-wrap-content-left d-xl-none">
                <!-- Navbar Vertical Toggle -->
                <button type="button" class="js-navbar-vertical-aside-toggle-invoker close mr-3">
                    <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                       data-placement="right" title="Collapse"></i>
                    <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                       data-template='<div class="tooltip d-none d-sm-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                       data-toggle="tooltip" data-placement="right" title="Expand"></i>
                </button>
                <!-- End Navbar Vertical Toggle -->
            </div>

            <!-- Secondary Content -->
            <div class="navbar-nav-wrap-content-right">
                <!-- Navbar -->
                <ul class="navbar-nav align-items-center flex-row">
                    <li class="nav-item d-none d-sm-inline-block">
                        <div class="hs-unfold">
                            <div class="bg-white p-1 rounded">
                                @php( $local = session()->has('local')?session('local'):'en')
                                @php($lang = \App\CentralLogics\Helpers::get_business_settings('language')??null)
                                <div class="topbar-text dropdown disable-autohide text-capitalize">
                                    @if(isset($lang) && array_key_exists('code', $lang[0]))
                                        <a class="topbar-link dropdown-toggle d-flex gap-2 align-items-center font-weight-bold dropdown-toggle-empty" href="#" data-toggle="dropdown">
                                            {{--<img class="avatar-img rounded-0" src="{{asset('public/assets/admin/img/flag.png')}}" alt="Image Description">--}}
                                            <i class="tio-globe"></i>
                                            @foreach($lang as $data)
                                                @if($data['code']==$local)
                                                    <span>{{$data['name']}}</span>
                                                @endif
                                            @endforeach
                                        </a>
                                        <ul class="dropdown-menu">
                                            @foreach($lang as $key =>$data)
                                                @if($data['status']==1)
                                                    <li>
                                                        <a class="dropdown-item pr-8 d-flex gap-2 align-items-center"
                                                        href="{{route('admin.lang',[$data['code']])}}">
                                                            {{--<img class="avatar-img rounded-0" src="{{asset('public/assets/admin/img/flag.png')}}" alt="Image Description">--}}
                                                            <span class="text-capitalize">{{\App\CentralLogics\Helpers::get_language_name($data['code'])}}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>

{{--                    <li class="nav-item d-none d-sm-inline-block">--}}
{{--                        <div class="hs-unfold">--}}
{{--                            <a class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"--}}
{{--                            href="{{route('admin.message.list')}}">--}}
{{--                                <i class="tio-messages-outlined"></i>--}}
{{--                                    <span class="btn-status btn-status-c1">0</span>--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    </li>--}}

                    <li class="nav-item d-none d-sm-inline-block">
                        <!-- Notification -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker btn btn-icon btn-ghost-secondary rounded-circle"
                               href="{{route('branch.order.list',['status'=>'pending'])}}">
                                <i class="tio-shopping-cart-outlined"></i>
                                <span class="btn-status btn-status-c1">0</span>
                            </a>
                        </div>
                        <!-- End Notification -->
                    </li>

                    <li class="nav-item ml-4">
                        <!-- Account -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker navbar-dropdown-account-wrapper media gap-2" href="javascript:;"
                               data-hs-unfold-options='{
                                     "target": "#accountNavbarDropdown",
                                     "type": "css-animation"
                                   }'>
                                <div class="media-body d-flex align-items-end flex-column">
{{--                                    @dump(auth('branch')->user())--}}
                                    <span class="card-title h5">{{auth('branch')->user()->name}}</span>
                                    <span class="card-text fz-12 font-weight-bold">{{translate('Branch Admin')}}</span>
                                </div>
                                <div class="avatar avatar-sm avatar-circle">
                                    <img class="avatar-img"
                                         onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                         src="{{asset('storage/app/public/branch')}}/{{auth('branch')->user()->image}}"
                                         alt="Image Description">
                                    <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                </div>
                            </a>

                            <div id="accountNavbarDropdown"
                                 class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account"
                                 style="width: 16rem;">
                                <div class="dropdown-item-text">
                                    <div class="media align-items-center">
                                        <div class="avatar avatar-sm avatar-circle mr-2">
                                            <img class="avatar-img"
                                                 onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                                 src="{{asset('storage/app/public/branch')}}/{{auth('branch')->user()->image}}"
                                                 alt="Image Description">
                                        </div>
                                        <div class="media-body">
                                            <span class="card-title h5">{{auth('branch')->user()->name}}</span>
                                            <span class="card-text">{{auth('branch')->user()->email}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{route('branch.settings')}}">
                                    <span class="text-truncate pr-2" title="Settings">{{translate('settings')}}</span>
                                </a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="javascript:" onclick="Swal.fire({
                                    title: '{{translate('Do you want to logout ?')}}',
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonColor: '#FC6A57',
                                    cancelButtonColor: '#363636',
                                    confirmButtonText: `{{translate('Yes')}}`,
                                    cancelButtonText: `{{translate('No')}}`,
                                    }).then((result) => {
                                    if (result.value) {
                                    location.href='{{route('branch.auth.logout')}}';
                                    } else{
                                        Swal.fire({
                                        title: '{{translate("Canceled")}}',
                                        confirmButtonText: '{{translate("Okay")}}',
                                        })
                                    }
                                    })">
                                    <span class="text-truncate pr-2" title="Sign out">{{translate('sign_out')}}</span>
                                </a>
                            </div>
                        </div>
                        <!-- End Account -->
                    </li>
                </ul>
                <!-- End Navbar -->
            </div>
            <!-- End Secondary Content -->
        </div>
    </header>
</div>
<div id="headerFluid" class="d-none"></div>
<div id="headerDouble" class="d-none"></div>
