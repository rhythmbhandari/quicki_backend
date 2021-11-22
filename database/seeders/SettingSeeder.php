<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Models\Setting;

class SettingSeeder extends Seeder
{
     
    
    /**
     * @var array
     */
    protected function getSettings() 
    { 
        $description = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Et beatae quia vero ducimus, est ipsum!";

        $settings = [
    

    
            [
                'key'                       =>  'footer_copyright_text',
                'value'                     =>  'Copyright © 2021 - <span class="text-primary font-weight-bold">LITG</span> | All Rights Reserved',
                'data_type'                 =>  'text',
                'group'                     =>  'footer',
                'description'               =>  $description,
                'required'                  =>  '1',
            ],

            [
                'key'                       =>  'footer_copyright_text_plain',
                'value'                     =>  'Copyright © 2021 - LITG | All Rights Reserved',
                'data_type'                 =>  'text',
                'group'                     =>  'footer',
                'description'               =>  $description,
                'required'                  =>  '1',
            ],

            [
                'key'                       =>  'social_facebook',
                'value'                     =>  'https://www.facebook.com/LetITGrowNepal/',
                'data_type'                 =>  'link',
                'group'                     =>  'social',
                'description'               =>  $description,
                'required'                  =>  '0',
            ],
            [
                'key'                       =>  'social_twitter',
                'value'                     =>  '#',
                'data_type'                 =>  'link',
                'group'                     =>  'social',
                'description'               =>  $description,
                'required'                  =>  '0',
            ],
            [
                'key'                       =>  'social_instagram',
                'value'                     =>  'https://www.instagram.com/letitgrownepal/',
                'data_type'                 =>  'link',
                'group'                     =>  'social',
                'description'               =>  $description,
                'required'                  =>  '0',
            ],
            [
                'key'                       =>  'social_linkedin',
                'value'                     =>  'https://www.linkedin.com/company/let-it-grow-nepal/',
                'data_type'                 =>  'link',
                'group'                     =>  'social',
                'description'               =>  $description,
                'required'                  =>  '0',
            ],
           

            //ESSESTIAL ONES
            [
                'key'                       =>  'site_name',
                'value'                     =>  'Let It Grow',
                'data_type'                 =>  'text',
                'group'                     =>  'general',
                'description'               =>  $description,
                'required'                  =>  '1',
            ],
            [
                'key'                       =>  'site_title',
                'value'                     =>  'E-Commerce',
                'data_type'                 =>  'text',
                'group'                     =>  'general',
                'description'               =>  $description,
                'required'                  =>  '0',
                'required'                  =>  '1',
            ],

            [
                'key'                       =>  'site_code',
                'value'                     =>  'LIG',
                'data_type'                 =>  'text',
                'group'                     =>  'general',
                'description'               =>  $description,
                'required'                  =>  '1',
            ],
 
            [
                'key'                       =>  'site_email',
                'value'                     =>  'letitgrow@gmail.com',
                'data_type'                 =>  'email',
                'group'                     =>  'general',
                'description'               =>  $description,
                'required'                  =>  '1',
            ],
            [
                'key'                       =>  'site_favicon',
                'value'                     =>  'favicon.ico',
                'data_type'                 =>  'icon',
                'group'                     =>  'general',
                'description'               =>  $description,
                'required'                  =>  '1',
            ],
            [
                'key'                       =>  'site_logo',
                'value'                     =>  'site_logo.png',
                'data_type'                 =>  'image',
                'group'                     =>  'general',
                'description'               =>  $description,
                'required'                  =>  '1',
            ],
            [
                'key'                       =>  'site_logo_light',
                'value'                     =>  'site_logo_light.png',
                'data_type'                 =>  'image',
                'group'                     =>  'general',
                'description'               =>  $description,
                'required'                  =>  '1',
            ],
            [
                'key'                       =>  'site_logo_header',
                'value'                     =>  'site_logo_header.png',
                'data_type'                 =>  'image',
                'group'                     =>  'header',
                'description'               =>  $description,
                'required'                  =>  '1',
            ],
            [
                'key'                       =>  'site_logo_footer',
                'value'                     =>  'site_logo_header.png',
                'data_type'                 =>  'image',
                'group'                     =>  'footer',
                'description'               =>  $description,
                'required'                  =>  '1',
            ],
            [
                'key'                       =>  'site_contact_footer',
                'value'                     =>  '+977 980-1030840',
                'data_type'                 =>  'text',
                'group'                     =>  'footer',
                'description'               =>  $description,
                'required'                  =>  '0',
            ],
            [
                'key'                       =>  'site_address_footer',
                'value'                     =>  'Sanepa, Lalitpur Kathmandu',
                'data_type'                 =>  'text',
                'group'                     =>  'footer',
                'description'               =>  $description,
                'required'                  =>  '0',
            ],
            [
                'key'                       =>  'site_address_link',
                'value'                     =>  'https://www.google.com/maps/place/Let+IT+Grow/@27.6869048,85.3021264,17z/data=!3m1!4b1!4m5!3m4!1s0x39eb1912be1381ad:0x9a3eee642b474478!8m2!3d27.6869001!4d85.3043151',
                'data_type'                 =>  'link',
                'group'                     =>  'general',
                'description'               =>  $description,
                'required'                  =>  '1',
            ],
            [
                'key'                       =>  'site_description_footer',
                'value'                     =>  $description,
                'data_type'                 =>  'text',
                'group'                     =>  'footer',
                'description'               =>  $description,
                'required'                  =>  '0',
                'required'                  =>  '1',
            ],
            // [
            //     'key'                       =>  'site_logo_light',
            //     'value'                     =>  'site_logo_footer.png',
            //     'data_type'                 =>  'image',
            //     'group'                     =>  'general',
            //     'description'               =>  $description,
            //     'required'                  =>  '0',
            // ],
            // [
            //     'key'                       =>  'site_logo_dark',
            //     'value'                     =>  'site_logo_header.png',
            //     'data_type'                 =>  'image',
            //     'group'                     =>  'general',
            //     'description'               =>  $description,
            //     'required'                  =>  '0',
            // ],
            [
                'key'                       =>  'homepage_main_banner_image',
                'value'                     =>  'homepage_main_banner.jpeg',
                //'image_ratio'               =>  '1.5',
                'data_type'                 =>  'image',
                'group'                     =>  'banners',
                'description'               =>  $description,
                'required'                  =>  '1',
            ],
            [
                'key'                       =>  'general_banner_image',
                'value'                     =>  'general_banner.jpg',
               // 'image_ratio'               =>  '1.5',
                'data_type'                 =>  'image',
                'group'                     =>  'banners',
                'description'               =>  $description,
                'required'                  =>  '1',
            ],
            [
                'key'                       =>  'exclusive_collection_banner_image',
                'value'                     =>  'exclusive_collection_banner.jpg',
                'image_ratio'               =>  '0.34',
                'data_type'                 =>  'image',
                'group'                     =>  'banners',
                'description'               =>  $description,
                'required'                  =>  '1',
            ],

            [
                'key'                       =>  'surge_amount',
                'value'                     =>  '30',
                'data_type'                 =>  'double',
                'group'                     =>  'surge',
                'description'               =>  $description,
                'required'                  =>  '1',
            ],
            [
                'key'                       =>  'minimum_customers_for_surge',
                'value'                     =>  '3',
                'data_type'                 =>  'double',
                'group'                     =>  'surge',
                'description'               =>  $description,
                'required'                  =>  '1',
            ],




        ];

        return $settings;
    }
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = $this->getSettings();
        foreach ($settings as $index => $setting)
        {
            $result = Setting::create($setting);
            if (!$result) {
                $this->command->info("Insert failed at record $index.");
                return;
            }
        }
        $this->command->info('Inserted '.count($settings). ' records');
    }
}
