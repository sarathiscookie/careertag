<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Usersearchcity;
use App\Http\Requests\UsercityRequest;
use Auth;

class SearchcityController extends Controller
{

    /**
     * Save google places api return strings - city,state, country and postal for user experience
     * @param UsercityRequest $request
     */
    public function saveCity(UsercityRequest $request)
    {
        $user_id  = Auth::user()->id;
        $id       = $request ->id;
        $row      = explode("_",$id);
        $row_id   = $row[1];

        if($row_id==0) {
            $insert = Usersearchcity::create([
                'user_id' => $user_id,
                'search_city' => ($request ->locality=='')?$request ->searched:$request ->locality,
                'search_state' => $request ->administrative_area_level_1,
                'search_country' => $request ->country,
                'search_postal' => $request ->postal_code,
                'search_string' => $request ->searched
            ]);
            $newrow  = $insert->id;
            $results = $this->getNextRow($newrow);
            $results['mode'] ="add";
            print json_encode($results);
        }
        else {
            Usersearchcity::where('id', $row_id)->update([
                'search_city' => ($request ->locality=='')?$request ->searched:$request ->locality,
                'search_state' => $request ->administrative_area_level_1,
                'search_country' => $request ->country,
                'search_postal' => $request ->postal_code,
                'search_string' => $request ->searched
            ]);
            $results = $this->getNextRow($row_id);
            print json_encode($results);
        }
    }

    /**
     * @param $row_id
     * @return array|string
     */
    private function getNextRow($row_id)
    {
        $user_id = Auth::user()->id;
        $count   = Usersearchcity::where('user_id', $user_id)->count();

        $results = '';
        if($row_id>0)
        {
            $cities       = Usersearchcity::find($row_id);
            if(($cities->search_city !="" || $cities->search_country !=''))
            {
                $delete_action = '<a id="delcity_'.$row_id.'" class="ct-sc-delete"><i class="fa fa-trash"></i></a>';
                if($count<=4) {
                    $input_city = '<input type="hidden" class="ct-sc-input" id="srchrowid_0" value="0"><input id="srchcity_'.($count+1).'" class="tag ct-sc-input srchcity-api" placeholder="City"></br>';
                    $results = array("searchcity" => $input_city, "action" => $delete_action, "action_for" => $row_id);
                }
                elseif($count==5)
                {
                    $results = array("searchcity" => "","action" => $delete_action, "action_for" => $row_id);
                }
            }
        }
        return $results;
    }

    /**
     * @param UsercityRequest $request
     */
    public function delete(UsercityRequest $request)
    {
        $user_id = Auth::user()->id;
        $id      = $request->field;
        Usersearchcity::where('id', $id)->delete();
        $count   = Usersearchcity::where('user_id', $user_id)->count();
        $results = array();
        if($count==0)
        {
            $input_city     = '<input type="hidden" class="ct-sc-input" id="srchrowid_0" value="0"><input id="srchcity_0" class="tag ct-sc-input srchcity-api" placeholder="City"></br>';
            $results["searchcity"] = $input_city;
        }
        print json_encode($results);
    }



}
