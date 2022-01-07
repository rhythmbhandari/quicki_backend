<?php

namespace App\Modules\Services\NewsletterSubscription;

use Illuminate\Http\Request;
use App\Modules\Services\Service;
use Yajra\DataTables\Facades\DataTables;

use App\Modules\Models\Newsletter;
use App\Modules\Models\Subscriber;
use Illuminate\Support\Facades\URL;

//services
use App\Modules\Services\User\RiderService;
class NewsletterService extends Service
{
    protected $newsletter;

    function __construct(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
    }



        /*For DataTable*/
        public function  getAllData($filter = null)
        {
            $query = $this->newsletter->all();
    
            return DataTables::of($query)
                ->addIndexColumn()
    
                ->addColumn('title', function (Newsletter $newsletter) {
                    return $newsletter->title;
                })
                ->addColumn('created_at', function (Newsletter $newsletter) {
                    // return "test 1";
                    return prettyDate($newsletter->created_at);
                })
                ->editColumn('image', function(Newsletter $newsletter){
                    return getTableHtml($newsletter, 'image');
                })
                ->editColumn('actions', function (Newsletter $newsletter) {
                    $editRoute = route('admin.newsletter.edit', $newsletter->id);
                    $deleteRoute = '';
                    $showRoute = route('admin.newsletter.show', $newsletter->id);;
                    $mapRoute = '';
                    $optionRouteText = '';
                    return getTableHtml($newsletter, 'actions', $editRoute, $deleteRoute, $showRoute, $optionRouteText, "", $mapRoute);
                })->rawColumns(['image','created_at', 'actions', 'title'])
                ->make(true);
        }
    



    function create($data)
    {
        try {
            $existing_codes = Newsletter::pluck('code')->toArray();
            $data['code'] = generateNewsletterCode($existing_codes);
            // $data['body'] = str_replace('../../..', url(''),$data['body'] );

            if(config('app.env') == 'production')
            {
                $data['body'] = str_replace('../..', url(''),$data['body'] );
            }
            else{
                $data['body'] = str_replace('../../..', url(''),$data['body'] );
            }

            $createdNewsletter = $this->newsletter->create($data);
            if($createdNewsletter)
                return $createdNewsletter;
        }
        catch(Exception $e){
            return NULL;
        }
        return NULL;
    }

    function update($data,$newsletterId)
    {
        try {
            // $url = url('');
            // $parse = parse_url($url);
            // $domain = $parse['host']; // prints 'google.com'
            
            // $data['body'] = str_replace('../../..', url(''),$data['body'] );

            if(config('app.env') == 'production')
            {
                $data['body'] = str_replace('../..', url(''),$data['body'] );
            }
            else{
                $data['body'] = str_replace('../../..', url(''),$data['body'] );
            }
            // dd(  $data['body']  ,url(''));
            //$data['body'] = asset($data['body']);
            
            $newsletter= Newsletter::findOrFail($newsletterId);
            $updatedNewsletter = $newsletter->update($data);
            return $updatedNewsletter;

        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return null;
        }
    }


    function uploadFile($file)
    {
        if (!empty($file)) {
            $this->uploadPath = 'uploads/newsletter';
            return $fileName = $this->uploadFromAjax($file);
        }
    }

    public function __deleteImages($newsletter)
    {
        try {
            if (is_file($newsletter->image_path))
                unlink($newsletter->image_path);

            if (is_file($newsletter->thumbnail_path))
                unlink($newsletter->thumbnail_path);
        } catch (\Exception $e) {
        }
    }

    public function updateImage($newsletterId, array $data)
    {
        try {
            $newsletter = $this->newsletter->find($newsletterId);
            $newsletter = $newsletter->update($data);

            return $newsletter;
        } catch (Exception $e) {
            //$this->logger->error($e->getMessage());
            return false;
        }
    }




}
