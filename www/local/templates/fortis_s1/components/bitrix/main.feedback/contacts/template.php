<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */
$this->setFrameMode(true);
?>

<div class="mfeedback">
<?if(!empty($arResult["ERROR_MESSAGE"]))
{
	foreach($arResult["ERROR_MESSAGE"] as $v)
		ShowError($v);
}
if(strlen($arResult["OK_MESSAGE"]) > 0)
{
	?><p class="alert alert-success"><?=$arResult["OK_MESSAGE"]?></p><?
}
?>

<form class="fortis-form" action="<?=POST_FORM_ACTION_URI?>" method="POST">
    <?=bitrix_sessid_post()?>

    <fieldset>

        <div class="form-group">
            <label for="form_user_name">
                <?=GetMessage("MFT_NAME")?>: <?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_FIELDS"])):?><span class="require">*</span><?endif?>
            </label>
            <input type="text" class="form-control" id="form_user_name" name="user_name" value="<?=$arResult["AUTHOR_NAME"]?>" />
        </div>

        <div class="form-group">
            <label for="form_user_email">
                <?=GetMessage("MFT_EMAIL")?>: <?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("EMAIL", $arParams["REQUIRED_FIELDS"])):?><span class="require">*</span><?endif?>
            </label>
            <input type="text" class="form-control" id="form_user_email" name="user_email" value="<?=$arResult["AUTHOR_EMAIL"]?>" />
        </div>

        <div class="form-group">
            <label for="form_MESSAGE">
                <?=GetMessage("MFT_MESSAGE")?>: <?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_FIELDS"])):?><span class="require">*</span><?endif?>
            </label>
            <textarea name="MESSAGE" id="form_MESSAGE" class="form-control" rows="5"><?=$arResult["MESSAGE"]?></textarea>
        </div>

        <?if($arParams["USE_CAPTCHA"] == "Y"):?>
            <?
            $dynamicArea = new \Bitrix\Main\Page\FrameStatic("feedback_captcha");
            $dynamicArea->startDynamicArea();
            ?>
            <div class="form-group">
                <label for="form-REVIEW-CAPTCHA"><?=GetMessage("MFT_CAPTCHA_CODE")?>: <span class="require">*</span></label>

                <div class="row">
                    <div class="col-lg-4 col-md-5 col-sm-6 col-xs-12">
                        <div class="captcha-img">
                            <input type="hidden" name="captcha_sid" value="<?=$arResult["capCode"]?>">
                            <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["capCode"]?>" width="180" height="40" alt="CAPTCHA">
                            <a href="#" class="update-captcha-img"><i class="fa fa-refresh"></i> <?=GetMessage('MFT_CAPTCHA_CHANGE_IMAGE')?></a>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7 col-sm-6 col-xs-12">
                        <div class="captcha-input">
                            <input class="form-control" id="form-REVIEW-CAPTCHA" type="text" name="captcha_word" size="30" maxlength="50" value="" />
                        </div>
                    </div>
                </div>
            </div>
            <?
            $dynamicArea->finishDynamicArea();
            ?>
        <?endif?>

        <div class="text-center">
            <input type="hidden" name="PARAMS_HASH" value="<?=$arResult["PARAMS_HASH"]?>">
            <input type="submit" name="submit" class="btn btn-primary" value="<?=GetMessage("MFT_SUBMIT")?>">
        </div>

    </fieldset>
</form>
</div>