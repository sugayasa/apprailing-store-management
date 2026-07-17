<?php
    $menuName           =   $menuDetail['MENUNAME'];
    $menuDescription    =   $menuDetail['DESCRIPTION'];
?>
<div id="containerMenuPengaturanVariabelSistem" class="pos">
    <h1 id="pengaturanVariabelSistem-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
    </h1>
    <hr id="pengaturanVariabelSistem-hr" class="mb-4">
    <div class="card mb-3" id="pengaturanVariabelSistem-tabPills">
        <div class="card-body">
            <ul class="nav nav-pills mb-0" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="pills-barang-sistem-utama-tab" data-bs-toggle="pill" href="#pills-barang-sistem-utama" aria-selected="true" role="tab">Data Barang Sistem Utama</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills-barang-sistem-utama" role="tabpanel">
            <div class="card pengaturanVariabelSistem-tabContent d-flex flex-column">
                <div class="card-header bg-white">
                    <div class="alert alert-info p-2 mb-0 d-flex align-items-center">
                        <strong class="me-1">Informasi |</strong> Data barang yang ditampilkan merupakan data yang diambil dari sistem utama. Lakukan sinkronisasi data barang secara berkala agar data barang yang ditampilkan selalu up to date.
                        <button id="btnSyncDataBarangSistemUtama" type="button" class="btn btn-primary ms-md-auto mt-md-0 mt-2">
                            <i class="fa fa-download ms-auto"></i> Sinkronisasi Data
                        </button>
                    </div>
                </div>
                <div class="card-body p-0 d-flex flex-column overflow-hidden">
                    <div class="p-3 mb-3 border-bottom">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Ketik sesuatu dan tekan ENTER untuk mencari.." id="dataBarangSistemUtama-searchKeyword">
                        </div>
                    </div>
                    <div class="table-responsive table-sticky-header px-3 pb-3 flex-fill">
                        <table id="dataBarangSistemUtama-table" class="table table-hover mb-0 w-100" style="table-layout: fixed; word-wrap: break-word; word-break: break-word;">
                            <thead>
                                <tr class="table-dark">
                                    <th class="py-2 sticky-top" width="10%">Merk</th>
                                    <th class="py-2 sticky-top" width="20%">Kategori</th>
                                    <th class="py-2 sticky-top" width="8%">Kualitas</th>
                                    <th class="py-2 sticky-top" width="14%">Finish</th>
                                    <th class="py-2 sticky-top">Nama & Kode Barang</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white d-md-flex align-items-center justify-content-between">
                    <div class="me-md-auto text-md-left text-center mb-2 mb-md-0" id="dataBarangSistemUtama-paginationInfo"></div>
                    <div class="btn-group btn-group-md" id="dataBarangSistemUtama-paginationControl"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	var jsFileUrl       =   "<?=BASE_URL_ASSETS_JS?>menu/pengaturan/variabelSistem.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>