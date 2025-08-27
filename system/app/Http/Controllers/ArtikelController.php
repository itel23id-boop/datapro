<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel;
use App\Models\ArtikelTag;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    public function create()
    {
        return view('components.artikel', [
            'data' => Artikel::where('status', 'Active')->orderBy('updated_at', 'desc')->get(),
            'category' => Artikel::inRandomOrder()->where('status', 'Active')->limit(6)->get(),
            'tags' => ArtikelTag::inRandomOrder()->orderBy('updated_at', 'desc')->limit(9)->get(),
            'pay_method' => \App\Models\Method::all()
        ]);
    }
    public function search(Request $request)
    {
        $datas = Artikel::where('url', $request->slug)->first();
        $ymdhis = explode(' ',$datas->created_at);
            $month = [
            1 => 'Januari','Februari','Maret','April','Mei','Juni',
                'Juli','Agustus','September','Oktober','November','Desember'
            ];
        $explode = explode('-', $ymdhis[0]);
        $formatted = $explode[2].' '.$month[(int)$explode[1]].' '.$explode[0];
        return view('components.artikel.index', [
            'data' => $datas,
            'date' => $formatted, 'time' => $ymdhis,
            'artikel' => Artikel::orderBy('updated_at', 'desc')->get(),
            'artikeltags' => ArtikelTag::inRandomOrder()->orderBy('updated_at', 'desc')->limit(9)->get(),
            'pay_method' => \App\Models\Method::all()
        ]);
    }
    public function tags(Request $request)
    {
        $artikeltag = ArtikelTag::where('name', $request->tags)->first();
        return view('components.artikel.tag-artikel', [
            'tag_names' => $request->tags,
            'data' => Artikel::where('id', $artikeltag->artikel_id)->get(),
            'category' => Artikel::inRandomOrder()->where('status', 'Active')->limit(6)->get(),
            'tags' => ArtikelTag::inRandomOrder()->orderBy('updated_at', 'desc')->limit(9)->get(),
            'pay_method' => \App\Models\Method::all()
        ]);
    }
    public function category(Request $request)
    {
        $request->category = Str::title(Str::ucfirst(Str::replaceArray('-', [' '],$request->category)));
        return view('components.artikel.category-artikel', [
            'cat_names' => $request->category,
            'data' => Artikel::where('category',$request->category)->get(),
            'category' => Artikel::inRandomOrder()->where('status', 'Active')->limit(6)->get(),
            'tags' => ArtikelTag::inRandomOrder()->orderBy('updated_at', 'desc')->limit(9)->get(),
            'pay_method' => \App\Models\Method::all()
        ]);
    }
    public function cariIndex(Request $request)
    {
        if(isset($request->data)){
            $data = Artikel::where('title','LIKE','%'.$request->data.'%')->orwhere('url','LIKE','%'.$request->data.'%')->orwhere('content','LIKE','%'.$request->data.'%')->orwhere('tags','LIKE','%'.$request->data.'%')->orwhere('category','LIKE','%'.$request->data.'%')->where('status','active')->limit(6)->get();
            return $data;
        } else {
            $data = Artikel::where('status','active')->get();
            return $data;
        }
    }
}
