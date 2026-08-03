<?php
    $menuName           =   $menuDetail['MENUNAME'];
    $menuDescription    =   $menuDetail['DESCRIPTION'];
?>

<div id="containerMenuCustomerKontenGaleriKlien">
    <h1 id="customerKontenGaleriKlien-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
        <span class="ms-md-auto mt-md-0 mt-2">
            <button id="btnAddKlien" type="button" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i> Klien Baru
            </button>
        </span>
    </h1>
    <hr id="customerKontenGaleriKlien-hr" class="mb-4">
</div>
<div class="gallery-content-container border-start-0">
    <div id="customerKontenGaleriKlien-content" class="gallery-content"></div>
</div>
<div class="modal fade" id="customerKontenGaleriKlien-editorKlien">
    <div class="modal-dialog modal-md">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Detail Klien</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img class="mb-2" src="<?=$defaultImageLogo?>" id="galeriKlienLogo" style="max-width: 200px; max-height: 120px;"/><br/>
                    <span id="uploadGaleriKlienLogo">Upload Logo Klien</span>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="namaKlien">Nama Klien</label>
                            <input type="text" class="form-control" name="namaKlien" id="namaKlien" placeholder="Nama Klien">
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
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="idKlien" value="">
                <input type="hidden" name="logoFileName" value="">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="customerKontenGaleriKlien-editorGaleri">
    <div class="modal-dialog modal-md">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Detail Galeri</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img class="mb-2" src="<?=$defaultImageGaleri?>" id="galeriKlienImage" style="max-width: 200px; max-height: 120px;"/><br/>
                    <span id="uploadGaleriKlienImage">Upload Gambar Galeri</span>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="idMerkUtama">Merk Utama</label>
                            <select class="form-select" id="idMerkUtama" name="idMerkUtama"></select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="form-label" for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" id="deskripsi" placeholder="Deskripsi Proyek" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="idKlien" value="">
                <input type="hidden" name="idGaleriKlien" value="">
                <input type="hidden" name="imageFileName" value="">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
    var imageGaleriKlienLogoUrl     =   "<?=BASE_URL_ASSETS_GALERI_KLIEN_LOGO?>",
        imageGaleriKlienLogoDefault =   imageGaleriKlienLogoUrl+"default.png",
        imageGaleriKlienUrl         =   "<?=BASE_URL_ASSETS_GALERI_KLIEN_PROYEK?>",
        imageGaleriDefault          =   imageGaleriKlienUrl+"noimage.jpg",
        imageLogoMerkUrl            =   "<?=BASE_URL_ASSETS_CUSTOMER_MERK?>",
        imageLogoMerkDefault        =   imageLogoMerkUrl+"default.jpg",
        jsFileUrl                   =   "<?=BASE_URL_ASSETS_JS?>menu/customer/konten/galeriKlien.js?<?=date("YmdHis")?>";
    $.getScript(jsFileUrl);
</script>
