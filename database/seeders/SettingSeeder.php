<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Models\Setting;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
     
    
    /**
     * @var array
     */
    protected function getSettings() 
    { 

        DB::table('settings')->delete();

        $description = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Et beatae quia vero ducimus, est ipsum!";

        $settings = [
            

      
            // [
            //     'key'                       =>  'minimum_customers_for_surge',
            //     'value'                     =>  '3',
            //     'data_type'                 =>  'double',
            //     'group'                     =>  'surge',
            //     'description'               =>  "Minimum number of customers for the density surge to be applied!",
            //     'required'                  =>  '1',
            // ],

            //ESSESTIAL ONES
            [
                'key'                       =>  'site_name',
                'value'                     =>  'Puryaideu V2',
                'data_type'                 =>  'text',
                'group'                     =>  'general',
                'description'               =>  $description,
                'required'                  =>  '1',
            ],
            [
                'key'                       =>  'site_title',
                'value'                     =>  'Puryaideu V2',
                'data_type'                 =>  'text',
                'group'                     =>  'general',
                'description'               =>  $description,
                'required'                  =>  '0',
                'required'                  =>  '1',
            ],
            // [
            //     'key'                       =>  'company_name',
            //     'value'                     =>  'Let It Grow',
            //     'data_type'                 =>  'text',
            //     'group'                     =>  'general',
            //     'description'               =>  $description,
            //     'required'                  =>  '1',
            // ],
            [
                'key'                       =>  'site_code',
                'value'                     =>  'PUR',
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
                'key'                       =>  'site_email_secondary',
                'value'                     =>  'amit.karn98@gmail.com',
                'data_type'                 =>  'email',
                'group'                     =>  'general',
                'description'               =>  $description,
                'required'                  =>  '0',
            ],
            [
                'key'                       =>  'site_favicon',
                'value'                     =>  'logo.png',
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

            //SCAN RADIUS
            [
                'key'                       =>  'scan_radius',
                'value'                     =>  '10',
                'data_type'                 =>  'double',
                'group'                     =>  'SURGE',
                'description'               =>   "Radius within which the density of riders to customers is deduced!",
                'required'                  =>  '1',
            ],





            //SOCIAL LINKS
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


            [
                'key'                       =>  'site_logo_header',
                'value'                     =>  'site_logo_header.png',
                'data_type'                 =>  'image',
                'group'                     =>  'header',
                'description'               =>  $description,
                'required'                  =>  '1',
            ],
            [
                'key'                       =>  'site_logo_header_mobile',
                'value'                     =>  'site_logo_header_mobile.png',
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
                'key'                       =>  'esewa_number',
                'value'                     =>  '9816910976',
                'data_type'                 =>  'text',
                'group'                     =>  'general',
                'description'               =>  $description,
                'required'                  =>  '0',
            ],
            [
                'key'                       =>  'khalti_number',
                'value'                     =>  '9816910976',
                'data_type'                 =>  'text',
                'group'                     =>  'general',
                'description'               =>  $description,
                'required'                  =>  '0',
            ],

            [
                'key'                       =>  'site_contact_footer_secondary',
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

            
            //About Us and Contact Pages' Contents
            [
                'key'                       =>  'about_description',
                'value'                     =>  $description,
                'data_type'                 =>  'text',
                'group'                     =>  'page_contents',
                'description'               =>  $description,
                'required'                  =>  '1',
            ], 
            [
                'key'                       =>  'contact_map_embeded_link',
                'value'                     =>  'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3532.9836540844417!2d85.30213177715841!3d27.686900082884584!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb1912be1381ad%3A0x9a3eee642b474478!2sLet%20IT%20Grow!5e0!3m2!1sen!2snp!4v1637477034949!5m2!1sen!2snp',
                'data_type'                 =>  'link',
                'group'                     =>  'page_contents',
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
