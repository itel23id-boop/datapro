<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita as BeritaModel;
use Illuminate\Support\Facades\Auth;
use App\Models\LogUser;
use App\Helpers\Formater;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Berita extends Controller
{
    public function create()
    {
        return view('components.admin.berita', ['berita' => BeritaModel::all()]);
    }

    public function post(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $request->validate([
            'banner' => 'required|file|mimes:jpg,png',
            'tipe' => 'required'
        ]);

        $file = $request->file('banner');
        $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
        $storedPath = $file->storeAs('banners', $filename, 'public');

        $berita = new BeritaModel();
        $berita->path = Storage::url($storedPath);
        $berita->deskripsi = isset($request->deskripsi) ? $request->deskripsi : null;
        $berita->tipe = $request->tipe;
        $berita->save();
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan upload slide/banner';
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();

        return back()->with('success', 'Berhasil upload!');
    }

    public function delete($id)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();

        $berita = BeritaModel::findOrFail($id);
        if ($berita->path) {
            $relativePath = str_replace('/storage/', '', $berita->path);
            Storage::disk('public')->delete($relativePath);
        }
        $berita->delete();
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan hapus data slide/banner';
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();
        
        return back()->with('success', 'Berhasil hapus!');

    }
}
