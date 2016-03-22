<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use App\Http\Requests\UserexperienceRequest;
use App\Userexperience;
use App\Usersearchcity;
use App\Userabilitytest;
use Illuminate\Support\Facades\DB;
use Storage;

class PublishprofileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session()->put('userID', Auth::user()->id);
        $confirmDetails   = User::select('alias')
            ->where('id', session()->get('userID'))
            ->first();
        return redirect($confirmDetails->alias.'/edit');
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
     * @return \Illuminate\Http\Response
     */
    public function store($userID)
    {
        $publishDate   = User::select('confirmed_at')
            ->where('id', $userID)
            ->first();
        if ( $publishDate->confirmed_at === NULL ){
            User::where('id', $userID)
                ->update(['confirmed_at' => date("Y-m-d H:i:s")]);
        }
        return view('pages.publishprofilesuccessfully')->with('publishDate', $publishDate->confirmed_at);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        session()->put('userID', Auth::user()->id);
        $userDetails   = User::select('confirmed_at as confirmTime','firstname','lastname','birthdate','city',
            'search_string','email','phone','webpage')
            ->where('id', session()->get('userID'))
            ->first()->toArray();

        $userDetails['city']         = ($userDetails['search_string'] != '') ? $userDetails['search_string'] : $userDetails['city'];
        $userDetails['birthdate']    = ($userDetails['birthdate'] != '0000-00-00')?date('d.m.Y', strtotime($userDetails['birthdate'])):'';

        $block      = $this->getPrivacySettings(Auth::user()->id);
        $experience = $this->getExperience();
        $searchcity = $this->getSearchCity();
        $usergraph  = $this->getGraphData();
        $userthumb  = $this->getProfileImage(Auth::user()->id);
        return view('test.frontend')->with($userDetails)->with($block)->with($experience)->with($searchcity)->with($usergraph)->with(['userthumb'=>$userthumb]);
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

    /**
     * get privacy settings
     * @param $user_id
     * @return array
     */
    private function getPrivacySettings($userID)
    {
        $block = array();
        $settings   = User::select('privacy_image','privacy_tag_firstname','privacy_tag_lastname',
            'privacy_tag_birthday','privacy_tag_city','privacy_tag_mail','privacy_tag_phone','privacy_cat_search',
            'privacy_cat_languages','privacy_cat_ambition','privacy_cat_experience','privacy_cat_company','privacy_cat_interests')
            ->where('id', $userID)
            ->first()->toArray();

        foreach($settings as $name =>$value)
        {
            $class = 'ct-privacy-tag';
            if (strpos($name, "_cat_") !== false)
                $class = 'ct-privacy-cat';

            $block[$name] = '<img src="/assets/img/privacy-' . $value . '.png" id="' . $name . '" alt="' . $value . '" class="ct-privacy ' . $class . '">';
        }
        return $block;
    }

    /**
     * list all rows on loading
     * @return array
     */
    public function getExperience()
    {
        $user_id     = Auth::user()->id;
        $experiences = Userexperience::where('user_id', $user_id)
            ->select('userexperiences.id','title_en as title', 'years','company','city','country','search_string')
            ->leftjoin('experiences AS exp','exp.id','=','userexperiences.title')
            ->orderBy('id','desc')
            ->get();
        $input_exp     = '';
        $input_company = '';
        $results       = array();
        $filled        = 0;
        $rows          = 0;
        $tot_years     = 0;
        foreach ($experiences as $experience) {
            $delete_action ='';
            if($experience->id >0) {
                $title = ($experience->title != '') ? $experience->title : '';
                $years = ($experience->years > 0) ? $experience->years : '';
                $company = ($experience->company != '') ? $experience->company : '';
                $city = ($experience->city != '') ? $experience->city : '';
                $search = ($experience->search_string != '') ? $experience->search_string : $city;
                if($title!='' && $years > 0 && $company != "" && ($city !="" || $experience->country)) {
                    $filled++;
                    $tot_years = $tot_years+$years;
                    $delete_action = '<a id="'.$experience->id.'" class="ct-ue-delete"><i class="fa fa-trash"></i></a>';
                }
                $input_exp .= '<input type="hidden" class="ct-ue-input" id="rowid_' . $experience->id . '" value="' . $experience->id . '"><select id="title_' . $experience->id . '" class="tag ct-ue-select" style="width: 70%"><option value="' . $experience->id . '" selected="selected">' . (($title=='')?"--Position--":$title) . '</option></select> <input type="number" min="0.5" max="100" step="0.5" id="years_' . $experience->id . '" class="tag ct-ue-input" style="width:20%" placeholder="Jahre" value="' . $years . '"><br>';
                $input_company .= '<input id="company_' . $experience->id . '" class="tag ct-ue-input" placeholder="Firma" value="' . $company . '"> <input id="city_' . $experience->id . '" class="tag ct-ue-input city-api" placeholder="Stadt" value="' . $search . '">'.$delete_action.'</br>';
                $rows++;
            }
        }
        if(($rows<=4 && $filled==$rows )) {
            $input_exp     = '<input type="hidden" class="ct-ue-input" id="rowid_0" value="0"><select id="title_'.($rows+1).'" class="tag ct-ue-select" style="width: 70%"><option value="0">--Position--</option></select> <input id="years_0" type="number" min="0.5" max="100" step="0.5" class="tag ct-ue-input" placeholder="Jahre" style="width:20%"> <br>'.$input_exp;
            $input_company = '<input id="company_0" class="tag ct-ue-input" placeholder="Firma"> <input id="city_'.($rows+1).'" class="tag ct-ue-input city-api" placeholder="Stadt"></br>'.$input_company;
        }
        if($rows==0) {
            $input_exp     = '<input type="hidden" class="ct-ue-input" id="rowid_0" value="0"><select id="title_0" class="tag ct-ue-select" style="width: 70%"><option value="0">--Position--</option></select> <input id="years_0" type="number" min="0.5" max="100" step="0.5" class="tag ct-ue-input" placeholder="Jahre" style="width:20%"><br>';
            $input_company = '<input id="company_0" class="tag ct-ue-input" placeholder="Firma"> <input id="city_0" class="tag ct-ue-input city-api" placeholder="Stadt"></br>';
            $results["experience"] = $input_exp;
            $results["company"]    = $input_company;
            $results["expyears"]   = $tot_years;
            return $results;
        }
        else {
            $results["experience"] = $input_exp;
            $results["company"]    = $input_company;
            $results["expyears"]   = $tot_years;
            return $results;
        }
    }

    /**
     * get list of cities that user added for search
     * @return array
     */
    protected function getSearchCity()
    {
        $user_id     = Auth::user()->id;
        $cities      = Usersearchcity::where('user_id', $user_id)
            ->select('id','search_city','search_state','search_country','search_postal','search_string')
            ->orderBy('id','desc')
            ->get();
        $input_city    = '';
        $results       = array();
        $filled        = 0;
        $rows          = 0;
        foreach ($cities as $usercity) {
            $delete_action ='';
            if($usercity->id >0) {
                $city = ($usercity->search_city != '') ? $usercity->search_city : '';
                $search = ($usercity->search_string != '') ? $usercity->search_string : $city;
                if($city !="" || $usercity->search_country) {
                    $filled++;
                    $delete_action = '<a id="delcity_'.$usercity->id.'" class="ct-sc-delete"><i class="fa fa-trash"></i></a>';
                }
                $input_city .= '<input type="hidden" class="ct-sc-input" id="srchrowid_' . $usercity->id . '" value="' . $usercity->id . '"><input id="srchcity_' . $usercity->id . '" class="tag ct-sc-input srchcity-api" placeholder="City" value="' . $search . '">'.$delete_action.'</br>';
                $rows++;
            }
        }
        if(($rows<=4 && $filled==$rows )) {
            $input_city     = '<input type="hidden" class="ct-sc-input" id="srchrowid_0" value="0"><input id="srchcity_'.($rows+1).'" class="tag ct-sc-input srchcity-api" placeholder="City"></br>'.$input_city;
        }
        if($rows==0) {
            $input_city     = '<input type="hidden" class="ct-sc-input" id="srchrowid_0" value="0"><input id="srchcity_0" class="tag ct-sc-input srchcity-api" placeholder="City"></br>';
            $results["searchcity"] = $input_city;
            return $results;
        }
        else {
            $results["searchcity"] = $input_city;
            return $results;
        }
    }

    /**
     * get Graph data
     * @return array
     */
    public function getGraphData()
    {
        $target  = [1=>'presentation', 2=>'conception' ,3=>'management', 4=>'creativity'];
        $user_id = Auth::user()->id;
        $count   = Userabilitytest::where('user_id', $user_id)->count();
        $results   = array();
        $abilitytestbutton = 'Balken selber positionieren <strong>-oder-</strong> <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModal" data-id="" id="abilitytest">Ability Test</button>';
        if($count==20) {
            $abilitytestbutton = '<button type="button" class="btn btn-sm btn-primary" id="del_abilitytest">Ability Test löschen</button>';
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
            $rank1 = $graph_ranking->graph_1 * 25;
            $rank2 = $graph_ranking->graph_2 * 25;
            $rank3 = $graph_ranking->graph_3 * 25;
            $rank4 = $graph_ranking->graph_4 * 25;
            $results = [$target[1] => $rank1, $target[2] => $rank2, $target[3] => $rank3, $target[4] => $rank4];
        }
        $results['abilitytestbutton'] = $abilitytestbutton;
        return $results;
    }

    protected function getProfileImage($id)
    {
        $userdetails         = User::select('id', 'alias','created_at')->findOrFail($id);
        $image[]                = '';
        $encrypt             = md5($userdetails->id.$userdetails->created_at);
        $directories         = Storage::files($encrypt);                                             // Listout Files
        foreach($directories as $values){
            $split_folder_file = explode('/', $values);           //08d16e9f44699e9334834833c02b7b8e/Profile.png
            $splitted_file     = end($split_folder_file);         //Profile.png
            $image             = explode('.', $splitted_file);    //explode(Profile.png)
        }

        return $image[0];
    }
}
