<?php
    $menuName           =   $menuDetail['MENUNAME'];
    $menuDescription    =   $menuDetail['DESCRIPTION'];
?>

<div id="containerMenuCustomerKontenTutorialPemasangan" class="pos">
    <h1 id="customerKontenTutorialPemasangan-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
        <span class="ms-md-auto mt-md-0 mt-2">
            <button id="btnUrutanTutorial" type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#customerKontenTutorialPemasangan-urutanTutorial">
                <i class="fa fa-sort-amount-asc me-1"></i> Urutan Tutorial
            </button>
            <button id="btnAddTutorialPemasangan" type="button" class="btn btn-primary ms-md-auto mt-md-0 mt-2">
                <i class="fa fa-plus me-1"></i> Tambah Tutorial Pemasangan
            </button>
        </span>
        <button id="btnBatalEditor" type="button" class="btn btn-warning ms-md-auto mt-md-0 mt-2 d-none">
            <i class="fa fa-arrow-left me-1"></i> Kembali
        </button>
    </h1>
    <hr id="customerKontenTutorialPemasangan-hr" class="mb-4">
    <div id="customerKontenTutorialPemasangan-leftContainer" class="show">
        <div id="customerKontenTutorialPemasangan-alert" class="alert alert-primary">
            <strong><i class="fa fa-fw fa-info-circle me-1"></i>Informasi | </strong>
            Jumlah tutorial pemasangan yang ditampilkan di aplikasi customer adalah <b>maksimal 8 tutorial pemasangan</b> berstatus aktif sesuai urutan. Jika lebih dari 8 yang berstatus aktif, maka yang ditampilkan hanya ururan 8 teratas.
        </div>
        <div id="customerKontenTutorialPemasangan-cardContent" class="card d-flex flex-column">
            <div class="p-3 mb-3 border-bottom">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Ketik sesuatu dan tekan ENTER untuk mencari.." id="customerKontenTutorialPemasangan-searchKeyword">
                </div>
            </div>
            <div class="table-responsive table-sticky-header px-4 flex-fill">
                <table class="table table-hover mb-0 w-100" style="table-layout: fixed; word-wrap: break-word; word-break: break-word;">
                    <thead>
                        <tr class="table-dark">
                            <th class="py-2 sticky-top sticky-col-left" width="10%"></th>
                            <th class="py-2 sticky-top sticky-col-left" width="20%">Judul</th>
                            <th class="py-2 sticky-top">Konten</th>
                            <th class="py-2 sticky-top" width="15%">Url Video</th>
                            <th class="py-2 sticky-top" width="10%">Detail Input</th>
                            <th class="py-2 sticky-top" width="4%">Status</th>
                            <th class="py-2 sticky-top" width="4%"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="d-md-flex align-items-center p-3 border-top">
                <div class="me-md-auto text-md-left text-center mb-2 mb-md-0" id="customerKontenTutorialPemasangan-paginationInfo"></div>
                <div class="btn-group btn-group-md" id="customerKontenTutorialPemasangan-paginationControl"></div>
            </div>
        </div>
    </div>
    <div id="customerKontenTutorialPemasangan-rightContainer" class="d-none">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Detail Tutorial Pemasangan</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img class="mb-2 rounded" src="<?=$defaultImage?>" id="imgThumbnailVideo" style="max-width: 500px; max-height: 209px;"/><br/>
                            <span id="uploadThumbnailVideo">Upload Thumbnail Video</span>
                            <input type="hidden" name="thumbnailVideoFileName" value="">
                        </div>
                        <hr class="my-4">
                        <div class="form-group mb-3">
                            <label class="form-label" for="judul">Judul</label>
                            <input type="text" class="form-control" name="judul" id="judul" placeholder="Judul Tutorial Pemasangan">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" for="urlVideo">URL Video</label>
                            <input type="text" class="form-control" name="urlVideo" id="urlVideo" placeholder="URL Video Youtube">
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
                        <h6 class="card-title">Konten Tutorial Pemasangan</h6>
                    </div>
                    <div class="card-body">
                        <textarea id="konten" class="summernote" rows="10"></textarea>
                    </div>
                    <div class="card-footer d-flex">
                        <input type="hidden" name="idVideoCaraPemasangan" value="">
                        <button type="button" class="btn btn-primary ms-auto" id="btnSimpanTutorialPemasangan">
                            <i class="fa fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </div>    
            </div>    
        </div>    
    </div>
</div>
<div class="modal fade" id="customerKontenTutorialPemasangan-urutanTutorial">
    <div class="modal-dialog modal-md">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Atur Urutan Tutorial</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="customerKontenTutorialPemasangan-sortable"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
	var baseURLImage=   "<?=BASE_URL_ASSETS_VIDEO_CARA_PASANG?>";
        defaultImage=   "<?=$defaultImage?>";
        jsFileUrl   =   "<?=BASE_URL_ASSETS_JS?>menu/customer/konten/tutorialPemasangan.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>