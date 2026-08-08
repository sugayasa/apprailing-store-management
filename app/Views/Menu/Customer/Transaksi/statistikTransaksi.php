<?php
    $menuName           =   $menuDetail['MENUNAME'] ?? '';
    $menuDescription    =   $menuDetail['DESCRIPTION'] ?? '';
?>

<div id="containerMenuCustomerTransaksiStatistik" class="d-flex flex-column h-100">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center" id="customerTransaksiStatistik-header">
        <h1 class="page-header mb-0">
            <span><?=$menuName?> <small><?=$menuDescription?></small></span>
        </h1>
        <div class="ms-md-auto mt-2 mt-md-0">
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                <input type="text" class="form-control" id="customerTransaksiStatistik-bulanTahunInput" placeholder="Pilih Bulan & Tahun" readonly>
            </div>
        </div>
    </div>
    <hr class="mb-4" id="customerTransaksiStatistik-hr">
    <div class="row flex-fill align-items-stretch" style="min-height: 0;" id="customerTransaksiStatistik-topContainer">
        <div class="col-xl-6 col-lg-12 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="card-title mb-0">Grafik Transaksi</h6>
                </div>
                <div class="card-body">
                    <canvas id="customerTransaksiStatistik-grafikTransaksiCanvas"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-12">
            <div class="row h-100 align-items-stretch">
                <div class="col-sm-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Rekap Per Merk</h6>
                        </div>
                        <div class="card-body">
                            <div id="customerTransaksiStatistik-rekapitulasiPerMerk"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Rekap Per Regional</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-1" id="customerTransaksiStatistik-totalTransaksiNominal">-</h5>
                            </div>
                            <div class="progress mb-2" style="height: 10px;" id="customerTransaksiStatistik-progressBarRegional">
                                <div class="progress-bar bg-gray" style="width: 100%"></div>
                            </div>
                            <div class="fs-15px" id="customerTransaksiStatistik-rekapitulasiPerRegional"></div>
                            <div class="fs-12px text-end">
                                <span class="fs-10px">* Transaksi yang dihitung hanya yang tidak dibatalkan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 col-md-12 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="card-title mb-0">Produk Best Seller</h6>
                </div>
                <div class="card-body" id="customerTransaksiStatistik-produkBestSellerContainer">
                    <div id="customerTransaksiStatistik-produkBestSeller"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-12 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="card-title mb-0">Transaksi Terakhir</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-sticky-header  mb-n2">
                        <table class="table table-borderless mb-0">
                            <thead>
                                <tr class="text-body table-dark">
                                    <th class="sticky-top sticky-col-left" width="30%">Detail Customer</th>
                                    <th class="sticky-top" width="42%">Detail Transaksi</th>
                                    <th class="sticky-top" width="25%">Detail Nominal</th>
                                </tr>
                            </thead>
                            <tbody id="customerTransaksiStatistik-daftarRiwayat"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	var jsFileUrl   =   "<?=BASE_URL_ASSETS_JS?>menu/customer/transaksi/statistikTransaksi.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>