<?php
    $menuName           =   $menuDetail['MENUNAME'];
    $menuDescription    =   $menuDetail['DESCRIPTION'];
?>

<div id="containerMenuCustomerKontenFeed" class="pos">
    <h1 id="customerKontenFeed-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
        <span class="ms-md-auto mt-md-0 mt-2">
            <button id="btnAddFeed" type="button" class="btn btn-primary">
                <i class="fa fa-plus me-1"></i> Tambah Feed
            </button>
        </span>
    </h1>
    <hr id="customerKontenFeed-hr" class="mb-4">
    
    <div class="pos-content">
        <div class="pos-content-container p-0">
            <div class="row gx-3">
                <div class="col-lg-8 col-md-12">
                    <div class="card" id="customerKontenFeed-cardContent">
                        <div class="card-header" id="customerKontenFeed-daftarDetailHeader">
                            <h6 class="card-title">Daftar Detail Feed</h6>
                        </div>
                        <div class="card-body overflow-hidden" id="customerKontenFeed-daftarDetailContainer">
                            <div class="p-1 pb-3 mb-3 border-bottom">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                                    <input type="text" class="form-control" placeholder="Ketik sesuatu dan tekan ENTER untuk mencari.." id="customerKontenFeed-searchKeyword">
                                </div>
                            </div>
                            <div class="table-responsive table-sticky-header px-1 pb-5 flex-fill">
                                <table class="table table-hover w-100" style="table-layout: fixed; word-wrap: break-word; word-break: break-word;">
                                    <thead>
                                        <tr class="table-dark">
                                            <th class="py-2 sticky-top sticky-col-left" width="20%">Judul</th>
                                            <th class="py-2 sticky-top">Deskripsi</th>
                                            <th class="py-2 sticky-top" width="20%">URL Video</th>
                                            <th class="py-2 sticky-top" width="6%" style="text-align: right;"><i class="fa fa-fw fa-heart"></i></th>
                                            <th class="py-2 sticky-top" width="6%" style="text-align: right;"><i class="fa fa-fw fa-bookmark"></i></th>
                                            <th class="py-2 sticky-top" width="12%">Detail Input</th>
                                            <th class="py-2 sticky-top" width="4%"></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-header text-center" id="customerKontenFeed-feedHeader">
                            <i class="fa fa-fw fa-angle-double-up me-1"></i>
                        </div>
                        <div class="card-body" id="customerKontenFeed-feedContainer">
                        </div>
                        <div class="card-footer text-center" id="customerKontenFeed-feedFooter">
                            <i class="fa fa-fw fa-angle-double-down me-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="customerKontenFeed-editor">
    <div class="modal-dialog modal-md">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Detail Feed</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label" for="judul">Judul</label>
                    <input type="text" class="form-control" name="judul" id="judul" placeholder="Judul">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" for="urlFeed">URL Feed</label>
                    <input type="text" class="form-control" name="urlFeed" id="urlFeed" placeholder="URL Feed">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" for="deskripsi">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" id="deskripsi" placeholder="Deskripsi Feed" rows="5"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="idFeed" value="">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
    var jsFileUrl   =   "<?=BASE_URL_ASSETS_JS?>menu/customer/konten/feed.js?<?=date("YmdHis")?>";
    $.getScript(jsFileUrl);
</script>
