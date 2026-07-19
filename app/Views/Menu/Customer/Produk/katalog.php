<?php
    $menuName           =   $menuDetail['MENUNAME'];
    $menuDescription    =   $menuDetail['DESCRIPTION'];
?>
<div id="containerMenuCustomerProdukKatalog" class="pos">
    <h1 id="customerProdukKatalog-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
        <button id="btnAddProduk" type="button" class="btn btn-primary ms-auto">
            <i class="fa fa-plus me-1"></i> Tambah Produk
        </button>
        <button id="btnBatalEditor" type="button" class="btn btn-warning ms-md-auto mt-md-0 mt-2 d-none">
            <i class="fa fa-arrow-left me-1"></i> Kembali
        </button>
    </h1>
    <hr id="customerProdukKatalog-hr" class="mb-4">
    <div id="customerProdukKatalog-leftContainer" class="show">
        <div class="card mb-3" id="customerProdukKatalog-filter">
            <div class="card-body pb-2">
                <div class="row">
                    <div class="col-xl-2 col-lg-3 col-md-3 col-sm-6">
                        <div class="form-group mb-3">
                            <label class="form-label" for="customerProdukKatalog-optionMerk">Merk</label>
                            <select class="form-select" id="customerProdukKatalog-optionMerk" name="customerProdukKatalog-optionMerk" option-all="Semua Merk"></select>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6">
                        <div class="form-group mb-3">
                            <label class="form-label" for="customerProdukKatalog-optionKategori">Kategori</label>
                            <select class="form-select" id="customerProdukKatalog-optionKategori" name="customerProdukKatalog-optionKategori" option-all="Semua Kategori"></select>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="customerProdukKatalog-keywordCariProduk">Cari Produk</label>
                            <input type="text" class="form-control" id="customerProdukKatalog-keywordCariProduk" name="customerProdukKatalog-keywordCariProduk" placeholder="Ketik dan tekan ENTER untuk mencari...">
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-8">
                        <div class="form-group mb-3">
                            <label class="form-label" for="customerProdukKatalog-optionUrutBerdasar">Urut Berdasarkan</label>
                            <select class="form-select" id="customerProdukKatalog-optionUrutBerdasar" name="customerProdukKatalog-optionUrutBerdasar">
                                <option value="1">Abjad Merk - Kategori - Kode</option>
                                <option value="2">Harga Jual</option>
                                <option value="3">Total Terjual</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-1 col-lg-2 col-md-3 col-sm-4">
                        <div class="form-group mb-3">
                            <label class="form-label" for="customerProdukKatalog-optionJenisUrutan">Jenis Urutan</label>
                            <select class="form-select" id="customerProdukKatalog-optionJenisUrutan" name="customerProdukKatalog-optionJenisUrutan">
                                <option value="ASC">Menaik</option>
                                <option value="DESC">Menurun</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pos-content">
            <div class="pos-content-container p-0">
                <div class="row gx-3 overflow-y-auto" id="customerProdukKatalog-content"></div>
            </div>
        </div>
    </div>
    <div id="customerProdukKatalog-rightContainer" class="d-none">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Foto</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div id="customerProdukKatalog-fotoProdukContainer" class="mb-2">
                                <div id="fotoProdukDefault" class="bg-light rounded d-flex align-items-center justify-content-center mx-auto mb-2" style="max-width:300px;height:150px;">
                                    <i class="fa fa-image fa-4x text-secondary"></i>
                                </div>
                            </div>
                            <span id="uploadFotoProduk" class="ms-auto">Upload Foto Produk</span>
                            <input type="hidden" name="arrFotoProdukFileName" value="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Detail Data</h6>
                    </div>
                    <div class="card-body row">
                        <div class="form-group col-12 mb-3">
                            <label class="form-label" for="namaProduk">Nama Produk</label>
                            <input type="text" class="form-control" name="namaProduk" id="namaProduk" placeholder="Nama Produk">
                        </div>
                        <div class="form-group col-xl-4 col-md-6 col-sm-12 mb-3">
                            <label class="form-label" for="optionMerk">Merk</label>
                            <select class="form-select" id="optionMerk" name="optionMerk"></select>
                        </div>
                        <div class="form-group col-xl-8 col-md-6 col-sm-12 mb-3">
                            <label class="form-label" for="optionKategori">Kategori</label>
                            <select class="form-select" id="optionKategori" name="optionKategori"></select>
                        </div>
                        <div class="form-group col-12 mb-3">
                            <label class="form-label" for="produkPadanan">Produk Padanan</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="produkPadanan" id="produkPadanan" placeholder="Produk Padanan (sesuai dengan sistem utama)" readonly>
                                <span class="input-group-text" id="icon-produkPadanan"><i class="fa fa-external-link-square"></i></span>
                            </div>
                        </div>
                        <div class="form-group col-xl-6 col-sm-12 mb-3">
                            <label class="form-label" for="hargaJual">Harga Jual</label>
                            <input type="text" class="form-control text-end" name="hargaJual" id="hargaJual"  placeholder="0" onkeypress="maskNumberInput(0, 999999999, 'hargaJual')">
                        </div>
                        <div class="form-group col-xl-6 col-sm-12 mb-3">
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
                    <div class="card-footer d-flex">
                        <input type="hidden" name="idProduk" value="">
                        <input type="hidden" name="idProdukPadanan" value="">
                        <button type="button" class="btn btn-primary ms-auto" id="btnSimpanProduk">
                            <i class="fa fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </div>    
            </div>    
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title">Deskripsi</h6>
                    </div>
                    <div class="card-body">
                        <textarea name="deskripsi" class="summernote" rows="10"></textarea>
                    </div>
                </div>    
            </div>    
        </div>    
    </div>
</div>
 <div class="modal fade" id="customerProdukKatalog-modalBarangPadanan">
    <div class="modal-dialog modal-lg">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Barang Padanan Produk</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="p-0 d-flex flex-column overflow-hidden" style="height: 500px;">
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" name="modalBarangPadanan-namaProduk" id="modalBarangPadanan-namaProduk" placeholder="Nama Produk">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                    <div class="table-responsive table-sticky-header pb-1 flex-fill">
                        <table id="modalBarangPadanan-table" class="table table-hover mb-0 w-100" style="table-layout: fixed; word-wrap: break-word; word-break: break-word;">
                            <thead>
                                <tr class="table-dark">
                                    <th class="py-2 sticky-top" width="18%">Merk</th>
                                    <th class="py-2 sticky-top">Nama / Kode Barang</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2" class="text-center py-4">Tidak ada data yang ditemukan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="alert alert-info p-3 mb-2 w-100">
                    <strong>Barang Dipilih :</strong> <span class="ms-1" id="modalBarangPadanan-namaProdukTerpilih"></span>
                </div>
                <span class="text-muted me-auto">*Klik pada baris data untuk memilih barang padanan</span>
                <input type="hidden" name="modalBarangPadanan-idProdukPadanan" value="">
                <button type="button" name="modalBarangPadanan-btnSetBarang" class="btn btn-primary" data-bs-dismiss="modal">Set Barang Padanan</button>
            </div>
        </form>
    </div>
</div>
<script>
	var imageProdukBaseUrl  =   "<?=BASE_URL_ASSETS_CUSTOMER_PRODUK?>",
        imageProdukDefault  =   "<?=$defaultImage?>",
        jsFileUrl           =   "<?=BASE_URL_ASSETS_JS?>menu/customer/produk/katalog.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>