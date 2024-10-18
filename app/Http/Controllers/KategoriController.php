<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
        public function index() {
        //JOBSHEET 5 TUGAS PRAKTIKUM m_kategori
            $breadcrumb = (object) [
                'title' => 'Daftar Kategori',
                'list' => ['Home', 'Kategori']
            ];
            $page = (object) [
                'title' => 'Daftar kategori yang terdaftar dalam sistem'
            ];
            $activeMenu = 'kategori'; // set menu yang sedang aktif
            $kategori = KategoriModel::all();
            return view('kategori.index', [
                'breadcrumb' => $breadcrumb, 
                'page' => $page, 
                'kategori' => $kategori,
                'activeMenu' => $activeMenu]);
        }
        public function list(Request $request) {
            $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');
            return DataTables::of($kategori)
                ->addIndexColumn()
                ->addColumn('aksi', function ($kategori) { // menambahkan kolom aksi
                    // $btn = '';
                    // $btn .= '<a href="' . url('/kategori/' . $kategori->kategori_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a>';
                    // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/kategori/' . $kategori->kategori_id) . '">
                    //             ' . csrf_field() . '
                    //             <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button>
                    //         </form>';

                    //UPDATE JS6
                    // $btn = '<a href="'.url('/kategori/' . $kat->kategori_id).'" class="btn btn-info btn-sm">Detail</a> ';
                    // $btn .= '<a href="'.url('/kategori/' . $kat->kategori_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                    // $btn .= '<form class="d-inline-block" method="POST" action="'.url('/kategori/'.$kat->kategori_id).'">'
                    //     . csrf_field() . method_field('DELETE') .
                    //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                    $btn = '<a href="' . url('/kategori/' . $kategori->kategori_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                        '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                    $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                        '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';        
                    return $btn;
                })
                ->rawColumns(['aksi']) 
                ->make(true);
        }
        public function create() {
            $breadcrumb = (object) [
                'title' => 'Tambah kategori',
                'list' => ['Home', 'kategori', 'Tambah']
            ];
            $page = (object) [
                'title' => 'Tambah kategori baru'
            ];
            $kategori = KategoriModel::all(); 
            $activeMenu = 'kategori'; 
            return view('kategori.create', [
                'breadcrumb' => $breadcrumb, 
                'page' => $page, 
                'kategori' => $kategori, 
                'activeMenu' => $activeMenu]);
        }
        public function store(Request $request)
        {
            $request->validate([
                'kategori_kode' => 'required|string|max:10|unique:m_kategori,kategori_kode',
                'kategori_nama' => 'required|string|max:100',
            ]);
            KategoriModel::create([
                'kategori_kode' => $request->kategori_kode,
                'kategori_nama' => $request->kategori_nama
                
            ]);
            return redirect('/kategori')->with('success', 'Data kategori berhasil disimpan');
        }
        
        // Menampilkan halaman form edit user
        public function edit(string $id)
        {
            $kategori = KategoriModel::find($id);
            $breadcrumb = (object) [
                'title' => 'Edit kategori',
                'list' => ['Home', 'kategori', 'Edit']
            ];
            $page = (object) [
                'title' => 'Edit kategori'
            ];
            $activeMenu = 'kategori'; // set menu yang sedang aktif
            return view('kategori.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
        }
        // Menyimpan perubahan data user
        public function update(Request $request, string $id)
        {
            $request->validate([
                'kategori_kode' => 'required|string|max:10',
                'kategori_nama' => 'required|string|max:100',
            ]);
            KategoriModel::find($id)->update([
                'kategori_kode' => $request->kategori_kode,
                'kategori_nama' => $request->kategori_nama,
                
            ]);
            return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
        }
        
    
        public function destroy(string $id)
        {
            $check = KategoriModel::find($id);
            if (!$check) {
                // untuk mengecek apakah data kategori dengan id yang dimaksud ada atau tidak
                return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
            }
            try {
                KategoriModel::destroy($id); // Hapus data kategori
                return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
            } catch (\Illuminate\Database\QueryException $e) {
                // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
                return redirect('/kategori')->with('error', 'Data kategori gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
            }
        }


        // JS 6
        public function create_ajax()
        {
            return view('kategori.create_ajax');
        }
        public function store_ajax(Request $request)
        {
            // cek apakah request berupa ajax
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'kategori_kode' => 'required|string|min:3|max:10|unique:m_kategori,kategori_kode',
                    'kategori_nama' => 'required|string|max: 100',
                ];
                // use Illuminate\Support\Facades\Validator;
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false, // response status, false: error/gagal, true: berhasil
                        'message' => 'Validasi Gagal',
                        'msgField' => $validator->errors(), // pesan error validasi
                    ]);
                }
                KategoriModel::create($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data kategori berhasil disimpan',
                ]);
            }
            redirect('/');
        }
        // Menampilkan halaman form edit kategori ajax
        public function edit_ajax(string $id)
        {
            $kategori = KategoriModel::find($id);
            return view('kategori.edit_ajax', ['kategori' => $kategori]);
        }
        public function update_ajax(Request $request, $id)
        {
            // cek apakah request dari ajax
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'kategori_kode' => 'required|max:10|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
                    'kategori_nama' => 'required|max:100',
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
                $check = KategoriModel::find($id);
                if ($check) {
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
            $kategori = KategoriModel::find($id);
            return view('kategori.confirm_ajax', ['kategori' => $kategori]);
        }
        public function delete_ajax(Request $request, $id)
        {
            // cek apakah request dari ajax
            if ($request->ajax() || $request->wantsJson()) {
                $kategori = KategoriModel::find($id);
                if ($kategori) {
                    $kategori->delete();
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


        //INSERT
        // $data = [
        //     'kategori_kode' => 'SNK',
        //     'kategori_nama' => 'Snack/Makanan Ringan',
        //     'created_at' => now()
        // ];
        // DB::table('m_kategori')->insert($data);
        // return 'Insert data baru berhasil';

        //UPDATE
        // $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->update(['kategori_nama' => 'Camilan']);
        // return 'Update data berhasil, Jumlah data yang diupdate: ' . $row . 'baris';

        //DELETE
        // $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->delete();
        // return 'Delete data berhasil, Jumlah data yang dihapus: ' .$row . ' baris';

        //VIEW
        // $data = DB::table('m_kategori')->get();
        // return view('kategori', ['data' => $data]);

}