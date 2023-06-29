@extends('layouts.admin.app')

@section('title', translate('customer_settings'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
            <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/admin/img/mail.png')}}" alt="public">
                <span>
                    {{ translate('Customer Settings') }}
                </span>
            </h2>
        </div>

        <!-- End Page Header -->
        <form action="{{ route('admin.customer.update-settings') }}" method="post" enctype="multipart/form-data"
              id="update-settings">
            @csrf
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-4 col-12">
                            <div class="form-group mb-0">
                                <label
                                    class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-4 form-control"
                                    for="customer_wallet">
                                    <span class="pr-2">{{ translate('customer_wallet') }} :</span>
                                    <input type="checkbox" class="toggle-switch-input"
                                           onclick="section_visibility('customer_wallet')" name="customer_wallet"
                                           id="customer_wallet" value="1" data-section="wallet-section"
                                        {{ isset($data['wallet_status']) && $data['wallet_status'] == 1 ? 'checked' : '' }}>
                                    <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-4 col-12">
                            <div class="form-group mb-0">
                                <label
                                    class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-4 form-control"
                                    for="customer_loyalty_point">
                                    <span class="pr-2">{{ translate('customer_loyalty_point') }}:</span>
                                    <input type="checkbox" class="toggle-switch-input"
                                           onclick="section_visibility('customer_loyalty_point')" name="customer_loyalty_point"
                                           id="customer_loyalty_point" data-section="loyalty-point-section" value="1"
                                        {{ isset($data['loyalty_point_status']) && $data['loyalty_point_status'] == 1 ? 'checked' : '' }}>
                                    <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-4 col-12">
                            <div class="form-group mb-0">
                                <label
                                    class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-4 form-control">
                                    <span
                                        class="pr-2">{{ translate('customer_referrer_earning') }}:</span>
                                    <input type="checkbox" class="toggle-switch-input"
                                           onclick="section_visibility('ref_earning_status')"
                                           name="ref_earning_status" id="ref_earning_status"
                                           data-section="referrer-earning" value="1"
                                        {{ isset($data['ref_earning_status']) && $data['ref_earning_status'] == 1 ? 'checked' : '' }}>
                                    <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
{{--            <div class="card mb-3 wallet-section">--}}
{{--                <div class="card-header">--}}
{{--                    <h5 class="card-title">--}}
{{--                        <span class="card-header-icon">--}}
{{--                            <i class="tio-settings"></i>--}}
{{--                        </span>--}}
{{--                        <span>--}}
{{--                            {{ translate('wallet') }}--}}
{{--                            {{ translate('settings') }}--}}
{{--                        </span>--}}
{{--                    </h5>--}}
{{--                </div>--}}
{{--                <div class="card-body">--}}
{{--                    <div class="row g-3">--}}
{{--                        <div class="col-sm-6 col-lg-4">--}}
{{--                            <div class="form-group m-0">--}}
{{--                                <label--}}
{{--                                    class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-4 form-control"--}}
{{--                                    for="refund_to_wallet">--}}
{{--                                    <span class="pr-2">{{ translate('refund_to_wallet') }}</span>--}}

{{--                                    <input type="checkbox" class="toggle-switch-input" name="refund_to_wallet"--}}
{{--                                           id="refund_to_wallet" value="1"--}}
{{--                                        {{ isset($data['wallet_add_refund']) && $data['wallet_add_refund'] == 1 ? 'checked' : '' }}>--}}
{{--                                    <span class="toggle-switch-label text">--}}
{{--                                        <span class="toggle-switch-indicator"></span>--}}
{{--                                    </span>--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="card mb-3 loyalty-point-section">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-header-icon">
                            <i class="tio-settings"></i>
                        </span>
                        <span>
                            {{ translate('Customer Loyalty Point') }}
                            {{ translate('Settings') }}
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group m-0">
                                <label class="input-label"
                                       for="loyalty_point_exchange_rate">{{ translate('1 '.\App\CentralLogics\Helpers::currency_code().' Equal to How Much Loyalty Points?') }}</label>
                                <input type="number" class="form-control" name="loyalty_point_exchange_rate"
                                       value="{{ $data['loyalty_point_exchange_rate'] ?? '0' }}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group m-0">
                                <label class="input-label"
                                       for="intem_purchase_point">{{ translate('Percentage of Loyalty Point on Order Amount') }}

                                </label>
                                <input type="number" class="form-control" name="item_purchase_point" step=".01"
                                       value="{{ $data['loyalty_point_item_purchase_point'] ?? '0' }}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-group m-0">
                                <label class="input-label"
                                       for="intem_purchase_point">{{ translate('Minimum Loyalty Points to Transfer Into Wallet') }}</label>
                                <input type="number" class="form-control" name="minimun_transfer_point" min="0"
                                       value="{{ $data['loyalty_point_minimum_point'] ?? '0' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3 referrer-earning">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-header-icon">
                            <i class="tio-settings"></i>
                        </span>
                        <span>
                            {{ translate('Customer Referrer') }}
                            {{ translate('settings') }}
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6 col-12">
                            <div class="form-group m-0">
                                <label class="input-label"
                                       for="referrer_earning_exchange_rate">{{ translate('One Referrer Equal To How Much ' .\App\CentralLogics\Helpers::currency_code())  }}</label>
                                <input type="number step=0.01" class="form-control" name="ref_earning_exchange_rate"
                                       value="{{ $data['ref_earning_exchange_rate'] ?? '0' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btn--container justify-content-end">
                <button type="submit" id="submit" class="btn btn-primary">{{ translate('submit') }}</button>
            </div>
        </form>
    </div>
@endsection
@push('script_2')
    <script>
        $(document).on('ready', function() {
{{--            @if (isset($data['wallet_status']) && $data['wallet_status'] != 1)--}}
{{--            $('.wallet-section').hide();--}}
{{--            @endif--}}
            @if (isset($data['loyalty_point_status']) && $data['loyalty_point_status'] != 1)
            $('.loyalty-point-section').hide();
            @endif
            @if (isset($data['ref_earning_status']) && $data['ref_earning_status'] != 1)
            $('.referrer-earning').hide();
            @endif

            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));
            $('#column1_search').on('keyup', function() {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            $('#column3_search').on('change', function() {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });
        });
    </script>

    <script>
        function section_visibility(id) {
            console.log($('#' + id).data('section'));
            if ($('#' + id).is(':checked')) {
                console.log('checked');
                $('.' + $('#' + id).data('section')).show();
            } else {
                console.log('unchecked');
                $('.' + $('#' + id).data('section')).hide();
            }
        }


        {{--$('#add_fund').on('submit', function(e) {--}}

        {{--    e.preventDefault();--}}
        {{--    var formData = new FormData(this);--}}

        {{--    Swal.fire({--}}
        {{--        title: '{{ translate('are_you_sure') }}',--}}
        {{--        text: '{{ translate('you_want_to_add_fund') }}' + $('#amount').val() +--}}
        {{--            ' {{ \App\CentralLogics\Helpers::currency_code() . ' ' . translate('to') }} ' + $(--}}
        {{--                '#customer option:selected').text() + '{{ translate('to_wallet') }}',--}}
        {{--        type: 'info',--}}
        {{--        showCancelButton: true,--}}
        {{--        cancelButtonColor: 'default',--}}
        {{--        confirmButtonColor: 'primary',--}}
        {{--        cancelButtonText: '{{ translate('no') }}',--}}
        {{--        confirmButtonText: '{{ translate('send') }}',--}}
        {{--        reverseButtons: true--}}
        {{--    }).then((result) => {--}}
        {{--        if (result.value) {--}}
        {{--            $.ajaxSetup({--}}
        {{--                headers: {--}}
        {{--                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
        {{--                }--}}
        {{--            });--}}
        {{--            $.post({--}}
        {{--                url: '{{ route('admin.customer.wallet.add-fund') }}',--}}
        {{--                data: formData,--}}
        {{--                cache: false,--}}
        {{--                contentType: false,--}}
        {{--                processData: false,--}}
        {{--                success: function(data) {--}}
        {{--                    if (data.errors) {--}}
        {{--                        for (var i = 0; i < data.errors.length; i++) {--}}
        {{--                            toastr.error(data.errors[i].message, {--}}
        {{--                                CloseButton: true,--}}
        {{--                                ProgressBar: true--}}
        {{--                            });--}}
        {{--                        }--}}
        {{--                    } else {--}}
        {{--                        toastr.success(--}}
        {{--                            '{{ translate('fund_added_successfully') }}', {--}}
        {{--                                CloseButton: true,--}}
        {{--                                ProgressBar: true--}}
        {{--                            });--}}
        {{--                    }--}}
        {{--                }--}}
        {{--            });--}}
        {{--        }--}}
        {{--    })--}}
        {{--})--}}
    </script>
@endpush
