<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use App\Models\Pembayaran;
use App\Models\Kategori;
use App\Models\Pembelian;
use App\Models\Layanan;
use App\Helpers\Formater;
use Str;
use App\Mail\SendEmailbySaldo;

class getNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:notify';

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
        $formatter = new Formater;
        $pesanan = Pembelian::where('status', 'Success')->where('notify', '0')->get();
        
        set_time_limit(240);
        header('Content-Type: application/json');
        
        if(count($pesanan) == 0) {
            $output = [0 => ['result' => false,'data' => 'Tidak ada data order untuk mengirim notif email']];
        } else {
            foreach($pesanan as $data){
                
                $pembayaran = Pembayaran::where('order_id', $data->order_id)->first();
                $dataPembeli = Pembelian::where('order_id', $data->order_id)->first();
                $dataLayanan = Layanan::where('id', $dataPembeli->id_layanan)->first();
                $dataKategori = Kategori::where('id', $dataLayanan->kategori_id)->first();
                if($dataKategori->tipe == "sosmed") {
                    Mail::to($pembayaran->email)->send(new SendEmailbySaldo([
                        'datalayanan' => $dataLayanan,
                        'kategori' => $dataKategori,
                        'target' => $dataPembeli->user_id,
                        'target2' => $dataPembeli->zone,
                        'orderid' => $data->order_id,
                        'created_at' => $dataPembeli->created_at,
                        'pay' => $pembayaran->metode,
                        'qty' => $dataPembeli->quantity,
                        'status' => 'Success',
                        'total' => $dataPembeli->harga
                    ]));
                } else {
                    Mail::to($pembayaran->email_pembeli)->send(new SendEmail([
                        'pembelian' => $dataPembeli,
                        'kategori' => $dataKategori,
                        'pembayaran' => $pembayaran
                    ]));
                }
                
                Pembelian::where('order_id', $data->order_id)->update(['notify' => 1]);
                
                $output[] = ['result' => true,'data' => 'Sukses Mengirim Notif Email ke #'.$data->order_id];
            }
        }

        echo json_encode($output, JSON_PRETTY_PRINT);
    }
}
