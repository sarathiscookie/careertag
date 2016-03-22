<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\PrivacysettingRequest;
use App\User;
use Auth;

class PrivacyController extends Controller
{
    /**
     * function to save privacy settings
     * @param PrivacysettingRequest $request
     * @return void;
     */
    public function saveSettings(PrivacysettingRequest $request)
    {
        $id     = Auth::user()->id;
        $column = $request ->param_set;
        $val    = ($request ->param_val =="show")? "hide":"show";

        User::where('id', $id)->update([$column => $val]);
        $row = User::select($column)->find($id);
        echo $row->$column;
    }
}
