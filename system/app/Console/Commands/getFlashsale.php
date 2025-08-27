<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Promo;
use App\Models\Layanan;

class getFlashsale extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:flashsale';

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
        
        $promo = Promo::get();
        
        if(count($promo) == 0) {
            //$output = [0 => ['result' => false,'data' => 'Tidak ada data flashsale.']];
        } else {
            foreach($promo as $data){
                if(date('Y-m-d H:i:s') > $data->expired_flash_sale){
                    Promo::where('id', $data->id)->delete();
                    
                    Layanan::where('id', $data->id_layanan)->update(['is_flash_sale' => 'No','expired_flash_sale' => null,'harga_flash_sale' => null]);
                    
                    //$output[] = ['result' => true,'data' => 'Sukses Menghapus flashsale '.$data->nama];
                }
            }
        }
        echo 'Sukses';
    }
}
