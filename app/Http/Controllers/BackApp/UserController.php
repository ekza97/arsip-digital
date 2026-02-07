<?php

namespace App\Http\Controllers\BackApp;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:191',
                'email' => 'required|email|unique:users,email',
                'division' => 'required|string|max:191',
                'role' => 'required|string|max:191',
                'password' => 'nullable|string|min:8',
                'is_active' => 'required|boolean'
            ]);

            User::create([
                'name' => ucwords($request->name),
                'email' => $request->email,
                'division' => $request->division,
                'role' => $request->role,
                'password' => bcrypt($request->password),
                'is_active' => $request->is_active
            ]);

            return response()->json([
                'status' => true, 'message' => 'Berhasil tersimpan'
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = User::find($id);
        $linkUpdate = route("user.update", Crypt::encrypt($data->id));
        return response()->json(['data' => $data, 'link' => $linkUpdate]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);

            $request->validate([
                'name' => 'required|string|max:191',
                'email' => 'required|string|email|max:191|unique:users,email,' . $id,
                'division' => 'required|string|max:191',
                'role' => 'required|string|max:191',
                'password' => 'nullable|string|min:8',
                'is_active' => 'required|boolean'
            ]);

            $db = User::find($id);

            if(!empty($request->password)) {
                $db->password = bcrypt($request->password);
            }

            $db->name = ucwords($request->name);
            $db->email = $request->email;
            $db->division = $request->division;
            $db->role = $request->role;
            $db->is_active = $request->is_active;

            $db->update();

            return response()->json([
                'status' => true, 'message' => 'Berhasil update'
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = Crypt::decrypt($id);
        $data = User::where('id', $id);
        
        $data->delete();
        return true;
    }
}
