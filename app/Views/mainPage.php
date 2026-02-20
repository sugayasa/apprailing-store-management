<script>
	const interval_id = window.setInterval(function(){}, Number.MAX_SAFE_INTEGER);
	for (let i = 1; i < interval_id; i++) {
	window.clearInterval(i);
	}
	if(!window.jQuery){
		window.location = window.location.origin;
	}
	if(window.location.href != '<?=BASE_URL?>') window.history.replaceState({Title: '<?=APP_NAME?>', Url: '<?=BASE_URL?>'}, '<?=APP_NAME?>', '<?=BASE_URL?>');
</script>
<div id="app" class="app app-with-top-nav app-without-sidebar">
	<div id="header" class="app-header">
		<div class="mobile-toggler">
			<button type="button" class="menu-toggler" data-toggle="top-nav-mobile">
				<span class="bar"></span>
				<span class="bar"></span>
			</button>
		</div>
		<div class="brand">
			<div class="desktop-toggler">
				<button type="button" class="menu-toggler" >
					<span class="bar"></span>
					<span class="bar"></span>
				</button>
			</div>
			
			<a href="index.html" class="brand-logo">
				<img src="<?=BASE_URL_ASSETS_IMG?>logo-wide.png" class="invert-dark" alt="" height="20">
			</a>
		</div>
		<div class="menu">
			<div class="menu-search mx-auto text-center"></div>
			<div class="menu-item dropdown">
				<a href="#" data-bs-toggle="dropdown" data-display="static" class="menu-link">
					<div class="menu-icon"><i class="fa fa-bell nav-icon"></i></div>
					<div class="menu-label">3</div>
				</a>
				<div class="dropdown-menu dropdown-menu-end dropdown-notification">
					<h6 class="dropdown-header text-body-emphasis mb-1">Notifications</h6>
					<a href="#" class="dropdown-notification-item">
						<div class="dropdown-notification-icon">
							<i class="fa fa-receipt fa-lg fa-fw text-success"></i>
						</div>
						<div class="dropdown-notification-info">
							<div class="title">Your store has a new order for 2 items totaling $1,299.00</div>
							<div class="time">just now</div>
						</div>
						<div class="dropdown-notification-arrow">
							<i class="fa fa-chevron-right"></i>
						</div>
					</a>
					<a href="#" class="dropdown-notification-item">
						<div class="dropdown-notification-icon">
							<i class="far fa-user-circle fa-lg fa-fw text-muted"></i>
						</div>
						<div class="dropdown-notification-info">
							<div class="title">3 new customer account is created</div>
							<div class="time">2 minutes ago</div>
						</div>
						<div class="dropdown-notification-arrow">
							<i class="fa fa-chevron-right"></i>
						</div>
					</a>
					<div class="p-2 text-center mb-n1">
						<a href="#" class="text-body-emphasis text-opacity-50 text-decoration-none">See all</a>
					</div>
				</div>
			</div>
			<div class="menu-item dropdown">
				<a href="#" data-bs-toggle="dropdown" data-display="static" class="menu-link">
					<div class="menu-img">
						<img src="<?=BASE_URL_ASSETS_IMG?>user.png" alt="" class="ms-100 mh-100 rounded-circle">
					</div>
					<div class="menu-text" id="userFullName"><?=$userAdminData['name']?></div>
				</a>
				<div class="dropdown-menu dropdown-menu-end me-lg-3">
					<a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#modal-userProfile" >Pengaturan Akun <i class="fa fa-user-circle fa-fw ms-auto text-body text-opacity-50"></i></a>
					<a class="dropdown-item d-flex align-items-center" href="#" onclick="clearAppData()">Bersihkan Cache <i class="fa fa-eraser fa-fw ms-auto text-body text-opacity-50"></i></a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item d-flex align-items-center linkLogout" href="#">Keluar <i class="fa fa-sign-out fa-fw ms-auto text-body text-opacity-50"></i></a>
				</div>
			</div>
		</div>
	</div>
	<div data-bs-theme="dark">
		<div id="top-nav" class="app-top-nav">
			<div class="menu">
				<div class="menu-item active">
					<a href="#" class="menu-app-item menu-link" data-alias="DASH" data-url="dashboard">
						<span class="menu-icon"><i class="fa fa-home"></i></span><span class="menu-text">Beranda</span>
					</a>
				</div>
				<?=$menuElement?>
				<div class="menu-item menu-control menu-control-start">
					<a href="javascript:;" class="menu-link" data-toggle="top-nav-prev"><i class="fa fa-chevron-left"></i></a>
				</div>
				<div class="menu-item menu-control menu-control-end">
					<a href="javascript:;" class="menu-link" data-toggle="top-nav-next"><i class="fa fa-chevron-right"></i></a>
				</div>
			</div>
		</div>
	</div>
	
	<div id="content-container" class="app-content"></div>
	<a href="#" data-click="scroll-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>
</div>
<div class="modal fade" id="modal-userProfile" aria-labelledby="Pengaturan Akun" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<form class="modal-content form-horizontal" id="form-userProfile">
			<div class="modal-header">
				<h4 class="modal-title" id="editor-userProfile">Pengaturan Akun</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-7 col-sm-12 mb-4">
						<label for="userProfile-name" class="form-label">Nama</label>
						<input type="text" class="form-control" id="userProfile-name" name="userProfile-name" placeholder="Nama" autocomplete="off" required>
					</div>
					<div class="col-lg-5 col-sm-12 mb-4">
						<label for="userProfile-username" class="form-label">Username</label>
						<div class="input-group">
							<span class="input-group-text" id="userProfile-prefixUsername"><i class="fa fa-at" aria-hidden="true"></i></span>
							<input type="text" class="form-control" id="userProfile-username"" name="userProfile-username" placeholder="Username" autocomplete="off" aria-label="Username" aria-describedby="userProfile-prefixUsername" required>
						</div>
					</div><br/>
					<div class="col-12">
						<div class="card mb-4">
                            <div class="card-header fw-bold">Isi form di bawah ini untuk mengganti password Anda</div>
						</div>
					</div>
					<div class="col-12 mb-4">
						<label for="userProfile-password" class="form-label">Password Lama</label>
						<div class="input-group">
							<input type="password" class="form-control" id="userProfile-oldPassword" name="userProfile-oldPassword" autocomplete="new-password" placeholder="Password Lama">
							<button type="button" class="btn btn-secondary inputPassword-toggleVisibility"><i class="far fa-eye"></i></button>
						</div>
					</div>
					<div class="col-lg-6 col-sm-12 mb-4">
						<label for="userProfile-newPassword" class="form-label">Password Baru</label>
						<div class="input-group">
							<input type="password" class="form-control" id="userProfile-newPassword" name="userProfile-newPassword" autocomplete="new-password" placeholder="Password Baru">
							<button type="button" class="btn btn-secondary inputPassword-toggleVisibility"><i class="far fa-eye"></i></button>
						</div>
					</div>
					<div class="col-lg-6 col-sm-12 mb-4">
						<label for="userProfile-repeatNewPassword" class="form-label">Ulangi Password Baru</label>
						<div class="input-group">
							<input type="password" class="form-control" id="userProfile-repeatNewPassword" name="userProfile-repeatNewPassword" autocomplete="new-password" placeholder="Ulangi Password Baru">
							<button type="button" class="btn btn-secondary inputPassword-toggleVisibility"><i class="far fa-eye"></i></button>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary" id="saveSetting">Simpan</button>
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
			</div>
		</form>
	</div>
</div>
<div class="modal fade" id="modalWarning" aria-labelledby="Warning-Information" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalWarningTitle">Informasi</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="modalWarningBody">-</div>
			<div class="modal-footer">
				<button class="btn btn-primary" id="modalWarningBtnOK" data-bs-dismiss="modal">OK</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-confirm-action" aria-labelledby="Konfirmasi" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="modal-confirm-title">Konfirmasi</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="modal-confirm-body"></div>
           <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="confirmBtn" data-idData="" data-function="">Lanjutkan</button>
           </div>
        </div>
    </div>
</div>
<div class="modal loader-modal" id="window-loader" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-transparent border-0">
			<div class="modal-body">
				<div class="d-flex justify-content-center">
					<div class="spinner-border text-success">
						<span class="visually-hidden">Memuat...</span>
					</div>
				</div><br/>
				<div class="row">
					<div class="col-12 text-center">
						<span>Memuat, harap tunggu..</span>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
<input type="hidden" id="lastMenuAlias" name="lastMenuAlias" value="">
<script>
	localStorage.setItem('lastApplicationLoadTime', '<?=gmdate("YmdHis")?>');
	localStorage.setItem('allowNotifList', '<?=json_encode($allowNotifList)?>');
	localStorage.setItem('appVisibility', true);
	var baseURL				=	'<?=BASE_URL?>',
		baseURLAssetsSound	=	'<?=BASE_URL_ASSETS_SOUND?>',
		loaderElem			=	"<center class='mt-5 fs-13px' id='loaderElem'>"+
								"	<img src='<?=BASE_URL_ASSETS_IMG?>loader_content.gif' class='h-30px'/><br/><br/>"+
								"	Memuat konten..."+
								"</center>";
		
	$.ajaxSetup({ cache: true });

	function getAllFunctionName() {
		var allFunctionName = [];
		for (var i in window) {
			if ((typeof window[i]).toString() == "function") {
				allFunctionName.push(window[i].name);
			}
		}

		return allFunctionName;
	}
	
	function clearAppData(showWarning = true){
		var localStorageKeys	=	Object.keys(localStorage),
			localStorageIdx		=	localStorageKeys.length,
			allFunctionName		=	getAllFunctionName();
		for(var i=0; i<localStorageIdx; i++){
			var keyName			=	localStorageKeys[i];
			if(keyName.substring(0, 5) == "form_"){
				localStorage.removeItem(keyName);
			}
		}

		for(var i=0; i<allFunctionName.length; i++){
			var functionName	=	allFunctionName[i];
			if(functionName.slice(-4) === "Func"){
				window[functionName]	=	null;
			}
		}

		if(showWarning){
			$("#modalWarning").on("show.bs.modal", function () {
				$("#modalWarningBody").html("Cache aplikasi telah dibersihkan.");
			});
			$("#modalWarning").modal("show");
		}
	}

	clearAppData(false);
</script>
<script>
	var intervalId;
	var arrClassColor	=	['info', 'warning', 'success', 'light', 'primary', 'secondary', 'danger', 'dark'];
	var timezoneOffset	=	moment.tz.guess(),
		dateToday		=	moment().format('DD-MM-YYYY');
		url				=	"<?=BASE_URL_ASSETS_JS?>app.js?<?=date('YmdHis')?>";
	$.getScript(url);
</script>