<?php
 
namespace App\Http\Controllers;

use App\Http\Models\requestaModel;
use App\Http\Models\requirementModel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use DB;

class requestaController extends BaseController
{
    public function requesta()
    {
        $bio = requestaModel::paginate(5);
        $reqq = requirementModel::get();

        // $bio = requestModel::get();
        return view('requesta', ['bio' => $bio]);
    }

    public function getInput()
{
    return view('inputrequesta');
}
 
public function simpanrequesta(Requesta $requasta)
{
    $file = Input::file('lampiran');
 
        $destinationPath = 'uploads';
        $size = $file->getSize();
        $extension = $file->getClientOriginalExtension();
        $fileName = $file->getClientOriginalName();
        $upload_success = $file->move($destinationPath, $fileName);
         
        if ($upload_success){
            $bio = new requestaModel;
            $bio->nama = $requasta->input('nama');
            $bio->namaaps = $requasta->input('namaaps');
            $bio->penjelasan = $requasta->input('penjelasan');
            $bio->lampiran = $fileName;
            $bio->status = $requasta->input('status');
 
            $bio->save();
             
            return redirect()->action('requestaController@requesta')->with('style', 'success')->with('alert', 'Berhasil Disimpan ! ')->with('msg', 'Menunggu persetujuan');
        
        }
    
 
}

public function getEdit($id)
{
    return view('editrequesta', ['requesta' => requestaModel::findOrFail($id)]);
}
 
public function ubahrequesta(Requesta $requasta)
{
    $bio     = new requestaModel;
    $id     = $requasta->input('id');
    $bio     = requestaModel::find($id);
     
    $bio->nama = $requasta->input('nama');
    $bio->namaaps = $requasta->input('namaaps');
    $bio->penjelasan = $requasta->input('penjelasan');
    $bio->lampiran = $requasta->input('lampiran');
    $bio->status = $requasta->input('status');


    $bio->save();
     
    return redirect()->action('requestaController@requesta')->with('style', 'success')->with('alert', 'Berhasil Diubah ! ')->with('msg', 'Data Diubah Di Database');
}

public function getDelete($id)
{
    $bio     = new requestaModel;
    $bio     = requestaModel::find($id);
    $bio->delete();
     
    return redirect()->action('requestaController@requesta')->with('style', 'success')->with('alert', 'Berhasil Dihapus ! ')->with('msg', 'Data Dihapus Di Database');
}

public function cari(Request $requesta)
    {
        // menangkap data pencarian
        $cari = $requesta->cari;
 
            // mengambil data dari table pegawai sesuai pencarian data        
        $bio = DB::table('request')
        ->where('nama','LIKE',"%{$cari}%")
        ->orWhere('namaaps', 'LIKE', "%{$cari}%") 
        ->paginate(5);
 
            // mengirim data pegawai ke view index
        return view('requesta',['bio' => $bio]);
 
    }

public function downfunc(){
    	$bio=DB::table('request')->get();
    	return view('requesta',compact('downloads'));
    }
    

}



?>