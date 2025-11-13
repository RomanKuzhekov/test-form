<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arResult
 */

if ($arResult["SUCCESS"]): ?>
	<div class="review-success"><?= htmlspecialcharsbx($arResult["SUCCESS"]) ?></div>
<?php elseif ($arResult["ERROR"]): ?>
	<div class="review-error"><?= htmlspecialcharsbx($arResult["ERROR"]) ?></div>
<?php endif; ?>

<form method="post" class="review-form">
	<?= bitrix_sessid_post() ?>

	<label>Имя*:</label>
	<input type="text" name="NAME" value="<?= htmlspecialcharsbx($_POST["NAME"] ?? '') ?>" required>

	<label>Плюсы:</label>
	<textarea name="PLUS"><?= htmlspecialcharsbx($_POST["PLUS"] ?? '') ?></textarea>

	<label>Минусы:</label>
	<textarea name="MINUS"><?= htmlspecialcharsbx($_POST["MINUS"] ?? '') ?></textarea>

	<label>Отзыв*:</label>
	<textarea name="REVIEW" required><?= htmlspecialcharsbx($_POST["REVIEW"] ?? '') ?></textarea>

	<button type="submit">Отправить отзыв</button>
</form>

<hr>

<h3>Отзывы</h3>

<?php
// Под вывод можно отдельный компонент написать или в этом оставить
if (!empty($arResult["REVIEWS"])): ?>
	<div class="reviews-list">
		<?php foreach ($arResult["REVIEWS"] as $review): ?>
			<div class="review-item">
				<div class="review-header">
					<strong><?= htmlspecialcharsbx($review["NAME"]) ?></strong>
					<span class="review-date"><?= $review["DATE_CREATE"] ?></span>
				</div>

				<?php if ($review["PROP_PLUS"]): ?>
					<div><b>Плюсы:</b> <?= htmlspecialcharsbx($review["PROP_PLUS"]) ?></div>
				<?php endif; ?>

				<?php if ($review["PROP_MINUS"]): ?>
					<div><b>Минусы:</b> <?= htmlspecialcharsbx($review["PROP_MINUS"]) ?></div>
				<?php endif; ?>

				<div class="review-text"><?= nl2br(htmlspecialcharsbx($review["TEXT"])) ?></div>
			</div>
		<?php endforeach; ?>
	</div>
<?php else: ?>
	<p>Пока нет опубликованных отзывов.</p>
<?php endif; ?>
