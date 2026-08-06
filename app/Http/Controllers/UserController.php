<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() { return view('users.index',['users'=>User::latest()->paginate(15),'logs'=>ActivityLog::with('user')->latest()->limit(10)->get()]); }
    public function store(Request $request)
    {
        $data=$request->validate(['name'=>'required|max:120','username'=>'required|max:50|alpha_dash|unique:users,username','email'=>'required|email|unique:users,email','role'=>'required|in:admin,karyawan,owner','password'=>'required|min:8|confirmed']);
        User::create($data); return back()->with('success','Pengguna berhasil ditambahkan.');
    }
    public function update(Request $request, User $user)
    {
        $data=$request->validate(['name'=>'required|max:120','username'=>'required|max:50|alpha_dash|unique:users,username,'.$user->id,'email'=>'required|email|unique:users,email,'.$user->id,'role'=>'required|in:admin,karyawan,owner','is_active'=>'required|boolean','password'=>'nullable|min:8|confirmed']); if(blank($data['password']??null))unset($data['password']); $user->update($data); return back()->with('success','Pengguna berhasil diperbarui.');
    }
}
