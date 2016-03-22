<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecordingvideoRequest;
use Storage, File, Response, Auth;
use App\User;

class RecordingvideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id                = Auth::user()->id;
        $details           = User::select('created_at')->find($id);
        $encrypt           = md5($id.$details->created_at);
        $directory         = Storage::disk('public')->allDirectories();
        foreach($directory as $directories){
            if($directories == $encrypt) {
                $listoutfiles  = Storage::disk('public')->files($encrypt);                              // Listout Files
                foreach($listoutfiles as $values){
                    $split_folder_file = explode('/', $values);           //08d16e9f44699e9334834833c02b7b8e/random.webm
                    $splitted_file     = end($split_folder_file);         //random.webm
                    $explode_filename  = explode('.', $splitted_file);    //explode(random.webm)
                    $explode_name      = $explode_filename[0];            //random
                    $file_extension    = $explode_filename[1];            //webm
                    $file              = url('uploads/'. $encrypt .'/'. $splitted_file);
                    switch( $file_extension ) {
                        case "mp4": $ctype="video/mp4"; break;
                        case "ogg": $ctype="video/ogg"; break;
                        case "webm": $ctype="video/webm"; break;
                        case "mov": $ctype="video/mov"; break;
                        case "wmv": $ctype="video/wmv"; break;
                        case "3gp": $ctype="video/3gp"; break;
                        default:
                    }
                    return response($file, 200)
                        ->header('Content-Type', $ctype);
                }
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RecordingvideoRequest $request)
    {
        $file              = $request->file('userVideo');
        $extension         = $file->getClientOriginalExtension();
        $filename          = str_random(10);
        $id                = Auth::user()->id;
        $details           = User::select('created_at')->find($id);
        $encrypt           = md5($id.$details->created_at);
        $directory         = Storage::disk('public')->allDirectories();
        if(!$directory){                                                //If no dir in a folder create a new dir
            Storage::disk('public')->makeDirectory($encrypt, 0777);                          // Create Directory
            Storage::disk('public')->put($encrypt.'/'.$filename.'.'.$extension,  File::get($file));
        }
        foreach($directory as $directories){
            if($directories == $encrypt) {                                                //If matching hash dir
                Storage::disk('public')->deleteDirectory($directories);                      // Delete Directory
                Storage::disk('public')->makeDirectory($encrypt, 0777);                      // Create Directory
                Storage::disk('public')->put($encrypt.'/'.$filename.'.'.$extension,  File::get($file));
            }
            else{                                                     //If no matching hash dir create a new dir
                Storage::disk('public')->makeDirectory($encrypt, 0777);                      // Create Directory
                Storage::disk('public')->put($encrypt.'/'.$filename.'.'.$extension,  File::get($file));
            }
        }
        return response()->json(['success' => 'Successfully Uploaded']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $id                  = Auth::user()->id;
        $details             = User::select('created_at')->find($id);
        $encrypt             = md5($id.$details->created_at);
        $directory           = Storage::disk('public')->allDirectories();                        // Listout Files
        foreach($directory as $directories)
        {
            if($directories == $encrypt) {                                                //If matching hash dir
                Storage::disk('public')->deleteDirectory($directories);                      // Delete Directory
            }
        }
        return response()->json(['deleted' => 'File deleted']);
    }
}
