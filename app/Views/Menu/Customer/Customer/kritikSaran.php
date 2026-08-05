<?php
    $menuName               =   $menuDetail['MENUNAME'] ?? '';
    $menuDescription        =   $menuDetail['DESCRIPTION'] ?? '';
    $statistikKritikSaran   =   $statistikKritikSaran ?? [];
?>

<div id="containerMenuCustomerKritikSaran" class="pos">
    <h1 id="customerKritikSaran-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
        <button id="btnKembali" type="button" class="btn btn-warning ms-md-auto mt-md-0 mt-2 d-none">
            <i class="fa fa-arrow-left me-1"></i> Kembali
        </button>
    </h1>
    <hr id="customerKritikSaran-hr" class="mb-4">
    <div class="row mb-3" id="customerKritikSaran-statistikRow">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="card bg-primary bg-opacity-10 border-primary border-opacity-25 h-100">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center h-100">
                        <i class="fa fa-comments fa-2x fa-fw text-primary me-3"></i>
                        <div class="flex-grow-1">
                            <div class="fw-600 text-body">Total Kritik & Saran</div>
                            <div class="fs-12px text-body text-opacity-50">Diterima sepanjang waktu</div>
                        </div>
                        <div class="text-end ms-3">
                            <div class="fw-600 fs-15px text-body placeholder-glow">
                                <span id="customerKritikSaran-totalKritikSaran"><?=number_format($statistikKritikSaran['TOTALKRITIKSARAN'] ?? 0, 0, ',', '.')?></span>
                            </div>
                            <div class="fs-12px text-body text-opacity-50 placeholder-glow">
                                <i class="fa fa-user fa-fw"></i> <span id="customerKritikSaran-totalCustomer"><?=number_format($statistikKritikSaran['TOTALCUSTOMER'] ?? 0, 0, ',', '.')?></span> Customer
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success bg-opacity-10 border-success border-opacity-25 h-100">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center h-100">
                        <i class="fa fa-clock fa-2x fa-fw text-success me-3"></i>
                        <div class="flex-grow-1">
                            <div class="fw-600 text-body">30 Hari Terakhir</div>
                            <div class="fs-12px text-body text-opacity-50">Total diterima dalam sebulan</div>
                        </div>
                        <div class="text-end ms-3">
                            <div class="fw-600 fs-15px text-body placeholder-glow">
                                <span id="customerKritikSaran-total30Hari"><?=number_format($statistikKritikSaran['TOTALKRITIKSARAN30HARI'] ?? 0, 0, ',', '.')?></span>
                            </div>
                            <div class="fs-12px text-body text-opacity-50 placeholder-glow">
                                <i class="fa fa-user fa-fw"></i> <span id="customerKritikSaran-total30HariCustomer"><?=number_format($statistikKritikSaran['TOTALCUSTOMER30HARI'] ?? 0, 0, ',', '.')?></span> Customer
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="customerKritikSaran-cardContent" class="card d-flex flex-column">
        <div class="row p-3 mb-3 border-bottom">
            <div class="col-lg-4 col-md-6 col-12 mb-2 mb-md-0">
                <div class="input-group input-daterange" id="customerKritikSaran-rentangTanggal">
                    <input type="text" class="form-control" name="tanggalAwal" placeholder="Tanggal Awal" readonly>
                    <span class="input-group-text">sampai</span>
                    <input type="text" class="form-control" name="tanggalAkhir" placeholder="Tanggal Akhir" readonly>
                </div>
            </div>
            <div class="col-lg-8 col-md-6 col-12 mb-2 mb-md-0">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Ketik sesuatu dan tekan ENTER untuk mencari.." id="customerKritikSaran-searchKeyword">
                </div>
            </div>
        </div>
        <div class="table-responsive table-sticky-header px-3 flex-fill">
            <table class="table table-hover mb-0 w-100" style="table-layout: fixed; word-wrap: break-word; word-break: break-word;">
                <thead>
                    <tr class="table-dark">
                        <th class="py-2 sticky-top sticky-col-left" width="10%">Tanggal Waktu</th>
                        <th class="py-2 sticky-top" width="16%">Detail Customer</th>
                        <th class="py-2 sticky-top" width="12%">Subyek</th>
                        <th class="py-2 sticky-top">Pesan</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="d-md-flex align-items-center p-3 border-top">
            <div class="me-md-auto text-md-left text-center mb-2 mb-md-0" id="customerKritikSaran-paginationInfo"></div>
            <div class="btn-group btn-group-md" id="customerKritikSaran-paginationControl"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="customerKritikSaran-detail">
    <div class="modal-dialog modal-md">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Detail Kritik & Saran</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <b id="customerKritikSaran-detail-nama">-</b><br>
                <span id="customerKritikSaran-detail-email">-</span><br>
                <span id="customerKritikSaran-detail-hp" class="mb-3">-</span>
                <hr/>
                <dl class="row mb-0">
                    <dt class="col-sm-3 mb-0">Tanggal Waktu</dt>
                    <dd class="col-sm-9 mb-0">: <span id="customerKritikSaran-detail-tanggalWaktu">-</span></dd>
                    <dt class="col-sm-3 mb-0">Subyek</dt>
                    <dd class="col-sm-9 mb-0">: <span id="customerKritikSaran-detail-subyek">-</span></dd>
                    <dt class="col-sm-12 mb-2">Pesan</dt>
                    <dd class="col-sm-12"><span id="customerKritikSaran-detail-pesan">-</span></dd>
                </dl>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
            </div>
        </form>
    </div>
</div>
<script>
	var jsFileUrl   =   "<?=BASE_URL_ASSETS_JS?>menu/customer/customer/kritikSaran.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>