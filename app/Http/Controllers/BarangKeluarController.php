<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangKeluar;
use App\Models\Barang;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $barangkeluars = BarangKeluar::with('barang')->paginate(10);

        return view('BarangKeluar.index', compact('barangkeluars'));
    }

    public function create()
    {
        $barangs = Barang::all();

        return view('BarangKeluar.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'tgl_keluar' => 'required|date',
            'qty_keluar' => 'required|integer|min:1',
            'barang_id' => 'required|exists:barang,id',
        ]);

        $barangkeluar = BarangKeluar::create($request->all());

        // $barang = Barang::find($request->barang_id);
        // $barang->stok += $request->qty_masuk;
        // $barang->save();

        return redirect()->route('BarangKeluar.index')->with(['success' => 'Data Barang Keluar Berhasil Disimpan!']);
    }

    public function show($id)
    {
        $barangkeluar = BarangKeluar::findOrFail($id);

        return view('BarangKeluar.show', compact('barangkeluar'));
    }

    public function edit($id)
    {
        $barangkeluar = BarangKeluar::findOrFail($id);
        $barangs = Barang::all();

        return view('BarangKeluar.edit', compact('barangkeluar', 'barangs'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'tgl_keluar' => 'required|date',
            'qty_keluar' => 'required|integer|min:1',
            'barang_id' => 'required|exists:barang,id',
        ]);

        $barangkeluar = BarangKeluar::findOrFail($id);
        $barangkeluar->update($request->all());

        // Update stok barang terkait
        // $barang = Barang::find($request->barang_id);
        // $barang->stok += $request->qty_masuk - $barangmasuk->qty_masuk;
        // $barang->save();

        return redirect()->route('BarangKeluar.index')->with(['success' => 'Data Barang Keluar Berhasil Diperbarui!']);
    }

    public function destroy($id)
    {
        $barangkeluar = BarangKeluar::findOrFail($id);

        // Mengurangi stok barang terkait
        // $barang = Barang::find($barangmasuk->barang_id);
        // $barang->stok -= $barangmasuk->qty_masuk;
        // $barang->save();

        $barangkeluar->delete();

        return redirect()->route('BarangKeluar.index')->with(['success' => 'Data Barang Keluar Berhasil Dihapus!']);
    }
}