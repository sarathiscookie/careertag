<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Tag;
use App\Language;
use App\Userexperience;
use App\Usersearchcity;
use App\Userprofession;
use App\Userabilitytest;
use Auth;
use Storage, File;
use Illuminate\Support\Facades\DB;

class ShareuserprofileController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getVideo()
    {
        /*$id                = Auth::user()->id;
        $details           = User::select('created_at')->find($id);
        $encrypt           = md5($id.$details->created_at);
        $directory         = Storage::disk('public')->allDirectories();
        foreach($directory as $directories){
            if($directories == $encrypt) {
                $listoutfiles  = Storage::disk('public')->files($encrypt);                              // Listout Files
                foreach($listoutfiles as $values){
                    $split_folder_file = explode('/', $values);           //08d16e9f44699e9334834833c02b7b8e/random.webm
                    $splitted_file     = end($split_folder_file);         //random.webm
                    if($splitted_file){
                        $videoFile              = url('uploads/'. $encrypt .'/'. $splitted_file);
                        return $videoFile;
                    }
                }
            }
        }*/
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
    public function show($alias)
    {
        //preg_match('#^[a-z]+\.[a-z]+\_[0-9]{3}$#', "sarath.ts_993"); Not allowed special characters in firstname lastname and rand
        //preg_match('/^(.*)\.(.*)\_\d{3}$/', "sarath.ts_993"); It allowed special characters in firstname and lastname
        if (preg_match('/^(.*)\.(.*)\_\d{3}$/', $alias)) {
            $getUserDetails = User::select('id', 'firstname', 'lastname', 'birthdate', 'city', 'email', 'phone', 'webpage', 'created_at' )
                ->where('alias', $alias)
                ->first();
            if ($getUserDetails) {
                $encrypt           = md5($getUserDetails->id.$getUserDetails->created_at);
                $directory         = Storage::disk('public')->allDirectories();
                $file ='';
                $videoType = '';
                foreach($directory as $directories){
                    if($directories == $encrypt) {
                        $listoutfiles  = Storage::disk('public')->files($encrypt);                     // Listout Files
                        foreach($listoutfiles as $values){
                            $split_folder_file = explode('/', $values);   //08d16e9f44699e9334834833c02b7b8e/random.webm
                            $splitted_file     = end($split_folder_file); //random.webm
                            $explode_filename  = explode('.', $splitted_file);    //explode(random.webm)
                            $explode_name      = $explode_filename[0];            //random
                            $file_extension    = $explode_filename[1];            //webm
                            $file              = url('uploads/'. $encrypt .'/'. $splitted_file);
                            switch( $file_extension ) {
                                case "mp4": $ctype="video/mp4"; break;
                                case "ogg": $ctype="video/ogg"; break;
                                case "webm": $ctype="video/webm"; break;
                                case "mov": $ctype="video/mov"; break;
                                case "wmv": $ctype="video/wmv"; break;
                                case "3gp": $ctype="video/3gp"; break;
                                default:
                            }
                            if(isset($splitted_file)){
                                $file              = url('uploads/'. $encrypt .'/'. $splitted_file);
                                $videoType         = $ctype;
                            }
                            else{
                                $file              = '';
                                $videoType         = '';
                            }
                        }
                    }
                }
                $getUserTags = Tag::select('tags.title_en', 'tags.tagcategory_id')
                    ->join('usertags', 'tags.id', '=', 'usertags.tag_id')
                    ->where('usertags.user_id', $getUserDetails->id)
                    ->get();
                $getUserLanguage = Language::select('languages.title_de', 'userlanguages.ranking')
                    ->join('userlanguages', 'languages.id', '=', 'userlanguages.language_id')
                    ->where('userlanguages.user_id', $getUserDetails->id)
                    ->groupBy('languages.title_de')
                    ->get();
                $getUserexperience = Userexperience::select('title_en as title','years','company','city')
                    ->leftjoin('experiences as exp', 'exp.id', '=', 'userexperiences.title')
                    ->where('user_id', $getUserDetails->id)
                    ->get();
                $getUserSearchCities   = Usersearchcity::select('search_city','search_string')
                    ->where('user_id', $getUserDetails->id)
                    ->get();
                $getUserProfession = Userprofession::select('userprofessions.grade', 'experiences.title_en as subject', 'graduations.title_en as graduation')
                    ->leftjoin('experiences', 'userprofessions.experience_id', '=', 'experiences.id')
                    ->join('graduations', 'userprofessions.graduation_id', '=', 'graduations.id')
                    ->where('userprofessions.user_id', $getUserDetails->id)
                    ->orderBy('graduations.title_en')
                    ->get();

                $getPrivacy  = $this->getPrivacySettings($getUserDetails->id);
                $profile_img = $this->getProfileImagePath($getUserDetails->id);
                $graphdata   = $this->getGraphData($getUserDetails->id);
                $tot_exp     = $this->getTotalExperience($getUserDetails->id);
                $lang_colors = $this->getColorCode();

                $from = new \DateTime($getUserDetails["birthdate"]);
                $to   = new \DateTime('today');
                $getUserDetails["age"] = $from->diff($to)->y;
            }

            return view('test.public')->with(array('getUserDetails'=> $getUserDetails,'usertags'=>$getUserTags, 'userlang'=>$getUserLanguage, 'experiences'=>$getUserexperience, 'professions'=>$getUserProfession, 'searchcity'=>$getUserSearchCities, 'profileimg' =>$profile_img, 'graphdata'=>$graphdata,'expyears' =>$tot_exp, 'videoFile' => $file, 'videoType' => $videoType,'langcolor' =>$lang_colors ))->with($getPrivacy);
        }
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
     * return user privacy settings
     * @param $userID
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
            $block[$name] = $value;
        }
        return $block;
    }

    /**
     * get user profile image path
     * @param $id
     * @return array
     */
    protected function getProfileImagePath($id)
    {
        $userdetails         = User::select('id', 'alias','created_at')->findOrFail($id);
        $image = ['filename' => 'noimage', 'route' => '/profile.png'];
        $encrypt             = md5($userdetails->id.$userdetails->created_at);
        $directories         = Storage::files($encrypt);                                             // Listout Files
        foreach($directories as $values){
            $split_folder_file = explode('/', $values);           //08d16e9f44699e9334834833c02b7b8e/Profile.png
            $splitted_file     = end($split_folder_file);         //Profile.png
            $explode_filename  = explode('.', $splitted_file);    //explode(Profile.png)
            $explode_name      = $explode_filename[0];            //Profile
            $route             = '/shareProfile/image/'.$userdetails->alias;
            if ($explode_name == 'Profile') {
                $image = ['filename' => $explode_name, 'route' => $route];
            }
        }
        return $image;
    }
    /**
     * show profile image
     * @param $id
     * @return mixed
     */
    public function showProfileImage($alias)
    {
        if (preg_match('/^(.*)\.(.*)\_\d{3}$/', $alias)) {
            $details = User::select('id', 'created_at')
                ->where('alias', $alias)
                ->first();
            $encrypt = md5($details->id . $details->created_at);
            $directories = Storage::files($encrypt);                                             // Listout Files
            foreach ($directories as $values) {
                $split_folder_file = explode('/', $values);           //08d16e9f44699e9334834833c02b7b8e/Profile.png
                $splitted_file = end($split_folder_file);         //Profile.png
                $explode_filename = explode('.', $splitted_file);    //explode(Profile.png)
                $explode_name = $explode_filename[0];            //Profile
                $file_extension = $explode_filename[1];            //png
                if ($explode_name == 'Profile') {
                    switch ($file_extension) {
                        case "gif":
                            $ctype = "image/gif";
                            break;
                        case "png":
                            $ctype = "image/png";
                            break;
                        case "jpeg":
                        case "jpg":
                            $ctype = "image/jpeg";
                            break;
                        default:
                    }
                    $file = Storage::disk('local')->get($encrypt . '/' . $splitted_file);
                } else {
                    $default_filename = 'profile.png';
                    $explode_filename = explode('.', $default_filename); //explode(Profile.png)
                    $file_extension = $explode_filename[1];            //png
                    switch ($file_extension) {
                        case "gif":
                            $ctype = "image/gif";
                            break;
                        case "png":
                            $ctype = "image/png";
                            break;
                        case "jpeg":
                        case "jpg":
                            $ctype = "image/jpeg";
                            break;
                        default:
                    }
                    $file = public_path() . '/' . $default_filename;
                }
            }
            return response($file, 200)
                ->header('Content-Type', $ctype);
        }
    }

    /**
     * get Graph data
     * @param $id
     * @return mixed
     */
    protected function getGraphData($user_id)
    {
        $target   = [1=>'presentation', 2=>'conception' ,3=>'management', 4=>'creativity'];
        $count    = Userabilitytest::where('user_id', $user_id)->count();
        $results  = array();
        if($count==20) {
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
            $graph_ranking = User::select('graph_1', 'graph_2', 'graph_3', 'graph_4')->find($user_id);
            $rank1 = $graph_ranking->graph_1 * 25;
            $rank2 = $graph_ranking->graph_2 * 25;
            $rank3 = $graph_ranking->graph_3 * 25;
            $rank4 = $graph_ranking->graph_4 * 25;
            $results = [$target[1] => $rank1, $target[2] => $rank2, $target[3] => $rank3, $target[4] => $rank4];
        }
        return $results;
    }

    /**
     * get total experience of user
     * @return mixed
     */
    protected function getTotalExperience($user_id)
    {
        $years = DB::table('userexperiences')
            ->where('user_id',$user_id)
            ->where('title','>',0)
            ->where('years','>',0)
            ->where('city','<>',"")
            ->where('company','<>',"")
            ->sum('years');
        return $years;
    }

    /**
     * rgba color code for language ranking display
     * @return array
     */
    protected function getColorCode()
    {
        return ['rgba(81, 159, 211, 1)','rgba(101, 198, 151, 1)','rgba(255, 185, 16, 1)','rgba(238, 52, 100, 1)'];
    }
}
