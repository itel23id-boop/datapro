<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kategori;
use App\Models\Layanan;
use App\Models\Profit;
use App\Http\Controllers\ProviderApi\FanstoreController;
use Str;

class getFanstore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:fanstore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        header('Content-Type: application/json');
        $json = new FanstoreController;
        $res = $json->service();
        
        $api = \DB::table('setting_webs')->where('id',1)->first();
        if($api->fanstore_status == "Active") {
            foreach(Kategori::get() as $kategori){
                foreach($res['data'] as $data){
                    if($kategori->provider == "fanstore") {
                        if(Str::upper($data['category']) === Str::upper($kategori->brand)){
                            $cekgame = Layanan::where('provider_id',$data['id'])->first();
                            $cekprofit = Profit::where('provider', 'Fanstore')->first();
                            if($cekprofit->percent == "%") {
                                $harga = $data['price'] + ($data['price'] * $cekprofit->profit / 100);
                                $harga_member = $data['price'] + ($data['price'] * $cekprofit->profit_member / 100);
                                $harga_reseller = $data['price'] + ($data['price'] * $cekprofit->profit_reseller / 100);
                                $harga_vip = $data['price'] + ($data['price'] * $cekprofit->profit_vip / 100);
                            }else if($cekprofit->percent == "+") {
                                $harga = $data['price'] + $cekprofit->profit;
                                $harga_member = $data['price'] + $cekprofit->profit_member;
                                $harga_reseller = $data['price'] + $cekprofit->profit_reseller;
                                $harga_vip = $data['price'] + $cekprofit->profit_vip;
                            }
                            if(!$cekgame){
                                        
                                $layanan = new Layanan();
                                $layanan->layanan = $data['name'];
                                $layanan->kategori_id = $kategori->id;
                                $layanan->provider_id = $data['id'];
                                $layanan->harga_provider = $data['price'];
                                $layanan->harga = $harga;
                                $layanan->harga_member = $harga_member;
                                $layanan->harga_reseller = $harga_reseller;
                                $layanan->harga_vip = $harga_vip;
                                $layanan->min = $data['min'];
                                $layanan->max = $data['max'];
                                $layanan->catatan = $data['note'];
                                $layanan->tipe = $data['type'];
                                $layanan->status = "available";
                                $layanan->provider = "fanstore";
                                $layanan->save();  
                            }else{
                                $cekgame->update([
                                    'harga' => $harga,
                                    'harga_member' => $harga_member,
                                    'harga_reseller' => $harga_reseller,
                                    'harga_vip' => $harga_vip,
                                    'status' => "available"
                                ]);  
                            }
                        }
                    }
                }
                $output = [
                    'result' => true,
                    'data' => [
                        'service' => ['insert' => Layanan::where('status','=','available')->count(),'update' => Layanan::count()],
                    ],
                    'message' => 'Success!'
                ];
            }
        } else {
            $output = [0 => ['result' => false,'data' => 'Failed to retrieve FAN STORE data with inactive status']];
        }
        echo json_encode($output, JSON_PRETTY_PRINT);
    }
}
