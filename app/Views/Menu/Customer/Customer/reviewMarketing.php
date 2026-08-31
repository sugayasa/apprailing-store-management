<?php
    $menuName               =   $menuDetail['MENUNAME'] ?? '';
    $menuDescription        =   $menuDetail['DESCRIPTION'] ?? '';
    $baseURLImageMarketing  =   $baseURLImageMarketing ?? '';
?>

<div id="containerMenuCustomerCustomerReviewMarketing">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center" id="customerReviewMarketing-header">
        <h1 class="page-header mb-0">
            <span><?=$menuName?> <small><?=$menuDescription?></small></span>
        </h1>
        <div class="ms-md-auto mt-2 mt-md-0">
            <div class="input-group input-daterange" id="customerReviewMarketing-rentangTanggal">
                <input type="text" class="form-control" name="tanggalAwal" placeholder="Tanggal Awal" readonly>
                <span class="input-group-text">sampai</span>
                <input type="text" class="form-control" name="tanggalAkhir" placeholder="Tanggal Akhir" readonly>
            </div>
        </div>
    </div>
    <hr class="mb-4" id="customerReviewMarketing-hr">
    <div class="row" id="customerReviewMarketing-statistikRow">
        <div class="col-xl-8 col-lg-7 col-md-12 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex mb-3">
                        <div class="flex-grow-1">
                            <h5 class="mb-1">Grafik Review</h5>
                            <div class="fs-13px">Tren jumlah review marketing sesuai rentang tanggal</div>
                        </div>
                    </div>
                    <div class="flex-grow-1" style="min-height: 150px; position: relative;">
                        <canvas id="customerReviewMarketing-grafikReviewCanvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5 col-md-12 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="flex-grow-1">
                            <h5 class="mb-1">Ringkasan</h5>
                            <div class="fs-13px">Statistik review di rentang tanggal</div>
                        </div>
                    </div>
                    <div class="fs-15px">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center ps-1">
                                    <i class="fa fa-circle fs-12px fa-fw text-primary me-2"></i>
                                    <div class="fw-600 text-body">Total Review</div>
                                </div>
                                <div class="fs-13px ms-4">Jumlah seluruh review yang diberikan</div>
                            </div>
                            <div class="fw-600 text-body" id="customerReviewMarketing-totalReview">0</div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center ps-1">
                                    <i class="fa fa-circle fs-12px fa-fw text-warning me-2"></i>
                                    <div class="fw-600 text-body">Rating Rerata</div>
                                </div>
                                <div class="fs-13px ms-4">Rata-rata rating seluruh marketing</div>
                            </div>
                            <div class="fw-600 text-body" id="customerReviewMarketing-ratingRerata">0.0</div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center ps-1">
                                    <i class="fa fa-circle fs-12px fa-fw text-green-200 me-2"></i>
                                    <div class="fw-600 text-body">Total Marketing</div>
                                </div>
                                <div class="fs-13px ms-4">Jumlah marketing yang memiliki review</div>
                            </div>
                            <div class="fw-600 text-body" id="customerReviewMarketing-totalMarketing">0</div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center ps-1">
                                    <i class="fa fa-circle fs-12px fa-fw text-orange me-2"></i>
                                    <div class="fw-600 text-body">Rata-rata Review Harian</div>
                                </div>
                                <div class="fs-13px ms-4">Rata-rata jumlah review per hari</div>
                            </div>
                            <div class="fw-600 text-body" id="customerReviewMarketing-rerataHarian">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-12 mb-3">
            <div class="card" id="customerReviewMarketing-cardTabelPeringkat">
                <div class="card-header" id="customerReviewMarketing-cardTabelPeringkat-header">
                    <h6 class="card-title mb-0">
                        <i class="fa fa-trophy text-warning me-1"></i> Peringkat Marketing Terbaik
                    </h6>
                </div>
                <div class="card-body d-flex flex-column overflow-hidden p-0 pt-3" style="height: 420px;">
                    <div class="table-responsive table-sticky-header flex-fill p-3 pt-0">
                        <table class="table table-hover mb-0 w-100">
                            <thead>
                                <tr class="table-dark">
                                    <th class="py-2 sticky-top sticky-left text-end" width="10%">#</th>
                                    <th class="py-2 sticky-top" width="44%">Marketing</th>
                                    <th class="py-2 sticky-top" width="23%">Rating</th>
                                    <th class="py-2 sticky-top text-end" width="23%">Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4">
                                        <div class="text-center py-4">
                                            <i class="fa fa-trophy fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Belum ada data peringkat</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-lg-7 col-md-12 mb-3">
            <div class="card" id="customerReviewMarketing-cardTabelReview">
                <div class="card-header d-flex align-items-center" id="customerReviewMarketing-cardTabelReview-header">
                    <h6 class="card-title mb-0 flex-grow-1">
                        <i class="fa fa-list-alt me-1"></i> Daftar Review Customer
                    </h6>
                </div>
                <div class="card-body d-flex flex-column overflow-hidden p-0 pt-3" style="height: 420px;">
                    <div class="table-responsive table-sticky-header flex-fill p-3 pt-0">
                        <table class="table table-hover mb-0 w-100" style="table-layout: fixed; word-wrap: break-word; word-break: break-word;">
                            <thead>
                                <tr class="table-dark">
                                    <th class="py-2 sticky-top sticky-left text-center" width="15%">Tanggal</th>
                                    <th class="py-2 sticky-top" width="16%">Customer</th>
                                    <th class="py-2 sticky-top" width="16%">Marketing</th>
                                    <th class="py-2 sticky-top" width="8%">Rating</th>
                                    <th class="py-2 sticky-top">Komentar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5">
                                        <div class="text-center py-4">
                                            <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Belum ada data review</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-md-flex align-items-center p-3 border-top">
                        <div class="me-md-auto text-md-left text-center mb-2 mb-md-0" id="customerReviewMarketing-paginationInfo"></div>
                        <div class="btn-group btn-group-sm" id="customerReviewMarketing-paginationControl"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var baseURLImageMarketing   = "<?=$baseURLImageMarketing?>",
        jsFileUrl               = "<?=BASE_URL_ASSETS_JS?>menu/customer/customer/reviewMarketing.js?<?=date("YmdHis")?>";
    $.getScript(jsFileUrl);
</script>
