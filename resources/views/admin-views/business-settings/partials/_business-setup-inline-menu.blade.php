<div class="mt-5 mb-5">
    <div class="inline-page-menu my-4">
        <ul class="list-unstyled">
            <li class="{{Request::is('admin/business-settings/restaurant/restaurant-setup')? 'active': ''}}"><a href="{{route('admin.business-settings.restaurant.restaurant-setup')}}">{{translate('Business_Settings')}}</a></li>
            <li class="{{Request::is('admin/business-settings/restaurant/main-branch-setup')? 'active' : ''}}"><a href="{{route('admin.business-settings.restaurant.main-branch-setup')}}">{{translate('Main_Branch_Setup')}}</a></li>
            <li class="{{Request::is('admin/business-settings/restaurant/time-schedule')? 'active' : ''}}"><a href="{{route('admin.business-settings.restaurant.time_schedule_index')}}">{{translate('Restaurant_Availabilty_Time_Slot')}}</a></li>
            <li class="{{Request::is('admin/business-settings/restaurant/delivery-fee-setup')? 'active' : ''}}"><a href="{{route('admin.business-settings.restaurant.delivery-fee-setup')}}">{{translate('Delivery_Fee_Setup')}}</a></li>
            <li class="{{Request::is('admin/business-settings/restaurant/cookies-setup')? 'active' : ''}}"><a href="{{route('admin.business-settings.restaurant.cookies-setup')}}">{{translate('Cookies Setup')}}</a></li>
            <li class="{{Request::is('admin/business-settings/restaurant/otp-setup')? 'active' : ''}}"><a href="{{route('admin.business-settings.restaurant.otp-setup')}}">{{translate('Login and OTP Setup')}}</a></li>
        </ul>
    </div>
</div>
