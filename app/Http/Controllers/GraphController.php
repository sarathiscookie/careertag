<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\GraphRequest;
use App\User;
use App\Userabilitytest;
use Auth;
use Illuminate\Support\Facades\DB;

class GraphController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $graph_ranking = User::select('graph_1', 'graph_2', 'graph_3', 'graph_4')
            ->find(Auth::user()->id);
        return response()->json( ['graph_ranking' => $graph_ranking] );
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
    public function store(GraphRequest $request)
    {
        User::where('id', Auth::user()->id)
            ->update(['graph_1' => $request->graph1, 'graph_2' => $request->graph2, 'graph_3' => $request->graph3, 'graph_4' => $request->graph4]);
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
    public function destroy($id)
    {
        //
    }
    public function graphData()
    {
        $target  = [1=>'bar1', 2=>'bar2' ,3=>'bar3', 4=>'bar4'];
        $user_id = Auth::user()->id;
        $count   = Userabilitytest::where('user_id', $user_id)->count();
        $results   = array();
        $abilitytest_btn = 'Balken selber positionieren <strong>-oder-</strong> <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModal" data-id="" id="abilitytest">Ability Test</button>';
        if($count==20) {
            $abilitytest_btn = '<button type="button" class="btn btn-sm btn-primary" id="del_abilitytest">Delete Ability Test</button>';
            $userability = Userabilitytest::where('user_id', $user_id)
                ->select(DB::raw('sum(points) as rank') , 'target')
                ->leftjoin('abilitytests', 'abilitytests.id', '=', 'userabilitytests.abilitytest_id')
                ->groupBy('abilitytests.target')
                ->get();
            foreach($userability as $graph)
            {
                $percentage = round(($graph->rank/15)*100,0);
                $results[$target[$graph->target]] = $percentage;
            }
        }
        else
        {
            $graph_ranking = User::select('graph_1', 'graph_2', 'graph_3', 'graph_4')->find(Auth::user()->id);
            $results = [
                $target[1] => $graph_ranking->graph_1 * 25,
                $target[2] => $graph_ranking->graph_2 * 25,
                $target[3] => $graph_ranking->graph_3 * 25,
                $target[4] => $graph_ranking->graph_4 * 25
            ];
        }
        $results['buttonhtml'] = $abilitytest_btn;
        return response()->json( $results );
    }
}
