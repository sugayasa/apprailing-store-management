<?php
    $menuName           =   $menuDetail['MENUNAME'] ?? '';
    $menuDescription    =   $menuDetail['DESCRIPTION'] ?? '';
?>

<div id="containerMenuCustomerDaftarCustomer" class="pos">
    <h1 id="customerDaftarCustomer-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
        <button id="btnKembali" type="button" class="btn btn-warning ms-md-auto mt-md-0 mt-2 d-none">
            <i class="fa fa-arrow-left me-1"></i> Kembali
        </button>
    </h1>
    <hr id="customerDaftarCustomer-hr" class="mb-4">
    <div id="customerDaftarCustomer-leftContainer" class="show">
        <div id="customerDaftarCustomer-cardContent" class="card d-flex flex-column">
            <div class="p-3 mb-3 border-bottom">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Ketik sesuatu dan tekan ENTER untuk mencari.." id="customerDaftarCustomer-searchKeyword">
                </div>
            </div>
            <div class="table-responsive table-sticky-header px-3 flex-fill">
                <table class="table table-hover mb-0 w-100" style="table-layout: fixed; word-wrap: break-word; word-break: break-word;">
                    <thead>
                        <tr class="table-dark">
                            <th class="py-2 sticky-top sticky-col-left" width="6%"></th>
                            <th class="py-2 sticky-top sticky-col-left" width="16%">Nama</th>
                            <th class="py-2 sticky-top" width="8%">Level Loyalti</th>
                            <th class="py-2 sticky-top" width="8%">Tanggal Daftar</th>
                            <th class="py-2 sticky-top" width="8%">Tanggal Lahir</th>
                            <th class="py-2 sticky-top" width="16%">Email</th>
                            <th class="py-2 sticky-top" width="12%">No. Telepon</th>
                            <th class="py-2 sticky-top">Kode Customer</th>
                            <th class="py-2 sticky-top" width="8%">Status</th>
                            <th class="py-2 sticky-top" width="8%">Developer</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="d-md-flex align-items-center p-3 border-top">
                <div class="me-md-auto text-md-left text-center mb-2 mb-md-0" id="customerDaftarCustomer-paginationInfo"></div>
                <div class="btn-group btn-group-md" id="customerDaftarCustomer-paginationControl"></div>
            </div>
        </div>
    </div>
    <div id="customerDaftarCustomer-rightContainer" class="d-none">
        <div class="card mb-3" id="detailCustomer-cardDetailCustomer">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-12 mb-lg-0 mb-3 text-center">
                        <img id="detailCustomer-avatarImage" src="" alt="" width="150" class="rounded-circle">
                    </div>
                    <div class="col-lg-5 col-md-6 col-sm-12">
                        <table class="table table-borderless table-sm m-0">
                            <tbody>
                                <tr>
                                    <td class="fw-bold w-130px">Nama</td>
                                    <td id="detailCustomer-nama" class="text-gray-500"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold w-130px">Level Loyalti</td>
                                    <td id="detailCustomer-loyaltiTier" class="text-gray-500"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold w-130px">Tanggal Lahir</td>
                                    <td id="detailCustomer-tanggalLahir" class="text-gray-500"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold w-130px">Tanggal Daftar</td>
                                    <td id="detailCustomer-tanggalDaftar" class="text-gray-500"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold w-130px">Kode Customer</td>
                                    <td id="detailCustomer-kodeCustomer" class="text-gray-500"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <table class="table table-borderless table-sm m-0">
                            <tbody>
                                <tr>
                                    <td class="fw-bold w-130px">Email</td>
                                    <td id="detailCustomer-email" class="text-gray-500"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold w-130px">No. Telpon</td>
                                    <td id="detailCustomer-noTelpon" class="text-gray-500"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold w-130px">Status</td>
                                    <td id="detailCustomer-status" class="text-gray-500"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold w-130px">Developer</td>
                                    <td id="detailCustomer-developer" class="text-gray-500"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="detailCustomer-tabContentHeader">
                <ul class="nav nav-pills mb-0" role="tablist">
                    <li class="nav-item detailCustomer-tabPills" role="presentation">
                        <a class="nav-link active" id="pills-data-alamat-tab" data-bs-toggle="pill" href="#pills-data-alamat" aria-selected="true" role="tab">Alamat</a>
                    </li>
                    <li class="nav-item detailCustomer-tabPills" role="presentation">
                        <a class="nav-link" id="pills-data-transaksi-tab" data-bs-toggle="pill" href="#pills-data-transaksi" aria-selected="false" role="tab">Transaksi</a>
                    </li>
                    <li class="nav-item detailCustomer-tabPills" role="presentation">
                        <a class="nav-link" id="pills-data-feed-tab" data-bs-toggle="pill" href="#pills-data-feed" aria-selected="false" role="tab">Feed</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="detailCustomer-tabContentBody">
                    <div class="tab-pane fade d-flex flex-column show active" id="pills-data-alamat" role="tabpanel">
                        <div class="table-responsive table-sticky-header flex-fill">
                            <table class="table table-hover mb-0 w-100" style="table-layout: fixed; word-wrap: break-word; word-break: break-word;">
                                <thead>
                                    <tr class="table-dark">
                                        <th class="py-2 sticky-top sticky-col-left" width="10%">Tag Alamat</th>
                                        <th class="py-2 sticky-top" width="20%">Detail Penerima</th>
                                        <th class="py-2 sticky-top">Detail Alamat</th>
                                        <th class="py-2 sticky-top" width="20%">Detail Alamat Lanjutan</th>
                                        <th class="py-2 sticky-top" width="6%">Utama</th>
                                        <th class="py-2 sticky-top" width="8%">Status</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="d-md-flex align-items-center p-0 pt-3 border-top">
                            <div class="me-md-auto text-md-left text-center mb-2 mb-md-0" id="detailCustomer-alamat-paginationInfo"></div>
                            <div class="btn-group btn-group-md" id="detailCustomer-alamat-paginationControl"></div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-data-transaksi" role="tabpanel">
                        <div class="table-responsive table-sticky-header flex-fill">
                            <table class="table table-hover mb-0 w-100" style="table-layout: fixed; word-wrap: break-word; word-break: break-word;">
                                <thead>
                                    <tr class="table-dark">
                                        <th class="py-2 sticky-top sticky-col-left" width="18%">Detail Transaksi</th>
                                        <th class="py-2 sticky-top" width="15%">Detail Pengiriman</th>
                                        <th class="py-2 sticky-top">Detail Alamat</th>
                                        <th class="py-2 sticky-top" width="15%">Catatan</th>
                                        <th class="py-2 sticky-top" width="14%">Detail Nominal</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="d-md-flex align-items-center p-0 pt-3 border-top">
                            <div class="me-md-auto text-md-left text-center mb-2 mb-md-0" id="detailCustomer-transaksi-paginationInfo"></div>
                            <div class="btn-group btn-group-md" id="detailCustomer-transaksi-paginationControl"></div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-data-feed" role="tabpanel">
                        <div class="table-responsive table-sticky-header flex-fill">
                            <table class="table table-hover mb-0 w-100" style="table-layout: fixed; word-wrap: break-word; word-break: break-word;">
                                <thead>
                                    <tr class="table-dark">
                                        <th class="py-2 sticky-top sticky-col-left" width="18%">Judul</th>
                                        <th class="py-2 sticky-top">Deskripsi</th>
                                        <th class="py-2 sticky-top" width="15%">URL</th>
                                        <th class="py-2 sticky-top" width="6%" style="text-align: center;"><i class="fa fa-fw fa-heart"></i></th>
                                        <th class="py-2 sticky-top" width="6%" style="text-align: center;"><i class="fa fa-fw fa-bookmark"></i></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="d-md-flex align-items-center p-0 pt-3 border-top">
                            <div class="me-md-auto text-md-left text-center mb-2 mb-md-0" id="detailCustomer-feed-paginationInfo"></div>
                            <div class="btn-group btn-group-md" id="detailCustomer-feed-paginationControl"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	var jsFileUrl   =   "<?=BASE_URL_ASSETS_JS?>menu/customer/customer/daftarCustomer.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>