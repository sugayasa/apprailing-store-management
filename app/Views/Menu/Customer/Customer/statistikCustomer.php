<?php
    $menuName           =   $menuDetail['MENUNAME'] ?? '';
    $menuDescription    =   $menuDetail['DESCRIPTION'] ?? '';
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
                        <div class="d-flex align-items-center mb-3">
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
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-12 mb-3">
            <div class="card h-100" id="customerStatistikCustomer-beritaInformasi">
                <div class="card-header">
                    <h6 class="card-title mb-0">Berita & Informasi</h6>
                </div>
                <div class="card-body d-flex flex-column overflow-hidden" style="height: 400px;">
                    <div class="d-flex align-items-stretch border rounded bg-light mb-3">
                        <div class="w-50 py-1 px-3 text-center border-end">
                            <div class="fs-12px text-body">
                                <i class="fa fa-eye fa-fw text-primary me-1"></i>Dilihat
                            </div>
                            <div class="fw-600 fs-15px text-body" id="customerStatistikCustomer-beritaDilihat">0</div>
                        </div>
                        <div class="w-50 py-1 px-3 text-center">
                            <div class="fs-12px text-body">
                                <i class="fa fa-list-alt fa-fw text-gray-200 me-1"></i>Artikel
                            </div>
                            <div class="fw-600 fs-15px text-body" id="customerStatistikCustomer-beritaArtikel">0</div>
                        </div>
                    </div>
                    <div class="table-responsive table-sticky-header flex-fill" id="customerStatistikCustomer-beritaTabel">
                        <table class="table table-hover mb-0 w-100" style="table-layout: fixed; word-wrap: break-word; word-break: break-word;">
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-12 mb-3">
            <div class="card h-100" id="customerStatistikCustomer-galeriKlien">
                <div class="card-header">
                    <h6 class="card-title mb-0">Galeri Klien</h6>
                </div>
                <div class="card-body d-flex flex-column overflow-hidden" style="height: 400px;">
                    <div class="d-flex align-items-stretch border rounded bg-light mb-3">
                        <div class="flex-grow-1 py-1 px-2 text-center border-end">
                            <div class="fs-12px text-body">
                                <i class="fa fa-eye fa-fw text-primary me-1"></i>Dilihat
                            </div>
                            <div class="fw-600 fs-15px text-body" id="customerStatistikCustomer-galeriKlienDilihat">0</div>
                        </div>
                        <div class="flex-grow-1 py-1 px-2 text-center border-end">
                            <div class="fs-12px text-body">
                                <i class="fa fa-image fa-fw text-green-200 me-1"></i>Galeri
                            </div>
                            <div class="fw-600 fs-15px text-body" id="customerStatistikCustomer-galeriKlienGaleri">0</div>
                        </div>
                        <div class="flex-grow-1 py-1 px-2 text-center">
                            <div class="fs-12px text-body">
                                <i class="fa fa-user fa-fw text-gray-200 me-1"></i>User
                            </div>
                            <div class="fw-600 fs-15px text-body" id="customerStatistikCustomer-galeriKlienUser">0</div>
                        </div>
                    </div>
                    <div class="table-responsive table-sticky-header flex-fill" id="customerStatistikCustomer-galeriKlienTabel">
                        <table class="table table-hover mb-0 w-100" style="table-layout: fixed; word-wrap: break-word; word-break: break-word;">
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-12 mb-3">
            <div class="card h-100" id="customerStatistikCustomer-galeriProyek">
                <div class="card-header">
                    <h6 class="card-title mb-0">Galeri Proyek</h6>
                </div>
                <div class="card-body d-flex flex-column overflow-hidden" style="height: 400px;">
                    <div class="d-flex align-items-stretch border rounded bg-light mb-3">
                        <div class="flex-grow-1 py-1 px-2 text-center border-end">
                            <div class="fs-12px text-body">
                                <i class="fa fa-eye fa-fw text-primary me-1"></i>Dilihat
                            </div>
                            <div class="fw-600 fs-15px text-body" id="customerStatistikCustomer-galeriProyekDilihat">0</div>
                        </div>
                        <div class="flex-grow-1 py-1 px-2 text-center border-end">
                            <div class="fs-12px text-body">
                                <i class="fa fa-image fa-fw text-green-200 me-1"></i>Galeri
                            </div>
                            <div class="fw-600 fs-15px text-body" id="customerStatistikCustomer-galeriProyekGaleri">0</div>
                        </div>
                        <div class="flex-grow-1 py-1 px-2 text-center">
                            <div class="fs-12px text-body">
                                <i class="fa fa-user fa-fw text-gray-200 me-1"></i>User
                            </div>
                            <div class="fw-600 fs-15px text-body" id="customerStatistikCustomer-galeriProyekUser">0</div>
                        </div>
                    </div>
                    <div class="table-responsive table-sticky-header flex-fill" id="customerStatistikCustomer-galeriProyekTabel">
                        <table class="table table-hover mb-0 w-100" style="table-layout: fixed; word-wrap: break-word; word-break: break-word;">
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-12 mb-3">
            <div class="card h-100" id="customerStatistikCustomer-feed">
                <div class="card-header">
                    <h6 class="card-title mb-0">Feed</h6>
                </div>
                <div class="card-body d-flex flex-column overflow-hidden" style="height: 400px;">
                    <div class="d-flex align-items-stretch border rounded bg-light mb-3">
                        <div class="flex-grow-1 py-1 px-2 text-center border-end">
                            <div class="fs-12px text-body">
                                <i class="fa fa-eye fa-fw text-primary me-1"></i>Dilihat
                            </div>
                            <div class="fw-600 fs-15px text-body" id="customerStatistikCustomer-feedDilihat">0</div>
                        </div>
                        <div class="flex-grow-1 py-1 px-2 text-center border-end">
                            <div class="fs-12px text-body">
                                <i class="fa fa-video-camera fa-fw text-orange-200 me-1"></i>Feed
                            </div>
                            <div class="fw-600 fs-15px text-body" id="customerStatistikCustomer-feedFeed">0</div>
                        </div>
                        <div class="flex-grow-1 py-1 px-2 text-center">
                            <div class="fs-12px text-body">
                                <i class="fa fa-user fa-fw text-gray-200 me-1"></i>User
                            </div>
                            <div class="fw-600 fs-15px text-body" id="customerStatistikCustomer-feedUser">0</div>
                        </div>
                    </div>
                    <div class="table-responsive table-sticky-header flex-fill" id="customerStatistikCustomer-feedTabel">
                        <table class="table table-hover mb-0 w-100" style="table-layout: fixed; word-wrap: break-word; word-break: break-word;">
                            <tbody></tbody>
                        </table>
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