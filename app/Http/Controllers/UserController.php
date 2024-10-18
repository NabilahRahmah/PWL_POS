<?php

namespace App\Http\Controllers;

use App\Models\LevelModel as ModelsLevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Database\QueryException;
use App\Http\Controllers\LevelModel;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
   // JS 6
   public function create_ajax()
   {
       $level = ModelsLevelModel::select('level_id', 'level_nama')->get();
       return view('user.create_ajax')
           ->with('level', $level);
   }
   public function store_ajax(Request $request)
   {
       // Cek apakah request berupa ajax atau ingin JSON
       if ($request->ajax() || $request->wantsJson()) {
           $rules = [
               'level_id' => 'required|integer',
               'username' => 'required|string|min:3|unique:m_user,username',
               'nama' => 'required|string|max:100',
               'password' => 'required|min:6'
           ];

           // Gunakan Validator dari Illuminate\Support\Facades\Validator;
           $validator = Validator::make($request->all(), $rules);
           // Jika validasi gagal
           if ($validator->fails()) {
               return response()->json([
                   'status' => false, // response status, false: error/gagal, true: berhasil
                   'message' => 'Validasi Gagal',
                   'msgField' => $validator->errors(), // pesan error validasi
               ]);
           }
           // Simpan data user
           UserModel::create($request->all());

           // Jika berhasil
           return response()->json([
               'status' => true,
               'message' => 'Data user berhasil disimpan',
           ]);
       }
       // Redirect jika bukan request Ajax
       return redirect('/');
   }
   // Menampilkan halaman form edit user ajax
   public function edit_ajax(string $id)
   {
       $user = UserModel::find($id);
       $level = ModelsLevelModel::select('level_id', 'level_nama')->get();
       return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
   }
   public function update_ajax(Request $request, $id)
   {
       // cek apakah request dari ajax
       if ($request->ajax() || $request->wantsJson()) {
           $rules = [
               'level_id' => 'required|integer',
               'username' => 'required|max:20|unique:m_user,username,' . $id . ',user_id',
               'nama' => 'required|max:100',
               'password' => 'nullable|min:6|max:20'
           ];
           // use Illuminate\Support\Facades\Validator;
           $validator = Validator::make($request->all(), $rules);
           if ($validator->fails()) {
               return response()->json([
                   'status' => false, // respon json, true: berhasil, false: gagal
                   'message' => 'Validasi gagal.',
                   'msgField' => $validator->errors() // menunjukkan field mana yang error
               ]);
           }
           $check = UserModel::find($id);
           if ($check) {
               if (!$request->filled('password')) { // jika password tidak diisi, maka hapus dari request
                   $request->request->remove('password');
               }
               $check->update($request->all());
               return response()->json([
                   'status' => true,
                   'message' => 'Data berhasil diupdate'
               ]);
           } else {
               return response()->json([
                   'status' => false,
                   'message' => 'Data tidak ditemukan'
               ]);
           }
       }
       return redirect('/');
   }
   public function confirm_ajax(string $id)
   {
       $user = UserModel::find($id);
       return view('user.confirm_ajax', ['user' => $user]);
   }
   public function delete_ajax(Request $request, $id)
   {
       // cek apakah request dari ajax
       if ($request->ajax() || $request->wantsJson()) {
           $user = UserModel::find($id);
           if ($user) {
               $user->delete();
               return response()->json([
                   'status' => true,
                   'message' => 'Data berhasil dihapus'
               ]);
           } else {
               return response()->json([
                   'status' => false,
                   'message' => 'Data tidak ditemukan'
               ]);
           }
       }
       return redirect('/');
   }  
   

    // JS5
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user';

        // Ambil data level
        $level = ModelsLevelModel::all();

        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }


    public function list(Request $request){
    $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
        ->with('level');

    // Filter data user berdasarkan level_id jika ada
    if ($request->level_id) {
        $users->where('level_id', $request->level_id);
    }

    // return DataTables::of($users)
    //     ->addIndexColumn()
    //     ->addColumn('aksi', function ($user) {
    //         $btn = '<a href="' . url('/user', $user->user_id) . '" class="btn btn-info btn-sm">Detail</a> ';
    //         $btn .= '<a href="' . url('user/' . $user->user_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
    //         $btn .= '<form class="d-inline-block" method="POST" action="' . url('/user', $user->user_id) . '">'
    //             . csrf_field()
    //             . method_field('DELETE')
    //             . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
    //         return $btn;
    //     })
    //     ->rawColumns(['aksi'])
    //     ->make(true);


    //Update JS 6
    return DataTables::of($users)
        ->addIndexColumn()
        ->addColumn('aksi', function ($user) {
            // $btn = '<a href="' . url('/user', $user->user_id) . '" class="btn btn-info btn-sm">Detail</a> ';
            // $btn .= '<a href="' . url('user/' . $user->user_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
            // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/user', $user->user_id) . '">'
            //     . csrf_field()
            //     . method_field('DELETE')
            //     . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
            // return $btn;
            $btn = '<button onclick="modalAction(\'' . url('/user/' . $user->user_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
        })
        ->rawColumns(['aksi'])
        ->make(true);
    
}

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];

        $level = ModelsLevelModel::all();
        $activeMenu = 'user';

        return view('user.create', compact('breadcrumb', 'page', 'level', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer'
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail user'
        ];

        $activeMenu = 'user';

        return view('user.show', compact('breadcrumb', 'page', 'user', 'activeMenu'));
    }

    public function edit(string $id)
    {
        $user = UserModel::findOrFail($id);
        $levels = ModelsLevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit User',
            'nama' => $user->nama,
        ];

        $activeMenu = 'user';

        return view('user.edit', compact('breadcrumb', 'page', 'user', 'levels', 'activeMenu'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
            'nama' => 'required|string|max:100',
            'password' => 'nullable|min:5',
            'level_id' => 'required|integer'
        ]);

        $user = UserModel::findOrFail($id);

        $user->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = UserModel::find($id);
        if (!$check) {
            return redirect('/user')->with('error', 'Data user tidak ditemukan');
        }

        try {
            UserModel::destroy($id);
            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        } catch (QueryException $e) {
            return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
    
    //JS 4 
    // public function index() {
    
        //PRAKTIKUM 2.7
        // //UPDATE
        // $user = UserModel::with('level')->get();
        // return view('user', ['data' => $user]);

        // $user = UserModel::with('level')->get();
        // dd($user);
    
        //PRAKTIKUM 2.6
    //     $user = UserModel::all();
    //     return view('user', ['data' => $user]);
    // }

    // public function tambah()
    // {
    //     return view('user_tambah');
    // }

    // public function tambah_simpan(Request $request)
    // {
    //     UserModel::create([
    //         'username' => $request->username,
    //         'nama' => $request->nama,
    //         'password' => Hash::make('$request->password'),
    //         'level_id' => $request->level_id
    //     ]);
    //     return redirect('/user');
    // }

    // public function ubah($id) {
    //     $user = UserModel::find($id);
    //     return view('user_ubah', ['data' => $user]);
    // }

    // public function ubah_simpan($id, Request $request){
    //     $user = UserModel::find($id);

    //     $user->username = $request->username;
    //     $user->nama = $request->nama;
    //     $user->password = Hash::make('$request->passowrd');
    //     $user->level_id = $request->level_id;

    //     $user->save();

    //     return redirect('/user');
    // }

    // public function hapus($id) {
    //     $user = UserModel::find($id);
    //     $user->delete();

    //     return redirect('/user');
    // }

        //PRAKTIKUM 2.5
        //UPDATE
        // $user = UserModel::create([
        //     'username' => 'manager11',
        //     'nama' => 'Manager11',
        //     'password' => Hash::make('12345'),
        //     'level_id' => 2,
        // ]);

        // $user->username = 'manager12';

        // $user->save();

        // $user->wasChanged(); //true
        // $user->wasChanged('username'); //true
        // $user->wasChanged('username', 'level_id'); //true
        // $user->wasChanged('nama'); //false
        // dd($user->wasChanged(['nama', 'username'])); //true


        // $user = UserModel::create([
        //     'username' => 'manager55',
        //     'nama' => 'Manager55',
        //     'password' => Hash::make('12345'),
        //     'level_id' => 2,
        // ]);

        // $user->username = 'manager56';

        // $user->isDirty(); //true
        // $user->isDirty('username'); //true
        // $user->isDirty('nama'); //false
        // $user->isDirty('nama', 'username'); //true

        // $user->isClean();//false
        // $user->isClean('username'); //false
        // $user->isClean('nama'); //true
        // $user->isClean('nama', 'username'); //false

        // $user->save();

        // $user->isDirty(); //false
        // $user->isClean(); //true
        // dd($user->isDirty());


        //PRAKTIKUM 2.4
        //UPDATE3
        // $user = UserModel::firstOrNew(
        //     [
        //         'username' => 'manager33',
        //         'nama' => 'Manager Tiga Tiga',
        //         'password' => Hash::make('12345'),
        //         'level_id' => 2
        //     ],
        // );
        // $user->save();
        // return view('user', ['data' => $user]);
        
        
        //UPDATE2
        // $user = UserModel::firstOrNew(
        //     [
        //         'username' => 'manager33',
        //         'nama' => 'Manager Tiga Tiga',
        //         'password' => Hash::make('12345'),
        //         'level_id' => 2
        //     ],
        // );
        // return view('user', ['data' => $user]);


        //UPDATE
        // $user = UserModel::firstOrNew(
        //     [
        //         'username' => 'manager',
        //         'nama' => 'Manager',
        //     ],
        // );
        // return view('user', ['data' => $user]);


        // $user = UserModel::firstOrCreate(
        //     [
        //         'username' => 'manager22',
        //         'nama' => 'Manager Dua Dua',
        //         'password' => Hash::make('12345'),
        //         'level_id' => 2
        //     ],
        // );
        // return view('user', ['data' => $user]);


        //PRAKTIKUM 2.3
        // $user = UserModel::where('level_id', 2)->count();
        //dd($user);
        // return view('user', ['data' => $user]);

        //PRAKTIKUM 2.2
        //UPDATE
        // $user = UserModel::where('username', 'manager9')->firstOrFail();
        // return view('user', ['data' => $user]);

        // $user = UserModel::findOrFail(1);
        // return view('user', ['data' => $user]);


        //PRAKTIKUM 2.1
        //UPDATE 4
        // $user = UserModel::findOr(20, ['username', 'nama'], function () {
        //     abort(404);
        // });
        // return view('user', ['data' => $user]);

        //UPDATE 3
        // $user = UserModel::findOr(1, ['username', 'nama'], function () {
        //     abort(404);
        // });
        // return view('user', ['data' => $user]);

        //UPDATE 2
        // $user = UserModel::firstWhere('level_id', 1);
        // return view('user', ['data' => $user]);

        //UPDATE1
        // $user = UserModel::where('level_id', 1)->first();
        // return view('user', ['data' => $user]);

        // $user = UserModel::find(1);
        // return view('user', ['data' => $user]);


        //UPDATE
        // $data = [
        //     'level_id' => 2,
        //     'username' => 'manager_tiga',
        //     'nama' => 'Manager 3',
        //     'password' => Hash::make('1234')
        // ];

        // $data = [
        //     'level_id' => 2,
        //     'username' => 'manager_dua',
        //     'nama' => 'Manager 2',
        //     'password' => Hash::make('1234')
        // ];
        // UserModel::create($data);

        // $user = UserModel::all();
        // return view('user', ['data' => $user]);
    //}
    
    
    // public function cust($id, $name){
    //     return view('ViewUser')
    //     ->with('id', $id)
    //     ->with('name', $name);
    // }
    

    //NO 8 PRAKTIKUM 6
    // public function index() {
    //     //tambah data user dengan Eloquent Model
    //     $data = [
    //         'username' => 'customer-1',
    //         'nama' => 'Pelanggan',
    //         'password' => Hash::make('12345'),
    //         'level_id' => 3
    //     ];
    //     UserModel::insert($data); //tambahkan data ke table m_user

    //     // NO 5 PRAKTIKUM 6
    //     //coba akses model UserModel
    //     $user = UserModel::all(); //ambil semua data dari  table m_user
    //     return view('user', ['data' => $user]);

    // }
}