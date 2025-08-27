@extends("main")

@section("content")
<div class="content-main mt-5">
    <div class="container">
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

<script>
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