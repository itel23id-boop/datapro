<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pembelian;
use App\Models\User;
use App\Models\Layanan;
use App\Models\Kategori;
use App\Helpers\Formater;
use App\Models\Mutation;
use App\Http\Controllers\ProviderApi\PartaisocmedController;
use App\Http\Controllers\ProviderApi\IrvankedesmmController;
use App\Http\Controllers\ProviderApi\VipMemberController;
use App\Http\Controllers\ProviderApi\IstanaMarketController;
use App\Http\Controllers\ProviderApi\FanstoreController;
use App\Http\Controllers\ProviderApi\RasxmediaController;
use App\Http\Controllers\WhatsappController;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class updatePesanan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:pesanan';

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
        $pesanan = Pembelian::Where('status', 'Pending')
                            ->orWhere('status', 'Processing')
                            ->get();
        $formatter = new Formater;
        $irvankedesmm= new IrvankedesmmController;
        $partaisocmed= new PartaisocmedController;
        $vipmember= new VipMemberController;
        $istanamarket= new IstanaMarketController;
        $fanstore= new FanstoreController;
        $rasxmedia= new RasxmediaController;
        $wa = new WhatsappController;
        
        $api = \DB::table('setting_webs')->where('id',1)->first();
        
        foreach($pesanan as $data) {
            $pesan = "Pembelian *$data->layanan* Telah Berhasil Dikirim, Silahkan Cek Transaksi Anda, Terima kasih Sudah Order\n\n".
                     "Jika Pesanan Anda Belum Masuk Harap Hubungi Admin\n".
                     "Whatsapp : ".$api->nomor_admin;

            $pembayaran = Pembayaran::where('order_id', $data->order_id)->first();

            $layanan = Layanan::where('id', $data->id_layanan)->first();
            $kategori = Kategori::where('id', $layanan->kategori_id)->first();
            
            $uid = $data->user_id;
            $order_id = $data->order_id;
            
            $provider_order_id = $data->provider_order_id;
                
            if($layanan->provider == "partaisocmed"){
                $checking = $partaisocmed->status($data->provider_order_id);
                $checking['data']['status'] = $checking['data']['status'];
                $remains = $checking['data']['remains'];
                $counts = $checking['data']['start_count'];
                $sn = null;
            }else if($layanan->provider == "irvankedesmm"){
                $checking = $irvankedesmm->status($data->provider_order_id);
                $checking['data']['status'] = $checking['data']['status'];
                $remains = $checking['data']['remains'];
                $counts = $checking['data']['start_count'];
                $sn = null;
            }else if($layanan->provider == "vipmember"){
                $checking = $vipmember->status($data->provider_order_id);
                $checking['data']['status'] = $checking['data']['status'];
                $remains = $checking['data']['remains'];
                $counts = $checking['data']['start_count'];
                $sn = null;
            }else if($layanan->provider == "istanamarket"){
                $checking = $istanamarket->status($data->provider_order_id);
                $checking['data']['status'] = $checking['data']['status'];
                $remains = $checking['data']['remains'];
                $counts = $checking['data']['start_count'];
                $sn = null;
            }else if($layanan->provider == "fanstore"){
                $checking = $fanstore->status($data->provider_order_id);
                $checking['data']['status'] = $checking['data']['status'];
                $remains = $checking['data']['remains'];
                $counts = $checking['data']['start_count'];
                $sn = null;
            }else if($layanan->provider == "rasxmedia"){
                $checking = $rasxmedia->status($data->provider_order_id);
                $checking['data']['status'] = $checking['data']['status'];
                $remains = $checking['data']['remains'];
                $counts = $checking['data']['start_count'];
                $sn = null;
            }

            if($checking['result'] == true){
                if($formatter->status($checking['data']['status']) == "Success"){
                    if(date('Y-m-d',strtotime($data->created_at)) == date('Y-m-d')){
                        $wa->send($pembayaran->no_pembeli,$pesan); 
                    } 
                    Pembelian::where('order_id', $order_id)
                        ->update(['note' => $sn, 'remain' => $remains, 'count' => $counts, 'status' => $formatter->status($checking['data']['status']), 'log' => json_encode($checking)]);
                } else {
                    Pembelian::where('order_id', $order_id)
                        ->update(['note' => $sn, 'remain' => $remains, 'count' => $counts, 'status' => $formatter->status($checking['data']['status']), 'log' => json_encode($checking)]);
                }
                
            } else {
                $output = [0 => ['result' => false,'data' => $checking['message']]]; 
            }
        }
        $output = [
            'result' => true,
            'data' => [
                'service' => ['update' => Pembelian::count()],
            ],
            'message' => 'Success!'
        ];
        echo json_encode($output, JSON_PRETTY_PRINT);
    }
     
}
