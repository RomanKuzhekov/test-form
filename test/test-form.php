<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Тестовая форма");
?><?$APPLICATION->IncludeComponent(
	"custom:review.form",
	"",
	Array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"IBLOCK_ID" => "12",
		"REVIEWS_COUNT" => "10",
		"SUCCESS_MESSAGE" => "Спасибо! Ваш отзыв отправлен на модерацию."
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>