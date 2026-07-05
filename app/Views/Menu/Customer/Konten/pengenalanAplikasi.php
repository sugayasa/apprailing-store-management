<?php
    $menuName           =   $menuDetail['MENUNAME'];
    $menuDescription    =   $menuDetail['DESCRIPTION'];
?>

<div id="containerMenuCustomerKontenPengenalanAplikasi" class="pos">
    <h1 id="customerKontenPengenalanAplikasi-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
        <span class="ms-md-auto mt-md-0 mt-2">
            <button id="btnUrutanSlide" type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#customerKontenPengenalanAplikasi-urutanSlide">
                <i class="fa fa-sort-amount-asc me-1"></i> Urutan Slide
            </button>
            <button id="btnAddSlide" type="button" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i> Tambah Slide
            </button>
        </span>
    </h1>
    <hr id="customerKontenPengenalanAplikasi-hr" class="mb-4">
    
    <div class="pos-content">
        <div class="pos-content-container p-0">
            <div class="row gx-3" id="customerKontenPengenalanAplikasi-content"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="customerKontenPengenalanAplikasi-editor">
    <div class="modal-dialog modal-md">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Detail Pengenalan Aplikasi - Onboarding Slide</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img class="mb-2" src="<?=$defaultImage?>" id="onboardingImg" style="max-width: 200px; max-height: 120px;"/><br/>
                    <span id="uploadOnboardingImg">Upload Image Onboarding</span>
                </div>
                <hr>
                <div class="form-group mb-3">
                    <label class="form-label" for="kontenDeskripsi">Konten/Deskripsi</label>
                    <textarea class="form-control" name="kontenDeskripsi" id="kontenDeskripsi" placeholder="Konten/Deskripsi" rows="3"></textarea>
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
                <input type="hidden" name="idSlideBoarding" value="">
                <input type="hidden" name="imageFileName" value="">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="customerKontenPengenalanAplikasi-urutanSlide">
    <div class="modal-dialog modal-md">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Atur Urutan Slide</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="customerKontenPengenalanAplikasi-sortable"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
    var imageOnboardingBaseUrl  =   "<?=BASE_URL_ASSETS_SLIDE_ONBOARDING?>",
        imageOnboardingDefault  =   "<?=$defaultImage?>",
        jsFileUrl               =   "<?=BASE_URL_ASSETS_JS?>menu/customer/konten/pengenalanAplikasi.js?<?=date("YmdHis")?>";
    $.getScript(jsFileUrl);
</script>
