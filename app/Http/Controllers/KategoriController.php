<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;

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
                    $btn = '';
                    $btn .= '<a href="' . url('/kategori/' . $kategori->kategori_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a>';
                    $btn .= '<form class="d-inline-block" method="POST" action="' . url('/kategori/' . $kategori->kategori_id) . '">
                                ' . csrf_field() . '
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button>
                            </form>';
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