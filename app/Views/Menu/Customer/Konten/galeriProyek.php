<?php
    $menuName           =   $menuDetail['MENUNAME'];
    $menuDescription    =   $menuDetail['DESCRIPTION'];
    $listFilterMerk     =   '<input type="radio" class="btn-check" name="customerKontenGaleriProyek-filterMerk" id="filterSemua" value="" checked>';
    $listFilterMerk     .=  '<label class="btn  btn-outline-dark fw-semibold" for="filterSemua">Semua</label>';

    if(!is_null($dataMerk) && count($dataMerk) > 0){
        foreach($dataMerk as $keyMerk){
            $listFilterMerk .=  '<input type="radio" class="btn-check" name="customerKontenGaleriProyek-filterMerk" id="filter'.$keyMerk->ID.'" value="'.$keyMerk->ID.'">';
            $listFilterMerk .=  '<label class="btn  btn-outline-dark fw-semibold" for="filter'.$keyMerk->ID.'">'.$keyMerk->VALUE.'</label>';
        }
    }
?>

<div id="containerMenuCustomerKontenGaleriProyek" class="pos">
    <h1 id="customerKontenGaleriProyek-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
        <span class="ms-md-auto mt-md-0 mt-2">
            <button id="btnAddGaleriProyek" type="button" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i> Tambah Galeri
            </button>
        </span>
    </h1>
    <hr id="customerKontenGaleriProyek-hr" class="mb-4">
    <div class="pos-content">
        <div class="pos-content-container p-0">
            <div class="row gx-3">
                <div class="col-12 mb-4">
                    <div class="btn-group flex-wrap" role="group" id="customerKontenGaleriProyek-filterMerkContainer"><?=$listFilterMerk?></div>
                </div>
            </div>
            <div class="row gx-3 overflow-y-auto" id="customerKontenGaleriProyek-content"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="customerKontenGaleriProyek-editor">
    <div class="modal-dialog modal-lg">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Detail Galeri Proyek</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img class="mb-2" src="<?=$defaultImage?>" id="galeriProyekImg" style="max-width: 200px; max-height: 120px;"/><br/>
                    <span id="uploadGaleriProyekImg">Upload Image Galeri</span>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="idMerkUtama">Merk Utama</label>
                            <select class="form-select" id="idMerkUtama" name="idMerkUtama"></select>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-6 col-sm-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="namaKlien">Nama Klien</label>
                            <input type="text" class="form-control" name="namaKlien" id="namaKlien" placeholder="Nama Klien">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="alamatProyek">Alamat Proyek</label>
                            <input type="text" class="form-control" name="alamatProyek" id="alamatProyek" placeholder="Alamat Proyek">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
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
                    <div class="col-sm-12">
                        <label class="form-label" for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" id="deskripsi" placeholder="Deskripsi Proyek" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="idGaleriProyek" value="">
                <input type="hidden" name="imageFileName" value="">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<style>
    .item-galeri-proyek .info i {
        color: #2d364b;
    }
    .item-galeri-proyek .info .deskripsi {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
<script>
    var imageGaleriProyekBaseUrl    =   "<?=BASE_URL_ASSETS_GALERI_PROYEK?>",
        imageGaleriProyekDefault    =   "<?=$defaultImage?>",
        jsFileUrl                   =   "<?=BASE_URL_ASSETS_JS?>menu/customer/konten/galeriProyek.js?<?=date("YmdHis")?>";
    $.getScript(jsFileUrl);
</script>
