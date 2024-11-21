<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
class PermissionController extends Controller
{

    public function create()
    {

        return view("permissions.create");
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'display_name' => 'required',
        ]);

        $permission = Permission::create($request->all());

        return redirect()->back()->with('success','Permission Created');
    }
}
