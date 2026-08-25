<?php
    $menuName           =   $menuDetail['MENUNAME'] ?? '';
    $menuDescription    =   $menuDetail['DESCRIPTION'] ?? '';
?>
<div id="containerMenuUtilitasCekOngkosKirim">
    <h1 id="utilitasCekOngkosKirim-header" class="page-header d-flex flex-column flex-md-row align-items-md-center">
        <span class="mb-2 mb-md-0"><?=$menuName?> <small><?=$menuDescription?></small></span>
    </h1>
    <hr id="utilitasCekOngkosKirim-hr" class="mb-4">
    <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-12 mb-3 mb-lg-0">
            <form class="card d-flex flex-column" id="utilitasCekOngkosKirim-formFilter" autocomplete="off">
                <div class="card-header" id="utilitasCekOngkosKirim-cardFormFilterHeader">
                    <h6 class="my-1"><i class="fa fa-filter me-1"></i> Filter Ongkos Kirim</h6>
                </div>
                <div class="card-body overflow-y-auto" id="utilitasCekOngkosKirim-cardFormFilterBody">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label" for="utilitasCekOngkosKirim-detailWilayahAsal">Detail Wilayah Asal *</label>
                            <input type="text" class="form-control" id="utilitasCekOngkosKirim-detailWilayahAsal" name="detailWilayahAsal" placeholder="Kecamatan / Kota / Provinsi" readonly>
                            <input type="hidden" id="utilitasCekOngkosKirim-idProvinsiAsal" name="idProvinsiAsal">
                            <input type="hidden" id="utilitasCekOngkosKirim-idKotaKabupatenAsal" name="idKotaKabupatenAsal">
                            <input type="hidden" id="utilitasCekOngkosKirim-idKecamatanAsal" name="idKecamatanAsal">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label" for="utilitasCekOngkosKirim-detailWilayahTujuan">Detail Wilayah Tujuan *</label>
                            <input type="text" class="form-control" id="utilitasCekOngkosKirim-detailWilayahTujuan" name="detailWilayahTujuan" placeholder="Kecamatan / Kota / Provinsi" readonly>
                            <input type="hidden" id="utilitasCekOngkosKirim-idProvinsiTujuan" name="idProvinsiTujuan">
                            <input type="hidden" id="utilitasCekOngkosKirim-idKotaKabupatenTujuan" name="idKotaKabupatenTujuan">
                            <input type="hidden" id="utilitasCekOngkosKirim-idKecamatanTujuan" name="idKecamatanTujuan">
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label class="form-label" for="utilitasCekOngkosKirim-berat">Berat *</label>
                            <div class="input-group">
                                <input type="text" class="form-control text-end" id="utilitasCekOngkosKirim-berat" name="berat" placeholder="0" onkeypress="maskNumberInput(1, 1000, 'utilitasCekOngkosKirim-berat')">
                                <span class="input-group-text">Kg</span>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label class="form-label" for="utilitasCekOngkosKirim-nilaiBarang">Nilai Barang</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control text-end" id="utilitasCekOngkosKirim-nilaiBarang" name="nilaiBarang" placeholder="0" onkeypress="maskNumberInput(0, 100000000, 'utilitasCekOngkosKirim-nilaiBarang')">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label d-block">Asuransi</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="asuransi" id="utilitasCekOngkosKirim-asuransiYa" value="1">
                                <label class="form-check-label" for="utilitasCekOngkosKirim-asuransiYa">Pakai Asuransi</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="asuransi" id="utilitasCekOngkosKirim-asuransiTidak" value="0" checked>
                                <label class="form-check-label" for="utilitasCekOngkosKirim-asuransiTidak">Tidak Pakai Asuransi</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-3 mb-md-0">
                            <label class="form-label" for="utilitasCekOngkosKirim-panjang">Panjang</label>
                            <div class="input-group">
                                <input type="text" class="form-control text-end" id="utilitasCekOngkosKirim-panjang" name="panjang" placeholder="0" onkeypress="maskNumberInput(1, 1000, 'utilitasCekOngkosKirim-panjang')">
                                <span class="input-group-text">Cm</span>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-3 mb-md-0">
                            <label class="form-label" for="utilitasCekOngkosKirim-lebar">Lebar</label>
                            <div class="input-group">
                                <input type="text" class="form-control text-end" id="utilitasCekOngkosKirim-lebar" name="lebar" placeholder="0" onkeypress="maskNumberInput(1, 1000, 'utilitasCekOngkosKirim-lebar')">
                                <span class="input-group-text">Cm</span>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-0">
                            <label class="form-label" for="utilitasCekOngkosKirim-tinggi">Tinggi</label>
                            <div class="input-group">
                                <input type="text" class="form-control text-end" id="utilitasCekOngkosKirim-tinggi" name="tinggi" placeholder="0" onkeypress="maskNumberInput(1, 1000, 'utilitasCekOngkosKirim-tinggi')">
                                <span class="input-group-text">Cm</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer" id="utilitasCekOngkosKirim-cardFormFilterFooter">
                    <button class="btn btn-primary w-100 my-1" id="utilitasCekOngkosKirim-btnCekOngkosKirim">
                        <i class="fa fa-search me-1"></i> Cek Ongkos Kirim
                    </button>
                </div>
            </form>
        </div>
        <div class="col-xl-9 col-lg-8 col-md-12">
            <div class="card d-flex flex-column">
                <div class="card-header" id="utilitasCekOngkosKirim-cardDaftarRateHeader">
                    <h6 class="my-1"><i class="fa fa-list-ul me-1"></i> Daftar Tarif Kurir</h6>
                </div>
                <div class="card-body d-flex flex-column overflow-hidden" id="utilitasCekOngkosKirim-cardDaftarRateBody">
                    <div class="table-responsive table-sticky-header flex-fill">
                        <table id="utilitasCekOngkosKirim-tableRate" class="table table-hover mb-0 w-100" style="table-layout: fixed; word-wrap: break-word; word-break: break-word;">
                            <thead>
                                <tr class="table-dark">
                                    <th class="py-2 sticky-top" width="18%">Kurir</th>
                                    <th class="py-2 sticky-top" width="16%">Layanan</th>
                                    <th class="py-2 sticky-top" width="10%">Estimasi Tiba</th>
                                    <th class="py-2 sticky-top text-end" width="12%">Harga</th>
                                    <th class="py-2 sticky-top text-end" width="12%">Handling Fee</th>
                                    <th class="py-2 sticky-top text-end" width="10%">Asuransi</th>
                                    <th class="py-2 sticky-top text-end" width="12%">Total Bayar</th>
                                    <th class="py-2 sticky-top text-end" width="10%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="utilitasCekOngkosKirim-rowInfoKosong">
                                    <td colspan="8" class="text-center pt-5 border-bottom-0">
                                        <div class="d-flex flex-column align-items-center justify-content-center gap-2 text-center mb-0">
                                            <i class="fa fa-info-circle fa-3x opacity-70"></i>
                                            <span>Silakan lengkapi filter wilayah asal, wilayah tujuan, dan berat, lalu klik <strong>Cek Ongkos Kirim</strong> untuk menampilkan daftar tarif ongkos kirim.</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="utilitasCekOngkosKirim-modalWilayah">
    <div class="modal-dialog modal-md">
        <form class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Detail Wilayah</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label" for="utilitasCekOngkosKirim-optionProvinsi">Provinsi</label>
                    <select class="form-select" id="utilitasCekOngkosKirim-optionProvinsi" name="optionProvinsi"></select>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" for="utilitasCekOngkosKirim-optionKotaKabupaten">Kota/Kabupaten</label>
                    <select class="form-select" id="utilitasCekOngkosKirim-optionKotaKabupaten" name="optionKotaKabupaten"></select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="utilitasCekOngkosKirim-optionKecamatan">Kecamatan</label>
                    <select class="form-select" id="utilitasCekOngkosKirim-optionKecamatan" name="optionKecamatan"></select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Set Wilayah</button>
            </div>
        </form>
    </div>
</div>
<script>
    var jsFileUrl   =   "<?=BASE_URL_ASSETS_JS?>menu/utilitas/cekOngkosKirim.js?<?=date("YmdHis")?>";
    $.getScript(jsFileUrl);
</script>