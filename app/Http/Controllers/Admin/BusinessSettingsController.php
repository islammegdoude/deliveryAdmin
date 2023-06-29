<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Branch;
use App\Model\BusinessSetting;
use App\Model\Currency;
use App\Model\SocialMedia;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Support\Renderable;

class BusinessSettingsController extends Controller
{
    public function __construct(
        private BusinessSetting $business_setting,
        private Currency        $currency,
        private SocialMedia     $social_media,
        private Branch          $branch
    )
    {
    }

    /**
     * @return Renderable
     */
    public function restaurant_index(): Renderable
    {
        if ($this->business_setting->where(['key' => 'minimum_order_value'])->first() == false) {
            $this->business_setting->updateOrInsert(['key' => 'minimum_order_value'], [
                'value' => 1,
            ]);
        }

        return view('admin-views.business-settings.restaurant-index');
    }

    /**
     * @return JsonResponse
     */
    public function maintenance_mode(): JsonResponse
    {
        $mode = Helpers::get_business_settings('maintenance_mode');
        $this->business_setting->updateOrInsert(['key' => 'maintenance_mode'], [
            'value' => isset($mode) ? !$mode : 1
        ]);
        if (!$mode) {
            return response()->json(['message' => translate('Maintenance Mode is On.')]);
        }
        return response()->json(['message' => translate('Maintenance Mode is Off.')]);
    }

    /**
     * @param $side
     * @return JsonResponse
     */
    public function currency_symbol_position($side): JsonResponse
    {
        $this->business_setting->updateOrInsert(['key' => 'currency_symbol_position'], [
            'value' => $side
        ]);
        return response()->json(['message' => translate('Symbol position is ') . $side]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function restaurant_setup(Request $request): RedirectResponse
    {
        if ($request->has('self_pickup')) {
            $request['self_pickup'] = 1;
        }
        if ($request->has('delivery')) {
            $request['delivery'] = 1;
        }
        if ($request->has('dm_self_registration')) {
            $request['dm_self_registration'] = 1;
        }
        if ($request->has('toggle_veg_non_veg')) {
            $request['toggle_veg_non_veg'] = 1;
        }

        if ($request->has('email_verification')) {
            $request['email_verification'] = 1;
            $request['phone_verification'] = 0;
        } elseif ($request->has('phone_verification')) {
            $request['email_verification'] = 0;
            $request['phone_verification'] = 1;
        }

        $this->business_setting->updateOrInsert(['key' => 'country'], [
            'value' => $request['country']
        ]);

        $this->business_setting->updateOrInsert(['key' => 'time_zone'], [
            'value' => $request['time_zone'],
        ]);

        $this->business_setting->updateOrInsert(['key' => 'phone_verification'], [
            'value' => $request['phone_verification']
        ]);

        $this->business_setting->updateOrInsert(['key' => 'email_verification'], [
            'value' => $request['email_verification']
        ]);

        $this->business_setting->updateOrInsert(['key' => 'self_pickup'], [
            'value' => $request['self_pickup'],
        ]);

        $this->business_setting->updateOrInsert(['key' => 'delivery'], [
            'value' => $request['delivery'],
        ]);

        $this->business_setting->updateOrInsert(['key' => 'restaurant_open_time'], [
            'value' => $request['restaurant_open_time'],
        ]);

        $this->business_setting->updateOrInsert(['key' => 'restaurant_close_time'], [
            'value' => $request['restaurant_close_time'],
        ]);

        $this->business_setting->updateOrInsert(['key' => 'restaurant_name'], [
            'value' => $request['restaurant_name'],
        ]);

        $this->business_setting->updateOrInsert(['key' => 'currency'], [
            'value' => $request['currency'],
        ]);

        $curr_logo = $this->business_setting->where(['key' => 'logo'])->first();
        $this->business_setting->updateOrInsert(['key' => 'logo'], [
            'value' => $request->has('logo') ? Helpers::update('restaurant/', $curr_logo->value, 'png', $request->file('logo')) : $curr_logo->value
        ]);

        $this->business_setting->updateOrInsert(['key' => 'phone'], [
            'value' => $request['phone'],
        ]);

        $this->business_setting->updateOrInsert(['key' => 'email_address'], [
            'value' => $request['email'],
        ]);

        $this->business_setting->updateOrInsert(['key' => 'address'], [
            'value' => $request['address'],
        ]);

        $this->business_setting->updateOrInsert(['key' => 'email_verification'], [
            'value' => $request['email_verification'],
        ]);

        $this->business_setting->updateOrInsert(['key' => 'footer_text'], [
            'value' => $request['footer_text'],
        ]);

        $this->business_setting->updateOrInsert(['key' => 'minimum_order_value'], [
            'value' => $request['minimum_order_value'],
        ]);

        $this->business_setting->updateOrInsert(['key' => 'point_per_currency'], [
            'value' => $request['point_per_currency'],
        ]);

        $this->business_setting->updateOrInsert(['key' => 'pagination_limit'], [
            'value' => $request['pagination_limit'],
        ]);

        $this->business_setting->updateOrInsert(['key' => 'default_preparation_time'], [
            'value' => $request['default_preparation_time'],
        ]);

        $this->business_setting->updateOrInsert(['key' => 'decimal_point_settings'], [
            'value' => $request['decimal_point_settings']
        ]);

        $this->business_setting->updateOrInsert(['key' => 'schedule_order_slot_duration'], [
            'value' => $request['schedule_order_slot_duration']
        ]);

        $this->business_setting->updateOrInsert(['key' => 'time_format'], [
            'value' => $request['time_format']
        ]);

        $curr_fav_icon = $this->business_setting->where(['key' => 'fav_icon'])->first();
        $this->business_setting->updateOrInsert(['key' => 'fav_icon'], [
            'value' => $request->has('fav_icon') ? Helpers::update('restaurant/', $curr_fav_icon->value, 'png', $request->file('fav_icon')) : $curr_fav_icon->value
        ]);

        $this->business_setting->updateOrInsert(['key' => 'dm_self_registration'], [
            'value' => $request['dm_self_registration'],
        ]);

        $this->business_setting->updateOrInsert(['key' => 'toggle_veg_non_veg'], [
            'value' => $request['toggle_veg_non_veg'],
        ]);

        Toastr::success(translate('Settings updated!'));
        return back();
    }

    /**
     * @return Renderable
     */
    public function mail_index(): Renderable
    {
        return view('admin-views.business-settings.mail-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function mail_config(Request $request): RedirectResponse
    {
        $request->has('status') ? $request['status'] = 1 : $request['status'] = 0;
        $this->business_setting->where(['key' => 'mail_config'])->update([
            'value' => json_encode([
                "status" => $request['status'],
                "name" => $request['name'],
                "host" => $request['host'],
                "driver" => $request['driver'],
                "port" => $request['port'],
                "username" => $request['username'],
                "email_id" => $request['email'],
                "encryption" => $request['encryption'],
                "password" => $request['password'],
            ]),
        ]);

        Toastr::success(translate('Configuration updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function mail_send(Request $request): JsonResponse
    {
        $response_flag = 0;
        try {
            $emailServices = Helpers::get_business_settings('mail_config');

            if (isset($emailServices['status']) && $emailServices['status'] == 1) {
                Mail::to($request->email)->send(new \App\Mail\TestEmailSender());
                $response_flag = 1;
            }
        } catch (\Exception $exception) {
            $response_flag = 2;
        }

        return response()->json(['success' => $response_flag]);
    }

    /**
     * @return Renderable
     */
    public function payment_index(): Renderable
    {
        return view('admin-views.business-settings.payment-index');
    }

    /**
     * @param Request $request
     * @param $name
     * @return RedirectResponse
     */
    public function payment_update(Request $request, $name): RedirectResponse
    {
        if ($name == 'cash_on_delivery') {
            $payment = $this->business_setting->where('key', 'cash_on_delivery')->first();
            if (isset($payment) == false) {
                $this->business_setting->insert([
                    'key' => 'cash_on_delivery',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $this->business_setting->where(['key' => 'cash_on_delivery'])->update([
                    'key' => 'cash_on_delivery',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'digital_payment') {
            $payment = $this->business_setting->where('key', 'digital_payment')->first();
            if (isset($payment) == false) {
                $this->business_setting->insert([
                    'key' => 'digital_payment',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $this->business_setting->where(['key' => 'digital_payment'])->update([
                    'key' => 'digital_payment',
                    'value' => json_encode([
                        'status' => $request['status'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'ssl_commerz_payment') {
            $payment = $this->business_setting->where('key', 'ssl_commerz_payment')->first();
            if (isset($payment) == false) {
                $this->business_setting->insert([
                    'key' => 'ssl_commerz_payment',
                    'value' => json_encode([
                        'status' => 1,
                        'store_id' => '',
                        'store_password' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $this->business_setting->where(['key' => 'ssl_commerz_payment'])->update([
                    'key' => 'ssl_commerz_payment',
                    'value' => json_encode([
                        'status' => $request['status'] == 'on' ? 1 : 0,
                        'store_id' => $request['store_id'],
                        'store_password' => $request['store_password'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'razor_pay') {
            $payment = $this->business_setting->where('key', 'razor_pay')->first();
            if (isset($payment) == false) {
                $this->business_setting->insert([
                    'key' => 'razor_pay',
                    'value' => json_encode([
                        'status' => 1,
                        'razor_key' => '',
                        'razor_secret' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $this->business_setting->where(['key' => 'razor_pay'])->update([
                    'key' => 'razor_pay',
                    'value' => json_encode([
                        'status' => $request['status'] == 'on' ? 1 : 0,
                        'razor_key' => $request['razor_key'],
                        'razor_secret' => $request['razor_secret'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'paypal') {
            $payment = $this->business_setting->where('key', 'paypal')->first();
            if (isset($payment) == false) {
                $this->business_setting->insert([
                    'key' => 'paypal',
                    'value' => json_encode([
                        'status' => 1,
                        'paypal_client_id' => '',
                        'paypal_secret' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $this->business_setting->where(['key' => 'paypal'])->update([
                    'key' => 'paypal',
                    'value' => json_encode([
                        'status' => $request['status'] == 'on' ? 1 : 0,
                        'paypal_client_id' => $request['paypal_client_id'],
                        'paypal_secret' => $request['paypal_secret'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'stripe') {
            $payment = $this->business_setting->where('key', 'stripe')->first();
            if (isset($payment) == false) {
                $this->business_setting->insert([
                    'key' => 'stripe',
                    'value' => json_encode([
                        'status' => 1,
                        'api_key' => '',
                        'published_key' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $this->business_setting->where(['key' => 'stripe'])->update([
                    'key' => 'stripe',
                    'value' => json_encode([
                        'status' => $request['status'] == 'on' ? 1 : 0,
                        'api_key' => $request['api_key'],
                        'published_key' => $request['published_key'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'senang_pay') {
            $payment = $this->business_setting->where('key', 'senang_pay')->first();
            if (isset($payment) == false) {
                $this->business_setting->insert([
                    'key' => 'senang_pay',
                    'value' => json_encode([
                        'status' => 1,
                        'secret_key' => '',
                        'merchant_id' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $this->business_setting->where(['key' => 'senang_pay'])->update([
                    'key' => 'senang_pay',
                    'value' => json_encode([
                        'status' => $request['status'] == 'on' ? 1 : 0,
                        'secret_key' => $request['secret_key'],
                        'merchant_id' => $request['merchant_id'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'paystack') {
            $payment = $this->business_setting->where('key', 'paystack')->first();
            if (isset($payment) == false) {
                $this->business_setting->insert([
                    'key' => 'paystack',
                    'value' => json_encode([
                        'status' => 1,
                        'publicKey' => '',
                        'secretKey' => '',
                        'paymentUrl' => '',
                        'merchantEmail' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $this->business_setting->where(['key' => 'paystack'])->update([
                    'key' => 'paystack',
                    'value' => json_encode([
                        'status' => $request['status'] == 'on' ? 1 : 0,
                        'publicKey' => $request['publicKey'],
                        'secretKey' => $request['secretKey'],
                        'paymentUrl' => $request['paymentUrl'],
                        'merchantEmail' => $request['merchantEmail'],
                    ]),
                    'updated_at' => now()
                ]);
            }
        } elseif ($name == 'internal_point') {
            $payment = $this->business_setting->where('key', 'internal_point')->first();
            if (isset($payment) == false) {
                $this->business_setting->insert([
                    'key' => 'internal_point',
                    'value' => json_encode([
                        'status' => $request['status'] == 'on' ? 1 : 0,
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $this->business_setting->where(['key' => 'internal_point'])->update([
                    'key' => 'internal_point',
                    'value' => json_encode([
                        'status' => $request['status'] == 'on' ? 1 : 0,
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'bkash') {
            $this->business_setting->updateOrInsert(['key' => 'bkash'], [
                'value' => json_encode([
                    'status' => $request['status'] == 'on' ? 1 : 0,
                    'api_key' => $request['api_key'],
                    'api_secret' => $request['api_secret'],
                    'username' => $request['username'],
                    'password' => $request['password'],
                ])
            ]);
        } elseif ($name == 'paymob') {
            $this->business_setting->updateOrInsert(['key' => 'paymob'], [
                'value' => json_encode([
                    'status' => $request['status'] == 'on' ? 1 : 0,
                    'api_key' => $request['api_key'],
                    'iframe_id' => $request['iframe_id'],
                    'integration_id' => $request['integration_id'],
                    'hmac' => $request['hmac']
                ])
            ]);
        } elseif ($name == 'flutterwave') {
            $this->business_setting->updateOrInsert(['key' => 'flutterwave'], [
                'value' => json_encode([
                    'status' => $request['status'] == 'on' ? 1 : 0,
                    'public_key' => $request['public_key'],
                    'secret_key' => $request['secret_key'],
                    'hash' => $request['hash']
                ])
            ]);
        } elseif ($name == 'mercadopago') {
            $this->business_setting->updateOrInsert(['key' => 'mercadopago'], [
                'value' => json_encode([
                    'status' => $request['status'] == 'on' ? 1 : 0,
                    'public_key' => $request['public_key'],
                    'access_token' => $request['access_token']
                ])
            ]);
        }

        Toastr::success(translate('payment settings updated!'));
        return back();
    }

    /**
     * @return Renderable
     */
    public function currency_index(): Renderable
    {
        return view('admin-views.business-settings.currency-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function currency_store(Request $request): RedirectResponse
    {
        $request->validate([
            'currency_code' => 'required|unique:currencies',
        ]);

        $this->currency->create([
            "country" => $request['country'],
            "currency_code" => $request['currency_code'],
            "currency_symbol" => $request['symbol'],
            "exchange_rate" => $request['exchange_rate'],
        ]);

        Toastr::success(translate('Currency added successfully!'));
        return back();
    }

    /**
     * @param $id
     * @return Renderable
     */
    public function currency_edit($id): Renderable
    {
        $currency = Currency::find($id);
        return view('admin-views.business-settings.currency-update', compact('currency'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function currency_update(Request $request, $id): RedirectResponse
    {
        $this->currency->where(['id' => $id])->update([
            "country" => $request['country'],
            "currency_code" => $request['currency_code'],
            "currency_symbol" => $request['symbol'],
            "exchange_rate" => $request['exchange_rate'],
        ]);

        Toastr::success(translate('Currency updated successfully!'));
        return redirect('admin/business-settings/currency-add');
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function currency_delete($id): RedirectResponse
    {
        $this->currency->where(['id' => $id])->delete();

        Toastr::success(translate('Currency removed successfully!'));
        return back();
    }

    /**
     * @return Renderable
     */
    public function terms_and_conditions(): Renderable
    {
        $tnc = $this->business_setting->where(['key' => 'terms_and_conditions'])->first();
        if ($tnc == false) {
            $this->business_setting->insert([
                'key' => 'terms_and_conditions',
                'value' => '',
            ]);
        }
        return view('admin-views.business-settings.terms-and-conditions', compact('tnc'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function terms_and_conditions_update(Request $request): RedirectResponse
    {
        $this->business_setting->where(['key' => 'terms_and_conditions'])->update([
            'value' => $request->tnc,
        ]);

        Toastr::success(translate('Terms and Conditions updated!'));
        return back();
    }

    /**
     * @return Renderable
     */
    public function privacy_policy(): Renderable
    {
        $data = $this->business_setting->where(['key' => 'privacy_policy'])->first();
        if ($data == false) {
            $data = [
                'key' => 'privacy_policy',
                'value' => '',
            ];
            $this->business_setting->insert($data);
        }

        return view('admin-views.business-settings.privacy-policy', compact('data'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function privacy_policy_update(Request $request): RedirectResponse
    {
        $this->business_setting->where(['key' => 'privacy_policy'])->update([
            'value' => $request->privacy_policy,
        ]);

        Toastr::success(translate('Privacy policy updated!'));
        return back();
    }

    /**
     * @return Renderable
     */
    public function about_us(): Renderable
    {
        $data = $this->business_setting->where(['key' => 'about_us'])->first();
        if ($data == false) {
            $data = [
                'key' => 'about_us',
                'value' => '',
            ];
            $this->business_setting->insert($data);
        }

        return view('admin-views.business-settings.about-us', compact('data'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function about_us_update(Request $request): RedirectResponse
    {
        $this->business_setting->where(['key' => 'about_us'])->update([
            'value' => $request->about_us,
        ]);

        Toastr::success(translate('About us updated!'));
        return back();
    }


    /**
     * @param Request $request
     * @return Renderable
     */
    public function return_page_index(Request $request): Renderable
    {
        $data = $this->business_setting->where(['key' => 'return_page'])->first();

        if ($data == false) {
            $data = [
                'key' => 'return_page',
                'value' => json_encode([
                    'status' => 0,
                    'content' => ''
                ]),
            ];
            $this->business_setting->insert($data);
        }

        return view('admin-views.business-settings.return_page-index', compact('data'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function return_page_update(Request $request): RedirectResponse
    {
        $this->business_setting->updateOrInsert(['key' => 'return_page'], [
            'key' => 'return_page',
            'value' => json_encode([
                'status' => $request['status'] == 1 ? 1 : 0,
                'content' => $request['content']
            ]),
            'created_at' => now(),
            'updated_at' => now(),

        ]);

        Toastr::success(translate('Updated Successfully'));
        return back();
    }


    /**
     * @return Renderable
     */
    public function refund_page_index(): Renderable
    {
        $data = $this->business_setting->where(['key' => 'refund_page'])->first();

        if ($data == false) {
            $data = [
                'key' => 'refund_page',
                'value' => json_encode([
                    'status' => 0,
                    'content' => ''
                ]),
            ];
            $this->business_setting->insert($data);
        }

        return view('admin-views.business-settings.refund_page-index', compact('data'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function refund_page_update(Request $request): RedirectResponse
    {
        $this->business_setting->updateOrInsert(['key' => 'refund_page'], [
            'key' => 'refund_page',
            'value' => json_encode([
                'status' => $request['status'] == 1 ? 1 : 0,
                'content' => $request['content'] == null ? null : $request['content']
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Toastr::success(translate('Updated Successfully'));
        return back();
    }


    /**
     * @return Renderable
     */
    public function cancellation_page_index(): Renderable
    {
        $data = $this->business_setting->where(['key' => 'cancellation_page'])->first();

        if ($data == false) {
            $data = [
                'key' => 'cancellation_page',
                'value' => json_encode([
                    'status' => 0,
                    'content' => ''
                ]),
            ];
            $this->business_setting->insert($data);
        }

        return view('admin-views.business-settings.cancellation_page-index', compact('data'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function cancellation_page_update(Request $request): RedirectResponse
    {
        $this->business_setting->updateOrInsert(['key' => 'cancellation_page'], [
            'key' => 'cancellation_page',
            'value' => json_encode([
                'status' => $request['status'] == 1 ? 1 : 0,
                'content' => $request['content']
            ]),
            'created_at' => now(),
            'updated_at' => now(),

        ]);

        Toastr::success(translate('Updated Successfully'));
        return back();
    }

    /**
     * @return Renderable
     */
    public function fcm_index(): Renderable
    {
        if ($this->business_setting->where(['key' => 'fcm_topic'])->first() == false) {
            $this->business_setting->insert([
                'key' => 'fcm_topic',
                'value' => '',
            ]);
        }
        if ($this->business_setting->where(['key' => 'fcm_project_id'])->first() == false) {
            $this->business_setting->insert([
                'key' => 'fcm_project_id',
                'value' => '',
            ]);
        }
        if ($this->business_setting->where(['key' => 'push_notification_key'])->first() == false) {
            $this->business_setting->insert([
                'key' => 'push_notification_key',
                'value' => '',
            ]);
        }

        if ($this->business_setting->where(['key' => 'order_pending_message'])->first() == false) {
            $this->business_setting->insert([
                'key' => 'order_pending_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => '',
                ]),
            ]);
        }

        if ($this->business_setting->where(['key' => 'order_confirmation_msg'])->first() == false) {
            $this->business_setting->insert([
                'key' => 'order_confirmation_msg',
                'value' => json_encode([
                    'status' => 0,
                    'message' => '',
                ]),
            ]);
        }

        if ($this->business_setting->where(['key' => 'order_processing_message'])->first() == false) {
            $this->business_setting->insert([
                'key' => 'order_processing_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => '',
                ]),
            ]);
        }

        if ($this->business_setting->where(['key' => 'out_for_delivery_message'])->first() == false) {
            $this->business_setting->insert([
                'key' => 'out_for_delivery_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => '',
                ]),
            ]);
        }

        if ($this->business_setting->where(['key' => 'order_delivered_message'])->first() == false) {
            $this->business_setting->insert([
                'key' => 'order_delivered_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => '',
                ]),
            ]);
        }

        if ($this->business_setting->where(['key' => 'delivery_boy_assign_message'])->first() == false) {
            $this->business_setting->insert([
                'key' => 'delivery_boy_assign_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => '',
                ]),
            ]);
        }

        if ($this->business_setting->where(['key' => 'delivery_boy_start_message'])->first() == false) {
            $this->business_setting->insert([
                'key' => 'delivery_boy_start_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => '',
                ]),
            ]);
        }

        if ($this->business_setting->where(['key' => 'delivery_boy_delivered_message'])->first() == false) {
            $this->business_setting->insert([
                'key' => 'delivery_boy_delivered_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => '',
                ]),
            ]);
        }

        if ($this->business_setting->where(['key' => 'customer_notify_message'])->first() == false) {
            $this->business_setting->insert([
                'key' => 'customer_notify_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => '',
                ]),
            ]);
        }

        if ($this->business_setting->where(['key' => 'customer_notify_message_for_time_change'])->first() == false) {
            $this->business_setting->insert([
                'key' => 'customer_notify_message_for_time_change',
                'value' => json_encode([
                    'status' => 0,
                    'message' => '',
                ]),
            ]);
        }

        return view('admin-views.business-settings.fcm-index');
    }

    public function update_fcm(Request $request)
    {
        $this->business_setting->updateOrInsert(['key' => 'fcm_project_id'], [
            'value' => $request['fcm_project_id'],
        ]);

        $this->business_setting->updateOrInsert(['key' => 'push_notification_key'], [
            'value' => $request['push_notification_key'],
        ]);

        Toastr::success(translate('Settings updated!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_fcm_messages(Request $request): RedirectResponse
    {
        $this->business_setting->updateOrInsert(['key' => 'order_pending_message'], [
            'value' => json_encode([
                'status' => $request['pending_status'] == 1 ? 1 : 0,
                'message' => $request['pending_message'],
            ]),
        ]);

        $this->business_setting->updateOrInsert(['key' => 'order_confirmation_msg'], [
            'value' => json_encode([
                'status' => $request['confirm_status'] == 1 ? 1 : 0,
                'message' => $request['confirm_message'],
            ]),
        ]);

        $this->business_setting->updateOrInsert(['key' => 'order_processing_message'], [
            'value' => json_encode([
                'status' => $request['processing_status'] == 1 ? 1 : 0,
                'message' => $request['processing_message'],
            ]),
        ]);

        $this->business_setting->updateOrInsert(['key' => 'out_for_delivery_message'], [
            'value' => json_encode([
                'status' => $request['out_for_delivery_status'] == 1 ? 1 : 0,
                'message' => $request['out_for_delivery_message'],
            ]),
        ]);

        $this->business_setting->updateOrInsert(['key' => 'order_delivered_message'], [
            'value' => json_encode([
                'status' => $request['delivered_status'] == 1 ? 1 : 0,
                'message' => $request['delivered_message'],
            ]),
        ]);

        $this->business_setting->updateOrInsert(['key' => 'delivery_boy_assign_message'], [
            'value' => json_encode([
                'status' => $request['delivery_boy_assign_status'] == 1 ? 1 : 0,
                'message' => $request['delivery_boy_assign_message'],
            ]),
        ]);

        $this->business_setting->updateOrInsert(['key' => 'delivery_boy_start_message'], [
            'value' => json_encode([
                'status' => $request['delivery_boy_start_status'] == 1 ? 1 : 0,
                'message' => $request['delivery_boy_start_message'],
            ]),
        ]);

        $this->business_setting->updateOrInsert(['key' => 'delivery_boy_delivered_message'], [
            'value' => json_encode([
                'status' => $request['delivery_boy_delivered_status'] == 1 ? 1 : 0,
                'message' => $request['delivery_boy_delivered_message'],
            ]),
        ]);

        $this->business_setting->updateOrInsert(['key' => 'customer_notify_message'], [
            'value' => json_encode([
                'status' => $request['customer_notify_status'] == 1 ? 1 : 0,
                'message' => $request['customer_notify_message'],
            ]),
        ]);

        $this->business_setting->updateOrInsert(['key' => 'customer_notify_message_for_time_change'], [
            'value' => json_encode([
                'status' => $request['customer_notify_status_for_time_change'] == 1 ? 1 : 0,
                'message' => $request['customer_notify_message_for_time_change'],
            ]),
        ]);

        $this->business_setting->updateOrInsert(['key' => 'returned_message'], [
            'value' => json_encode([
                'status' => $request['returned_status'] == 1 ? 1 : 0,
                'message' => $request['returned_message'],
            ]),
        ]);

        $this->business_setting->updateOrInsert(['key' => 'failed_message'], [
            'value' => json_encode([
                'status' => $request['failed_status'] == 1 ? 1 : 0,
                'message' => $request['failed_message'],
            ]),
        ]);

        $this->business_setting->updateOrInsert(['key' => 'canceled_message'], [
            'value' => json_encode([
                'status' => $request['canceled_status'] == 1 ? 1 : 0,
                'message' => $request['canceled_message'],
            ]),
        ]);

        Toastr::success(translate('Message updated!'));
        return back();
    }

    /**
     * @return Renderable
     */
    public function map_api_settings(): Renderable
    {
        return view('admin-views.business-settings.map-api');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_map_api(Request $request): RedirectResponse
    {
//        $this->business_setting->updateOrInsert(['key' => 'map_api_key'], [
//            'value' => $request->map_api_key,
//        ]);

        DB::table('business_settings')->updateOrInsert(['key' => 'map_api_server_key'], [
            'value' => $request['map_api_server_key'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'map_api_client_key'], [
            'value' => $request['map_api_client_key'],
        ]);

        Toastr::success(translate('Settings updated!'));
        return back();
    }

    /**
     * @param Request $request
     * @return Renderable
     */
    public function recaptcha_index(Request $request): Renderable
    {
        return view('admin-views.business-settings.recaptcha-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function recaptcha_update(Request $request): RedirectResponse
    {
        $this->business_setting->updateOrInsert(['key' => 'recaptcha'], [
            'key' => 'recaptcha',
            'value' => json_encode([
                'status' => $request['status'],
                'site_key' => $request['site_key'],
                'secret_key' => $request['secret_key']
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Toastr::success(translate('Updated Successfully'));
        return back();
    }

    /**
     * @return Renderable
     */
    public function app_setting_index(): Renderable
    {
        return view('admin-views.business-settings.app-setting-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function app_setting_update(Request $request): RedirectResponse
    {
        if ($request->platform == 'android') {
            $this->business_setting->updateOrInsert(['key' => 'play_store_config'], [
                'value' => json_encode([
                    'status' => $request['play_store_status'],
                    'link' => $request['play_store_link'],
                    'min_version' => $request['android_min_version'],

                ]),
            ]);

            Toastr::success(translate('Updated Successfully for Android'));
            return back();
        }

        if ($request->platform == 'ios') {
            $this->business_setting->updateOrInsert(['key' => 'app_store_config'], [
                'value' => json_encode([
                    'status' => $request['app_store_status'],
                    'link' => $request['app_store_link'],
                    'min_version' => $request['ios_min_version'],
                ]),
            ]);
            Toastr::success(translate('Updated Successfully for IOS'));
            return back();
        }

        Toastr::error(translate('Updated failed'));
        return back();
    }

    /**
     * @return Renderable
     */
    public function firebase_message_config_index(): Renderable
    {
        return view('admin-views.business-settings.firebase-config-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function firebase_message_config(Request $request): RedirectResponse
    {
        $this->business_setting->updateOrInsert(['key' => 'firebase_message_config'], [
            'key' => 'firebase_message_config',
            'value' => json_encode([
                'apiKey' => $request['apiKey'],
                'authDomain' => $request['authDomain'],
                'projectId' => $request['projectId'],
                'storageBucket' => $request['storageBucket'],
                'messagingSenderId' => $request['messagingSenderId'],
                'appId' => $request['appId'],
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        self::firebase_message_config_file_gen();

        Toastr::success(translate('Config Updated Successfully'));
        return back();
    }

    /**
     * @return void
     */
    function firebase_message_config_file_gen()
    {
        //configs
        $config = Helpers::get_business_settings('firebase_message_config');
        $apiKey = $config['apiKey'] ?? '';
        $authDomain = $config['authDomain'] ?? '';
        $projectId = $config['projectId'] ?? '';
        $storageBucket = $config['storageBucket'] ?? '';
        $messagingSenderId = $config['messagingSenderId'] ?? '';
        $appId = $config['appId'] ?? '';

        try {
            $old_file = fopen("firebase-messaging-sw.js", "w") or die("Unable to open file!");

            $new_text = "importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js');\n";
            $new_text .= "importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js');\n";
            $new_text .= 'firebase.initializeApp({apiKey: "' . $apiKey . '",authDomain: "' . $authDomain . '",projectId: "' . $projectId . '",storageBucket: "' . $storageBucket . '", messagingSenderId: "' . $messagingSenderId . '", appId: "' . $appId . '"});';
            $new_text .= "\nconst messaging = firebase.messaging();\n";
            $new_text .= "messaging.setBackgroundMessageHandler(function (payload) { return self.registration.showNotification(payload.data.title, { body: payload.data.body ? payload.data.body : '', icon: payload.data.icon ? payload.data.icon : '' }); });";
            $new_text .= "\n";

            fwrite($old_file, $new_text);
            fclose($old_file);

        } catch (\Exception $exception) {
        }

    }


    /**
     * @return Renderable
     */
    public function social_media(): Renderable
    {
        return view('admin-views.business-settings.social-media');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $data = $this->social_media->orderBy('id', 'desc')->get();
            return response()->json($data);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function social_media_store(Request $request): JsonResponse
    {
        try {
            $this->social_media->updateOrInsert([
                'name' => $request->get('name'),
            ], [
                'name' => $request->get('name'),
                'link' => $request->get('link'),
            ]);

            return response()->json([
                'success' => 1,
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'error' => 1,
            ]);
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function social_media_edit(Request $request): JsonResponse
    {
        $data = $this->social_media->where('id', $request->id)->first();
        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function social_media_update(Request $request): JsonResponse
    {
        $social_media = $this->social_media->find($request->id);
        $social_media->name = $request->name;
        $social_media->link = $request->link;
        $social_media->save();

        return response()->json();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function social_media_delete(Request $request): JsonResponse
    {
        $br = $this->social_media->find($request->id);
        $br->delete();
        return response()->json();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function social_media_status_update(Request $request): JsonResponse
    {
        $this->social_media->where(['id' => $request['id']])->update([
            'status' => $request['status'],
        ]);
        return response()->json([
            'success' => 1,
        ], 200);
    }

    /**
     * @return Renderable
     */
    public function web_footer_index(): Renderable
    {
        return View('admin-views.business-settings.web-footer-index');
    }

    /**
     * @return Renderable
     */
    public function delivery_fee_setup(): Renderable
    {
        return view('admin-views.business-settings.restaurant.delivery-fee');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_delivery_fee(Request $request): RedirectResponse
    {
        if ($request->delivery_charge == null) {
            $request->delivery_charge = $this->business_setting->where(['key' => 'delivery_charge'])->first()->value;
        }
        $this->business_setting->updateOrInsert(['key' => 'delivery_charge'], [
            'value' => $request->delivery_charge,
        ]);

        if ($request['min_shipping_charge'] == null) {
            $request['min_shipping_charge'] = Helpers::get_business_settings('delivery_management')['min_shipping_charge'];
        }
        if ($request['shipping_per_km'] == null) {
            $request['shipping_per_km'] = Helpers::get_business_settings('delivery_management')['shipping_per_km'];
        }
        if ($request['shipping_status'] == 1) {
            $request->validate([
                'min_shipping_charge' => 'required',
                'shipping_per_km' => 'required',
            ],
                [
                    'min_shipping_charge.required' => 'Minimum shipping charge is required while shipping method is active',
                    'shipping_per_km.required' => 'Shipping charge per Kilometer is required while shipping method is active',
                ]);
        }

        $this->business_setting->updateOrInsert(['key' => 'delivery_management'], [
            'value' => json_encode([
                'status' => $request['shipping_status'],
                'min_shipping_charge' => $request['min_shipping_charge'],
                'shipping_per_km' => $request['shipping_per_km'],
            ]),
        ]);

        Toastr::success(translate('Delivery_fee_updated_successfully'));
        return back();
    }

    /**
     * @return Renderable
     */
    public function main_branch_setup(): Renderable
    {
        $branch = $this->branch->find(1);
        return view('admin-views.business-settings.restaurant.main-branch', compact('branch'));
    }

    /**
     * @return Renderable
     */
    public function social_login(): Renderable
    {
        return view('admin-views.business-settings.social-login');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function social_login_status(Request $request): JsonResponse
    {

        if ($request->btn_name == 'google_social_login') {
            $this->business_setting->updateOrInsert(['key' => 'google_social_login'], [
                'value' => $request->status,
            ]);
        }
        if ($request->btn_name == 'facebook') {
            $this->business_setting->updateOrInsert(['key' => 'facebook_social_login'], [
                'value' => $request->status,
            ]);
        }

        return response()->json(['status' => $request->status], 200);
    }

    /**
     * @return Renderable
     */
    public function chat_index(): Renderable
    {
        return view('admin-views.business-settings.chat-index');
    }

    /**
     * @param Request $request
     * @param $name
     * @return RedirectResponse
     */
    public function chat_update(Request $request, $name): RedirectResponse
    {
        if ($name == 'whatsapp') {
            $this->business_setting->updateOrInsert(['key' => 'whatsapp'], [
                'value' => json_encode([
                    'status' => $request['status'] == 'on' ? 1 : 0,
                    'number' => $request['number'],
                ]),
            ]);
        }

        Toastr::success(translate('chat settings updated!'));
        return back();
    }

    /**
     * @return Renderable
     */
    public function cookies_setup(): Renderable
    {
        return view('admin-views.business-settings.cookies-setup-index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function cookies_setup_update(Request $request): RedirectResponse
    {
        $this->business_setting->updateOrInsert(['key' => 'cookies'], [
            'value' => json_encode([
                'status' => $request['status'],
                'text' => $request['text'],
            ])
        ]);

        Toastr::success(translate('Settings updated!'));
        return back();
    }

    public function otp_setup(): Factory|View|Application
    {
        return view('admin-views.business-settings.otp-setup');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function otp_setup_update(Request $request): RedirectResponse
    {
        DB::table('business_settings')->updateOrInsert(['key' => 'maximum_otp_hit'], [
            'value' => $request['maximum_otp_hit'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'otp_resend_time'], [
            'value' => $request['otp_resend_time'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'temporary_block_time'], [
            'value' => $request['temporary_block_time'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'maximum_login_hit'], [
            'value' => $request['maximum_login_hit'],
        ]);
        DB::table('business_settings')->updateOrInsert(['key' => 'temporary_login_block_time'], [
            'value' => $request['temporary_login_block_time'],
        ]);

        Toastr::success(translate('Settings updated!'));
        return back();
    }


}
