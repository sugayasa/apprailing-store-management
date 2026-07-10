<?php
    $menuName           =   $menuDetail['MENUNAME'];
    $menuDescription    =   $menuDetail['DESCRIPTION'];
?>
<div id="containerMenuCustomerDataDasarSosmedMarketplace" class="pos">
    <h1 id="customerDataDasarSosmedMarketplace-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
        <span class="ms-md-auto mt-md-0 mt-2">
            <button id="btnUrutanTipeSosmedMarketplace" type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#customerDataDasarSosmedMarketplace-urutanTipe">
                <i class="fa fa-sort-amount-asc me-1"></i> Urutan
            </button>
            <button id="btnAddTipeSosmedMarketplace" type="button" class="btn btn-primary ms-md-auto mt-md-0 mt-2">
                <i class="fa fa-plus me-1"></i> Tipe Sosmed / Marketplace
            </button>
        </span>
    </h1>
    <hr id="customerDataDasarSosmedMarketplace-hr" class="mb-4">
    <div class="row" id="customerDataDasarSosmedMarketplace-content"></div>
</div>
<div class="modal fade" id="customerDataDasarSosmedMarketplace-editorTipe">
    <div class="modal-dialog modal-sm">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Detail Sosmed & Marketplace</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img class="mb-2" src="<?=$defaultImageIcon?>" id="iconSosmedMarketplaceImg" style="max-width: 200px; max-height: 120px;"/><br/>
                    <span id="uploadIconSosmedMarketplace">Upload Logo Sosmed / Marketplace</span>
                </div>
                <hr>
                <div class="form-group mb-3">
                    <label class="form-label" for="namaTipeSosmedMarketplace">Nama Tipe Sosmed / Marketplace</label>
                    <input type="text" class="form-control" name="namaTipeSosmedMarketplace" id="namaTipeSosmedMarketplace" placeholder="Nama Tipe Sosmed / Marketplace">
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
                <input type="hidden" name="idTipeSosmedMarketplace" value="">
                <input type="hidden" name="iconFileName" value="">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="customerDataDasarSosmedMarketplace-urutanTipe">
    <div class="modal-dialog modal-md">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Atur Urutan Tipe Sosmed / Marketplace</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="customerDataDasarSosmedMarketplace-sortable"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="customerDataDasarSosmedMarketplace-editorAkun">
    <div class="modal-dialog modal-sm">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Detail Akun</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center me-2 flex-shrink-0" style="width: 28px; height: 28px; background-color: #2D2D2D;">
                        <img id="iconTipeSosmedMarketplaceImg" src="<?= $defaultImageIcon ?>" class="img-fluid" style="max-height: 14px;">
                    </span>
                    <h5 class="card-title mb-0" id="namaSosmedMarketplace">-</h5>
                </div>
                <hr>
                <div class="form-group mb-3">
                    <label class="form-label" for="namaAkun">Nama Akun</label>
                    <input type="text" class="form-control" name="namaAkun" id="namaAkun" placeholder="Nama Akun">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" for="urlAkun">URL Akun</label>
                    <input type="text" class="form-control" name="urlAkun" id="urlAkun" placeholder="URL Akun">
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="idTipeSosmedMarketplace" value="">
                <input type="hidden" name="idSosmedMarketplace" value="">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
	var iconBaseUrl     =   "<?=BASE_URL_ASSETS_CUSTOMER_SOSMED_MARKETPLACE?>",
        defaultImageIcon=   "<?=$defaultImageIcon?>",
        jsFileUrl       =   "<?=BASE_URL_ASSETS_JS?>menu/customer/dataDasar/sosmedMarketplace.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>