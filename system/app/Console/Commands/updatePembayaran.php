<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pembayaran;
use App\Models\Pembelian;
use App\Models\Voucher;
use App\Models\VoucherList;
use Illuminate\Support\Carbon;

class updatePembayaran extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:pembayaran';

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
        $date = Carbon::now();
        $pembayaran = Pembayaran::where('status', 'Menunggu Pembayaran')->get();
        
        if(count($pembayaran) == 0) {
            //$output = ['result' => false,'data' => 'Tidak ada data Pembayaran dengan status Menunggu Pembayaran.'];
        } else {
            foreach($pembayaran as $data){
                $api = \DB::table('setting_webs')->where('id',1)->first();
                $pembayarans = Pembayaran::where('order_id', $data->order_id)->first();
                $expired = date('Y-m-d H:i:s', strtotime('+'.$api->expired_invoice_hours.' Hours +'.$api->expired_invoice_minutes.' minutes', strtotime($pembayarans->created_at)));
                $formatTanggal = Carbon::parse($date)->format('Y-m-d H:i:s');
                if($formatTanggal > $expired){
                    $voc = Voucher::where('kode', $pembayarans->voucher_code)->first();
                    if($voc) {
                        Voucher::where('kode', $pembayarans->voucher_code)->update(['stock' => $voc->stock + 1]);
                        $voc_list = VoucherList::where('kode', $pembayarans->voucher_code)->first();
                        if($voc_list) {
                            VoucherList::where('kode', $pembayarans->voucher_code)->update(['limit' => $voc_list->limit - 1]);
                        }
                    }
                    Pembayaran::where('order_id', $pembayarans->order_id)->update(['status' => 'Expired']);
                    
                    Pembelian::where('order_id', $pembayarans->order_id)->update(['status' => 'Failed','note' => 'Pembayaran anda kadaluarsa.']);
                    
                    //$output[] = ['result' => true,'data' => 'Sukses Update Pembayaran #'.$data->order_id];
                }
            }
        }
        echo 'Sukses';
    }
}
