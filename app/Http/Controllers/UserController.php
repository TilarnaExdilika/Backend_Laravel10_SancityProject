<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function index()
    {
        $users = User::
            join('departments', 'users.department_id', '=', 'departments.id')
            ->join('users_status', 'users.status_id', '=', 'users_status.id')
            ->select
                (
                'users.*', 
                'departments.name as departments', 
                'users_status.name as status'
                )
            ->get();

        return response()->json($users);
    }

    public function create() 
    {
        $users_status = DB::table("users_status")
        ->select(
            "id as value",
            "name as label"
        )
        ->get();

        $departments = DB::table("departments")
        ->select(
            "id as value",
            "name as label"
        )
        ->get();

        return response()->json([
            "users_status" => $users_status,
            "departments" => $departments
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            "status_id" => "required",
            "username" => "required|unique:users,username",
            "name" => "required",
            "email" => "required|email",
            "department_id" => "required",
            "password" => "required|confirmed"

        ], [
            "status_id.required" => "Nhập tình trạng",
            "username.required" => "Nhập username",
            "username.unique" => "Tên tài khoản đã tồn tại",
            "name.required" => "Nhập họ và tên",
            "email.required" => "Nhập email",
            "email.email" => "Email không hợp lệ",
            "department_id.required" => "Nhập department_id",
            "password.required" => "Nhập mật khẩu",
            "password.confirmed" => "Xác nhận mật khẩu không chính khớp"
        ]);
    }
}
