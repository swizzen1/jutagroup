<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Models\User;
use Excel;
use Illuminate\Http\Request;

class UsersController extends BaseController
{
    public $data = [];

    public function index(Request $request)
    {
        $users = User::whereNotNull('id');

        if ($request->first_name) {
            $users->where('first_name', 'like', "%$request->first_name%");
        }

        if ($request->last_name) {
            $users->where('last_name', 'like', "%$request->last_name%");
        }

        if ($request->phone) {
            $users->where('phone', 'like', "%$request->phone%");
        }

        if ($request->email) {
            $users->where('email', 'like', "%$request->email%");
        }

        if ($request->from) {
            $users->where('created_at', '>', $request->from);
        }

        if ($request->to) {
            $users->where('created_at', '<', $request->to);
        }

        $this->data['users'] = $users->orderby('id', 'DESC')->paginate(10);

        return view('Administrator.users.list', $this->data);
    }

    public function export(Request $request)
    {
        return Excel::download(new UsersExport($request), 'მომხმარებლები.xlsx');
    }
}
