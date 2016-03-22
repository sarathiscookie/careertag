<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Storage, File, Input, Auth;
use App\User;
use App\Http\Requests\ProfileimageRequest;


class ProfileimageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $id                  = Auth::user()->id;
        $userdetails         = User::select('id', 'created_at')->findOrFail($id);
        $encrypt             = md5($userdetails->id.$userdetails->created_at);
        $directories         = Storage::files($encrypt);                                             // Listout Files
        foreach($directories as $values){
            $split_folder_file = explode('/', $values);           //08d16e9f44699e9334834833c02b7b8e/Profile.png
            $splitted_file     = end($split_folder_file);         //Profile.png
            $explode_filename  = explode('.', $splitted_file);    //explode(Profile.png)
            $explode_name      = $explode_filename[0];            //Profile
            $route             = route('viewprofileimage');
            if ($explode_name == 'Profile') {
                return response()->json(['filename' => $explode_name, 'route' => $route]);
            }
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
    public function store(ProfileimageRequest $request)
    {
        $id                  = Auth::user()->id;
        $details             = User::select('id', 'created_at')->findOrFail($id);

        $tmp				 = explode(',',$_POST['data']);
        $imgdata 			 = base64_decode($tmp[1]);
        $imgname             = explode('.',$request->name);
        $extension			 = strtolower(end($imgname));
        $encrypt             = md5($details->id.$details->created_at);
        $directory           = Storage::allDirectories();

        if(!$directory) {                                                        //If no dir in a folder create a new dir
            Storage::makeDirectory($encrypt, 0777);                                                  // Create Directory
            Storage::put($encrypt . '/' . 'Profile.' . $extension, $imgdata);
        }
        foreach($directory as $directories){
            if($directories == $encrypt){                                       //If matching hash dir upload image file
                $listoutfiles  = Storage::files($encrypt);                                             // Listout Files
                foreach($listoutfiles as $values){
                    $split_folder_file = explode('/', $values);           //08d16e9f44699e9334834833c02b7b8e/Profile.png
                    $splitted_file     = end($split_folder_file);         //Profile.png
                    $explode_filename  = explode('.', $splitted_file);    //explode(Profile.png)
                    $explode_name      = $explode_filename[0];            //Profile
                    if ($explode_name == 'Profile') {
                        Storage::delete($encrypt . '/' . $splitted_file);
                    }
                }
                Storage::put($encrypt.'/'.'Profile.'.$extension,  $imgdata);
            }
            else{                                                             //If no matching hash dir create a new dir
                Storage::makeDirectory($encrypt, 0777);                                              // Create Directory
                Storage::put($encrypt.'/'.'Profile.'.$extension,  $imgdata);
            }
        }
        $response = array(
            "status" 		=> "success",
            "url" 			=> "/viewProfile/image", //$dir.$filename.'?'.time(), //added the time to force update when editting multiple times
            "filename" 		=> "/viewProfile/image"
        );

        return response($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show()
    {
        $id                  = Auth::user()->id;
        $details             = User::select('id', 'created_at')->findOrFail($id);
        $encrypt             = md5($details->id.$details->created_at);
        $directories         = Storage::files($encrypt);                                             // Listout Files
        foreach($directories as $values){
            $split_folder_file = explode('/', $values);           //08d16e9f44699e9334834833c02b7b8e/Profile.png
            $splitted_file     = end($split_folder_file);         //Profile.png
            $explode_filename  = explode('.', $splitted_file);    //explode(Profile.png)
            $explode_name      = $explode_filename[0];            //Profile
            $file_extension    = $explode_filename[1];            //png
            if ($explode_name == 'Profile') {
                switch( $file_extension ) {
                    case "gif": $ctype="image/gif"; break;
                    case "png": $ctype="image/png"; break;
                    case "jpeg":
                    case "jpg": $ctype="image/jpeg"; break;
                    default:
                }
                $file = Storage::disk('local')->get($encrypt.'/'.$splitted_file);
            }
            else{
                $default_filename = 'profile.png';
                $explode_filename  = explode('.', $default_filename); //explode(Profile.png)
                $file_extension    = $explode_filename[1];            //png
                switch( $file_extension ) {
                    case "gif": $ctype="image/gif"; break;
                    case "png": $ctype="image/png"; break;
                    case "jpeg":
                    case "jpg": $ctype="image/jpeg"; break;
                    default:
                }
                $file  = public_path().'/'.$default_filename;
            }
        }
        return response($file, 200)
            ->header('Content-Type', $ctype);
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
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
        $id = Auth::user()->id;
        $details             = User::select('id', 'created_at')->findOrFail($id);
        $encrypt             = md5($details->id.$details->created_at);
        $directories         = Storage::files($encrypt);                                             // Listout Files
        foreach($directories as $values)
        {
          $split_folder_file = explode('/', $values);           //08d16e9f44699e9334834833c02b7b8e/Profile.png
          $splitted_file     = end($split_folder_file);         //Profile.png
          $explode_filename  = explode('.', $splitted_file);    //explode(Profile.png)
          $explode_name      = $explode_filename[0];            //Profile
          if ($explode_name == 'Profile') {
              Storage::delete($encrypt . '/' . $splitted_file);
          }
        }
    }
}
