<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

$iFortisSkipHeading          = true;
$iFortisSkipContentContainer = true;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Ошибка 404 :: Страница не найдена");
$APPLICATION->SetPageProperty("title", "Ошибка 404 :: Страница не найдена");
?>

	<div class="content">

		<div class="container wrapper-container">
			<div class="row page-error-404">

				<div class="col-md-5 left">
					<h3>Страница не найдена!</h3>
					<p>Возможно, страница временно недоступна, изменена или удалена.</p>
					<div class="error_code">
						404
					</div>
				</div>

				<div class="col-md-7 right">

					<h3>Карта сайта</h3>

					<?
					$APPLICATION->IncludeComponent(
	"bitrix:main.map", 
	"error_404", 
	array(
		"LEVEL" => "1",
		"COL_NUM" => "3",
		"SHOW_DESCRIPTION" => "N",
		"SET_TITLE" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A"
	),
	false
);
					?>

				</div>
			</div>
		</div>

	</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>