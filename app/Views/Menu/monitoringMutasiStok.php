<div id="containerMenuStokBarang" class="pos">
    <h1 class="page-header">Monitoring Mutasi Stok <small>Monitoring mutasi stok barang per gudang regional</small></h1>
    <hr class="mb-4">
    <div class="pos-content">
        <div class="pos-content-container p-0">
            <div class="row gx-3">
                <?php foreach($arrRegional AS $dataRegional) { ?>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 pb-3">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0"><?= $dataRegional['NAMAKOTA'] ?></h5>
                        </div>
                        <div class="card-body overflow-auto cardRegionalMonitoringMutasi" data-idRegional="<?= $dataRegional['IDKOTA'] ?>" style="height: 650px; max-height: 650px;"></div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
	var jsFileUrl   =   "<?=BASE_URL_ASSETS_JS?>menu/monitoringMutasiStok.js?<?=date("YmdHis")?>";
	$.getScript(jsFileUrl);
</script>