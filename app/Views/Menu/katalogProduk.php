<div id="containerMenuKatalogProduk" class="pos">
    <h1 class="page-header">Katalog Produk <small>Daftar produk yang tersedia, harga dan stok setiap regional</small></h1>
    <hr class="mb-4">
    <div class="card mb-3">
        <div class="card-body pb-2">
            <div class="row" id="katalogProdukFilter">
                <div class="col-xl-2 col-lg-3 col-md-3 col-sm-6">
                    <div class="form-group mb-3">
                        <label class="form-label" for="filterKatalogProduk-optionMerk">Merk</label>
                        <select class="form-select" id="filterKatalogProduk-optionMerk" name="filterKatalogProduk-optionMerk" option-all="Semua Merk"></select>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6">
                    <div class="form-group mb-3">
                        <label class="form-label" for="filterKatalogProduk-optionKategori">Kategori</label>
                        <select class="form-select" id="filterKatalogProduk-optionKategori" name="filterKatalogProduk-optionKategori" option-all="Semua Kategori"></select>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                        <label class="form-label" for="filterKatalogProduk-keywordCariBarang">Cari Barang</label>
                        <input type="text" class="form-control" id="filterKatalogProduk-keywordCariBarang" name="filterKatalogProduk-keywordCariBarang" placeholder="Ketik dan tekan ENTER untuk mencari...">
                    </div>
                </div>
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-8">
                    <div class="form-group mb-3">
                        <label class="form-label" for="filterKatalogProduk-optionUrutBerdasar">Urut Berdasarkan</label>
                        <select class="form-select" id="filterKatalogProduk-optionUrutBerdasar" name="filterKatalogProduk-optionUrutBerdasar">
                            <option value="1">Abjad Merk - Kategori - Kode</option>
                            <option value="2">Harga Jual Retail</option>
                        </select>
                    </div>
                </div>
                <div class="col-xl-1 col-lg-2 col-md-3 col-sm-4">
                    <div class="form-group mb-3">
                        <label class="form-label" for="filterKatalogProduk-optionJenisUrutan">Jenis Urutan</label>
                        <select class="form-select" id="filterKatalogProduk-optionJenisUrutan" name="filterKatalogProduk-optionJenisUrutan">
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
            <div class="row gx-3" id="katalogProdukContent"></div>
        </div>
    </div>
</div>
<script>
	var jsFileUrl   =   "<?=BASE_URL_ASSETS_JS?>menu/katalogProduk.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>