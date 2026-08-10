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
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12 mb-lg-0 mb-3">
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center bg-none fw-bold">Customer</div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img src="<?=BASE_URL_ASSETS_CUSTOMER_AVATAR?>default.jpg" width="92" class="rounded-pill me-2" id="detailTransaksi-avatarCustomer"/>
                            <div class="flex-fill ps-2">
                                <div class="d-block text-decoration-none text-body fw-semibold">
                                    <span id="detailTransaksi-namaCustomer">-</span>
                                    <span id="detailTransaksi-kodeCustomer" class="text-primary ms-2 text-opacity-50">-</span>
                                </div>
                                <div class="fs-14px mt-1 mb-0">
                                    <div class="text-body text-opacity-50">
                                        <i class="fa fa-envelope fa-fw me-1"></i>
                                        <span id="detailTransaksi-emailCustomer">-</span>
                                    </div>
                                    <div class="text-body text-opacity-50">
                                        <i class="fa fa-phone fa-fw me-1"></i>
                                        <span id="detailTransaksi-telpCustomer">-</span>
                                    </div>
                                    <div class="text-body text-opacity-50">
                                        <img src="<?=BASE_URL_ASSETS_ICON_LEVEL_LOYALTI?>default.png" class="img-fluid rounded-circle me-1" id="detailTransaksi-levelLoyaltiCustomerIcon" style="width: 18px; height: 18px;"/>
                                        <span id="detailTransaksi-levelLoyaltiCustomer">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center bg-none fw-bold">Detail Order</div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="fw-600 text-body" id="detailTransaksi-kode">-</div>
                            <div id="detailTransaksi-statusContainer" class="ms-auto"></div>
                        </div>
                        <div class="fs-14px">
                            <div id="detailTransaksi-tanggalWaktu">-</div>
                            <div id="detailTransaksi-regional">-</div>
                            <div class="mb-3" id="detailTransaksi-metodePembayaran">-</div>
                            <div class="fw-semibold">Catatan</div>
                            <div id="detailTransaksi-catatan">-</div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center bg-none fw-bold">Pengiriman</div>
                    <div class="card-body">
                        <dl class="row mb-3">
                            <dt class="col-sm-3">Ekspedisi</dt>
                            <dd class="col-sm-9 mb-0" id="detailTransaksi-ekspedisi">-</dd>
                            <dt class="col-sm-3">No. Resi</dt>
                            <dd class="col-sm-9 mb-0" id="detailTransaksi-noResi">-</dd>
                            <dt class="col-sm-3">Tag Alamat</dt>
                            <dd class="col-sm-9 mb-0" id="detailTransaksi-tagAlamat">-</dd>
                        </dl>
                        <div class="text-body text-opacity-50">
                            <i class="fa fa-user fa-fw me-1"></i>
                            <span id="detailTransaksi-namaTelponCustomer">-</span>
                        </div>
                        <div class="text-body text-opacity-50">
                            <i class="fa fa-map-marker fa-fw me-1"></i>
                            <span id="detailTransaksi-alamatPengiriman">-</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 mb-lg-0 mb-3">
                <div class="card mb-3 d-flex flex-column" style="height: 400px;">
                    <div class="card-header d-flex align-items-center bg-none fw-bold">Daftar Produk</div>
                    <div class="card-body overflow-auto" id="detailTransaksi-daftarProdukContainer"></div>
                </div>
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center bg-none fw-bold">Pembayaran</div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm m-0">
                            <tbody>
                                <tr>
                                    <td class="w-150px">Harga Produk</td>
                                    <td class="text-body text-opacity-50">
                                        <span id="detailTransaksi-totalProduk">0</span> Produk, <span id="detailTransaksi-totalPcs">0</span> Pcs
                                    </td>
                                    <td class="text-end">Rp. <span id="detailTransaksi-totalHargaProduk">0</span></td>
                                </tr>
                                <tr>
                                    <td>Ongkos Kirim</td>
                                    <td class="text-body text-opacity-50">
                                        <span id="detailTransaksi-namaEkspedisi">-</span>
                                    </td>
                                    <td class="text-end">Rp. <span id="detailTransaksi-ongkosKirim">0</span></td>
                                </tr>
                                <tr>
                                    <td>Diskon</td>
                                    <td class="text-body text-end text-opacity-50">(-)</td>
                                    <td class="text-end">Rp. <span id="detailTransaksi-diskon">0</span></td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <hr class="m-0">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pb-0" colspan="2"><b>Total</b></td>
                                    <td class="text-end pb-0"><b>Rp. <span id="detailTransaksi-totalBayar">0</span></b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	var baseUrlCustomerAvatar   =   "<?=BASE_URL_ASSETS_CUSTOMER_AVATAR?>",
        baseUrlIconLevelLoyalti =   "<?=BASE_URL_ASSETS_ICON_LEVEL_LOYALTI?>",
        baseUrlCustomerProduk   =   "<?=BASE_URL_ASSETS_CUSTOMER_PRODUK?>",
        jsFileUrl               =   "<?=BASE_URL_ASSETS_JS?>menu/customer/transaksi/daftarTransaksi.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>