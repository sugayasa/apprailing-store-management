<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html;">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="<?=APP_NAME_FORMAL?>" />
        <meta name="author" content="<?=APP_COMPANY_NAME?>" />
        <title><?=APP_NAME?></title>

        <link rel="icon" href="<?=BASE_URL_ASSETS_IMG?>logo-single-2025.ico" type="image/x-icon"/>
        <link rel="stylesheet" href="<?=BASE_URL_ASSETS_CSS?>app.min.css?<?=date('YmdHis')?>" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="<?=BASE_URL_ASSETS_CSS?>vendor.min.css?<?=date('YmdHis')?>" rel="stylesheet" type="text/css">
	</head>
    <body id="mainbody">
        <div id="app" class="app app-full-height app-without-header">
			<div class="login_wrapper mt-0">
				<section class="login_content text-center pt-5" id="center_content">
					<h3><?=APP_NAME?></h3>
					<img src="<?=BASE_URL_ASSETS_IMG?>loader.gif"/>
					<p id="loadtext">Checking session...</p>
				</section>
			</div>
		</div>
	</body>
	<script>
		window.history.replaceState(null, "", "<?=BASE_URL?>");
	</script>
	<script src="<?=BASE_URL_ASSETS_JS?>moment.min.js?<?=date('YmdHis')?>"></script>
	<script src="https://momentjs.com/downloads/moment-timezone-with-data.js"></script>
	<script src="<?=BASE_URL_ASSETS_JS?>ubid-0.1.2.min.js"></script>
	<script src="<?=BASE_URL_ASSETS_JS?>define.js?<?=date('YmdHis')?>"></script>
	<script src="<?=BASE_URL_ASSETS_JS?>app.min.js?<?=date('YmdHis')?>"></script>
	<script src="<?=BASE_URL_ASSETS_JS?>vendor.min.js?<?=date('YmdHis')?>"></script>
	<script src="<?=BASE_URL_ASSETS_JS?>session-controller.js?<?=date('YmdHis')?>"></script>
</html>