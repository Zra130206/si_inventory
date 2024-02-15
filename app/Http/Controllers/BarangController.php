<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;


class BarangController extends Controller
{
    
	public function index(Request $request)
    {
        $rsetBarang = Barang::with('kategori')->latest()->paginate(10);

        return view('Barang.index', compact('rsetBarang'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $akategori = Kategori::all();
        return view('Barang.create',compact('akategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return $request;
        //validate form
        $this->validate($request, [
            'merk'          => 'required',
            'seri'          => 'required',
            'spesifikasi'   => 'required',
            'kategori_id'   => 'required|not_in:blank',
            'foto'          => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'

        ]);

        //upload image
        $foto = $request->file('foto');
        $foto->storeAs('public/foto', $foto->hashName());

        //create post
        Barang::create([
            'merk'             => $request->merk,
            'seri'             => $request->seri,
            'spesifikasi'      => $request->spesifikasi,
            'kategori_id'      => $request->kategori_id,
            'foto'             => $foto->hashName()
        ]);

        //redirect to index
        return redirect()->route('Barang.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rsetBarang = Barang::find($id);

        //return $rsetBarang;

        //return view
        return view('Barang.show', compact('rsetBarang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    $akategori = Kategori::all();
    $rsetBarang = Barang::find($id);
    $selectedKategori = Kategori::find($rsetBarang->kategori_id);

    return view('Barang.edit', compact('rsetBarang', 'akategori', 'selectedKategori'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'merk'        => 'required',
            'seri'        => 'required',
            'spesifikasi' => 'required',
            'stok'        => 'required',
            'kategori_id' => 'required|not_in:blank',
            'foto'        => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $rsetBarang = Barang::find($id);

        //check if image is uploaded
        if ($request->hasFile('foto')) {

            //upload new image
            $foto = $request->file('foto');
            $foto->storeAs('public/foto', $foto->hashName());

            //delete old image
            Storage::delete('public/foto/'.$rsetBarang->foto);

            //update post with new image
            $rsetBarang->update([
                'merk'          => $request->merk,
                'seri'          => $request->seri,
                'spesifikasi'   => $request->spesifikasi,
                'stok'          => $request->stok,
                'kategori_id'   => $request->kategori_id,
                'foto'          => $foto->hashName()
            ]);

        } else {

            //update post without image
            $rsetBarang->update([
                'merk'          => $request->merk,
                'seri'          => $request->seri,
                'spesifikasi'   => $request->spesifikasi,
                'stok'          => $request->stok,
                'kategori_id'   => $request->kategori_id,
            ]);
        }

        // Redirect to the index page with a success message
        return redirect()->route('Barang.index')->with(['success' => 'Data Berhasil Diubah!']);
    }


    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     $rsetBarang = Barang::find($id);

    //     //delete post
    //     $rsetBarang->delete();

    //     //redirect to index
    //     return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Dihapus!']);
    // }

    public function destroy(string $id)
    {
        // Cek apakah ada transaksi barang keluar terkait dengan barang
        $barangmasuk = BarangMasuk::where('barang_id', $id)->exists();
        // Cek apakah ada transaksi barang keluar terkait dengan barang
        $barangkeluar = BarangKeluar::where('barang_id', $id)->exists();
    
        // Cek apakah stok barang tidak nol
        $stokBarang = Barang::where('id', $id)->where('stok', '>', 0)->exists();
    
        if ($barangmasuk || $barangkeluar || $stokBarang) {
            return redirect()->route('Barang.index')->with(['error' => 'Barang tidak dapat dihapus karena terkait dengan transaksi atau masih memiliki stok.']);
        } else {
            // Hapus barang jika tidak terkait dengan transaksi dan stoknya nol
            $barangSeed = Barang::find($id);
            $barangSeed->delete();
    
            return redirect()->route('Barang.index')->with(['success' => 'Barang berhasil dihapus.']);
        }
    }
}
