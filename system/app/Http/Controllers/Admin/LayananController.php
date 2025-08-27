<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Layanan;
use App\Models\Profit;
use App\Models\PaketLayanan;
use App\Models\LogUser;
use App\Models\LogoProduct;
use Illuminate\Support\Str;
use App\Helpers\Formater;
use Illuminate\Support\Facades\Auth;

class LayananController extends Controller
{
    public function create()
    {
        $datas = Layanan::join('kategoris', 'layanans.kategori_id', 'kategoris.id')->orderBy('id', 'ASC')
                ->select('layanans.*', 'kategoris.nama AS nama_kategori', 'kategoris.id AS id_kategori')->get();

        return view('components.admin.layanan.index', ['datas' => $datas, 'kategoris' => Kategori::get()]);
    }
    
    public function get_logo(Request $request)
    {
        $data = LogoProduct::where('category_id', $request->kategori)->get();
        
        if ($data == "[]") return response()->json(['status' => false, 'data' => '<option value="0">Logo tidak ditemukan!</option>']);
        
        $dataHtml = '<option value="0">- Select Logo -</option>';
        foreach ($data as $d) {
            $dataHtml .= '<option value="'.$d->id.'">'.$d->name.'</option>';
        }

        return response()->json([
            'status' => true,
            'data' => $dataHtml
        ]);
    }
    public function get_quantity(Request $request)
    {
            $dataHtml = '
                    <label for="" class="col-lg-1 col-form-label">Minimal</label>
                    <div class="col-lg-5">
                        <input type="number" class="form-control" name="min">
                    </div>
                    <label for="" class="col-lg-1 col-form-label">Maximal</label>
                    <div class="col-lg-5">
                        <input type="number" class="form-control" name="max">
                    </div>
            ';
        return response()->json([
            'status' => true,
            'data' => $dataHtml
        ]);
    }
    public function get_logo_view(Request $request)
    {
        $api = \DB::table('setting_webs')->where('id',1)->first();
        $data = LogoProduct::where('id', $request->id)->first();
        
        if (!$data) return response()->json(['status' => false, 'data' => 'Logo tidak ditemukan!']);
        
        $dataHtml = '<img src="'.$data->path.'" alt="" style="width:auto;height:55px;">';

        return response()->json([
            'status' => true,
            'data' => $dataHtml
        ]);
    }
    
    public function calculate(Request $request)
    {
        $kategori = Kategori::where('id', $request->kategori)->select('provider')->first();
        if (!$kategori) return response()->json(['status' => false, 'harga_public' => 0, 'harga_member' => 0, 'harga_reseller' => 0, 'harga_vip' => 0]);
        $harga = '';
        $harga_member = '';
        $harga_reseller= '';
        $harga_vip = '';
        if($kategori->provider != 'manual') {
            if($kategori->provider == "partaisocmed") {
                $cekprofit = Profit::where('provider', 'PartaiSocmed')->first();
                if($cekprofit->percent == "%") {
                    $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $request->harga_provider + $cekprofit->profit;
                    $harga_member = $request->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
                }
            } else if($kategori->provider == "irvankedesmm") {
                $cekprofit = Profit::where('provider', 'IrvanKedeSMM')->first();
                if($cekprofit->percent == "%") {
                    $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $request->harga_provider + $cekprofit->profit;
                    $harga_member = $request->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
                }
            } else if($kategori->provider == "vipmember") {
                $cekprofit = Profit::where('provider', 'Vipmember')->first();
                if($cekprofit->percent == "%") {
                    $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $request->harga_provider + $cekprofit->profit;
                    $harga_member = $request->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
                }
            } else if($kategori->provider == "istanamarket") {
                $cekprofit = Profit::where('provider', 'IstanaMarket')->first();
                if($cekprofit->percent == "%") {
                    $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $request->harga_provider + $cekprofit->profit;
                    $harga_member = $request->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
                }
            } else if($kategori->provider == "fanstore") {
                $cekprofit = Profit::where('provider', 'Fanstore')->first();
                if($cekprofit->percent == "%") {
                    $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $request->harga_provider + $cekprofit->profit;
                    $harga_member = $request->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
                }
            } else if($kategori->provider == "rasxmedia") {
                $cekprofit = Profit::where('provider', 'Rasxmedia')->first();
                if($cekprofit->percent == "%") {
                    $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $request->harga_provider + $cekprofit->profit;
                    $harga_member = $request->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
                }
            }
        } else {
            $cekprofit = Profit::where('provider', 'Manual')->first();
            if($cekprofit->percent == "%") {
                $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
            } else if($cekprofit->percent == "+") {
                $harga = $request->harga_provider + $cekprofit->profit;
                $harga_member = $request->harga_provider + $cekprofit->profit_member;
                $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
            }
        }
        return response()->json(['status' => true, 'harga_public' => $harga, 'harga_member' => $harga_member, 'harga_reseller' => $harga_reseller, 'harga_vip' => $harga_vip]);
    }

    public function store(Request $request)
    {
        $formatter = new Formater;
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        $request->validate([
            'nama' => 'required',
            'kategori' => 'required',
            'harga_provider' => 'required|numeric',
            'provider_id' => 'required|unique:layanans,provider_id',
        ]);
        
        $kategori = Kategori::where('id', $request->kategori)->select('provider')->first();
        
        if($kategori->provider != 'manual') {
            if($kategori->provider == "partaisocmed") {
                $cekprofit = Profit::where('provider', 'PartaiSocmed')->first();
                if($cekprofit->percent == "%") {
                    $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $request->harga_provider + $cekprofit->profit;
                    $harga_member = $request->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
                }
            } else if($kategori->provider == "irvankedesmm") {
                $cekprofit = Profit::where('provider', 'IrvanKedeSMM')->first();
                if($cekprofit->percent == "%") {
                    $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $request->harga_provider + $cekprofit->profit;
                    $harga_member = $request->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
                }
            } else if($kategori->provider == "vipmember") {
                $cekprofit = Profit::where('provider', 'Vipmember')->first();
                if($cekprofit->percent == "%") {
                    $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $request->harga_provider + $cekprofit->profit;
                    $harga_member = $request->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
                }
            } else if($kategori->provider == "istanamarket") {
                $cekprofit = Profit::where('provider', 'IstanaMarket')->first();
                if($cekprofit->percent == "%") {
                    $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $request->harga_provider + $cekprofit->profit;
                    $harga_member = $request->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
                }
            } else if($kategori->provider == "fanstore") {
                $cekprofit = Profit::where('provider', 'Fanstore')->first();
                if($cekprofit->percent == "%") {
                    $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $request->harga_provider + $cekprofit->profit;
                    $harga_member = $request->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
                }
            } else if($kategori->provider == "rasxmedia") {
                $cekprofit = Profit::where('provider', 'Rasxmedia')->first();
                if($cekprofit->percent == "%") {
                    $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $request->harga_provider + $cekprofit->profit;
                    $harga_member = $request->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
                }
            }
        } else {
            $cekprofit = Profit::where('provider', 'Manual')->first();
            if($cekprofit->percent == "%") {
                $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
            } else if($cekprofit->percent == "+") {
                $harga = $request->harga_provider + $cekprofit->profit;
                $harga_member = $request->harga_provider + $cekprofit->profit_member;
                $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
            }
        }
            
        $layanan = new Layanan();
        $layanan->kategori_id = $request->kategori;
        $layanan->layanan = $request->nama;
        $layanan->provider_id = $request->provider_id;
        $layanan->harga_provider = $request->harga_provider;
        $layanan->harga = $harga;
        $layanan->harga_member = $harga_member;
        $layanan->harga_reseller = $harga_reseller;
        $layanan->harga_vip = $harga_vip;
        $layanan->min = $request->min;
        $layanan->max = $request->max;
        $layanan->catatan = $request->description;
        $layanan->provider = $kategori->provider;
        $layanan->status = 'available';
        $layanan->product_logo = $request->product_logo;
        $layanan->save();
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan penambahan Layanan '.$request->nama;
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();
    
        return back()->with('success', 'Berhasil menginput Layanan'.$request->nama);
    }

    public function delete($id)
    {
        $formatter = new Formater;
        $data = Layanan::where('id', $id)->select('layanan')->first();
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        Layanan::where('id', $id)->delete();
        PaketLayanan::where('layanan_id', $id)->delete();
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan hapus Sub Produk '.$data->layanan;
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();
        
        return back()->with('success', 'Berhasil menghapus Sub Produk '.$data->layanan);
    }

    public function update($id, $status)
    {
        $formatter = new Formater;
        $data = Layanan::where('id', $id)->select('layanan')->first();
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        Layanan::where('id', $id)->update([
            'status' => $status
        ]);
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan update status Layanan '.$data->layanan;
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();
        
        return back()->with('success', 'Berhasil mengupdate Layanan '.$data->layanan);
    }
    
    public function sync()
    {
        $layanan = Layanan::get();
        foreach($layanan as $ds) {
            if($ds->provider != 'manual') {
                if($ds->provider == "partaisocmed") {
                    $cekprofit = Profit::where('provider', 'PartaiSocmed')->first();
                    if($cekprofit->percent == "%") {
                        $harga = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit / 100);
                        $harga_member = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit_member / 100);
                        $harga_reseller = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit_reseller / 100);
                        $harga_vip = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit_vip / 100);
                    } else if($cekprofit->percent == "+") {
                        $harga = $ds->harga_provider + $cekprofit->profit;
                        $harga_member = $ds->harga_provider + $cekprofit->profit_member;
                        $harga_reseller = $ds->harga_provider + $cekprofit->profit_reseller;
                        $harga_vip = $ds->harga_provider + $cekprofit->profit_vip;
                    }
                } else if($ds->provider == "irvankedesmm") {
                    $cekprofit = Profit::where('provider', 'IrvanKedeSMM')->first();
                    if($cekprofit->percent == "%") {
                        $harga = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit / 100);
                        $harga_member = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit_member / 100);
                        $harga_reseller = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit_reseller / 100);
                        $harga_vip = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit_vip / 100);
                    } else if($cekprofit->percent == "+") {
                        $harga = $ds->harga_provider + $cekprofit->profit;
                        $harga_member = $ds->harga_provider + $cekprofit->profit_member;
                        $harga_reseller = $ds->harga_provider + $cekprofit->profit_reseller;
                        $harga_vip = $ds->harga_provider + $cekprofit->profit_vip;
                    }
                } else if($ds->provider == "Vipmember") {
                    $cekprofit = Profit::where('provider', 'vipmember')->first();
                    if($cekprofit->percent == "%") {
                        $harga = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit / 100);
                        $harga_member = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit_member / 100);
                        $harga_reseller = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit_reseller / 100);
                        $harga_vip = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit_vip / 100);
                    } else if($cekprofit->percent == "+") {
                        $harga = $ds->harga_provider + $cekprofit->profit;
                        $harga_member = $ds->harga_provider + $cekprofit->profit_member;
                        $harga_reseller = $ds->harga_provider + $cekprofit->profit_reseller;
                        $harga_vip = $ds->harga_provider + $cekprofit->profit_vip;
                    }
                } else if($ds->provider == "istanamarket") {
                    $cekprofit = Profit::where('provider', 'IstanaMarket')->first();
                    if($cekprofit->percent == "%") {
                        $harga = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit / 100);
                        $harga_member = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit_member / 100);
                        $harga_reseller = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit_reseller / 100);
                        $harga_vip = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit_vip / 100);
                    } else if($cekprofit->percent == "+") {
                        $harga = $ds->harga_provider + $cekprofit->profit;
                        $harga_member = $ds->harga_provider + $cekprofit->profit_member;
                        $harga_reseller = $ds->harga_provider + $cekprofit->profit_reseller;
                        $harga_vip = $ds->harga_provider + $cekprofit->profit_vip;
                    }
                } else if($ds->provider == "fanstore") {
                    $cekprofit = Profit::where('provider', 'Fanstore')->first();
                    if($cekprofit->percent == "%") {
                        $harga = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit / 100);
                        $harga_member = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit_member / 100);
                        $harga_reseller = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit_reseller / 100);
                        $harga_vip = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit_vip / 100);
                    } else if($cekprofit->percent == "+") {
                        $harga = $ds->harga_provider + $cekprofit->profit;
                        $harga_member = $ds->harga_provider + $cekprofit->profit_member;
                        $harga_reseller = $ds->harga_provider + $cekprofit->profit_reseller;
                        $harga_vip = $ds->harga_provider + $cekprofit->profit_vip;
                    }
                } else if($ds->provider == "rasxmedia") {
                    $cekprofit = Profit::where('provider', 'Rasxmedia')->first();
                    if($cekprofit->percent == "%") {
                        $harga = $ds->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                        $harga_member = $ds->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                        $harga_reseller = $ds->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                        $harga_vip = $ds->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                    } else if($cekprofit->percent == "+") {
                        $harga = $ds->harga_provider + $cekprofit->profit;
                        $harga_member = $ds->harga_provider + $cekprofit->profit_member;
                        $harga_reseller = $ds->harga_provider + $cekprofit->profit_reseller;
                        $harga_vip = $ds->harga_provider + $cekprofit->profit_vip;
                    }
                }
            } else {
                $cekprofit = Profit::where('provider', 'Manual')->first();
                if($cekprofit->percent == "%") {
                    $harga = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $ds->harga_provider + ($ds->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $ds->harga_provider + $cekprofit->profit;
                    $harga_member = $ds->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $ds->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $ds->harga_provider + $cekprofit->profit_vip;
                }
            }
            Layanan::where('id', $ds->id)->update([
                'harga' => $harga,
                'harga_member' => $harga_member,
                'harga_reseller' => $harga_reseller,
                'harga_vip' => $harga_vip,
            ]);
        }
        return back()->with('success', 'Berhasil sync Profit Produk Ootmatis by Provider!');
    }
    
    public function detail($id)
    {
        $data = Layanan::where('layanans.id', $id)->join('kategoris', 'layanans.kategori_id', 'kategoris.id')
                ->select('layanans.*', 'kategoris.nama AS nama_kategori', 'kategoris.id AS id_kategori','kategori_id')->first();
        $kategoris = Kategori::get();
        $kategoriss = Kategori::where('id', $data->kategori_id)->first();

        return view('components.admin.layanan.edit', compact('data', 'kategoris', 'kategoriss'));
    }
    
    public function patch(Request $request, $id)
    {
        $formatter = new Formater;
        $data = Layanan::where('id', $id)->select('layanan')->first();
        $client_ip = $formatter->client_ip();
        $client_iploc = $formatter->client_iploc($client_ip);
        $browser = $formatter->devices();
        
        if($request->provider != 'manual') {
            if($request->provider == "partaisocmed") {
                $cekprofit = Profit::where('provider', 'PartaiSocmed')->first();
                if($cekprofit->percent == "%") {
                    $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $request->harga_provider + $cekprofit->profit;
                    $harga_member = $request->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
                }
            } else if($request->provider == "irvankedesmm") {
                $cekprofit = Profit::where('provider', 'IrvanKedeSMM')->first();
                if($cekprofit->percent == "%") {
                    $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $request->harga_provider + $cekprofit->profit;
                    $harga_member = $request->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
                }
            } else if($request->provider == "vipmember") {
                $cekprofit = Profit::where('provider', 'Vipmember')->first();
                if($cekprofit->percent == "%") {
                    $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $request->harga_provider + $cekprofit->profit;
                    $harga_member = $request->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
                }
            } else if($request->provider == "istanamarket") {
                $cekprofit = Profit::where('provider', 'IstanaMarket')->first();
                if($cekprofit->percent == "%") {
                    $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $request->harga_provider + $cekprofit->profit;
                    $harga_member = $request->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
                }
            } else if($request->provider == "fanstore") {
                $cekprofit = Profit::where('provider', 'Fanstore')->first();
                if($cekprofit->percent == "%") {
                    $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $request->harga_provider + $cekprofit->profit;
                    $harga_member = $request->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
                }
            } else if($request->provider == "rasxmedia") {
                $cekprofit = Profit::where('provider', 'Rasxmedia')->first();
                if($cekprofit->percent == "%") {
                    $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                    $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                    $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                    $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
                } else if($cekprofit->percent == "+") {
                    $harga = $request->harga_provider + $cekprofit->profit;
                    $harga_member = $request->harga_provider + $cekprofit->profit_member;
                    $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                    $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
                }
            }
        } else {
            $cekprofit = Profit::where('provider', 'Manual')->first();
            if($cekprofit->percent == "%") {
                $harga = $request->harga_provider + ($request->harga_provider * $cekprofit->profit / 100);
                $harga_member = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_member / 100);
                $harga_reseller = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_reseller / 100);
                $harga_vip = $request->harga_provider + ($request->harga_provider * $cekprofit->profit_vip / 100);
            } else if($cekprofit->percent == "+") {
                $harga = $request->harga_provider + $cekprofit->profit;
                $harga_member = $request->harga_provider + $cekprofit->profit_member;
                $harga_reseller = $request->harga_provider + $cekprofit->profit_reseller;
                $harga_vip = $request->harga_provider + $cekprofit->profit_vip;
            }
        }
        
        Layanan::where('id', $id)->update([
            'layanan' => $request->layanan,
            'provider' => $request->provider,
            'provider_id' => $request->provider_id,
            'harga_provider' => $request->harga_provider,
            'harga' => $harga,
            'harga_member' => $harga_member,
            'harga_reseller' => $harga_reseller,
            'harga_vip' => $harga_vip,
            'status' => $request->status,
        ]);
        
        if($request->product_logo != 0) {
            $logo_p = LogoProduct::where('id', $request->product_logo)->first();
            Layanan::where('id', $id)->update([
                'product_logo' => $logo_p->path
            ]);
        }
        
        $log = new LogUser();
        $log->user = Auth::user()->username;
        $log->type = 'system';
        $log->text = 'IP : '.$client_ip.' Melakukan update Layanan '.$data->layanan;
        $log->ip = $client_ip;
        $log->loc = $client_iploc;
        $log->ua = $browser;
        $log->save();
           
        return back()->with('success', 'Berhasil update Layanan '.$data->layanan);        
    }
}
