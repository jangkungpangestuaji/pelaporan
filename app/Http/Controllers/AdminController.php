<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\User;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified']);
    }
    public function index()
    {
        $dataUser = User::select('users.id', 'users.name', 'users.username', 'users.email', 'users.phone', 'users.level', 'instansi.nama_instansi')
            ->orderBy('users.id', 'desc')
            ->leftJoin('instansi', 'instansi.id', '=', 'users.instansi_id')
            ->get();
        $dataInstansi = Instansi::get();

        $array = [
            'dataInstansi' => $dataInstansi,
            'dataUser' => $dataUser,
        ];
        if (request()->ajax()) {
            return datatables()->of($dataUser)
                ->addColumn('Aksi', function ($dataUser) {
                    $button = "
                <button type='button' id='" . $dataUser->id . "' class='destroy btn btn-danger'>
                    Hapus
                </button>";

                    return $button;
                })
                ->rawColumns(['Aksi'])
                ->make(true);
        }
        return view('pages.admin.kelola-akun', $array, compact('dataUser'));
    }

    public function instansi()
    {
        $type_menu = ([
            'type_menu' => 'instansi',
        ]);
        $data = Instansi::orderBy('id', 'desc')->get();
        if (request()->ajax()) {
            return datatables()->of($data)
                ->addColumn('Aksi', function ($data) {
                    $button = "
                <button type='button' id='" . $data->id . "' class='update btn btn-warning'  >
                    <i class='fas fa-edit'></i>
                </button>";

                    $button .= "
                <button type='button' id='" . $data->id . "' class='destroy btn btn-danger'>
                    <i class='fas fa-times'></i>
                </button>";

                    return $button;
                })
                ->rawColumns(['Aksi'])
                ->make(true);
        }
        return view('pages.admin.instansi', $type_menu, compact('data'));
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'instansi_id' => 'required',
                'username' => 'required',
                'password' => 'required',
                'level' => 'required',
            ],
        );
        // $request->password = '';
        $data = new User;
        $data->name = $request->name;
        $data->instansi_id = $request->instansi_id;
        $data->username = $request->username;
        $data->password = Hash::make($request->password);
        $data->level = $request->level;
        $data->save();
    }
    public function show(Request $request)
    {
        $id = $request->id;
        $data = User::find($id);
        return response()->json(['data' => $data]);
    }
    public function update(Request $request)
    {
        $id = $request->id;
        // dd($request);
        $update = [
            'name' => $request->name,
            'instansi_id' => $request->instansi_id,
            'username' => $request->username,
            'level' => $request->level,
        ];

        $data = User::find($id);
        $data->update($update);
        $data->save();
        return response()->json(['data' => $data]);
    }
    public function destroy(Request $request)
    {
        $id = $request->id;
        $data = User::find($id);
        $data->delete();
        return response()->json(['data' => $data]);
    }

    public function store_instansi(Request $request)
    {
        $request->validate(
            [
                'nama_instansi' => 'required',
            ],
        );

        $data = new Instansi;
        $data->nama_instansi = $request->nama_instansi;
        $data->save();
    }
    public function show_instansi(Request $request)
    {
        $id = $request->id;
        $data = Instansi::find($id);
        return response()->json(['data' => $data]);
    }
    public function update_instansi(Request $request)
    {
        $id = $request->id;
        $update = [
            'nama_instansi' => $request->nama_instansi,
        ];

        $data = Instansi::find($id);
        $data->update($update);
        $data->save();
        return response()->json(['data' => $data]);
    }
    public function destroy_instansi(Request $request)
    {
        $id = $request->id;

        $data = Instansi::find($id);
        $data->delete();
        return response()->json(['data' => $data]);
    }
}
