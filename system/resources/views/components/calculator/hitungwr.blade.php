@extends("main")

@section("content")
<div class="content-main mt-5">
    <div class="container">
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
                        <form class="mb-3">
                            <div class="mb-3">
                                <label class="mb-1 mt-3" for="idMatch">Total Pertandingan Anda</label>
                                <input type="number" placeholder="Contoh: 351" autofocus="" autocomplete="off" id="tMatch" class="form-control" fdprocessedid="qig8c" />
                            </div>
                            <div class="mb-3">
                                <label class="mb-1" for="tWr">Total Win Rate Anda</label>
                                <input type="number" placeholder="Contoh: 51.4%" step="any" autocomplete="off" id="tWr" class="form-control" fdprocessedid="0zbkj" />
                            </div>
                            <div class="mb-3">
                                <label class="mb-1" for="wrReq">Win Rate yang anda inginkan</label>
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
                        <span id="resultText" class="text-center d-block"> </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
@endsection