<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Tag;
use App\Usertag;
use App\Http\Requests\TagRequest;
use DB, Input, Auth;

class TagController extends Controller
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
    public function store(TagRequest $request)
    {
        $rowid =0;
        $tags = Tag::where(['tagcategory_id' => $request->tagcategoryid, 'title_de'=>$request->tag])
                ->orWhere(['tagcategory_id' => $request->tagcategoryid, 'title_en' => $request->tag])
                ->select('id')
                ->first();
        if($tags)
         $rowid =$tags->id;

        $tag                 = new Tag; //Insert in to tag table
        $usertag             = new Usertag; // Insert in to usertags table
        $tag->tagcategory_id = $request->tagcategoryid;
        $tag->title_de       = $request->tag;
        $tag->title_en       = $request->tag;
        $tag->suggestion     = 'no';
        $tag->created_by     = Auth::user()->id;
        DB::transaction(function() use ($tag, $usertag, $request, $rowid) {
            if($rowid==0) {
                $tag->save(); //Insert in to tag table
            }
        $usertag->user_id    = Auth::user()->id;
        $usertag->tag_id     = ($tag->id>0)?$tag->id:$rowid;
        $usertag->save(); //Insert in to usertags table
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
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
    public function update(TagRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(TagRequest $request)
    {
        $selectTagInterestId = Usertag::select('usertags.id')
            ->join('tags', 'usertags.tag_id', '=', 'tags.id')
            ->join('users', 'usertags.user_id', '=', 'users.id')
            ->where('tags.title_en', $request->tag)
            ->where('users.id', Auth::user()->id)
            ->where('tags.tagcategory_id', Input::get('tagcategoryid'))
            ->get();
        $selectTagInterestIdresult   = array();
        foreach ($selectTagInterestId as $selectTagInterestIdd)
        {
            $selectTagInterestIdresult[] = $selectTagInterestIdd->id;
        }
        Usertag::where('id', '=', $selectTagInterestIdresult)->delete();
    }
}
