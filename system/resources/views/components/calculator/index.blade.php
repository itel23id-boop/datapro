@extends("../../main")

@section("content")
<div class="content-mains">
    <div class="container">
        <div class="row">
			<div class="col-md-9">
				<div class="category">
					<ul>
					    <li class="li-category tab-skeleton-content active" id="li-wr">
							<div class="product1 from-murky-700 bg-gradient-to-t" style="background-color: var(--warna_4);" onclick="load_games('wr');">
							Win Rate</div>
						</li>
						<li class="li-category tab-skeleton-content" id="li-mw">
							<div class="product1 from-murky-700 bg-gradient-to-t" style="background-color: var(--warna_4);" onclick="load_games('mw');">
							Magic Wheel
							</div>
						</li>
						
						<li class="li-category tab-skeleton-content" id="li-zd">
							<div class="product1 from-murky-700 bg-gradient-to-t" style="background-color: var(--warna_4);" onclick="load_games('zd');">
							Zodiac</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row-games mb-3" id="category-wr">
		    <div class="row justify-content-center">
                <div class="col-sm-5 col-12 mb-1">
                    <div class="card mb-4 border-0 shadow-form">
                        <div class="card-body">
                            <img src="{{ !$config ? '' : $config->logo_header }}" class="logo-img" width="140">
                            <h5 class="text-white mt-3 mb-1">Calculator Winrate Mobile Legends</h5>
                            <small class="text-white">Digunakan untuk menghitung total pertandingan yang harus ditempuh untuk mencapai target winrate yang diinginkan.<br></small>
                        </div>
                    </div>
                </div>
                <div class="col-sm-7 col-12">
                    <div class="card border-0 shadow-form">
                        <div class="card-body">
                            <form class="">
                                <h5 class="text-white">Masukkan Win Rate Anda</h5>
                                <div class="mb-3">
                                    <label class="mb-1 mt-3 text-white" for="idMatch">Total Pertandingan Anda</label>
                                    <input type="number" placeholder="Contoh: 351" autofocus="" autocomplete="off" id="tMatch" class="form-control" fdprocessedid="qig8c" />
                                </div>
                                <div class="mb-3">
                                    <label class="mb-1 text-white" for="tWr">Total Win Rate Anda</label>
                                    <input type="number" placeholder="Contoh: 51.4%" step="any" autocomplete="off" id="tWr" class="form-control" fdprocessedid="0zbkj" />
                                </div>
                                <div class="mb-3">
                                    <label class="mb-1 text-white" for="wrReq">Win Rate yang anda inginkan</label>
                                    <input type="number" placeholder="Contoh: 70%" step="any" autocomplete="off" id="wrReq" class="form-control" fdprocessedid="pfhxs" />
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <button class="btn btn-primary d-block w-100" type="button" id="hasil" fdprocessedid="21xmy">Hasil</button>
                                    </div>
                                    <div class="col">
                                        <a class="btn btn-primary d-block w-100" href="#">Joki</a>
                                    </div>
                                </div>
                            </form>
                            <span id="resultText" class="text-center d-block text-white"> </span>
                        </div>
                    </div>
                </div>
            </div>
		</div>
		<div class="row-games mb-3 d-none" id="category-mw">
		    <div class="row justify-content-center">
                <div class="col-sm-5 col-12 mb-1">
                    <div class="card mb-4 border-0 shadow-form">
                        <div class="card-body">
                            <img src="{{ !$config ? '' : $config->logo_header }}" class="logo-img" width="140">
                            <h5 class="text-white mt-3 mb-1">Kalkulator Magic Wheel</h5>
                            <small class="text-white">Kalkulator Magic Wheel berfungsi untuk mengetahui total maksimal diamond yang kamu butuhkan untuk mendapatkan skin LEGEND.<br></small>
                        </div>
                    </div>
                </div>
                <div class="col-sm-7 col-12">
                    <div class="card border-0 shadow-form">
                        <div class="card-body">
                            <form method="post" target="">
                                <div class="row">
                                    <h5 class="mb-2 text-white">Masukkan Point Magic Wheel Anda</h5>
                                    <div class="col-12 col-lg-8 mb-5">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1">Point Magic Wheel Anda :</span>
                                            <input type="number" class="form-control" name="magic_wheel_point" id="magic_wheel_point" min="0" max="200" value="100">
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon2">Membutuhkan Maksimal :</span>
                                            <input type="number" class="form-control form-control-games" name="diamonds_needed" id="diamonds_needed" readonly>
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-gem" style="font-size: 14px; color: #00c8c8"></i></span>
                                        </div>
                                        <br>
                                        <a href="{{ env('APP_URL').'/order/'.$kategori->kode }}" class="btn btn-primary btn-lg">
                                            Klik Untuk Membeli Diamond
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
		</div>
		<div class="row-games mb-3 d-none" id="category-zd">
		    <div class="row justify-content-center">
                <div class="col-sm-5 col-12 mb-1">
                    <div class="card mb-4 border-0 shadow-form">
                        <div class="card-body">
                            <img src="{{ !$config ? '' : $config->logo_header }}" class="logo-img" width="140">
                            <h5 class="text-white mt-3 mb-1">Kalkulator Zodiac</h5>
                            <small class="text-white">Kalkulator Zodiac ini berfungsi untuk mengetahui total maksimal diamond yang kamu butuhkan untuk mendapatkan skin Zodiac.<br></small>
                        </div>
                    </div>
                </div>
                <div class="col-sm-7 col-12">
                    <div class="card border-0 shadow-form">
                        <div class="card-body">
                            <form method="post" target="">
                                <div class="row">
                                    <h5 class="mb-2 text-white">Masukkan Point Zodiac Anda</h5>
                                    <div class="col-12 col-lg-8 mb-5">
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1">Star Point Anda &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</span>
                                            <input type="number" class="form-control" name="zodiac_point" id="zodiac_point" min="50" max="100" value="50">
                                        </div>
                                        <br>
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon2">Membutuhkan Maksimal :</span>
                                            <input type="number" class="form-control form-control-games" name="diamonds_needed_zodiac" id="diamonds_needed_zodiac" readonly>
                                            <span class="input-group-text" id="basic-addon3"><i class="fas fa-gem" style="color: #00c8c8"></i></span>
                                        </div>
                                        <br>
                                        <a href="{{ env('APP_URL').'/order/'.$kategori->kode }}" class="btn btn-primary btn-lg">
                                            Klik Untuk Membeli Diamond
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
		</div>
    </div>
</div>
@section('js')
<script>
    function calculateDiamonds() {
        var zodiacPoint = document.getElementById("zodiac_point").value;
        var diamondsNeededzodiac = 0;

        if (zodiacPoint > 100) {
            document.getElementById("zodiac_point").value = 100;
            zodiacPoint = 100;
        }
        
        if (zodiacPoint < 90) {
            diamondsNeededzodiac = Math.ceil((2000 - zodiacPoint * 20) * 850 / 1000);
        } else {
            diamondsNeededzodiac = Math.ceil(2000 - zodiacPoint * 20);
        }
        
        document.getElementById("diamonds_needed_zodiac").value = diamondsNeededzodiac;
    }
    
    document.getElementById("zodiac_point").addEventListener("input", calculateDiamonds);
</script>
<script>
        const tMatch = document.querySelector("#tMatch");
        const tWr = document.querySelector("#tWr");
        const wrReq = document.querySelector("#wrReq");
        const hasil = document.querySelector("#hasil");
        const resultText = document.querySelector("#resultText");
        function res() {
            const resultNum = rumus(tMatch.value, tWr.value, wrReq.value);
            const text =
                `Anda memerlukan sekitar <b>${resultNum}</b> win tanpa lose untuk mendapatkan win rate <b>${wrReq.value}%</b>`;
            resultText.innerHTML = text;
        }
        function rumus(tMatch, tWr, wrReq) {
            let tWin = tMatch * (tWr / 100);
            let tLose = tMatch - tWin;
            let sisaWr = 100 - wrReq;
            let wrResult = 100 / sisaWr;
            let seratusPersen = tLose * wrResult;
            let final = seratusPersen - tMatch;
            return Math.round(final);
        }
        window.addEventListener("load", init);
        function init() {
            load();
            eventListener();
        }

        function load() {}
        function eventListener() {
            hasil.addEventListener("click", res);
        }
</script>
<script>
    function load_games(category) {
		$(".li-category").removeClass('active');
		$("#li-" + category).addClass('active');

		if (category == 'wr') {
			$(".row-games").removeClass('d-none');
		} else {
			$(".row-games").addClass('d-none');
			$("#category-" + category).removeClass('d-none');
		}
	}
    var magicWheelPoint = document.getElementById("magic_wheel_point");
    var diamondsNeeded = document.getElementById("diamonds_needed");

    function calculateDiamondsNeeded(point) {
        if (point < 196) {
            var remainingPoints = 200 - point;
            var requiredSpins = Math.ceil(remainingPoints / 5);
            var diamondCost = requiredSpins * 270;
        } else {
            var remainingPoints = 200 - point;
            var diamondCost = remainingPoints * 60;
        }
        return diamondCost;
    }

    magicWheelPoint.addEventListener("input", function () {
        var pointValue = this.value;
        if (pointValue > 200) {
            this.value = 200;
            pointValue = 200;
        }
        var diamondValue = calculateDiamondsNeeded(pointValue);
        diamondsNeeded.value = diamondValue;
    });

    diamondsNeeded.value = "";
</script>
@endsection

@endsection