<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Requests\UserinputRequest;
use App\Http\Requests\UsercityRequest;
use Auth, Input, DB, Storage, File;
use Carbon\Carbon ;

class InputController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Load a newly created resource in storage.
     *
     */
    public function loadcertificatefile()
    {
        $selectCertificateFile = User::select('created_at')
            ->find(Auth::user()->id);
        $encrypt           = md5(Auth::user()->id.$selectCertificateFile->created_at);
        $directories       = Storage::files($encrypt);                                             // Listout Files
        foreach($directories as $values){
            $split_folder_file = explode('/', $values);           //08d16e9f44699e9334834833c02b7b8e/Certificate.pdf
            $splitted_file     = end($split_folder_file);         //Certificate.pdf
            $explode_filename  = explode('.', $splitted_file);    //explode(Certificate.pdf)
            $explode_name      = $explode_filename[0];            //Certificate
            if ($explode_name == 'Certificate') {
                return response()->json(['certificateFile' => $splitted_file]);
            }
        }
    }

    /**
     * Delete a newly created resource in storage.
     *
     */
    public function deletecertificatefile()
    {
        $id                  = Auth::user()->id;
        $details             = User::select('created_at')->find($id);
        $encrypt             = md5($id.$details->created_at);
        $directories         = Storage::files($encrypt);                                             // Listout Files
        foreach($directories as $values)
        {
            $split_folder_file = explode('/', $values);           //08d16e9f44699e9334834833c02b7b8e/Certificate.pdf
            $splitted_file     = end($split_folder_file);         //Certificate.pdf
            $explode_filename  = explode('.', $splitted_file);    //explode(Certificate.pdf)
            $explode_name      = $explode_filename[0];            //Certificate
            if ($explode_name == 'Certificate') {
                Storage::delete($encrypt . '/' . $splitted_file);
                return response()->json(['deleteFile' => 'success']);
            }
        }
    }

    /**
     * For download the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function downloadcertificate()
    {
        $id                  = Auth::user()->id;
        $details             = User::select('created_at')->find($id);
        $encrypt             = md5($id.$details->created_at);
        $directories         = Storage::files($encrypt);                                         // Listout Files
        foreach($directories as $values)
        {
            $split_folder_file = explode('/', $values);           //08d16e9f44699e9334834833c02b7b8e/Certificate.pdf
            $splitted_file     = end($split_folder_file);         //Certificate.pdf
            $explode_filename  = explode('.', $splitted_file);    //explode(Certificate.pdf)
            $explode_name      = $explode_filename[0];            //Certificate
            $userdata          = 'userdata';
            $filenameresult    = storage_path().'/'.$userdata.'/'.$encrypt.'/'.$splitted_file;
            if ($explode_name == 'Certificate') {
                return response()->download($filenameresult, $splitted_file, ['Content-Type' => 'application/pdf']);
            }
        }
    }

    /**
     * Upload a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function uploadfilestore()
    {
        $file                = Input::file('certificate');
        $filename            = $file->getClientOriginalName();
        $extension           = $file->getClientOriginalExtension();
        $filesize            = $file->getSize(); //File size max 5000000: 5mb
        if($filesize <= 5000000){
            if($extension == 'pdf'){
                $id                  = Auth::user()->id;
                $details             = User::select('created_at')->find($id);
                $encrypt             = md5($id.$details->created_at);
                $directory           = Storage::allDirectories();
                if(!$directory){                                                        //If no dir in a folder create a new dir
                    Storage::makeDirectory($encrypt, 0777);                                              // Create Directory
                    Storage::put($encrypt.'/'.'Certificate.'.$extension,  File::get($file));
                }
                foreach($directory as $directories){
                    if($directories == $encrypt){                                     //If matching hash dir upload pdf file
                        $listoutfiles  = Storage::files($encrypt);                                          // Listout Files
                        foreach($listoutfiles as $values){
                            $split_folder_file = explode('/', $values);       //08d16e9f44699e9334834833c02b7b8e/Certificate.pdf
                            $splitted_file     = end($split_folder_file);     //Certificate.pdf
                            $explode_filename  = explode('.', $splitted_file);//explode(Certificate.pdf)
                            $explode_name      = $explode_filename[0];        //Certificate
                            if ($explode_name == 'Certificate') {
                                Storage::delete($encrypt . '/' . $splitted_file);
                            }
                        }
                        Storage::put($encrypt.'/'.'Certificate.'.$extension,  File::get($file));
                    }
                    else{                                                         //If no matching hash dir create a new dir
                        Storage::makeDirectory($encrypt, 0777);                                          // Create Directory
                        Storage::put($encrypt.'/'.'Certificate.'.$extension,  File::get($file));
                    }
                }
                return response()->json(['filename' => $filename]);
            }
            else{
                return response()->json(['filename' => 'Not a valid file']);
            }
        }
        else{
            return response()->json(['filename' => 'Too large file']);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(UserinputRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return void
     */
    public function update(UserinputRequest $request)
    {
        $id   = Auth::user()->id;
        if($request->field == 'birthdate' || $request->field == 'search_begin')
        {
            $check = explode(".",$request->value);
            if(count($check)===3 && $check[0]>0 && $check[1]>0 && $check[2]>0)
                $request->value = Carbon::createFromFormat('d.m.Y', $request->value);
            else
                $request->value = '';
        }
        if($request->field=="webpage" && $request->value!="")
        {
            if (!filter_var($request->value, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
                print "invalid";
                exit;
            }
        }
        if($request->field!="firstname" && $request->field!="lastname" && $request->field!="email")
            User::where('id', $id)->update([$request->field => $request->value]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * update google places api return strings - city,state, country and postal - for user's contact city
     * @param UsercityRequest $request
     */
    public function updateCityInputs(UsercityRequest $request)
    {
        $id  = Auth::user()->id;
        User::where('id', $id)->update([
            'city'    => ($request ->locality=='')?$request ->searched:$request ->locality,
            'state'   => $request ->administrative_area_level_1,
            'country' => $request ->country,
            'postal'  => $request ->postal_code,
            'search_string' => $request ->searched
        ]);
    }
}
