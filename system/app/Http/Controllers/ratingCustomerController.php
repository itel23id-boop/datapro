<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Rating;
use App\Models\Pembelian;
use Illuminate\Support\Facades\DB;

class ratingCustomerController extends Controller
{
    public function create()
    {
        
        $ratings = Rating::select('bintang', 'comment', 'id', 'created_at','kategori_id','no_pembeli','layanan')
        ->orderBy('created_at', 'DESC')
        ->paginate();
        
        return view('components.ratingcust', [
            'pay_method' => \App\Models\Method::all(),
            'nominal' => Pembelian::get(),
            'ratings' => $ratings,
        ]);
    }
    public function loadMoreData(Request $request) {
        if(isset($request->start)){
            $data = Rating::orderBy('created_at', 'DESC')->offset($request->start)->limit(5)->get();
            $res = '';
            foreach($data as $d){
                $kategoris = \DB::table('kategoris')->where('id',$d->kategori_id)->select('nama','thumbnail')->first();
                if($d->bintang == 5) {
                    $class_bintang = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
                } else if($d->bintang == 4) {
                    $class_bintang = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>';
                } else if($d->bintang == 3) {
                    $class_bintang = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
                } else if($d->bintang == 2) {
                    $class_bintang = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
                } else if($d->bintang == 1) {
                    $class_bintang = '<i class="fas fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
                } else if($d->bintang == 0) {
                    $class_bintang = '<i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
                }
                $ymdhis = explode(' ',$d->created_at);
                $month = [
                    1 => 'Januari','Februari','Maret','April','Mei','Juni',
                    'Juli','Agustus','September','Oktober','November','Desember'
                ];
                $explode = explode('-', $ymdhis[0]);
                $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
                $res .= '
    							<div class="pt-8 sm:inline-block sm:w-full sm:px-4">
                                    <figure class="card w-full rounded-2xl p-6 text-sm leading-6 shadow-form">
                                        <h3 class="font-bold">'.$kategoris->nama.'</h3>
                                        <blockquote class="mt-3 italic text-white"><p>“'.$d->comment.'”</p></blockquote>
                                        <figcaption class="mt-3 flex w-full flex-col items-center justify-center gap-x-4">
                                            <div class="flex w-full items-center justify-between">
                                                <div class="text-murky-300">08********'.substr($d->no_pembeli, -2).'</div>
                                                <div class="flex items-center">
                                                     <div class="star-rating">
                                                        <td>'.$class_bintang.'</td>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2 flex w-full items-center justify-between">
                                                <div class="text-xs text-murky-300">'.$d->layanan.'</div>
                                                <div class="flex items-center text-xs text-white">'.$formatted.' '.substr($ymdhis[1],0,5).' WIB</div>
                                            </div>
                                        </figcaption>
                                    </figure>
                                </div>
                ';
            }
            return response()->json([
                'status' => true,
                'data' => $res,
                'next' => $request->start + 5
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => 'Error',
                'next' => $request->start + 5
            ]);
        }
    }
}

