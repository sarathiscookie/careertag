<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\TagambitionRequest;
use DB, Input, Storage, File, Auth;
use App\Tag;
use App\Usertag;
use App\User;

class TagambitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $availableTagstitle = Tag::where('tagcategory_id', Input::get('tagcategoryid'))
            ->where('suggestion', 'yes')
            ->select('title_en', 'title_de')
            ->get();
        $availableTagsresult   = array();
        foreach ($availableTagstitle as $availableTagstitles)
        {
            $availableTagsresult[] = $availableTagstitles->title_en;
        }
        $assignedTagstitle = Tag::select('tags.title_de', 'tags.title_en')
            ->join('usertags', 'tags.id', '=', 'usertags.tag_id')
            ->join('users', 'usertags.user_id', '=', 'users.id')
            ->where('users.id', Auth::user()->id)
            ->where('tags.tagcategory_id', Input::get('tagcategoryid'))
            ->get();
        $assignedTagsresult   = array();
        foreach ($assignedTagstitle as $assignedTagstitles)
        {
            $assignedTagsresult[] = $assignedTagstitles->title_en;
        }
        return response()->json(['availableTags' => $availableTagsresult, 'assignedTags' => $assignedTagsresult]);
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
    public function store(TagambitionRequest $request)
    {
        $tag                 = new Tag; //Insert in to tag table
        $usertag             = new Usertag; // Insert in to usertags table
        $tag->tagcategory_id = $request->tagcategoryid;
        $tag->title_de       = $request->tag;
        $tag->title_en       = $request->tag;
        $tag->suggestion     = 'no';
        $tag->created_by     = Auth::user()->id;
        DB::transaction(function() use ($tag, $usertag, $request) {
            $tag->save(); //Insert in to tag table
            $usertag->user_id    = Auth::user()->id;
            $usertag->tag_id     = $tag->id;
            $usertag->save(); //Insert in to usertags table
        });
        return response()->json(['tagambition' => $request->tag, 'id' => $usertag->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(TagambitionRequest $request)
    {
        $selectProfessionFIle = User::select('created_at')
            ->find(Auth::user()->id);
            $encrypt           = md5(Auth::user()->id.$selectProfessionFIle->created_at);
            $directories       = Storage::files($encrypt);                                         // Listout Files
            $pdf               = preg_grep('/\/.*.pdf$/', $directories);
        $filename = array();
        foreach($pdf as $files){
            $split_folder_file = explode('/', $files);            //60e4dda43c442fe610bdbd4a0e5c3a12/19.pdf
            $splitted_file     = end($split_folder_file);         //19.pdf
            $explode_filename  = explode('.', $splitted_file);    //explode(19.pdf)
            $filename[]        = $explode_filename[0];            //19
        }
        if(isset($filename)){
            foreach($filename as $filenames)
            {
                $selectProfessionFIle = Usertag::select('usertags.id', 'tags.title_en')
                    ->join('tags', 'usertags.tag_id', '=', 'tags.id')
                    ->join('users', 'usertags.user_id', '=', 'users.id')
                    ->where('users.id', Auth::user()->id)
                    ->where('tags.tagcategory_id', Input::get('tagcategoryid'))
                    ->where('usertags.id', $filenames)
                    ->get();
                foreach($selectProfessionFIle as $selectProfessionFIles){
                    $filedetails[] = $selectProfessionFIles;
                }
            }
            if(isset($filedetails)){
                return response()->json(['ProfessionFile' => $filedetails]);
            }
        }
    }

    /**
     * For download the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function download(TagambitionRequest $request)
    {
        $selectFile = Usertag::select('users.created_at', 'tags.title_en')
            ->join('tags', 'usertags.tag_id', '=', 'tags.id')
            ->join('users', 'usertags.user_id', '=', 'users.id')
            ->where('users.id', Auth::user()->id)
            ->where('usertags.id', $request->id)
            ->first();
        $encrypt           = md5(Auth::user()->id.$selectFile->created_at);
        $directories       = Storage::files($encrypt);                                         // Listout Files
        $pdf               = preg_grep('/\/.*.pdf$/', $directories);
        foreach($pdf as $files){
            $split_folder_file = explode('/', $files);            //60e4dda43c442fe610bdbd4a0e5c3a12/19.pdf
            $splitted_file     = end($split_folder_file);         //19.pdf
            $userdata          = 'userdata';
            $filenameresult    = storage_path().'/'.$userdata.'/'.$encrypt.'/'.$splitted_file;
        }
        return response()->download($filenameresult, $selectFile->title_en.'.pdf', ['Content-Type' => 'application/pdf']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(TagambitionRequest $request)
    {
        $userTagID     = $request->hiddenid;
        $file          = $request->file('file');
        $filename      = $file->getClientOriginalName();
        $extension     = $file->getClientOriginalExtension();
        if($extension == 'pdf'){
            $userdetails   = Usertag::select('usertags.user_id', 'users.id', 'users.created_at')
                ->join('users', 'usertags.user_id', '=', 'users.id')
                ->find($userTagID);
            $encrypt       = md5($userdetails->id.$userdetails->created_at);
            $directory     = Storage::allDirectories();
            if(!$directory){                                                   //If no dir in a folder create a new dir
                Storage::makeDirectory($encrypt, 0777);
                Storage::put($encrypt.'/'.$userTagID.'.'.$extension,  File::get($file));
            }
            foreach($directory as $directories){                               //If matching hash dir upload a PDF file
                if($directories == $encrypt){
                    Storage::put($encrypt.'/'.$userTagID.'.'.$extension,  File::get($file));
                }
                else{                                                         //If no matching hash dir create a new dir
                    Storage::makeDirectory($encrypt, 0777);
                    Storage::put($encrypt.'/'.$userTagID.'.'.$extension,  File::get($file));
                }
            }
            return response()->json(['filename' => $filename]);
        }
        else{
            return response()->json(['filename' => 'Not a valid file']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(TagambitionRequest $request)
    {
        $selectTagAmbitionId = Usertag::select('usertags.id', 'usertags.user_id', 'users.created_at')
            ->join('tags', 'usertags.tag_id', '=', 'tags.id')
            ->join('users', 'usertags.user_id', '=', 'users.id')
            ->where('tags.title_en', $request->tag)
            ->where('users.id', Auth::user()->id)
            ->where('tags.tagcategory_id', Input::get('tagcategoryid'))
            ->get();
        foreach($selectTagAmbitionId as $selectTagAmbitionIdd)
        {
            $usertagID  = $selectTagAmbitionIdd->id;
            $userID     = $selectTagAmbitionIdd->user_id;
            $created_at = $selectTagAmbitionIdd->created_at;
        }
        $encrypt       = md5($userID.$created_at);
        $directories   = Storage::files($encrypt);                                             // Listout Files
        foreach($directories as $values)
        {
            $split_folder_file = explode('/', $values);           //08d16e9f44699e9334834833c02b7b8e/188.pdf
            $splitted_file     = end($split_folder_file);         //188.pdf
            $explode_filename  = explode('.', $splitted_file);    //explode(188.pdf)
            $explode_name      = $explode_filename[0];            //188
            if ($explode_name == $usertagID) {
                Storage::delete($encrypt . '/' . $splitted_file);
            }
        }
        Usertag::where('id', '=', $usertagID)->delete();
        return response()->json(['tagambitionID' => $usertagID]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroyFileOnly(TagambitionRequest $request)
    {
        $selectTagProfessionalID = Usertag::select('users.created_at')
            ->join('users', 'usertags.user_id', '=', 'users.id')
            ->where('usertags.id', $request->removefile)
            ->where('users.id', Auth::user()->id)
            ->first();
        $encrypt           = md5(Auth::user()->id.$selectTagProfessionalID->created_at);
        $directories       = Storage::files($encrypt);                                         // Listout Files
        $pdf               = preg_grep('/\/.*.pdf$/', $directories);
        foreach($pdf as $files){
            $split_folder_file = explode('/', $files);            //60e4dda43c442fe610bdbd4a0e5c3a12/19.pdf
            $splitted_file     = end($split_folder_file);         //19.pdf
            $explode_filename  = explode('.', $splitted_file);    //explode(19.pdf)
            $filename          = $explode_filename[0];            //19
            if ($filename == $request->removefile) {
                Storage::delete($encrypt . '/' . $splitted_file);
            }
        }
        return response()->json(['fileDeleted' => $request->removefile]);
    }
}
