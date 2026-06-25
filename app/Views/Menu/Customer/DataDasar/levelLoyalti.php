<?php
    $menuName           =   $menuDetail['MENUNAME'];
    $menuDescription    =   $menuDetail['DESCRIPTION'];
?>
<div id="containerMenuCustomerDataDasarLevelLoyalti" class="pos">
    <h1 id="customerDataDasarLevelLoyalti-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
        <button id="btnAddLevelLoyalti" type="button" class="btn btn-primary ms-auto">
            <i class="fa fa-plus me-1"></i> Tambah Level Loyalti
        </button>
    </h1>
    <hr id="customerDataDasarLevelLoyalti-hr" class="mb-4">
    <div class="pos-content">
        <div class="pos-content-container p-0">
            <div class="row gx-3" id="customerDataDasarLevelLoyalti-content"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="customerDataDasarLevelLoyalti-editor">
    <div class="modal-dialog modal-md">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Level Loyalti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="text-center mb-3">
                            <img class="mb-2 rounded" src="<?=$defaultImageCard?>" id="cardLevelLoyaltiImg" style="max-width: 200px; max-height: 120px;"/><br/>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="text-center mb-3">
                            <img class="mb-2 img-fluid rounded-circle" src="<?=$defaultImageIcon?>" id="iconLevelLoyaltiImg" style="max-width: 80px; max-height: 80px;"/><br/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="text-center mb-3">
                            <span id="uploadCardLevelLoyalti">Upload Card Level Loyalti</span>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="text-center mb-3">
                            <span id="uploadIconLevelLoyalti">Upload Icon Level Loyalti</span>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="levelLoyalti">Level Loyalti</label>
                            <input type="text" class="form-control" name="levelLoyalti" id="levelLoyalti" placeholder="Level Loyalti">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="deskripsi">Deskripsi</label>
                            <input type="text" class="form-control" name="deskripsi" id="deskripsi" placeholder="Deskripsi">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="minNominalPembelian">Min. Nominal Pembelian</label>
                            <input type="text" class="form-control text-end" name="minNominalPembelian" id="minNominalPembelian" placeholder="0" onkeypress="maskNumberInput(0, 999999999, 'minNominalPembelian')">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group mb-3">
                            <label class="form-label d-block">Status</label>
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="radio" name="status" id="statusAktif" value="1">
                                <label class="form-check-label" for="statusAktif">Aktif</label>
                            </div>
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="radio" name="status" id="statusNonAktif" value="-1">
                                <label class="form-check-label" for="statusNonAktif">Non Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="idCustomerLoyalti" value="">
                <input type="hidden" name="cardFileName" value="">
                <input type="hidden" name="iconFileName" value="">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
	var levelLoyaltiCardBaseUrl =   "<?=BASE_URL_ASSETS_CARD_LEVEL_LOYALTI?>",
        levelLoyaltiCardDefault =   "<?=$defaultImageCard?>",
        levelLoyaltiIconBaseUrl =   "<?=BASE_URL_ASSETS_ICON_LEVEL_LOYALTI?>",
        levelLoyaltiIconDefault =   "<?=$defaultImageIcon?>",
        jsFileUrl               =   "<?=BASE_URL_ASSETS_JS?>menu/customer/dataDasar/levelLoyalti.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>