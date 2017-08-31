<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class IsaController extends Controller
{
    public function jumpIos(Request $request)
    {
        $parentShootId = $request->input('id');

        return view('mp.jump_ios',['id'=>$parentShootId]);
    }
    public function sss(){
        return view('mp.sss');
    }
}
