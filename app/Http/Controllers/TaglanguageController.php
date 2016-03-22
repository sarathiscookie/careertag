<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Language;
use App\Userlanguage;
use App\Http\Requests\TaglanguageRequest;
use Input, Auth;

class TaglanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(TaglanguageRequest $request)
    {
        $user_id = Auth::user()->id;
        $results = '';
        $availableTaglanguage = Language::select('id', 'title_de', 'title_en')
            ->where('title_de', 'LIKE', '%'.$request->q.'%')
            ->whereNotIn('id',Userlanguage::where('user_id',$user_id)->get()->lists('language_id'))
            ->groupBy('title_de')
            ->get();
        foreach ($availableTaglanguage as $row) {
            $results[] = array("id" => $row->id,"text" => $row->title_de );
        }
        if($results!='')
            return response()->json($results);
        else
            return response()->json(array());
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
    public function store(TaglanguageRequest $request)
    {
        $count = Userlanguage::where('user_id',Auth::user()->id)->where('language_id',$request->userLangLangID)->count();
        if($count==0) {
            $usertaglanguage = new Userlanguage; // Insert in to usertlanguages table
            $usertaglanguage->user_id = Auth::user()->id;
            $usertaglanguage->language_id = $request->userLangLangID;
            $usertaglanguage->ranking = $request->rate;
            $usertaglanguage->save(); //Insert in to usertlanguages table

            $userlangnamerank = Userlanguage::select('languages.title_en', 'languages.title_de', 'userlanguages.ranking', 'userlanguages.id')
                ->join('languages', 'userlanguages.language_id', '=', 'languages.id')
                ->join('users', 'userlanguages.user_id', '=', 'users.id')
                ->where('users.id', Auth::user()->id)
                ->where('languages.id', $request->userLangLangID)
                ->get();

            return response()->json(['userLangNameRank' => $userlangnamerank]);
        }
        else{
            return response()->json(array());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show()
    {
        $selectLanguageRankingTagId = Userlanguage::select('userlanguages.id', 'userlanguages.ranking', 'languages.title_en', 'languages.title_de')
            ->join('languages', 'userlanguages.language_id', '=', 'languages.id')
            ->join('users', 'userlanguages.user_id', '=', 'users.id')
            ->where('users.id', Auth::user()->id)
            ->groupBy('languages.title_de')
            ->get();
        return response()->json(['selectLanguageRankingTagId' => $selectLanguageRankingTagId]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(TaglanguageRequest $request)
    {
        Userlanguage::where('id', $request->rateUserLangID)
               ->where('user_id', Auth::user()->id)
               ->update(['ranking' => $request->rate]);

        $update_language_name_rank = Userlanguage::select('userlanguages.id', 'languages.title_en', 'languages.title_de', 'userlanguages.ranking')
            ->join('languages', 'userlanguages.language_id', '=', 'languages.id')
            ->join('users', 'userlanguages.user_id', '=', 'users.id')
            ->where('users.id', Auth::user()->id)
            ->where('userlanguages.id', $request->rateUserLangID)
            ->get();
        return response()->json(['updateUserLangNameRank' => $update_language_name_rank]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(TaglanguageRequest $request)
    {
        Userlanguage::where('id', '=', $request->delUserLangID)->delete();
        return response()->json(['successDelUserLangID' => $request->delUserLangID]);
    }
}
