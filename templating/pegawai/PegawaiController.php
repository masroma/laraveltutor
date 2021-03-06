<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use App\Models\Pegawai;
use DataTables;
use Exception;
use Auth;
use Gate;
use DB;
use Illuminate\Foundation\Validation\ValidatesRequests;

class PegawaiController extends Controller
{
     /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use ValidatesRequests;


    public function data()
    {
        try {

            $data = Pegawai::all();
            return DataTables::of($data)

                    ->addColumn('action', function ($data)  {

                        $btn = '';

                        if ($canUpdate) {
                            $btn .= '<a class="btn-floating btn-small" href="tahunajaran/' .$data->id. '/edit"><i class="material-icons">edit</i></a>';
                        }

                        if ($canDelete) {
                            $btn .= '<button class="btn-floating purple darken-1 btn-small" type="button" onClick="deleteConfirm('.$data->id.')"><i class="material-icons">delete</i></button>';
                        }

                        return $btn;
                    })
                    ->addIndexColumn()
                    ->make(true);

        } catch (Exception $e) {
            DB::commit();
            return response()->json(
                [
                    'status' => false,
                    'message' => $e->getMessage()
                ]
            );
        }


    }

    public function index()
    {

        $name_page = "pegawai";
        $title = "Pegawai";
        $data = array(
            'page' => $name_page,
            'title' => $title
        );
        return view('pegawai.index')->with($data);
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $name_page = "pegawai";
        $title = "Pegawau";
        $data = array(
            'page' => $name_page,
            'title' => $title

        );

        return view('pegawai.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'nama_pegawai' => 'required',
                'gender' => 'required',
                'usia' => 'required',
                'alamat' => 'required'
            ]);

            $save = new Pegawai();
            $save->nama_lengkap = $request->nama_lengkap;
            $save->usia = $request->usia;
            $save->gender = $request->gender;
            $save->alamat = $request->alamat;
            $save->save();

            DB::commit();
        } catch (ModelNotFoundException $exception) {
            DB::rollback();
            return back()->with('success', $exception->getMessage());
        }

        if ($save) {
            //redirect dengan pesan sukses
            return redirect()->route('pegawai.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('pegawai.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('pegawai.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $name_page = "tahunajaran";
        $title = "Tahun Ajaran";
        $data = array(
            'page' => $name_page,
            'pegawai' => $pegawai,
            'title' => $title
        );
        return view('pegawai.edit')->with($data);
    }
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {

        DB::beginTransaction();
        try {
            $this->validate($request, [
                'nama_pegawai' => 'required',
                'gender' => 'required',
                'usia' => 'required',
                'alamat' => 'required'
            ]);

            $save = Pegawai::find($id);
            $save->nama_lengkap = $request->nama_lengkap;
            $save->usia = $request->usia;
            $save->gender = $request->gender;
            $save->alamat = $request->alamat;
            $save->save();
          
            DB::commit();
        } catch (ModelNotFoundException $exception) {
            DB::rollback();
            return back()->withError($exception->getMessage())->withInput();
        }

            if ($update) {
                //redirect dengan pesan sukses
                return redirect()->route('tahunajaran.index')->with(['success' => 'Data Berhasil Diubah!']);
            } else {
                //redirect dengan pesan error
                return redirect()->route('tahunajaran.index')->with(['error' => 'Data Gagal Diubah!']);
            }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $delete = Pegawai::find($id)->delete();
                DB::commit();
        } catch (ModelNotFoundException $exception) {
            DB::rollback();
            return back()->with(['error' => $exception->getMessage()])->withError($exception->getMessage())->withInput();
        }

        if ($delete) {
            //redirect dengan pesan sukses
            return redirect()->back()->with(['success' => 'Data Berhasil Dihapus!']);
        } else {
            //redirect dengan pesan error
            return redirect()->back()->with(['error' => 'Data Gagal Dihapus!']);
        }

    }
}
