<?php
    $menuName           =   $menuDetail['MENUNAME'] ?? '';
    $menuDescription    =   $menuDetail['DESCRIPTION'] ?? '';
?>
<div id="containerMenuPengaturanUserLevelMenu">
    <h1 id="pengaturanUserLevelMenu-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
    </h1>
    <hr id="pengaturanUserLevelMenu-hr" class="mb-4">
    <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-12 mb-3 mb-md-0">
            <div class="card" id="pengaturanUserLevelMenu-cardLevelUser">
                <div class="card-body">
                    <div class="list-group list-group-flush" id="pengaturanUserLevelMenu-listLevelUser"></div>
                </div>
                <div class="card-footer text-center">
                    <span class="btn btn-theme fw-semibold d-block mb-0" id="btnAddLevelUser"><i class="fa fa-plus"></i> Level User</span>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-8 col-sm-12 mb-3 mb-md-0">
            <div class="card" id="pengaturanUserLevelMenu-cardMenuLevel">
                <div class="card-body overflow-hidden" id="pengaturanUserLevelMenu-daftarMenuLevel">
                    <div class="table-responsive table-sticky-header px-1 pb-5 flex-fill">
                        <table class="table table-hover w-100" style="table-layout: fixed; word-wrap: break-word; word-break: break-word;">
                            <thead>
                                <tr class="table-dark">
                                    <th class="py-2 sticky-top sticky-col-left" width="10%">Platform</th>
                                    <th class="py-2 sticky-top" width="12%">Grup Menu</th>
                                    <th class="py-2 sticky-top">Sub Menu</th>
                                    <th class="py-2 sticky-top" width="20%">Akses Khusus</th>
                                    <th class="py-2 sticky-top" width="12%">Akses</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <input type="hidden" id="pengaturanUserLevelMenu-idUserLevel" name="pengaturanUserLevelMenu-idUserLevel" value="">
                    <span class="btn btn-success fw-semibold mb-0" id="pengaturanUserLevelMenu-btnSaveMenuLevel"><i class="fa fa-save"></i> Simpan</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="pengaturanUserLevelMenu-editor">
    <div class="modal-dialog modal-sm">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Detail Level Pengguna</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label" for="namaLevel">Nama Level</label>
                    <input type="text" class="form-control" name="namaLevel" id="namaLevel" placeholder="Nama Level">
                </div>
                <div class="form-group">
                    <label class="form-label" for="deskripsi">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" id="deskripsi" placeholder="Deskripsi Level" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="idLevelUser" value="">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
	var jsFileUrl   =   "<?=BASE_URL_ASSETS_JS?>menu/pengaturan/userLevelMenu.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>