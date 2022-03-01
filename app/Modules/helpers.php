<?php

use Illuminate\Support\Facades\DB;

function test()
{
    return 'helpers test!';
}


//This function takes in latitude and longitude of two location and returns the distance between them as the crow flies (in km)
function calcuateDistance($lat1, $lon1, $lat2, $lon2)
{
    $PI =  3.1415926535898;     //PI value
    // The math module contains a function
    // named toRadians which converts from
    // degrees to radians.
    $lon1 = $lon1 * $PI / 180;
    $lon2 = $lon2 * $PI / 180;
    $lat1 = $lat1 * $PI / 180;
    $lat2 = $lat2 * $PI / 180;

    // Haversine formula
    $dlon = $lon2 - $lon1;
    $dlat = $lat2 - $lat1;
    $a = pow(sin($dlat / 2), 2) +
        cos($lat1) * cos($lat2) *
        pow(sin($dlon / 2), 2);

    $c = 2 * asin(sqrt($a));

    // Radius of earth in kilometers. Use 3956
    // for miles
    $r = 6371;

    // calculate the result
    return ($c * $r);
}

function prettyDate($data)
{
    return date('d M Y', strtotime($data));
}

function formatDate($data)
{
    return \Carbon\Carbon::parse($data)->format('d M, Y');
}

function formatTimeStamp($data)
{
    return \Carbon\Carbon::parse($data)->format('d M, Y h:m A');
}

function getLabel($status)
{
    $label = '';
    switch ($status) {
        case 'yes':
            $label = 'label label-lg font-weight-bold label-light-success label-inline';
            break;

        case 'no':
            $label = 'label label-lg font-weight-bold label-light-danger label-inline';
            break;

        case 'active':
            $label = 'label label-lg font-weight-bold label-light-success label-inline';
            break;

        case 'closed':
            $label = 'label label-lg font-weight-bold label-light-warning label-inline';
            break;

        case 'in_active':
            $label = 'label label-lg font-weight-bold label-light-danger label-inline';
            break;

        case 'blacklisted':
            $label = 'label label-lg font-weight-bold label-light-warning label-inline';
            break;

        case 'visible':
            $label = 'label label-lg font-weight-bold label-light-success label-inline';
            break;

        case 'invisible':
            $label = 'label label-lg font-weight-bold label-light-danger label-inline';
            break;

        case 'available':
            $label = 'label label-lg font-weight-bold label-light-success label-inline';
            break;

        case 'not_available':
            $label = 'label label-lg font-weight-bold label-light-danger label-inline';
            break;

        case 'unpaid':
            $label = 'label label-lg font-weight-bold label-light-danger label-inline';
            break;

        case 'draft':
            $label = 'label label-lg font-weight-bold label-light-info label-inline';
            break;

        case 'paid':
            $label = 'label label-lg font-weight-bold label-light-success label-inline';
            break;

        case 'pending':
            $label = 'label label-lg font-weight-bold label-light-primary label-inline';
            break;

        case 'accepted':
            $label = 'label label-lg font-weight-bold label-light-warning label-inline';
            break;

        case 'running':
            $label = 'label label-lg font-weight-bold label-light-warning label-inline';
            break;

        case 'cancelled':
            $label = 'label label-lg font-weight-bold label-light-danger label-inline';
            break;

        case 'completed':
            $label = 'label label-lg font-weight-bold label-light-success label-inline';
            break;

        case 'push_notification':
            $label = 'label label-lg font-weight-bold label-light-info label-inline';
            break;
        case 'push_promo_notification':
            $label = 'label label-lg font-weight-bold label-light-success label-inline';
            break;
        case 'booking_created':
            $label = 'label label-lg font-weight-bold label-light-primary label-inline';
            break;
        case 'booking_cancelled':
            $label = 'label label-lg font-weight-bold label-light-danger label-inline';
            break;
        case 'booking_completed':
            $label = 'label label-lg font-weight-bold label-light-success label-inline';
            break;
        case 'booking_running':
            $label = 'label label-lg font-weight-bold label-light-primary label-inline';
            break;
        case 'booking_accepted':
            $label = 'label label-lg font-weight-bold label-light-warning label-inline';
            break;
        case 'customer_ignored':
            $label = 'label label-lg font-weight-bold label-light-danger label-inline';
            break;
        case 'sos_event':
            $label = 'label label-lg font-weight-bold label-light-danger label-inline';
            break;

        case 'rider':
            $label = 'label label-lg font-weight-bold label-light-warning label-inline';
            break;
        
        case 'customer':
            $label = 'label label-lg font-weight-bold label-light-primary label-inline';
            break;
        
          
        case 'admin':
            $label = 'label label-lg font-weight-bold label-light-success label-inline';
            break;
                
        case 'all':
            $label = 'label label-lg font-weight-bold label-light-success label-inline';
            break;
    }

    return $label;
}

function getTableHtml($object, $type, $editRoute = null, $deleteRoute = null, $showRoute = null, $optionalMenuRoute = null, $optionalMenuText = null, $optional2MenuRoute = null, $optional2MenuText = null, $uploadRoute = null, $details = null, $switch = null)
{
    switch ($type) {
        case 'visibility':
            return '<span class="' . getLabel($object->visibility) . '">' . $object->visibility_text . '</span>';
            break;
        case 'availability':
            return '<span class="' . getLabel($object->availability) . ' availability-label" data-id="' . $object->id . '">' . $object->availability_text . '</span>';
            break;
        case 'has_subcategory':
            return '<span data-uk-tooltip title="' . $object->has_subcategory_text . '" class="' . getLabel($object->has_subcategory) . '"><i class="material-icons" style="color: white;">' . getStatusIcons($object->has_subcategory) . '</i></span>';
            break;
        case 'job_types':
            return '<span class="label">' . $object->jobtypes->name . '</span>';
            break;
        case 'position':
            return '<span class="label">' . $object->positions->name . '</span>';
            break;
        case 'status':
            return '<label data-uk-tooltip title="' . $object->status_text . '" class="' . getLabel($object->status) . '">' . ucwords($object->status_text) . '</label>';
            break;
        case 'status2':
            return '<label data-uk-tooltip title="' . $object->status_text . '" class="btn ' . getLabel($object->status) . '">' . ucwords($object->status_text) . '</label>';
            break;
        case 'paid':
            if ($object->status == 'active') {
                return '<label data-uk-tooltip title="' . $object->status_text . '" class="' . getLabel($object->status) . '">' . ucwords($object->paid_text) . '</label>';
            }
            return '<a  href="' . route("admin.transaction.paid", $object->id) . '"><label style="cursor:pointer" data-uk-tooltip title="' . $object->status_text . '" class="' . getLabel($object->status) . '">' . ucwords($object->paid_text) . '</label></a>';
            break;
        case 'is_verified':
            return '<span data-uk-tooltip title="' . $object->is_verified . '" class="' . getLabel($object->is_verified) . '">' . $object->is_verified . '</span>';
            break;
        case 'created_by':
            if (str_contains($object->creator->thumbnail_path, '.')) {
                return '<span class=""><a href="' . route('user-detail.index', $object->creator->slug) . '" class="user_action_image">
                    <img data-uk-tooltip title="' . $object->creator->full_name . '" class="md-user-image " src=' . asset($object->creator->thumbnail_path) . ' alt=""/></a>';
                break;
            } else {
                return '<span class=""><a href="' . route('user-detail.index', $object->creator->slug) . '" class="user_action_image">
                    <img data-uk-tooltip title="' . $object->creator->full_name . '" class="md-user-image " src=' . asset('resources/assets/img/avatars/user.png') . ' alt=""/></a>';
                break;
            }

        case 'associated_user':
            if (!empty($object->user->id)) {
                return '<a href="' . route('user-detail.show', $object->user->id) . '" class="user_action_image">
                    <img data-uk-tooltip title="' . $object->user->full_name . '" class="md-user-image " src=' . asset($object->user->thumbnail_path) . ' alt=""/>
                    </a>';
            } else {
                return '<a href="#" class="user_action_image">
                    <img class="md-user-image" src=' . asset("resources/admin/img/user.png") . ' alt=""/>
                    </a>';
            }
        case 'actions':
            return view('admin.general.table-actions', compact('object', 'editRoute', 'deleteRoute', 'showRoute', 'uploadRoute', 'optionalMenuRoute', 'optionalMenuText', 'optional2MenuRoute', 'optional2MenuText', 'details', 'switch'));
            break;

        case 'image':
            return view('admin.general.lightbox', compact('object'));
            break;
        case 'insurance':
            return '<div class="d-flex align-items-center">
                <a href="' . asset($object->insurance_path) . '" data-toggle="lightbox" data-gallery="example-gallery">
                    <div class="symbol symbol-50 flex-shrink-0">
                        <img src="' . asset($object->insurance_path) . '" alt="photo">
                    </div>
                </a>
            </div>';
            break;
        case 'bluebook':
            return '<div class="d-flex align-items-center">
                <a href="' . asset($object->bluebook_path) . '" data-toggle="lightbox" data-gallery="example-gallery">
                    <div class="symbol symbol-50 flex-shrink-0">
                        <img src="' . asset($object->bluebook_path) . '" alt="photo">
                    </div>
                </a>
            </div>';
            break;
        case 'roles':
            $role = '';
            foreach ($object->roles as $k => $v) {
                $role = $role . ' <span class="label label-success">' . $v->display_name . '</span>';
            }
            return $role;
            break;

        case 'username':
            $username = '<a href="' . route('user-detail.show', $object->slug) . '">' . $object->username . '</a>';
            return $username;
            break;

        case 'user_name':
            if (!empty($object->user->id)) {
                $username = '<a href="' . route('user-detail.index', $object->user->id) . '">' . $object->user->full_name . '</a>';
                return $username;
            } else {
                return "N/A";
            }
            break;
    }
}

/**
 * Reformat oject->document['license', 'bluebook', 'inurance'] to object->license, 
 * object->bluebook,object->insurance if document present.
 */
function getDocuments($object)
{
    $documents = $object['documents'];
    if ($documents) {
        foreach ($documents as $document) {
            if ($document['type'] == "license") {
                $object['license']['document_number'] = $document['document_number'];
                $object['license']['issue_date'] = $document['issue_date'];
                $object['license']['expiry_date'] = $document['expiry_date'];
                $object['license']['image_path'] = $document['image_path'];
                $object['license']['thumbnail_path'] = $document['thumbnail_path'];
            }
            if ($document['type'] == "bluebook") {
                $object['bluebook']['document_number'] = $document['document_number'];
                $object['bluebook']['issue_date'] = $document['issue_date'];
                $object['bluebook']['expiry_date'] = $document['expiry_date'];
                $object['bluebook']['image_path'] = $document['image_path'];
                $object['bluebook']['thumbnail_path'] = $document['thumbnail_path'];
            }
            if ($document['type'] == "insurance") {
                $object['insurance']['document_number'] = $document['document_number'];
                $object['insurance']['issue_date'] = $document['issue_date'];
                $object['insurance']['expiry_date'] = $document['expiry_date'];
                $object['insurance']['image_path'] = $document['image_path'];
                $object['insurance']['thumbnail_path'] = $document['thumbnail_path'];
            }
        }
    }
    return $object;
}


function getTotalCommissions($rider)
{
    $data = DB::table('payments')
        ->select(DB::raw("SUM(commission_amount) as total_commissions"))
        ->whereIn('completed_trip_id', $rider->completed_trips->pluck('id'))
        ->first();
    return $data->total_commissions ? $data->total_commissions : 0;
}

function getTotalPaid($user)
{
    $data = DB::table('transactions')
        ->select(DB::raw("SUM(amount) as total_paid"))
        ->where('creditor_id', $user->id)
        ->where('creditor_type', 'rider')
        ->first();

    return $data->total_paid ? $data->total_paid : 0;
}

/**
 * Generates a random string of specified length at the end of the product.
 *
 * @param  int  $length
 * @return String 
 */
function randomStringCodeGenerator($code, $length = 4)
{
    //$length = 6;
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
    $generatedCode = $code . $randomString;
    return $generatedCode;
}


function generateBookingCode($existing_codes = null)
{

    $company_code = !empty(config('settings.site_code')) ? config('settings.site_code') : config('app.site_code');

    //$generatedCode = $company_code. '-' .strtoupper('BK'.substr($brand_name,0,3));
    $prefix = '#';
    // $generatedCode =  '#'; //$company_code.'-BK-';
    $generatedCode = randomStringCodeGenerator($prefix, 7);
    if ($existing_codes) {
        while (in_array($generatedCode, $existing_codes)) {
            $generatedCode = randomStringCodeGenerator($prefix, 7);
        }
    }


    return $generatedCode;
}



function generateNewsletterCode($existing_codes = null)
{

    //$company_code = 'LIG';
    //$company_code = config('app.company_code');

    $company_code = !empty(config('settings.site_code')) ? config('settings.site_code') : config('app.site_code');

    //$generatedCode = $company_code. '-' .strtoupper('BK'.substr($brand_name,0,3));
    $prefix = '#'.$company_code.'NL';
    // $generatedCode =  '#'; //$company_code.'-BK-';
    $generatedCode = randomStringCodeGenerator($prefix, 6);
    if ($existing_codes) {
        while (in_array($generatedCode, $existing_codes)) {
            $generatedCode = randomStringCodeGenerator($prefix, 6);
        }
    }


    return $generatedCode;
}

function generateNotificationCode($existing_codes = null)
{

    $company_code = !empty(config('settings.site_code')) ? config('settings.site_code') : config('app.site_code');

    $prefix = '#'.$company_code.'NF';
    // $generatedCode =  '#'; //$company_code.'-BK-';
    $generatedCode = randomStringCodeGenerator($prefix, 6);
    if ($existing_codes) {
        while (in_array($generatedCode, $existing_codes)) {
            $generatedCode = randomStringCodeGenerator($prefix, 6);
        }
    }


    return $generatedCode;
}

function generateTransactionCode($existing_codes = null)
{

    $company_code = !empty(config('settings.site_code')) ? config('settings.site_code') : config('app.site_code');

    $prefix = '#'.$company_code;
    // $generatedCode =  '#'; //$company_code.'-BK-';
    $generatedCode = randomStringCodeGenerator($prefix, 7);
    if ($existing_codes) {
        while (in_array($generatedCode, $existing_codes)) {
            $generatedCode = randomStringCodeGenerator($prefix, 7);
        }
    }


    return $generatedCode;
}

function generateVoucherCode($existing_codes = null)
{
    $company_code = !empty(config('settings.site_code')) ? config('settings.site_code') : config('app.site_code');
    $prefix = '';

    $generatedCode = randomStringCodeGenerator($prefix, 8);
    if ($existing_codes) {
        while (in_array($generatedCode, $existing_codes)) {
            $generatedCode = randomStringCodeGenerator($prefix, 8);
        }
    }

    return $generatedCode;
}



function hasRole($user_id, $checkRole)
{
    $user = App\Modules\Models\User::find($user_id);
    foreach ($user->roles as $role) {
        if ($role->name == $checkRole)
            return true;
    }
    return false;
}

function getSpacedTextAttribute($str)
{
    return ucwords(str_replace('_', ' ', $str));
}
