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
    </h1>
    <hr id="customerProdukKatalog-hr" class="mb-4">
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
                            <option value="2">Harga Jual Retail</option>
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
<script>
	var imageProdukBaseUrl  =   "<?=BASE_URL_ASSETS_CUSTOMER_PRODUK?>",
        imageProdukDefault  =   "<?=$defaultImage?>",
        jsFileUrl           =   "<?=BASE_URL_ASSETS_JS?>menu/customer/produk/katalog.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>