<?php
    $menuName           =   $menuDetail['MENUNAME'] ?? '';
    $menuDescription    =   $menuDetail['DESCRIPTION'] ?? '';
?>

<div id="containerMenuCustomerKontenSlideKolaborasi" class="pos">
    <h1 id="customerKontenSlideKolaborasi-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
        <button id="btnAddSlideKolaborasi" type="button" class="btn btn-primary ms-md-auto mt-md-0 mt-2">
            <i class="fa fa-plus me-1"></i> Konten Kolaborasi
        </button>
        <button id="btnBatalEditor" type="button" class="btn btn-warning ms-md-auto mt-md-0 mt-2 d-none">
            <i class="fa fa-arrow-left me-1"></i> Kembali
        </button>
    </h1>
    <hr id="customerKontenSlideKolaborasi-hr" class="mb-4">
    <div id="customerKontenSlideKolaborasi-leftContainer" class="show">
        <div id="customerKontenSlideKolaborasi-alert" class="alert alert-primary">
            <strong><i class="fa fa-fw fa-info-circle me-1"></i>Informasi | </strong>
            Jumlah konten kolaborasi yang ditampilkan di aplikasi customer adalah <b>maksimal 4 konten kolaborasi</b> terbaru yang berstatus aktif. Jika lebih dari 4 yang berstatus aktif, maka yang ditampilkan adalah 4 konten kolaborasi terbaru.
        </div>
        <div id="customerKontenSlideKolaborasi-cardContent" class="card d-flex flex-column">
            <div class="p-3 mb-3 border-bottom">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Ketik sesuatu dan tekan ENTER untuk mencari.." id="customerKontenSlideKolaborasi-searchKeyword">
                </div>
            </div>
            <div class="table-responsive table-sticky-header px-4 flex-fill">
                <table class="table table-hover mb-0 w-100" style="table-layout: fixed; word-wrap: break-word; word-break: break-word;">
                    <thead>
                        <tr class="table-dark">
                            <th class="py-2 sticky-top sticky-col-left" width="6%">Produk</th>
                            <th class="py-2 sticky-top" width="10%">Thumbnail Video</th>
                            <th class="py-2 sticky-top" width="20%">Judul</th>
                            <th class="py-2 sticky-top" width="15%">URL Video</th>
                            <th class="py-2 sticky-top">Konten</th>
                            <th class="py-2 sticky-top" width="10%">Detail Input</th>
                            <th class="py-2 sticky-top" width="4%">Status</th>
                            <th class="py-2 sticky-top" width="4%"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="d-md-flex align-items-center p-3 border-top">
                <div class="me-md-auto text-md-left text-center mb-2 mb-md-0" id="customerKontenSlideKolaborasi-paginationInfo"></div>
                <div class="btn-group btn-group-md" id="customerKontenSlideKolaborasi-paginationControl"></div>
            </div>
        </div>
    </div>
    <div id="customerKontenSlideKolaborasi-rightContainer" class="d-none">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Detail Konten Kolaborasi</h6>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6 text-center px-2 border-end">
                                <h6 class="fw-semibold small mb-2">Gambar Produk</h6>
                                <img class="mb-2 rounded" src="<?=$defaultImage ?? 'default.jpg'?>" id="imgProduk" style="max-width: 100%; max-height: 160px;"/><br/>
                                <span id="uploadImageProduk">Upload Gambar Konten Kolaborasi</span>
                                <input type="hidden" name="produkFileName" value="">
                            </div>
                            <div class="col-6 text-center px-2">
                                <h6 class="fw-semibold small mb-2">Thumbnail Video</h6>
                                <img class="mb-2 rounded" src="<?=$defaultImageThumbnail ?? 'default.jpg'?>" id="imgThumbnail" style="max-width: 100%; max-height: 160px;"/><br/>
                                <span id="uploadImageThumbnail">Upload Thumbnail Video</span>
                                <input type="hidden" name="thumbnailFileName" value="">
                            </div>
                        </div>
                        <hr class="my-4">
                        <div class="form-group mb-3">
                            <label class="form-label" for="judul">Judul</label>
                            <input type="text" class="form-control" name="judul" id="judul" placeholder="Judul Konten Kolaborasi">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" for="urlVideo">URL Video</label>
                            <input type="text" class="form-control" name="urlVideo" id="urlVideo" placeholder="URL Video Konten Kolaborasi">
                        </div>
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
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Konten Kolaborasi</h6>
                    </div>
                    <div class="card-body">
                        <textarea id="konten" class="summernote" rows="10"></textarea>
                    </div>
                    <div class="card-footer d-flex">
                        <input type="hidden" name="idSlideKolaborasi" value="">
                        <button type="button" class="btn btn-primary ms-auto" id="btnSimpanSlideKolaborasi">
                            <i class="fa fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </div>    
            </div>    
        </div>    
    </div>
</div>
<script>
	var baseURLImageProduk      =   "<?=BASE_URL_ASSETS_SLIDE_KOLABORASI_PRODUK?>",
        defaultImageProduk      =   "<?=$defaultImageProduk ?? 'default.jpg'?>",
        baseURLImageThumbnail   =   "<?=BASE_URL_ASSETS_SLIDE_KOLABORASI_THUMBNAIL?>",
        defaultImageThumbnail   =   "<?=$defaultImageThumbnail ?? 'default.jpg'?>",
        jsFileUrl               =   "<?=BASE_URL_ASSETS_JS?>menu/customer/konten/slideKolaborasi.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>