<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Abilitytest;
use App\Userabilitytest;
use App\Http\Requests\AbilitytestRequest;
use Auth, DB;

class AbilitytestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $abilitytests = Abilitytest::paginate(1);
        return view('test.abilitytestmodal', ['abilitytests' => $abilitytests]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paginationindex()
    {
        $abilitytests = Abilitytest::paginate(1);
        $data         = view('test.ajaxabilitytestmodal')->with('abilitytests', $abilitytests)->render();
        return response()->json($data);
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
    public function store(AbilitytestRequest $request)
    {
        $alreadyExistAnswer = Userabilitytest::select('id')
            ->where('abilitytest_id', $request->abilityquestionid)
            ->where('user_id', Auth::user()->id)
            ->first();
        $abilityAnswer                 = new Userabilitytest;
        $abilityAnswer->user_id        = Auth::user()->id;
        $abilityAnswer->abilitytest_id = $request->abilityquestionid;
        $abilityAnswer->points         = $request->abilityanswer;
        if($alreadyExistAnswer === null){
            $abilityAnswer->save();                                                                 //Insert in to table
        }
        else{
            DB::transaction(function() use ($abilityAnswer, $alreadyExistAnswer, $request) {
                Userabilitytest::where('abilitytest_id', $request->abilityquestionid)
                    ->where('user_id', Auth::user()->id)
                    ->where('id', $alreadyExistAnswer->id)
                    ->update(['points' => $request->abilityanswer]);
            });
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paginationstore(AbilitytestRequest $request)
    {
        $alreadyExistAnswer = Userabilitytest::select('id')
            ->where('abilitytest_id', $request->abilityquestionid)
            ->where('user_id', Auth::user()->id)
            ->first();
        $abilityAnswer                 = new Userabilitytest;
        $abilityAnswer->user_id        = Auth::user()->id;
        $abilityAnswer->abilitytest_id = $request->abilityquestionid;
        $abilityAnswer->points         = $request->abilityanswer;
        if($alreadyExistAnswer === null){
            $abilityAnswer->save();                                                                 //Insert in to table
        }
        else{
            DB::transaction(function() use ($abilityAnswer, $alreadyExistAnswer, $request) {
                Userabilitytest::where('abilitytest_id', $request->abilityquestionid)
                    ->where('user_id', Auth::user()->id)
                    ->where('id', $alreadyExistAnswer->id)
                    ->update(['points' => $request->abilityanswer]);
            });
        }
        $count   = Userabilitytest::where('user_id', Auth::user()->id)->count();
        return response()->json(['anscount' => $count]);
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
        Userabilitytest::where('user_id',Auth::user()->id)->delete();
    }
}
