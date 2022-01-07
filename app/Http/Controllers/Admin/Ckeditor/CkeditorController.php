<?php

namespace App\Http\Controllers\Admin\Ckeditor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CkeditorController extends Controller
{
     /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // return view('ckeditor');
    }
  
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        // if($request->hasFile('upload')) {
        //     $originName = $request->file('upload')->getClientOriginalName();
        //     $fileName = pathinfo($originName, PATHINFO_FILENAME);
        //     $extension = $request->file('upload')->getClientOriginalExtension();
        //     $fileName = $fileName.'_'.time().'.'.$extension;
        
        //     $request->file('upload')->move(public_path('uploads/ckeditor'), $fileName);
   
        //     $CKEditorFuncNum = $request->input('CKEditorFuncNum');
        //     $url = asset('uploads/ckeditor/'.$fileName); 
        //     $msg = 'Image uploaded successfully'; 
        //     $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
               
        //     @header('Content-type: text/html; charset=utf-8'); 
        //     echo $response;
        // }

        if($request->hasFile('file')) {
            $originName = $request->file('file')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
        
            $request->file('file')->move(public_path('uploads/ckeditor'), $fileName);
   
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('uploads/ckeditor/'.$fileName); 
            $msg = 'Image uploaded successfully'; 
            // $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
            $path = '/uploads/ckeditor/'.$fileName;
            return response()->json(['location'=>$path]); 
            @header('Content-type: text/html; charset=utf-8'); 
            echo $response;
        }
    } 


    // public function upload(Request $request){
    //     $fileName=$request->file('file')->getClientOriginalName();
    //     $path=$request->file('file')->storeAs('uploads/ckeditor', $fileName, 'public');
    //     return response()->json(['location'=>"/$path"]); 
    //     // $path=$request->file('file')->storeAs('uploads', $fileName, 'public');
    //     // return response()->json(['location'=>"/storage/$path"]); 

        
    //     /*$imgpath = request()->file('file')->store('uploads', 'public'); 
    //     return response()->json(['location' => "/storage/$imgpath"]);*/

    // }


}
