<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateIncident extends Controller
{
   public function index(){
    
    $sql1 = "SELECT * from incident";
    $result = json_encode(DB::select($sql1));
    return view('update',compact('result'));
   }
}
