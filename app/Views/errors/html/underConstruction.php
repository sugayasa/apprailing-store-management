<?php
    $menuName           =   $menuDetail['MENUNAME'];
    $menuDescription    =   $menuDetail['DESCRIPTION'];
?>

<div class="container-fluid p-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="py-5">
                        <i class="fa fa-wrench fa-4x text-warning mb-4"></i>
                        <h4 class="text-muted mb-5">Sedang Dalam Pembangunan</h4>
                        <h5 class="text-muted mb-2"><?=$menuName?></h5>
                        <p class="text-muted mb-5"><?=$menuDescription?></p>
                        <p class="text-muted mb-0 fw-bold">Menu ini belum tersedia. Silakan coba lagi nanti.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
