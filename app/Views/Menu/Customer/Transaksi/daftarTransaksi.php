<?php
    $menuName           =   $menuDetail['MENUNAME'] ?? '';
    $menuDescription    =   $menuDetail['DESCRIPTION'] ?? '';
?>

<div id="containerMenuCustomerDaftarTransaksi" class="pos">
    <h1 id="customerDaftarTransaksi-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
        <button id="btnKembali" type="button" class="btn btn-warning ms-md-auto mt-md-0 mt-2 d-none">
            <i class="fa fa-arrow-left me-1"></i> Kembali
        </button>
    </h1>
    <hr id="customerDaftarTransaksi-hr" class="mb-4">
    <div id="customerDaftarTransaksi-leftContainer" class="show">
        <div id="customerDaftarTransaksi-cardContent" class="card d-flex flex-column">
            <div class="p-3 mb-3 border-bottom">
                <div class="row gy-3 gy-lg-0">
                    <div class="col-lg-2 col-md-4 col-sm-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                            <select class="form-select" id="customerDaftarTransaksi-optionRegional" name="customerDaftarTransaksi-optionRegional" option-all="Semua Regional"></select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-8 col-sm-12">
                        <div class="input-group input-daterange" id="customerDaftarTransaksi-rentangTanggal">
                            <input type="text" class="form-control" id="customerDaftarTransaksi-tanggalAwal" name="tanggalAwal" placeholder="Tanggal Awal" readonly>
                            <span class="input-group-text">sampai</span>
                            <input type="text" class="form-control" id="customerDaftarTransaksi-tanggalAkhir" name="tanggalAkhir" placeholder="Tanggal Akhir" readonly>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Ketik sesuatu dan tekan ENTER untuk mencari.." id="customerDaftarTransaksi-searchKeyword">
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive table-sticky-header px-3 flex-fill">
                <table class="table table-hover mb-0 w-100" style="table-layout: fixed; word-wrap: break-word; word-break: break-word;">
                    <thead>
                        <tr class="table-dark">
                            <th class="py-2 sticky-top sticky-col-left" width="12%">Customer</th>
                            <th class="py-2 sticky-top" width="13%">Transaksi</th>
                            <th class="py-2 sticky-top" width="16%">Pengiriman</th>
                            <th class="py-2 sticky-top">Alamat</th>
                            <th class="py-2 sticky-top" width="18%">Catatan</th>
                            <th class="py-2 sticky-top" width="14%">Nominal</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="d-md-flex align-items-center p-3 border-top">
                <div class="me-md-auto text-md-left text-center mb-2 mb-md-0" id="customerDaftarTransaksi-paginationInfo"></div>
                <div class="btn-group btn-group-md" id="customerDaftarTransaksi-paginationControl"></div>
            </div>
        </div>
    </div>
    <div id="customerDaftarTransaksi-rightContainer" class="d-none">
    </div>
</div>
<script>
	var jsFileUrl   =   "<?=BASE_URL_ASSETS_JS?>menu/customer/transaksi/daftarTransaksi.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>