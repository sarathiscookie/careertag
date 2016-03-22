<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserprofessionRequest;
use App\Graduation;
use App\Userprofession;
use App\Experience;
use Auth;

class ProfessionController extends Controller
{
    /**
     * Ajax - select2 options - graduations -list
     * @param UserprofessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listGraduation(UserprofessionRequest $request)
    {
        $results = '';
        $graduations = Graduation::select('id', 'title_de', 'title_en')
            ->where('title_en', 'LIKE', '%'.$request->q.'%')
            ->orWhere('title_de', 'LIKE', '%'.$request->q.'%')
            ->groupBy('title_en')
            ->get();
        foreach ($graduations as $row) {
            $results[] = array("id" => $row->id, "text" => $row->title_en );
        }
        if($results!='')
            return response()->json($results);
        else
            return response()->json(array());
    }

    /**
     * Ajax - select2 options - experience -list
     * @param UserprofessionRequest $request
     */
    public function listExperience(UserprofessionRequest $request)
    {
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
     * Insert a new user profession
     * @param UserprofessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserprofessionRequest $request)
    {
        if($request->graduation_id>0) {
            $userprofession = new Userprofession;
            $userprofession->user_id = Auth::user()->id;
            $userprofession->graduation_id = $request->graduation_id;
            $userprofession->experience_id = $request->experience_id;
            $userprofession->grade = $request->grade;
            $userprofession->save();
            return response()->json(['professionID' => $userprofession->id]);
        }
        else
        {
            return response()->json(['professionID' =>0]);
        }
    }

    /**
     * list all user professions
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        $user_professions = Userprofession::select('userprofessions.id', 'userprofessions.grade', 'experiences.title_en as subject', 'graduations.title_en as graduation')
            ->leftjoin('experiences', 'userprofessions.experience_id', '=', 'experiences.id')
            ->join('graduations', 'userprofessions.graduation_id', '=', 'graduations.id')
            ->where('userprofessions.user_id', Auth::user()->id)
            ->orderBy('graduations.title_en')
            ->get();
        return response()->json(['userProfessions' => $user_professions]);
    }

    /**
     * Delete user profession for requested id
     * @param UserprofessionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(UserprofessionRequest $request)
    {
        Userprofession::where('id',$request->professionID)->delete();
        return response()->json(['professionID' => $request->professionID]);
    }
}
