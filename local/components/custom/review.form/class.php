<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Iblock\Iblock;
use Bitrix\Main\Localization\Loc;

class CustomReviewFormComponent extends CBitrixComponent
{
	public function onPrepareComponentParams($params)
	{
		$params["IBLOCK_ID"] = (int)$params["IBLOCK_ID"];
		$params["REVIEWS_COUNT"] = (int)($params["REVIEWS_COUNT"] ?: 10);
		$params["CACHE_TIME"] = (int)($params["CACHE_TIME"] ?: 3600);
		$params["SUCCESS_MESSAGE"] = trim($params["SUCCESS_MESSAGE"]) ?: Loc::getMessage("SUCCESS_MESSAGE");
		return $params;
	}

	public function executeComponent()
	{
		$this->arResult = [
			"ERROR" => "",
			"SUCCESS" => "",
			"REVIEWS" => [],
		];

		if ($_SERVER["REQUEST_METHOD"] === "POST" && check_bitrix_sessid()) {
			$name = trim($_POST["NAME"] ?? "");
			$review = trim($_POST["REVIEW"] ?? "");

			if ($name === "" || $review === "") {
				$this->arResult["ERROR"] = "Поля 'Имя' и 'Отзыв' обязательны для заполнения.";
			} else {
				if ($this->addReview($_POST)) {
					$this->arResult["SUCCESS"] = $this->arParams["SUCCESS_MESSAGE"];
					$this->clearResultCache();
					$_POST = [];
				} else {
					$this->arResult["ERROR"] = "Ошибка при сохранении отзыва.";
				}
			}
		}

		// Кэширование отзывов
		if ($this->StartResultCache(false, ($this->arResult["SUCCESS"] ? false : true))) {
			$this->arResult["REVIEWS"] = $this->getApprovedReviews();
			$this->EndResultCache();
		}

		$this->includeComponentTemplate();
	}

	/**
	 * Получение активных отзывов
	 */
	protected function getApprovedReviews(): array
	{
		if (!Loader::includeModule("iblock")) {
			return [];
		}

		$entity = $this->getEntityIblock($this->arParams['IBLOCK_ID']);
		$reviews = $entity::getList([
			'select' => [
				'ID',
				'NAME',
				'PREVIEW_TEXT',
				'DATE_CREATE',
				'PROP_PLUS' => 'PLUS.VALUE',
				'PROP_MINUS' => 'MINUS.VALUE'
			],
			'filter' => [
				'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
				'ACTIVE' => 'Y'
			],
			'order' => [
				'DATE_CREATE' => 'DESC',
				'ID' => 'DESC'
			],
			'limit' => $this->arParams['REVIEWS_COUNT']
		])->fetchAll();

		return $reviews;
	}

	/**
	 * Добавление отзыва в инфоблок
	 */
	protected function addReview(array $data): bool
	{
		if (!Loader::includeModule("iblock")) {
			return false;
		}

		$el = new \CIBlockElement();
		$arFields = [
			'IBLOCK_ID' => $this->arParams["IBLOCK_ID"],
			'NAME' => trim(htmlspecialcharsbx($data["NAME"])),
			"ACTIVE" => "N",
			'PROPERTY_VALUES' => [
				'PLUS' => trim(htmlspecialcharsbx($data["PLUS"])),
				'MINUS' => trim(htmlspecialcharsbx($data["MINUS"])),
			],
		];

		return (bool)$el->Add($arFields);
	}

	/**
	 * Получает ORM-класс для инфоблока
	 * @param int $iblockId
	 */
	private function getEntityIblock(int $iblockId)
	{
		return Iblock::wakeUp($iblockId)->getEntityDataClass();
	}
}
