<?php 

namespace App\Http\Traits;
use Illuminate\Http\Request;

trait SparrowSms {


    public function sendSms(Request $request)
    {
       // dd($request->all(),config('app.name'),config('app.sparrow_key'));
        $message =  config('app.name') . " Application. Your Verification Code is-" . $request->code;

        $phone = $request->phone;

        $args = http_build_query(array(
            'token' => config('app.sparrow_key'),
            'from'  => "InfoSMS",
            'to'    => $phone,
            'text'  => $message
        ));

        $url = "http://api.sparrowsms.com/v2/sms/";

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Response
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status_code == 200) {
            // return response([$response, 'msg' => 'SMS Sent Successfully']);
            return response([ 'message' => 'SMS Sent Successfully', 'response'=>$response],200);
        } else {
            // return response([$response, 'error' => 'SMS could not be sent', 'status'=>$status_code]);
            return response([$response, 'error' => 'SMS could not be sent', 'status'=>$status_code],$status_code);
        }
    }
}
?>