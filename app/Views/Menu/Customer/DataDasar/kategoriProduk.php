<?php
    $menuName           =   $menuDetail['MENUNAME'];
    $menuDescription    =   $menuDetail['DESCRIPTION'];
?>

<div id="containerMenuCustomerDataDasarKategoriProduk" class="pos">
    <h1 id="customerDataDasarKategoriProduk-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
        <button id="btnAddKategoriProduk" type="button" class="btn btn-primary ms-md-auto mt-md-0 mt-2">
            <i class="fa fa-plus me-1"></i> Tambah Kategori Produk
        </button>
    </h1>
    <hr id="customerDataDasarKategoriProduk-hr" class="mb-4">
    <div id="customerDataDasarKategoriProduk-cardContent" class="card d-flex flex-column">
        <div class="p-3 mb-3 border-bottom">
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Ketik sesuatu dan tekan ENTER untuk mencari.." id="customerDataDasarKategoriProduk-searchKeyword">
            </div>
        </div>
        <div class="table-responsive table-sticky-header px-4 flex-fill">
            <table class="table table-hover text-nowrap mb-0 w-100">
                <thead>
                    <tr class="table-dark">
                        <th class="py-2 sticky-top sticky-col-left" width="25%">Nama Kategori</th>
                        <th class="py-2 sticky-top">Deskripsi</th>
                        <th class="py-2 sticky-top" width="6%">Status</th>
                        <th class="py-2 sticky-top" width="6%"></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="d-md-flex align-items-center p-3 border-top">
            <div class="me-md-auto text-md-left text-center mb-2 mb-md-0" id="customerDataDasarKategoriProduk-paginationInfo"></div>
            <div class="btn-group btn-group-md" id="customerDataDasarKategoriProduk-paginationControl"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="customerDataDasarKategoriProduk-editor">
    <div class="modal-dialog modal-sm">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Detail Kategori Produk</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label" for="kategoriProduk">Kategori Produk</label>
                    <input type="text" class="form-control" name="kategoriProduk" id="kategoriProduk" placeholder="Kategori Produk">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" for="deskripsi">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" id="deskripsi" placeholder="Deskripsi Kategori" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label d-block">Status</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="statusAktif" value="1">
                        <label class="form-check-label" for="statusAktif">Aktif</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="statusNonAktif" value="-1">
                        <label class="form-check-label" for="statusNonAktif">Non Aktif</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="idKategoriProduk" value="">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
	var jsFileUrl   =   "<?=BASE_URL_ASSETS_JS?>menu/customer/dataDasar/kategoriProduk.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>