<div id="containerMenuDashboard">
    <h1 class="page-header">Beranda <small>Daftar statistik - histori penjualan, stok barang, dan perubahan harga</small></h1>
    <hr class="mb-4">
    <div class="row">
        <div class="col-xl-6 mb-3">
            <div class="card h-100" id="grafikPenjualan">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="flex-grow-1">
                            <h5 class="mb-1">Grafik Penjualan</h5>
                            <div class="fs-13px">Statistik penjualan bulan berjalan dalam bentuk grafik</div>
                        </div>
                    </div>
                    <canvas id="grafikPenjualanCanvas"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="row">
                <div class="col-sm-6 mb-3 d-flex flex-column">
                    <div class="card mb-3 flex-1" id="statistikPerMerk">
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">Statistik Per Merk</h5>
                                    <div>Penjualan marketplace per merk pada bulan berjalan</div>
                                </div>
                            </div>
                            <div id="statistikPerMerkContent"></div>
                        </div>
                    </div>
                    <div class="card flex-1" id="statistikPerMarketplace">
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">Statistik Per Marketplace</h5>
                                    <div>Penjualan per marketplace pada bulan berjalan</div>
                                </div>
                            </div>
                            <div id="statistikPerMarketplaceContent"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 mb-3" id="statistikPerRegional">
                    <div class="card h-100">	
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">Statistik Regional</h5>
                                    <div class="fs-13px">Penjualan marketplace per regional pada bulan berjalan</div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h3 class="mb-1" id="totalSalesOrderNominal">-</h3>
                            </div>
                            <div class="progress mb-4" style="height: 10px;" id="progressBarStatistikRegional">
                                <div class="progress-bar bg-gray" style="width: 100%"></div>
                            </div>
                            <div class="fs-15px" id="statistikPerRegionalContent"></div>
                            <div class="fs-12px text-end">
                                <span class="fs-10px">* Sales order yang dihitung hanya yang tidak dibatalkan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 mb-3" id="bestSellerBarang">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h5 class="mb-1">Penjualan Terbaik</h5>
                            <div class="fs-13px">Daftar Barang Paling Banyak terjual pada periode bulan ini</div>
                        </div>
                    </div>
                    <div id="bestSellerBarangContent"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-3" id="historiSalesOrder">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-grow-1">
                            <h5 class="mb-1">Transaksi / Sales Order</h5>
                            <div class="fs-13px">Riwayat transaksi / sales order terbaru</div>
                        </div>
                    </div>
                    <div class="table-responsive mb-n2">
                        <table class="table table-borderless mb-0">
                            <thead>
                                <tr class="text-body">
                                    <th>Detail Sales Order</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end pe-0">Nominal</th>
                                </tr>
                            </thead>
                            <tbody id="historiSalesOrderContent"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?=BASE_URL_ASSETS_JS?>chart.umd.js?<?=date('YmdHis')?>"></script>
<script>
	var jsFileUrl   =   "<?=BASE_URL_ASSETS_JS?>menu/dashboard.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>