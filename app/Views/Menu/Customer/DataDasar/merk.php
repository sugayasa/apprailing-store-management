<?php
    $menuName           =   $menuDetail['MENUNAME'];
    $menuDescription    =   $menuDetail['DESCRIPTION'];
?>
<div id="containerMenuCustomerDataDasarMerk" class="pos">
    <h1 id="customerDataDasarMerk-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
        <button id="btnAddMerk" type="button" class="btn btn-primary ms-auto">
            <i class="fa fa-plus me-1"></i> Tambah Merk
        </button>
    </h1>
    <hr id="customerDataDasarMerk-hr" class="mb-4">
    <div class="pos-content">
        <div class="pos-content-container p-0">
            <div class="row gx-3" id="customerDataDasarMerk-content"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="customerDataDasarMerk-editor">
    <div class="modal-dialog modal-sm">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Detail Merk</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img class="mb-2" src="<?=$defaultImage?>" id="logoMerkImg" style="max-width: 200px; max-height: 120px;"/><br/>
                    <span id="uploadLogoMerk">Upload Logo Merk</span>
                </div>
                <hr>
                <div class="form-group mb-3">
                    <label class="form-label" for="namaMerk">Nama Merk</label>
                    <input type="text" class="form-control" name="namaMerk" id="namaMerk" placeholder="Nama Merk">
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
                <input type="hidden" name="idMerk" value="">
                <input type="hidden" name="logoFileName" value="">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="customerDataDasarMerk-editorKatalog">
    <div class="modal-dialog modal-lg">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Detail Katalog Merk</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-5 col-sm-12">
                        <div class="form-group">
                            <label class="form-label d-block">Status</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusTersedia" value="1">
                                <label class="form-check-label" for="statusTersedia">Tersedia</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusTidakTersedia" value="0">
                                <label class="form-check-label" for="statusTidakTersedia">Tidak Tersedia</label>
                            </div>
                        </div>
                        <hr>
                        <div class="text-center">
                            <img class="mb-2" src="<?=$defaultPdfKatalogThumbnail?>" id="pdfThumbnail" style="max-width: 300px; max-height: 420px;"/><br/>
                            <span id="uploadPdfThumbnail">Upload Thumbnail PDF</span>
                        </div>
                    </div>
                    <div class="col-lg-7 col-sm-12 border-start-lg border-dark">
                        <div class="text-center">
                            <iframe class="mb-2" src="<?=$defaultPdfKatalogFile?>" id="pdfFile" style="max-width: 430px; max-height: 500px; width: 100%; height: 500px; border: 1px solid #dee2e6;"></iframe><br/>
                            <span id="uploadPdfFile">Upload File PDF</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="idMerk" value="">
                <input type="hidden" name="pdfThumbnailName" value="">
                <input type="hidden" name="pdfFileName" value="">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
	var logoMerkBaseUrl =   "<?=BASE_URL_ASSETS_LOGO_MERK?>",
        logoMerkDefault =   "<?=$defaultImage?>",
        jsFileUrl       =   "<?=BASE_URL_ASSETS_JS?>menu/customer/dataDasar/merk.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>