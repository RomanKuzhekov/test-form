<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

if (!Loader::includeModule("iblock"))
	return;

$arIBlocks = [];
$rsIBlocks = CIBlock::GetList(["NAME" => "ASC"], ["ACTIVE" => "Y"]);
while ($arIBlock = $rsIBlocks->Fetch()) {
	$arIBlocks[$arIBlock["ID"]] = "[{$arIBlock["ID"]}] {$arIBlock["NAME"]}";
}

$arComponentParameters = [
	"PARAMETERS" => [
		"IBLOCK_ID" => [
			"PARENT" => "BASE",
			"NAME" => "Инфоблок отзывов",
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"REFRESH" => "Y",
		],
		"REVIEWS_COUNT" => [
			"PARENT" => "BASE",
			"NAME" => "Количество отзывов для вывода",
			"TYPE" => "STRING",
			"DEFAULT" => "10",
		],
		"SUCCESS_MESSAGE" => [
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => "Сообщение после успешной отправки",
			"TYPE" => "STRING",
			"DEFAULT" => "Спасибо! Ваш отзыв отправлен на модерацию.",
		],
		"CACHE_TIME" => [
			"DEFAULT" => 3600,
		],
	],
];
