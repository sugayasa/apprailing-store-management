<?php
    $menuName               =   $menuDetail['MENUNAME'] ?? '';
    $menuDescription        =   $menuDetail['DESCRIPTION'] ?? '';
    $baseURLImageMarketing  =   $baseURLImageMarketing ?? '';
    $defaultImage           =   $defaultImage ?? '';
?>

<div id="containerMenuCustomerDataDasarDaftarMarketing" class="pos">
    <h1 id="customerDataDasarDaftarMarketing-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
    </h1>
    <hr id="customerDataDasarDaftarMarketing-hr" class="mb-4">
    <div class="pos-content">
        <div class="pos-content-container p-0">
            <div class="row gx-2 gy-2" id="customerDataDasarDaftarMarketing-content">
                <div id="customerDataDasarDaftarMarketing-rowInfoKosong" class="col-12">
                    <div class="alert alert-info d-flex flex-column align-items-center justify-content-center gap-2 text-center mb-0 py-4">
                        <i class="fa fa-users fa-3x opacity-50"></i>
                        <div>
                            <div class="fw-semibold">Belum ada data marketing</div>
                            <small class="text-muted info">Data marketing akan tampil di sini setelah dimuat</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="customerDataDasarMarketing-editor">
    <div class="modal-dialog modal-md">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Detail Marketing</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <p>
                        <div class="fs-5 fw-semibold" id="marketingNama"></div>
                        <div class="fs-6 text-muted" id="marketingRegional"></div>
                    </p>
                </div>
                <div class="text-center mb-3">
                    <img class="mb-2 rounded" src="<?=$baseURLImageMarketing . ($defaultImage ?? 'default.jpg')?>" id="marketingImg" style="max-width: 200px; max-height: 120px;"/><br/>
                </div>
                <div class="text-center">
                    <span id="uploadImageMarketing">Upload Card Level Loyalti</span>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="idMarketing" value="">
                <input type="hidden" name="marketingImageFileName" value="">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
    var baseURLImageMarketing   =   "<?=$baseURLImageMarketing?>",
        defaultImage            =   "<?=$defaultImage?>",
        jsFileUrl               =   "<?=BASE_URL_ASSETS_JS?>menu/customer/dataDasar/daftarMarketing.js?<?=date("YmdHis")?>";
    $.getScript(jsFileUrl);
</script>