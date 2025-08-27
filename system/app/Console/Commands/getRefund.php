<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pembayaran;
use App\Models\Pembelian;
use App\Models\User;

class getRefund extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:refund';

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
        $pesanan = Pembelian::join('pembayarans', 'pembelians.order_id', 'pembayarans.order_id')->where('pembelians.status', 'Failed')->where('pembelians.username', '!=', null)->where('refund', '0')
        ->where('pembayarans.metode_code', 'Saldo')->where('pembayarans.status', 'Lunas')->get();
        if(count($pesanan) == 0) {
            $output = [0 => ['result' => false,'data' => 'Tidak ada data order dengan status failed.']];
        } else {
            foreach($pesanan as $data){
                $wid = $data->order_id;
                $price = $data->harga;
                $user = $data->username;
                $users = User::where('username', $user)->first();
                
                User::where('username', $user)->update(['balance' => $price + $users->balance]);
                
                Pembelian::where('order_id', $wid)->update(['refund' => 1,'status' => 'Refund']);
                
                $output[] = ['result' => true,'data' => 'Refunded Rp '.number_format($price).' to '.$user.' (TID: #'.$wid.')'];
            }
        }
        echo json_encode($output, JSON_PRETTY_PRINT);
    }
}
