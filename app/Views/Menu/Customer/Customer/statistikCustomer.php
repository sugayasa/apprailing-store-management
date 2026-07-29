<?php
    $menuName           =   $menuDetail['MENUNAME'];
    $menuDescription    =   $menuDetail['DESCRIPTION'];
?>

<div id="containerMenuCustomerStatistikCustomer">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center" id="customerStatistikCustomer-header">
        <h1 class="page-header mb-0">
            <span><?=$menuName?> <small><?=$menuDescription?></small></span>
        </h1>
        <div class="ms-md-auto mt-2 mt-md-0">
            <div class="input-group input-daterange" id="customerStatistikCustomer-rentangTanggal">
                <input type="text" class="form-control" name="tanggalAwal" placeholder="Tanggal Awal" readonly>
                <span class="input-group-text">sampai</span>
                <input type="text" class="form-control" name="tanggalAkhir" placeholder="Tanggal Akhir" readonly>
            </div>
        </div>
    </div>
    <hr class="mb-4">
    <div class="row">
        <div class="col-xl-9 col-lg-7 col-md-12 mb-3">
            <div class="card h-100" id="customerStatistikCustomer-grafikKunjungan">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="flex-grow-1">
                            <h5 class="mb-1">Grafik Kunjungan</h5>
                            <div class="fs-13px">Statistik kunjungan sesuai dengan rentang tanggal dalam bentuk grafik</div>
                        </div>
                    </div>
                    <div style="max-height: 300px; width: 100%;">
                        <canvas id="customerStatistikCustomer-grafikKunjunganCanvas" style="width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-5 col-md-12 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="flex-grow-1">
                            <h5 class="mb-1">Total Data Kunjungan</h5>
                            <div class="fs-13px">Total data berdasarkan jenis pada grafik</div>
                        </div>
                    </div>
                    <div class="fs-15px mb-3" id="statistikPerRegionalContent">
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center ps-1">
                                    <i class="fa fa-circle fs-12px fa-fw text-primary me-2"></i>
                                    <div class="fw-600 text-body">Total Kunjungan</div>
                                </div>
                                <div class="fs-13px ms-4">Total kunjungan customer di rentang tanggal</div>
                            </div>
                            <div class="fw-600 text-body" id="customerStatistikCustomer-totalKunjungan">0</div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center ps-1">
                                    <i class="fa fa-circle fs-12px fa-fw text-gray-200 me-2"></i>
                                    <div class="fw-600 text-body">Jumlah Perangkat</div>
                                </div>
                                <div class="fs-13px ms-4">Jumlah perangkat unik yang digunakan</div>
                            </div>
                            <div class="fw-600 text-body" id="customerStatistikCustomer-jumlahPerangkat">0</div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center ps-1">
                                    <i class="fa fa-circle fs-12px fa-fw text-orange me-2"></i>
                                    <div class="fw-600 text-body">Jumlah Tamu</div>
                                </div>
                                <div class="fs-13px ms-4">Jumlah kunjungan tamu dan tidak mendaftar</div>
                            </div>
                            <div class="fw-600 text-body" id="customerStatistikCustomer-jumlahTamu">0</div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center ps-1">
                                    <i class="fa fa-circle fs-12px fa-fw text-yellow me-2"></i>
                                    <div class="fw-600 text-body">Jumlah Registrasi Baru</div>
                                </div>
                                <div class="fs-13px ms-4">Jumlah customer yang baru mendaftar</div>
                            </div>
                            <div class="fw-600 text-body" id="customerStatistikCustomer-jumlahRegistrasi">0</div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center ps-1">
                                    <i class="fa fa-circle fs-12px fa-fw text-green-200 me-2"></i>
                                    <div class="fw-600 text-body">Jumlah Customer Teregistrasi</div>
                                </div>
                                <div class="fs-13px ms-4">Jumlah kunjungan customer teregistrasi</div>
                            </div>
                            <div class="fw-600 text-body" id="customerStatistikCustomer-jumlahTeregistrasi">0</div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center ps-1">
                                    <i class="fa fa-circle fs-12px fa-fw text-black me-2"></i>
                                    <div class="fw-600 text-body">Rata-rata Kunjungan Harian</div>
                                </div>
                                <div class="fs-13px ms-4">Rata-rata kunjungan harian di rentang tanggal</div>
                            </div>
                            <div class="fw-600 text-body" id="customerStatistikCustomer-rerataKunjungan">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	var jsFileUrl   =   "<?=BASE_URL_ASSETS_JS?>menu/customer/customer/statistikCustomer.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>