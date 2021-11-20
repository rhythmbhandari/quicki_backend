<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Modules\Models\Setting;
use Illuminate\Support\Facades\Config;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * Bind the setting model and then using the AliasLoader, we register it as a facade.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('settings', function ($app) {
            return new Setting();
        });
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Setting', Setting::class);
    }

    /**
     * Firstly we are checking if the application is not running in the console and there is a table exist
     * with the name settings. Then we are loading all settings from the Setting model and then setting all
     * values using the Laravel Config class.
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
          // only use the Settings package if the Settings table is present in the database
        if (!\App::runningInConsole() && count(Schema::getColumnListing('settings'))) {
            $settings = Setting::all();
            foreach ($settings as $key => $setting)
            {
                //Config::set('settings.'.$setting->key, $setting->value);  //default
                
                $k = $setting->key;
                Config::set('settings.'.$k, $setting->value);       //Example: config(settings.site_logo) gives "site_logo.PNG"
                
                $data_type_key = 'settings.'.$k.'_data_type';
                $data_type = $setting->data_type;
                Config::set($data_type_key, );        //Example: config(settings.site_logo_data_type) gives "image"
                
                if( $data_type == "image" || $data_type == "icon" )
                {
                    $image_path_key = 'settings.'.$k.'_image_path';
                    $thumbnail_path_key = 'settings.'.$k.'_thumbnail_path';
                    Config::set($image_path_key, $setting->image_path);        //Example: config(settings.site_logo_image_path) gives the image path
                    Config::set($thumbnail_path_key, $setting->thumbnail_path);        //Example: config(settings.site_logo_thumbnail_path) gives the thumbnail path
                }
               
            }
            if(isset($setting[0]))
            {   Config::set('settings.image_path', $settings[0]->image_path);       //Path of the images stored 
                Config::set('settings.thumbnail_path', $settings[0]->thumbnail_path); 
            }


            //SETTING MAIL CONFIGS
            /*
            if($email_setting = Setting::where('key','site_email')->first())
            {
                $from_email = isset($email_setting->value) ? $email_setting->value : config('app.email');
                $from_name = ($name_setting = Setting::where('key','site_name')->first()) ? $name_setting->value : config('app.name');
                // $config = array(
                //     // 'driver'     => $mail->driver,
                //     // 'host'       => config(''),
                //     // 'port'       => $mail->port,
                //     'from'       => array('address' => $from_email, 'name' => $from_name),
                //     // 'encryption' => $mail->encryption,
                //     // 'username'   => $mail->username,
                //     // 'password'   => $mail->password,
                //     // 'sendmail'   => '/usr/sbin/sendmail -bs',
                //     // 'pretend'    => false,
                // );
                //Config::set('mail', $config);

                $from = array('address' => $from_email, 'name' => $from_name);
                Config::set('mail.from', $from);
            }   */

           
        }
    }
}
