<?php

namespace App\Http\Controllers\BackendControllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Tag;
use Input;
use App\Http\Requests\BackendRequests\GettagsRequest;

class GettagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $selectTags = Tag::select('tags.id','tags.tagcategory_id', 'tags.title_de', 'tags.title_en', 'tags.suggestion', 'tags.created_by', 'tags.created_at', 'users.firstname', 'users.lastname', 'users.alias')
            ->leftJoin('users', 'tags.created_by', '=', 'users.id')
            ->where('tagcategory_id', Input::get('tagcategoryid'))
            ->orderBy('tags.id', 'asc')
            ->get();
        return response()->json(['Tags' => $selectTags]);
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
    public function update(GettagsRequest $request)
    {
        if($request->toggleSuggestion == 'yes')
        {
            $updateTags = Tag::where('id', $request->toggleTags)
                ->update(['suggestion' => 'no']);
        }
        else{
            $updateTags = Tag::where('id', $request->toggleTags)
                ->update(['suggestion' => 'yes']);
        }
        return response()->json(['UpdatedToggleTags' => $updateTags]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
