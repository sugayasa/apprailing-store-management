<div class="login">
	<div class="login-content">
		<form id="login-form" method="POST">
			<div class="text-center mb-4">
				<a href="index.html" class="auth-logo mb-5 d-block"><img src="<?=BASE_URL_ASSETS_IMG?>logo-single-2025.png" width="80px"></a>
			</div>
			<h4 class="text-center"><?=APP_NAME?></h4>
			<div class="text-muted text-center mb-4">Masukkan username dan password Anda</div>
			<div class="card p-4 mb-4">
				<div class="mb-3" id="container-warning-element">
					<div class="alert alert-warning alert-dismissible fade show" role="alert" id="warning-element">
						<p class="mb-0"></p>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				</div>
				<div class="mb-3">
					<label class="form-label">Username</label>
					<div class="input-group mb-3 bg-light-subtle rounded-3">
						<span class="input-group-text text-muted"><i class="far fa-user"></i></span>
						<input type="text" class="form-control form-control-lg fs-15px" id="username" name="username" placeholder="Masukkan Username" aria-label="Masukkan Username">
					</div>
				</div>
				<div class="mb-3">
					<label class="form-label">Password</label>
					<div class="input-group mb-3 bg-light-subtle rounded-3">
						<span class="input-group-text text-muted"><i class="fas fa-lock"></i></span>
						<input type="password" class="form-control form-control-lg fs-15px" id="password" name="password" placeholder="Masukkan Password" aria-label="Masukkan Password">
						<span class="btn btn-default far fa-eye" id="togglePassword" style="padding-top: 12px;"></span>
					</div>
				</div>
				<div class="mb-3">
					<label class="form-label">Captcha</label>
					<div class="input-group mb-3 bg-light-subtle rounded-3">
						<span class="input-group-text text-muted"><i class="fas fa-keyboard"></i></span>
						<input type="text" class="form-control form-control-lg fs-15px" id="captcha" name="captcha" placeholder="Ketik Ulang Captcha" aria-label="Ketik Ulang Captcha" aria-describedby="basic-addon5">
						<img id="captchaImage" class="mx-auto" style="max-width: 150px; max-height: 46px; border: var(--bs-border-width) solid var(--bs-border-color)">
						<button type="button" class="btn btn-primary waves-effect waves-light" id="btnRefreshCaptcha"><i class="fas fa-refresh"></i></button>
					</div>
				</div>
				<div class="mb-4"></div>
				<button type="submit" class="btn btn-theme btn-lg d-block w-100 fw-500">Masuk</button>
			</div>
			<div class="mt-3 text-center">
				<a class="fw-bold text-decoration-none" id="clearCacheReloadLink" href="#">Bersihkan Cache & Muat Ulang</a>
			</div>
		</form>
		<div class="mt-5 text-center">
			<p>© 2025 - <?=APP_COMPANY_NAME?></p>
		</div>
	</div>
</div>
<a href="#" data-click="scroll-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>
<script src="<?=BASE_URL_ASSETS_JS?>login.js?<?=date('YmdHis')?>"></script>