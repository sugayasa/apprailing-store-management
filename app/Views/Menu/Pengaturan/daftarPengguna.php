<?php
    $menuName           =   $menuDetail['MENUNAME'] ?? '';
    $menuDescription    =   $menuDetail['DESCRIPTION'] ?? '';
?>

<div id="containerMenuPengaturanDaftarPengguna">
    <h1 id="pengaturanDaftarPengguna-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
        <button id="btnAddPengguna" type="button" class="btn btn-primary ms-md-auto mt-md-0 mt-2">
            <i class="fa fa-plus me-1"></i> Pengguna
        </button>
    </h1>
    <hr id="pengaturanDaftarPengguna-hr" class="mb-4">
    <div id="pengaturanDaftarPengguna-cardContent" class="card d-flex flex-column">
        <div class="p-3 mb-3 border-bottom">
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Ketik sesuatu dan tekan ENTER untuk mencari.." id="pengaturanDaftarPengguna-searchKeyword">
            </div>
        </div>
        <div class="table-responsive table-sticky-header px-3 flex-fill">
            <table class="table table-hover text-nowrap mb-0 w-100">
                <thead>
                    <tr class="table-dark">
                        <th class="py-2 sticky-top sticky-col-left" width="10%">Level</th>
                        <th class="py-2 sticky-top" width="18%">Nama</th>
                        <th class="py-2 sticky-top" width="12%">Username</th>
                        <th class="py-2 sticky-top">Email</th>
                        <th class="py-2 sticky-top" width="12%">Terakhir Login</th>
                        <th class="py-2 sticky-top" width="12%">Terakhir Aktivitas</th>
                        <th class="py-2 sticky-top" width="8%">Status</th>
                        <th class="py-2 sticky-top" width="6%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data yang ditampilkan</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-md-flex align-items-center p-3 border-top">
            <div class="me-md-auto text-md-left text-center mb-2 mb-md-0" id="pengaturanDaftarPengguna-paginationInfo"></div>
            <div class="btn-group btn-group-md" id="pengaturanDaftarPengguna-paginationControl"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="pengaturanDaftarPengguna-editor">
    <div class="modal-dialog modal-md">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Detail Pengguna</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="nama">Nama</label>
                            <input type="text" class="form-control" id="pengaturanDaftarPengguna-nama" name="nama" placeholder="Nama">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" id="pengaturanDaftarPengguna-email" name="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="level">Level</label>
                            <select class="form-select" id="pengaturanDaftarPengguna-level" name="level"></select>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="username">Username</label>
                            <input type="text" class="form-control" id="pengaturanDaftarPengguna-username" name="username" placeholder="Username">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label class="form-label d-block">Status</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="pengaturanDaftarPengguna-statusAktif" value="1">
                                <label class="form-check-label" for="pengaturanDaftarPengguna-statusAktif">Aktif</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="pengaturanDaftarPengguna-statusNonAktif" value="-1">
                                <label class="form-check-label" for="pengaturanDaftarPengguna-statusNonAktif">Non Aktif</label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div id="pengaturanDaftarPengguna-alertUpdate" class="px-2 d-none" role="alert">
                        <div class="alert alert-warning" role="alert">
                            <strong><i class="fa fa-fw fa-info-circle me-1"></i>Informasi | </strong>
                            Kosongkan kolom password jika tidak ingin mengubah password pengguna.
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" class="form-control" id="pengaturanDaftarPengguna-password" name="password" placeholder="Password" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="pengaturanDaftarPengguna-konfirmasiPassword">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="pengaturanDaftarPengguna-konfirmasiPassword" name="konfirmasiPassword" placeholder="Konfirmasi Password" autocomplete="new-password">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="idPengguna" value="">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
	var jsFileUrl   =   "<?=BASE_URL_ASSETS_JS?>menu/pengaturan/daftarPengguna.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>