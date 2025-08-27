<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artikel;
use App\Models\ArtikelTag;
use App\Models\LogUser;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Formater;
use Illuminate\Support\Str;

class ArtikelAdminController extends Controller
{
    public function create()
    {
        
        return view('components.admin.artikel.index', ['datas' => Artikel::orderBy('id', 'desc')->paginate(100)]);
    }
    public function add()
    {
        return view('components.admin.artikel.add', ['kategoris' => Kategori::get()]);
    }
    
    public function detail($id)
    {
        $data = Artikel::find($id);
        return view('components.admin.artikel.edit', compact('data'));
    }

    public function store(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $request->validate([
            'banner' => 'required|image|mimes:jpg,png,webp',
            'kategori' => 'required',
            'title' => 'required',
            'content' => 'required',
            'author' => 'required',
            'tags' => 'required'
        ]);
        
        $img = $request->file('banner');
        $filename = Str::random('15') . '.' . $img->extension();
        $img->move('assets/artikel/', $filename);
        
        $art = new Artikel();
        $art->title = $request->title;
        $art->url = Str::slug($request->title, '-');
        $art->path = "/assets/artikel/".$filename;
        $art->content = $request->content;
        $art->author = $request->author;
        $art->tags = $request->tags;
        $art->category = $request->kategori;
        $art->status = 'active';
        $art->save();
        
        if (!empty($request->tags)) {
            $tagList = array_filter(explode(",", $request->tags));
            $data = Artikel::where('url', Str::slug($request->title, '-'))->first();
            foreach ($tagList as $tags) {
                $tag = ArtikelTag::firstOrCreate(['artikel_id' => $data->id,'name' => $tags, 'slug' => Str::slug($request->title, '-')]);
            }
    
            $tags = ArtikelTag::whereIn('name', $tagList)->get()->pluck('id');
        }
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan penambahan Artikel '.$request->title;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();

        return back()->with('success', 'Berhasil menambahkan Artikel '.$request->title);
    }

    public function delete($id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        $data = Artikel::where('id', $id)->select('title')->first();

        Artikel::where('id', $id)->delete();
        @unlink($data->path);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan hapus Artikel '.$data->title;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
            
        return back()->with('success', 'Berhasil hapus Artikel '.$data->title);
    }
    
    public function update($id, $status)
    {
        $formatter = new Formater;
        $data = Artikel::where('id', $id)->select('title')->first();
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        Artikel::where('id', $id)->update(['status' => $status]);
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan update status Artikel '.$data->title;
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();
        
        return back()->with('success', 'Berhasil mengupdate status Artikel '.$data->title);
    }
    
    public function patch(Request $request, $id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        if($request->file('banner')){
            $img = $request->file('banner');
            $filename = Str::random('15') . '.' . $img->extension();
            $img->move('assets/artikel', $filename);
            Artikel::where('id', $id)->update([
                'path' => "/assets/artikel/".$filename
            ]);
        }
        
        Artikel::where('id', $id)->update([
            'category' => $request->kategori,
            'title' => $request->title,
            'author' => $request->author,
            'content' => $request->content,
            'tags' => $request->tags
        ]);
        
        $LogUser = new LogUser();
        $LogUser->user = Auth::user()->username;
        $LogUser->type = 'system';
        $LogUser->text = 'IP : '.$client_ip.' Melakukan update Artikel '.$request->title;
        $LogUser->ip = $client_ip;
        $LogUser->loc = $client_iploc;
        $LogUser->ua = $browser;
        $LogUser->save();
           
        return back()->with('success', 'Berhasil update Artikel '.$request->title);        
    }        
}
