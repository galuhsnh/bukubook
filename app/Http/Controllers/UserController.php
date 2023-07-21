<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index()
    {
        abort_if(!Gate::allows('list-user'), 403, 'You are not allowed to view this page!');

        // if(Gate::allow('list_user')) {
        //     abort(403, 'You are not allowed to view this page!')
        // }
        return view('user.index', [
            'users' => User::paginate(2)
        ]);
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        //dd($request->all());

        $request->validate([
            'name'      => ['required'],
            'email'     => ['required', 'email', 'unique:users'],
            'roles'      => ['required'],
            'password'  => ['required', 'min:8', 'confirmed']
        ]);

        $name = $request->get('name');
        $email = $request->get('email');
        $roles = $request->get('roles');
        $password = $request->get('password');


        //masukin ke database user
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->roles = $roles;
        $user->password = Hash::make($password);
        $user->save();

        return redirect()->route('user.index')
            ->with('success', 'data berhasil ditambahkan!');
    }

    public function edit($id)
    {
        //dd($id);

        return view('user.edit', [
            'user' => User::find($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        //dd($request->all(), $id);
        $request->validate([
            'name'      => ['required'],
            'email'     => ['required', 'email', 'unique:users,email,' . $id],
            'roles'     => ['required'],
            'password'  => ['nullable', 'min:8', 'confirmed']
        ]);

        $password = $request->get('password');

        //masukin ke database user
        try {
            $user = User::find($id);
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->roles = $request->get('roles');
            if (!empty($password)) {
                $user->password = Hash::make($password);
            }
            $user->save();

            return redirect()->route('user.index')
                ->with('success', 'data berhasil ditambahkan!');
        } catch (\Exception $e){
            return redirect()
                ->route('user.index')
                ->with('error', $e->getMessage());

        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()
            ->route('user.index')
            ->with('success', 'Data Berhasil Dihapus!');
    }
}
