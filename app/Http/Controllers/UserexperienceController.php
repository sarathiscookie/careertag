<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserexperienceRequest;
use App\User;
use App\Userexperience;
use App\Experience;
use Auth;
use Illuminate\Support\Facades\DB;

class UserexperienceController extends Controller
{
    /**
     * update user experience
     * @param UserexperienceRequest $request
     */
    public function update(UserexperienceRequest $request)
    {
        $user_id = Auth::user()->id;
        $value   = $request ->value;
        $identifier =explode("_",$request ->field);
        $column = $identifier[0];
        $row_id = $identifier[1];

        if($row_id==0) {
            $insert = Userexperience::create(['user_id' => $user_id, $column => $value]);
            $newrow = $insert->id;
            print json_encode(array('id'=>$newrow));
        }
        else {
            Userexperience::where('id', $row_id)->update([$column => $value]);
            $results = $this->getNextRow($row_id);
            print json_encode($results);
        }


    }

    /**
     * Check conditions and return a new html row
     * @param $row_id
     * @return array|string
     */
    private function getNextRow($row_id)
    {
        $user_id = Auth::user()->id;
        $count   = Userexperience::where('user_id', $user_id)->count();

        $results = '';
        $tot_years = $this->getTotalExperience();
        if($row_id>0)
        {
            $experiences       = Userexperience::find($row_id);
            if($experiences->title > 0 && $experiences->years > 0 && $experiences->company != "" && ($experiences->city !="" || $experiences->country !=''))
            {
                $delete_action = '<a id="'.$row_id.'" class="ct-ue-delete"><i class="fa fa-trash"></i></a>';
                if($count<=4) {
                    $input_exp = '<input type="hidden" class="ct-ue-input" id="rowid_0" value="0"><select id="title_'.($count+1).'" class="tag ct-ue-select" style="width: 70%"><option value="0">--Position--</option></select> <input id="years_0" class="tag ct-ue-input" placeholder="Jahre" type="number" min="0.5" max="100" step="0.5" style="width:20%"></br>';
                    $input_company = '<input id="company_0" class="tag ct-ue-input" placeholder="Firma"> <input id="city_'.($count+1).'" class="tag ct-ue-input city-api" placeholder="Stadt"></br>';
                    $results = array("experience" => $input_exp, "company" => $input_company, "action" => $delete_action, "action_for" => $row_id, "exp_years" => $tot_years);
                }
                elseif($count==5)
                {
                    $results = array("experience" => "", "company" => "","action" => $delete_action, "action_for" => $row_id, "exp_years" => $tot_years);
                }
            }
        }
        return $results;
    }

    /**
     * delete user experience for requested ID
     * @param UserexperienceRequest $request
     */
    public function delete(UserexperienceRequest $request)
    {
        $user_id = Auth::user()->id;
        $id      = $request->field;
        Userexperience::where('id', $id)->delete();
        $count   = Userexperience::where('user_id', $user_id)->count();
        $tot_years = $this->getTotalExperience();
        $results = array();
        if($count==0)
        {
            $input_exp     = '<input type="hidden" class="ct-ue-input" id="rowid_0" value="0"><select id="title_0" class="tag ct-ue-select" style="width: 70%"><option value="0">--Position--</option></select> <input id="years_0" class="tag ct-ue-input" placeholder="Jahre" type="number" min="0.5" max="100" step="0.5" style="width:20%"></br>';
            $input_company = '<input id="company_0" class="tag ct-ue-input" placeholder="Firma"> <input id="city_0" class="tag ct-ue-input city-api" placeholder="Stadt"></br>';
            $results["experience"] = $input_exp;
            $results["company"]    = $input_company;
        }
        $results['exp_years'] =$tot_years;
        print json_encode($results);
    }

    /**
     * Ajax- getting preset values for Position select2
     * @param UserexperienceRequest $request
     */
    public function getSuggestion(UserexperienceRequest $request){
        $results = '';
        $experience = Experience::where('title_en', 'LIKE', '%'.$request->q.'%')
            ->select('title_en', 'id')
            ->orderBy('title_en')
            ->get();
        foreach ($experience as $row) {
                $results[] = array("id" => $row->id,"text" => $row->title_en );
        }
        if($results!='')
            print json_encode($results);
        else
            print json_encode(array());
    }

    /**
     * Save google places api return strings - city,state, country and postal for user experience
     * @param UserexperienceRequest $request
     */
    public function saveCity(UserexperienceRequest $request)
    {
        $user_id  = Auth::user()->id;
        $id       = $request ->id;
        $row      = explode("_",$id);
        $row_id   = $row[1];

        if($row_id==0) {
            $insert = Userexperience::create([
                'user_id' => $user_id,
                'city' => ($request ->locality=='')?$request ->searched:$request ->locality,
                'state' => $request ->administrative_area_level_1,
                'country' => $request ->country,
                'postal' => $request ->postal_code,
                'search_string' => $request ->searched
            ]);
            $newrow = $insert->id;
            print json_encode(array('id'=>$newrow));
        }
        else {
            Userexperience::where('id', $row_id)->update([
                'city' => ($request ->locality=='')?$request ->searched:$request ->locality,
                'state' => $request ->administrative_area_level_1,
                'country' => $request ->country,
                'postal' => $request ->postal_code,
                'search_string' => $request ->searched
            ]);
            $results = $this->getNextRow($row_id);
            print json_encode($results);
        }
    }

    /**
     * get user total experience
     * @return mixed
     */
    protected function getTotalExperience()
    {
        $user_id = Auth::user()->id;
        $years = DB::table('userexperiences')
            ->where('user_id',$user_id)
            ->where('title','>',0)
            ->where('years','>',0)
            ->where('city','<>',"")
            ->where('company','<>',"")
            ->sum('years');
        return $years;
    }
}
