<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicControl;
use Illuminate\Http\Request;

class NavbarController extends Controller
{
   public function index()
   {
       return view('admin.navbar_style.index');
   }

   public function changeNavbarStyle(Request $request)
   {
        $style = $request->style;

        $basiControl = BasicControl::firstOrNew();
        $basiControl->navbar_style = $style;
        $basiControl->save();
        return response()->json(true);
   }
}
