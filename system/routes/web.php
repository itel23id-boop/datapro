<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\indexController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CariController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\SyaratKetentuanController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\PricelistController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ratingCustomerController;
use App\Http\Controllers\ArtikelController;
#### CALLBACK Payment Gateway ####
use App\Http\Controllers\PaymentGateway\duitkuCallbackController;
use App\Http\Controllers\PaymentGateway\TriPayCallbackController;
use App\Http\Controllers\PaymentGateway\TokoPayCallbackController;
use App\Http\Controllers\PaymentGateway\iPaymuController;
use App\Http\Controllers\PaymentGateway\LinkQuCallbackController;
use App\Http\Controllers\PaymentGateway\PaydisiniController;
use App\Http\Controllers\PaymentGateway\XenditController;
#### End ####
### Admin ###
use App\Http\Controllers\Users\DsController;
use App\Http\Controllers\Users\UpgradeController;
use App\Http\Controllers\Users\DepositController;
#### End ####
### Admin ###
use App\Http\Controllers\Admin\KategoriTipeController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KategoriInputController;
use App\Http\Controllers\Admin\LayananController;
use App\Http\Controllers\Admin\MethodController;
use App\Http\Controllers\Admin\OrderManualController;
use App\Http\Controllers\Admin\HistoryPembayaranController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\SettingWebController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ProfitController;
use App\Http\Controllers\Admin\DataJokiController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrder;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanOrder;
use App\Http\Controllers\Admin\OvoController;
use App\Http\Controllers\Admin\GojekController;
use App\Http\Controllers\Admin\Berita;
use App\Http\Controllers\Admin\PaketController;
use App\Http\Controllers\Admin\PaketLayananController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\LogWebsiteController;
use App\Http\Controllers\Admin\LogoProductController;
use App\Http\Controllers\Admin\MultipledController;
use App\Http\Controllers\Admin\PhoneCountryController;
use App\Http\Controllers\Admin\TopRankingController;
use App\Http\Controllers\Admin\PertanyaanUmumController;
use App\Http\Controllers\Admin\ArtikelAdminController;
#### End ####
Route::get('/web/mt', function(){
   Illuminate\Support\Facades\Artisan::call("down");
});

Route::get('/web/up', function(){
    Illuminate\Support\Facades\Artisan::call("up");
});

Route::get('/getistanamarket', function(){
    Illuminate\Support\Facades\Artisan::call("get:istanamarket");
});
Route::get('/getpartaisocmed', function(){
    Illuminate\Support\Facades\Artisan::call("get:partaisocmed");
});
Route::get('/getirvankede', function(){
    Illuminate\Support\Facades\Artisan::call("get:irvankede");
});
Route::get('/getvipmember', function(){
    Illuminate\Support\Facades\Artisan::call("get:vipmember");
});
Route::get('/updatepesanan', function(){
    Illuminate\Support\Facades\Artisan::call("update:pesanan");
});
Route::get('/getnotify', function(){
    Illuminate\Support\Facades\Artisan::call("get:notify");
});

    Route::get('/user/dashboard',   [DsController::class,'dashboard'])->middleware('auth');
    Route::get('/user/orders',  [DsController::class,'orders'])->middleware('auth');
    Route::post('/user/orders',  [DsController::class,'orders'])->middleware('auth');
    Route::get('/user/settings',  [DsController::class,'settings'])->middleware('auth');
    Route::get('/user/topup/riwayat',  [DsController::class,'riwayat'])->middleware('auth');
    Route::get('/user/upgrade/riwayat',  [DsController::class,'riwayat_upgrade'])->middleware('auth');
    Route::get('/user/edit/profile',[DsController::class,'editProfile'])->middleware('auth');
    Route::post('/cari/index',[IndexController::class,'cariIndex']);
    Route::get('/',                                             [indexController::class, 'create'])->name('home');
    Route::get('/getnotif',                                             [indexController::class, 'get_notif'])->name('getnotif');
    Route::get('404', [indexController::class, 'notFound'])->name('404');
    Route::get('500', [indexController::class, 'error'])->name('500');
    Route::get('/order/{kategori:kode}',                         [OrderController::class, 'create']);
    Route::get('/order/description/{id}',                                  [OrderController::class, 'description'])->name('ajax.description');
    Route::get('/order/harga/{id}/{qty}',                                  [OrderController::class, 'price'])->name('ajax.price');
    Route::post('/order/konfirmasi-data',                        [OrderController::class, 'confirm'])->name('ajax.confirm-data');
    Route::post('/order/pembelian',                              [OrderController::class, 'store'])->name('order');
    Route::get('/pembelian/invoice/{order}',                    [InvoiceController::class, 'create'])->name('pembelian');
    Route::post('/pembelian/invoice/{order}',                    [InvoiceController::class, 'ratingCustomer'])->name('rating.pembelian');
    Route::get('/pembelian/invoice/get-invoice/{order}',                                        [InvoiceController::class, 'getinvoice']);
    Route::post('/check-voucher',                               [VoucherController::class, 'confirm'])->name('check.voucher');
    
    
    
    Route::get('/cari',                                         [CariController::class, 'create'])->name('cari');
    Route::post('/cari',                                        [CariController::class, 'store'])->name('cari.post');
    Route::get('/gethistory',                                        [CariController::class, 'gethistory'])->name('cari.get-history');
    
    Route::get('/reviews',                                [ratingCustomerController::class, 'create'])->name('reviews');
    Route::post('/load-more-data',                       [ratingCustomerController::class,'loadMoreData'])->name('load.more');
    
    Route::get('/daftar-harga',                                 [PricelistController::class, 'create'])->name('price');
    Route::get('/daftar-harga/{id}',                                 [PricelistController::class, 'create']);
    
    Route::get('/page/question',                                 [QuestionController::class, 'create'])->name('question');
    Route::get('/page/help',                                 [HelpController::class, 'create'])->name('help');
    Route::post('/page/help',                                 [HelpController::class, 'pesan'])->name('post.pesan');
    Route::get('/page/terms',                                 [SyaratKetentuanController::class, 'create'])->name('syaratketentuan');
    Route::get('/page/privacy-policy',                                 [PrivacyPolicyController::class, 'create'])->name('privacypolicy');
    
    Route::post('/v1/produk',                               [ApiController::class,'produk']);
    Route::get('/v1/produk',                                [ApiController::class,'produk']);
    Route::post('/v1/orders',                               [ApiController::class,'orders']);
    Route::get('/v1/status',                                [ApiController::class,'status']);
    Route::post('/v1/cekid',                                [ApiController::class,'cekid']);
    
    Route::get('/artikel',                                         [ArtikelController::class, 'create'])->name('artikel');
    Route::post('/artikel/cari',                                   [ArtikelController::class,'cariIndex']);
    Route::get('/artikel/{slug}',                                         [ArtikelController::class, 'search'])->name('artikel.search');
    Route::get('/artikel/tags/{tags}',                                         [ArtikelController::class, 'tags'])->name('artikel.tags');
    Route::get('/artikel/category/{category}',                                         [ArtikelController::class, 'category'])->name('artikel.category');
    
    Route::middleware(['guest'])->group(function () {
        Route::get('/login',                                            [LoginController::class, 'create'])->name('login');
        Route::post('/login',                                           [LoginController::class, 'store'])->name('post.login');
        
        Route::get('/register',                                         [RegisterController::class, 'create'])->name('register');
        Route::post('/register',                                        [RegisterController::class, 'store'])->name('post.register');
        Route::post('/register/otp/verify',                             [RegisterController::class, 'verifyOtp'])->name('verify-otp.register');
        
        Route::get('/forgot-password',                              [ForgotPasswordController::class,'forgotPassword']);
        Route::post('/forgot-password',                             [ForgotPasswordController::class,'sendOtp']);
        Route::post('/verify/otp',                                  [ForgotPasswordController::class,'verifyOtp']);
    });
    
    Route::post('/callback',                                    [TriPayCallbackController::class, 'handle']);
    Route::post('/callback_duitku',                                    [duitkuCallbackController::class, 'handle']);
    Route::post('/callback_ipaymu',                                    [iPaymuController::class, 'handle']);
    Route::post('/callback_tokopay',                                    [TokoPayCallbackController::class, 'handle']);
    Route::post('/callback_linkqu',                                         [LinkQuCallbackController::class, 'handle']);
    Route::post('/callback_paydisini',                                         [PaydisiniController::class, 'handle']);
    Route::post('/callback_xendit',                                         [XenditController::class, 'handle']);
    
    Route::middleware(['auth'])->group(function(){
        
        Route::get('/logout',                                  [LoginController::class, 'destroy'])->name('logout');
        
        Route::post('/user/edit/profile',[DsController::class,'saveEditProfile'])->name('setting');
        
        Route::get('/user/topup',                                  [DepositController::class, 'create'])->name('topup');
        Route::post('/topup/price',                           [DepositController::class,'price'])->name('ajax.ptopup');
        Route::post('/topup',                                 [DepositController::class, 'store'])->name('topup.post');
        Route::get('/topup/invoice/{order}',                  [DepositController::class,'invoice_topup'])->name('ajax.topup');
        
        Route::get('/user/upgrade',                             [UpgradeController::class, 'create'])->name('upgrade');
        Route::post('/upgrade/price',                           [UpgradeController::class,'price'])->name('upgrade.price');
        Route::post('/upgrade',                                 [UpgradeController::class, 'store'])->name('upgrade.post');
        Route::get('/upgrade/invoice/{order}',                  [UpgradeController::class,'invoice_upgrade']);
    });
    
    Route::middleware(['auth', 'check.role'])->group(function (){
        Route::get('/dashboard',                                [DashboardController::class, 'create'])->name('dashboard');
    
        //History Transaksi
        Route::get('/pesanan',                                  [AdminOrder::class, 'create'])->name('pesanan');
        Route::get('/order-status/{order_id}/{status}',         [AdminOrder::class, 'update']);
        
        //Laporan Transaksi
        Route::get('/laporan',                                  [AdminLaporanOrder::class, 'create'])->name('laporan');
        
        //log
        Route::get('/log-website',                                  [LogWebsiteController::class, 'create'])->name('log-website');
        
        //Top Ranking
        Route::get('/top-ranking',                                       [TopRankingController::class, 'create'])->name('top-ranking');
    
        //Mutasi Ovo
        Route::get('/ovo',                                      [OvoController::class, 'create'])->name('ovo');
        Route::post('/ovo',                                     [OvoController::class, 'store'])->name('ovo.post');
        Route::get('/ovo/Get-OTP/{no}',                         [OvoController::class, 'GetOTP']);
        Route::post('/ovo/Validasi-OTP',                        [OvoController::class, 'VerifOTP']);
        Route::post('/ovo/Validasi-PIN',                        [OvoController::class, 'VerifPIN']);
        Route::get('/Ovo-Transaksi',                            [OvoController::class, 'getTransaction']);
    
        //Mutasi Gopay
        Route::get('/gopay',                                    [GojekController::class, 'create'])->name('gopay');
        Route::post('/gopay',                                   [GojekController::class, 'store'])->name('gopay.post');
        Route::get('/gopay/Gojek-OTP/{no}',                     [GojekController::class, 'GetOTP']);
        Route::post('/gopay/Gojek-validasi',                    [GojekController::class, 'VerifOTP']);
        Route::get('/Gopay-Transaksi',                          [GojekController::class, 'getTransaction'])->name('gopay.transaction');
    
        //Berita
        Route::get('/berita',                                   [Berita::class, 'create'])->name('berita');
        Route::post('/berita',                                  [Berita::class, 'post'])->name('berita.post');
        Route::get('/berita/hapus/{id}',                        [Berita::class, 'delete'])->name('berita.delete');
    
        //Kategori
        Route::get('/kategori',                                 [KategoriController::class, 'create'])->name('kategori');
        Route::get('/kategori/add',                                [KategoriController::class, 'add'])->name('kategori.add');
        Route::get('/kategori/{id}/detail',                                [KategoriController::class, 'detail'])->name('kategori.detail');
        Route::post('/kategori',                                [KategoriController::class, 'store'])->name('kategori.post');
        Route::get('/kategori/hapus/{id}',                      [KategoriController::class, 'delete'])->name('kategori.delete');
        Route::get('/kategori-status/{id}/{status}',             [KategoriController::class, 'update'])->name('kategori.update');
        Route::get('/kategori/tipe/{id}/{code}',             [KategoriController::class, 'update_tab'])->name('tab.kategori.update');
        Route::post('/kategori/validasi',                           [KategoriController::class, 'validasi'])->name('kategori.validasi');
        Route::get('/kategori/{id}/edit',                     [KategoriController::class, 'edit'])->name('kategori.edit');
        Route::post('/kategori/{id}/edit',                     [KategoriController::class, 'patch'])->name('kategori.edit.update');
        
        //Kategori Input
        Route::get('/kategori-input/{id}/create',               [KategoriInputController::class, 'create'])->name('input.create');
        Route::get('/kategori-input/{id}/create-edit',          [KategoriInputController::class, 'create_edit'])->name('input.create-edit');
        Route::get('/kategori-input/{id}/create-pilihan',       [KategoriInputController::class, 'create_pilihan'])->name('input.create-pilihan');
        Route::post('/kategori-input/',                         [KategoriInputController::class, 'store'])->name('input.store');
        Route::post('/kategori-input/{id}/edit',                [KategoriInputController::class, 'edit'])->name('input.edit');
        Route::post('/kategori-input/{id}/update',              [KategoriInputController::class, 'update'])->name('input.update');
        Route::get('/kategori-input/hapus/{id}',              [KategoriInputController::class, 'destroy'])->name('input.destroy');
    
        //Layanan
        Route::get('/layanan',                                  [LayananController::class, 'create'])->name('layanan');
        Route::post('/layanan',                                 [LayananController::class, 'store'])->name('layanan.post');
        Route::post('/layanan/calculate',                         [LayananController::class,'calculate'])->name('layanan.calculate');
        Route::post('/layanan/get-logo',                         [LayananController::class,'get_logo'])->name('layanan.get-logo');
        Route::post('/layanan/get-logo-view',                         [LayananController::class,'get_logo_view'])->name('layanan.get-logo-view');
        Route::post('/layanan/get-quantity',                         [LayananController::class,'get_quantity'])->name('layanan.get-quantity');
        Route::get('/layanan/hapus/{id}',                       [LayananController::class, 'delete'])->name('layanan.delete');
        Route::get('/layanan/all-hapus',                            [LayananController::class, 'AllDelete'])->name('layanan.deleteAll');
        Route::get('/layanan-status/{id}/{status}',             [LayananController::class, 'update'])->name('layanan.update');
        
        Route::get('/layanan/{id}/detail',                      [LayananController::class, 'detail'])->name('layanan.detail');
        Route::post('/layanan/{id}/update',                     [LayananController::class, 'patch'])->name('layanan.detail.update');
        Route::post('/layanan/sync/',                           [LayananController::class, 'sync'])->name('sync.layanan.get.post');
        
        //Multiple update logo layanan
        Route::post('/multiple/update',                      [MultipledController::class, 'multiple'])->name('multiple.update');
        
        //Kategori Tipe
        Route::get('/kategori-tipe',                                 [KategoriTipeController::class, 'create'])->name('kategori-tipe');
        Route::post('/kategori-tipe',                                [KategoriTipeController::class, 'store'])->name('kategori-tipe.post');
        Route::get('/kategori-tipe/hapus/{id}',                      [KategoriTipeController::class, 'delete'])->name('kategori-tipe.delete');
        
        Route::get('/kategori-tipe/{id}/detail',                     [KategoriTipeController::class, 'detail'])->name('kategori-tipe.detail');
        Route::post('/kategori-tipe/{id}/detail',                     [KategoriTipeController::class, 'patch'])->name('kategori-tipe.detail.update');
        
        //Logo Produk
        Route::get('/logo-product',                                 [LogoProductController::class, 'create'])->name('logo-product');
        Route::post('/logo-product',                                [LogoProductController::class, 'store'])->name('logo-product.post');
        Route::get('/logo-product/hapus/{id}',                      [LogoProductController::class, 'delete'])->name('logo-product.delete');
        Route::post('/logo-product/update',                           [LogoProductController::class, 'patch'])->name('logo-product.detail.update');
        
        Route::get('/logo-product/{id}/detail',                     [LogoProductController::class, 'detail'])->name('logo-product.detail');
        Route::post('/logo-product/{id}/detail',                     [LogoProductController::class, 'patch'])->name('logo-product.detail.update');
        
        //Artikel
        Route::get('/artikel-admin/add',                                [ArtikelAdminController::class, 'add'])->name('artikel-admin.add');
        Route::get('/artikel-admin',                                 [ArtikelAdminController::class, 'create'])->name('artikel-admin');
        Route::post('/artikel-admin',                                [ArtikelAdminController::class, 'store'])->name('artikel-admin.post');
        Route::get('/artikel-admin/hapus/{id}',                      [ArtikelAdminController::class, 'delete'])->name('artikel-admin.delete');
        Route::get('/artikel-admin-status/{id}/{status}',             [ArtikelAdminController::class, 'update'])->name('artikel-admin.update');
        
        Route::get('/artikel-admin/{id}/detail',                     [ArtikelAdminController::class, 'detail'])->name('artikel-admin.detail');
        Route::post('/artikel-admin/{id}/detail',                     [ArtikelAdminController::class, 'patch'])->name('artikel-admin.detail.update');
        
        Route::resources(['paket'=>PaketController::class,'paket-layanan'=>PaketLayananController::class]);
        Route::post('paket-layanan-get-layanan',[PaketLayananController::class,'get_layanan'])->name('paket-layanan.get-layanan');
        Route::post('paket-layanan-get-harga',[PaketLayananController::class,'get_harga'])->name('paket-layanan.get-harga');
        
        //Method
        Route::get('/method',                                 [MethodController::class, 'create'])->name('method');
        Route::post('/method',                                [MethodController::class, 'store'])->name('method.post');
        Route::get('/method/hapus/{id}',                      [MethodController::class, 'delete'])->name('method.delete');
        Route::post('/method/update',                           [MethodController::class, 'patch'])->name('method.detail.update');
        Route::post('/method/get-code',                         [MethodController::class,'get_code'])->name('method.get-code');
        Route::get('/method/{id}/detail',                     [MethodController::class, 'detail'])->name('method.detail');
        Route::post('/method/{id}/detail',                     [MethodController::class, 'patch'])->name('method.detail.update');
        
        //Seting
        Route::get('/setting/web', [SettingWebController::class,'settingWeb'])->name('settingweb');
        Route::post('/setting/web', [SettingWebController::class,'saveSettingWeb']);
        Route::post('/setting/warnaweb', [SettingWebController::class,'saveSettingWarna']);
        Route::post('/setting/hargamembership', [SettingWebController::class,'saveHargaMembership']);
        Route::post('/setting/tripay', [SettingWebController::class,'saveSettingTripay']);
        Route::post('/setting/partaisocmed', [SettingWebController::class,'saveSettingPartaisocmed']);
        Route::get('/setting/partaisocmedsaldo', [SettingWebController::class,'saveSettingPartaisocmedSaldo']);
        Route::post('/setting/irvankedesmm', [SettingWebController::class,'saveSettingIrvankedesmm']);
        Route::get('/setting/irvankedesmmsaldo', [SettingWebController::class,'saveSettingIrvankedesmmSaldo']);
        Route::post('/setting/vipmember', [SettingWebController::class,'saveSettingVipmember']);
        Route::get('/setting/vipmembersaldo', [SettingWebController::class,'saveSettingVipmemberSaldo']);
        Route::post('/setting/istanamarket', [SettingWebController::class,'saveSettingIstanamarket']);
        Route::get('/setting/istanamarketsaldo', [SettingWebController::class,'saveSettingIstanamarketSaldo']);
        Route::post('/setting/fanstore', [SettingWebController::class,'saveSettingFanstore']);
        Route::get('/setting/fanstoresaldo', [SettingWebController::class,'saveSettingFanstoreSaldo']);
        Route::post('/setting/rasxmedia', [SettingWebController::class,'saveSettingRasxmedia']);
        Route::get('/setting/rasxmediasaldo', [SettingWebController::class,'saveSettingRasxmediaSaldo']);
        Route::post('/setting/wagateway', [SettingWebController::class,'saveSettingWagateway']);
        Route::post('/setting/mystic', [SettingWebController::class,'saveSettingMystic']);
        Route::post('/setting/ipaymu', [SettingWebController::class,'saveSettingIpaymu']);
        Route::post('/setting/duitku', [SettingWebController::class,'saveSettingDuitku']);
        Route::post('/setting/tokopay', [SettingWebController::class,'saveSettingTokoPay']);
        Route::post('/setting/xendit', [SettingWebController::class,'saveSettingXendit']);
        Route::post('/setting/paydisini', [SettingWebController::class,'saveSettingPaydisini']);
        Route::post('/setting/linkqu', [SettingWebController::class,'saveSettingLinkQu']);
        Route::post('/setting/membership', [SettingWebController::class,'saveSettingMembership']);
        Route::post('/setting/tampilan', [SettingWebController::class,'saveSettingChangeTheme']);
        Route::post('/setting/lainnya', [SettingWebController::class,'saveSettingLainnya']);
        
        //Profit
        Route::get('/profit',                                 [ProfitController::class, 'create'])->name('profit');
        Route::post('/profit/update',                           [ProfitController::class, 'patch'])->name('profit.detail.update');
        
        Route::get('/profit/{id}/detail',                     [ProfitController::class, 'detail'])->name('profit.detail');
        Route::post('/profit/{id}/detail',                     [ProfitController::class, 'patch'])->name('profit.detail.update');
    
        //Member
        Route::get('/member',                                   [MemberController::class, 'create'])->name('member');
        Route::get('/member/{id}/delete',                       [MemberController::class, 'delete'])->name('member.delete');
        Route::post('/member',                                  [MemberController::class, 'store'])->name('member.post');
        Route::post('/send-balance',                            [MemberController::class, 'send'])->name('saldo.post');
        Route::get('/member/{id}/detail',                       [MemberController::class, 'show'])->name('member.detail');
        Route::post('/member/{id}/update',                           [MemberController::class, 'patch'])->name('member.detail.update');
    
        //Deposit
        Route::get('/history-pembayaran',                             [HistoryPembayaranController::class, 'create'])->name('history');
        Route::get('/history-pembayaran/{id}/{status}',               [HistoryPembayaranController::class, 'patch'])->name('confirm.history');
        
        //Voucher
        Route::get('/voucher',                                  [VoucherController::class, 'create'])->name('voucher');
        Route::post('/voucher',                                 [VoucherController::class, 'store'])->name('voucher.post');
        Route::get('/voucher/{id}/delete',                      [VoucherController::class, 'destroy'])->name('voucher.delete');
        
        Route::get('/voucher-list/{id}/delete',                      [VoucherController::class, 'destroy_list'])->name('voucher-list.delete');
        
        Route::get('/voucher-list/{id}/detail',                      [VoucherController::class, 'show_list'])->name('voucher-list.detail');
        Route::post('/voucher-list/{id}/update',                      [VoucherController::class, 'patch_list'])->name('voucher-list.detail.update');
        
        Route::get('/voucher/{id}/detail',                      [VoucherController::class, 'show'])->name('voucher.detail');
        Route::post('/voucher/{id}/update',                      [VoucherController::class, 'patch'])->name('voucher.detail.update');
        Route::post('/voucher/global',                           [VoucherController::class, 'globals'])->name('voucher.global');
        
        //FlasSale
        Route::get('/flashsale',                                        [PromoController::class, 'create'])->name('flashsale');
        Route::post('/flashsale',                                       [PromoController::class, 'store'])->name('flashsale.post');
        Route::get('/flashsale/hapus/{id}',                             [PromoController::class, 'delete'])->name('flashsale.delete');
        Route::post('/flashsale/update',                                [PromoController::class, 'patch'])->name('flashsale.detail.update');
        Route::get('/flashsale/{id}/detail',                            [PromoController::class, 'detail'])->name('flashsale.detail');
        Route::post('/flashsale/{id}/detail',                           [PromoController::class, 'patch'])->name('flashsale.detail.update');
        
        //Pertanyaan Umum
        Route::get('/pertanyaan-umum',                                        [PertanyaanUmumController::class, 'create'])->name('pertanyaan-umum');
        Route::post('/pertanyaan-umum',                                       [PertanyaanUmumController::class, 'store'])->name('pertanyaan-umum.post');
        Route::get('/pertanyaan-umum/hapus/{id}',                             [PertanyaanUmumController::class, 'delete'])->name('pertanyaan-umum.delete');
        Route::post('/pertanyaan-umum/update',                                [PertanyaanUmumController::class, 'patch'])->name('pertanyaan-umum.detail.update');
        Route::get('/pertanyaan-umum/{id}/detail',                            [PertanyaanUmumController::class, 'detail'])->name('pertanyaan-umum.detail');
        Route::post('/pertanyaan-umum/{id}/detail',                           [PertanyaanUmumController::class, 'patch'])->name('pertanyaan-umum.detail.update');
        
        //Phone Country
        Route::get('/phone-country',                                        [PhoneCountryController::class, 'create'])->name('phone-country');
        Route::post('/phone-country',                                       [PhoneCountryController::class, 'store'])->name('phone-country.post');
        Route::get('/phone-country/hapus/{id}',                             [PhoneCountryController::class, 'delete'])->name('phone-country.delete');
        Route::get('/phone-country/{id}/detail',                            [PhoneCountryController::class, 'detail'])->name('phone-country.detail');
        Route::post('/phone-country/{id}/detail',                           [PhoneCountryController::class, 'patch'])->name('phone-country.detail.update');
        
        //PESANAN MANUAL
        Route::get('/pesanan/manual',                           [OrderManualController::class,'orderManual'])->name('pesananmanual');
        Route::post('/pesanan/manual/ajax/layanan',             [OrderManualController::class,'ajaxLayanan']);
        Route::get('/pesanan/{id}/detail',                      [OrderManualController::class, 'detail'])->name('pesanan.detail');
        Route::post('/pesanan/{id}/detail',                     [OrderManualController::class, 'patch'])->name('pesanan.detail.update');
        //PESANAN MANUAL STATUS FAILED
        Route::get('/pesanan-failed/{id}/detail',                      [OrderManualController::class, 'detail_failed'])->name('pesanan.failed.detail');
        Route::post('/pesanan-failed/{id}/detail',                     [OrderManualController::class, 'patch_failed'])->name('pesanan.failed.detail.update');
        //PESANAN MANUAL STATUS PENDING
        Route::get('/pesanan-pending/{id}/detail',                      [OrderManualController::class, 'detail_pending'])->name('pesanan.pending.detail');
        Route::post('/pesanan-pending/{id}/detail',                     [OrderManualController::class, 'patch_pending'])->name('pesanan.pending.detail.update');
    });