<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicControl;
use App\Models\PayoutMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Facades\App\Services\BasicService;
use Facades\App\Services\CurrencyLayerService;
use App\Traits\Upload;
use Exception;
use function GuzzleHttp\Promise\all;


class BasicControlController extends Controller
{
    use Upload;
    public function index($settings = null)
    {
        $settings = $settings ?? 'settings';
        abort_if(!in_array($settings, array_keys(config('generalsettings'))), 404);
        $settingsDetails = config("generalsettings.{$settings}");
        return view('admin.control_panel.settings', compact('settings', 'settingsDetails'));
    }

    public function basicControl()
    {
        $data['basicControl'] = basicControl();
        $data['timeZones'] = timezone_identifiers_list();
        $data['dateFormat'] = config('dateformat');
        return view('admin.control_panel.basic_control', $data);
    }

    public function basicControlUpdate(Request $request)
    {

        $request->validate([
            'site_title' => 'required|string|min:3|max:100',
            'time_zone' => 'required|string',
            'base_currency' => 'required|string|min:1|max:100',
            'currency_symbol' => 'required|string|min:1|max:100',
            'fraction_number' => 'required|integer|not_in:0',
            'paginate' => 'required|integer|not_in:0',
            'date_format' => 'required',
            'admin_prefix' => 'required|string|min:3|max:100',
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
        ]);

        try {
            $basic = BasicControl();
            $response = BasicControl::updateOrCreate([
                'id' => $basic->id ?? ''
            ], [
                'site_title' => $request->site_title,
                'time_zone' => $request->time_zone,
                'base_currency' => $request->base_currency,
                'currency_symbol' => $request->currency_symbol,
                'fraction_number' => $request->fraction_number,
                'date_time_format' => $request->date_format,
                'paginate' => $request->paginate,
                'admin_prefix' => $request->admin_prefix,
                'primary_color' => $request->primary_color,
                'secondary_color' => $request->secondary_color,
            ]);

            if (!$response)
                throw new Exception('Something went wrong, when updating data');

            $env = [
                'APP_TIMEZONE' => $response->time_zone,
                'APP_DEBUG' => $response->error_log == 0 ? 'true' : 'false'
            ];
            BasicService::setEnv($env);

            session()->flash('success', 'Basic Control Configure Successfully');
            Artisan::call('optimize:clear');
            return redirect()->route('admin.basic.control');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function basicControlActivityUpdate(Request $request)
    {

        $request->validate([
            'strong_password' => 'nullable|numeric|in:0,1',
            'registration' => 'nullable|numeric|in:0,1',
            'error_log' => 'nullable|numeric|in:0,1',
            'is_active_cron_notification' => 'nullable|numeric|in:0,1',
            'has_space_between_currency_and_amount' => 'nullable|numeric|in:0,1',
            'is_force_ssl' => 'nullable|numeric|in:0,1',
            'is_currency_position' => 'nullable|string|in:left,right'
        ]);

        try {
            $basic = BasicControl();
            $response = BasicControl::updateOrCreate([
                'id' => $basic->id ?? ''
            ], [
                'error_log' => $request->error_log,
                'strong_password' => $request->strong_password,
                'registration' => $request->registration,
                'is_active_cron_notification' => $request->is_active_cron_notification,
                'has_space_between_currency_and_amount' => $request->has_space_between_currency_and_amount,
                'is_currency_position' => $request->is_currency_position,
                'is_force_ssl' => $request->is_force_ssl
            ]);

            $env = [
                'APP_DEBUG' => $response->error_log == 0 ? 'false' : 'true'
            ];
            BasicService::setEnv($env);

            if (!$response)
                throw new Exception('Something went wrong, when updating data');

            session()->flash('success', 'Basic Control Configure Successfully');
            Artisan::call('optimize:clear');
            return back();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }


    }

    public function cookie()
    {
        return view('admin.control_panel.cookie');
    }

    public function updateCookie(Request $request){
        $basic = BasicControl();
        $request->validate([
            'cookie_title' => 'required|string|min:3|max:100',
            'cookie_button_name' => 'required|string|max:30',
            'cookie_button_url' => 'nullable',
            'cookie_short_text' => 'required|string|max:200',
            'cookie_image' => 'nullable|max:3072|image|mimes:jpg,jpeg,png'
        ]);

        if($request->hasFile('cookie_image')){

            $image = $this->fileUpload($request->cookie_image,config('filelocation.cookie.path'),null,null,'webp',80);
            if($image){
                $path = $image['path'];
                $driver = $image['driver'];
            }else{
                return back()->with('error', 'Image could not be uploaded.');
            }
        }


        try {
            $basic = BasicControl();
            $response = BasicControl::updateOrCreate([
                'id' => $basic->id ?? ''
            ], [
                'cookie_title' => $request->cookie_title,
                'cookie_button_name' => $request->cookie_button_name,
                'cookie_button_url' => $request->cookie_button_url,
                'cookie_short_text' => $request->cookie_short_text,
                'cookie_status' => $request->cookie_status,
                'cookie_image' => $path??$basic->cookie_image,
                'cookie_driver' => $driver??$basic->cookie_driver,
            ]);

            if (!$response){
                throw new Exception('Something went wrong, when updating data');
            }

            session()->flash('success', 'Cookie Update Successfully');
            Artisan::call('optimize:clear');
            return back();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function currencyExchangeApiConfig()
    {

        $data['scheduleList'] = config('schedulelist.schedule_list');
        $data['basicControl'] = basicControl();
        return view('admin.control_panel.exchange_api_setting', $data);

    }

    public function currencyExchangeApiConfigUpdate(Request $request)
    {
        $request->validate([
            'currency_layer_access_key' => 'required|string',
            'coin_market_cap_app_key' => 'required|string',
        ]);

        try {
            $basicControl = basicControl();
            $basicControl->update([
                'currency_layer_access_key' => $request->currency_layer_access_key,
                'currency_layer_auto_update' => $request->currency_layer_auto_update,
                'currency_layer_auto_update_at' => $request->currency_layer_auto_update_at,
                'coin_market_cap_app_key' => $request->coin_market_cap_app_key,
                'coin_market_cap_auto_update' => $request->coin_market_cap_auto_update,
                'coin_market_cap_auto_update_at' => $request->coin_market_cap_auto_update_at
            ]);
            return back()->with('success', 'Configuration changes successfully');
        } catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }
}
