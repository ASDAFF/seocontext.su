<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
//var_dump($_POST);
//var_dump($arResult);
//var_dump($arParams);
//var_dump($_SERVER);

$this->setFrameMode(true);
?>

<?if( ! empty($arResult['SUCCESS'])):?>
    <p class="alert alert-success" id="addReview"><?=$arParams['SUCCESS_TEXT']?></p>
<?else:?>

    <form class="fortis-form" enctype="multipart/form-data" method="post" action="<?=$sAction?>#addReview" id="addReview"<?=$sSubmitGoal?>>
        <input type="hidden" name="add_new_order" value="1" />

        <fieldset class="bordered">
            <?=bitrix_sessid_post()?>

            <legend><?=GetMessage('FORM_REV_ADD_LEGEND')?></legend>

            <?if(!empty($arResult['ERROR']['GLOBAL'])):?>
                <p class="alert alert-danger"><?=$arResult['ERROR']['GLOBAL']?></p>
            <?endif?>

            <div class="form-group<?=(!empty($arResult['ERROR']['NAME']) && !empty($_POST) ? ' has-error' : '');?>">
                <label for="form-REVIEW-NAME"><?=( ! empty($arParams['VIEW_NAME_LABEL']) ? $arParams['VIEW_NAME_LABEL'] : GetMessage('FORM_REV_FIELD_NAME'))?>: <span class="require">*</span></label>
                <input type="text" class="form-control" id="form-REVIEW-NAME" name="NAME" value="<?=$arResult['DATA']['NAME'];?>" />
                <?=(!empty($arResult['ERROR']['NAME']) && !empty($_POST) ? '<span class="help-block">'.$arResult['ERROR']['NAME'].'</span>' : '');?>
            </div>

            <?if($arParams['VIEW_STATUS'] == 'Y'):?>
                <div class="form-group<?=(!empty($arResult['ERROR']['STATUS']) && !empty($_POST) ? ' has-error' : '');?>">
                    <label for="form-REVIEW-STATUS"><?=( ! empty($arParams['VIEW_STATUS_LABEL']) ? $arParams['VIEW_STATUS_LABEL'] : GetMessage('FORM_REV_FIELD_STATUS'))?>:</label>
                    <input type="text" class="form-control" id="form-REVIEW-STATUS" name="STATUS" value="<?=$arResult['DATA']['STATUS'];?>" />
                    <?=(!empty($arResult['ERROR']['STATUS']) && !empty($_POST) ? '<span class="help-block">'.$arResult['ERROR']['STATUS'].'</span>' : '');?>
                </div>
            <?endif?>

            <?if($arParams['VIEW_SITE'] == 'Y'):?>
                <div class="form-group<?=(!empty($arResult['ERROR']['SITE']) && !empty($_POST) ? ' has-error' : '');?>">
                    <label for="form-REVIEW-SITE"><?=( ! empty($arParams['VIEW_SITE_LABEL']) ? $arParams['VIEW_SITE_LABEL'] : GetMessage('FORM_REV_FIELD_SITE'))?>:</label>
                    <input type="text" class="form-control" id="form-REVIEW-SITE" name="SITE" value="<?=$arResult['DATA']['SITE'];?>" />
                    <?=(!empty($arResult['ERROR']['SITE']) && !empty($_POST) ? '<span class="help-block">'.$arResult['ERROR']['SITE'].'</span>' : '');?>
                </div>
            <?endif?>

            <?if($arParams['VIEW_PREVIEW_PICTURE'] == 'Y'):?>
                <div class="form-group<?=(!empty($arResult['ERROR']['PREVIEW_PICTURE']) && !empty($_POST) ? ' has-error' : '');?>">
                    <label for="form-REVIEW-PREVIEW_PICTURE"><?=( ! empty($arParams['VIEW_PREVIEW_PICTURE_LABEL']) ? $arParams['VIEW_PREVIEW_PICTURE_LABEL'] : GetMessage('FORM_REV_FIELD_PREVIEW_PICTURE'))?>:</label>
                    <input type="file" class="form-control" id="form-REVIEW-PREVIEW_PICTURE" name="PREVIEW_PICTURE" value="" />
                    <?=(!empty($arResult['ERROR']['PREVIEW_PICTURE']) && !empty($_POST) ? '<span class="help-block">'.$arResult['ERROR']['PREVIEW_PICTURE'].'</span>' : '');?>
                </div>
            <?endif?>

            <div class="form-group<?=(!empty($arResult['ERROR']['PREVIEW_TEXT']) && !empty($_POST) ? ' has-error' : '');?>">
                <label for="form-REVIEW-PREVIEW_TEXT"><?=GetMessage('FORM_REV_FIELD_COMMENT')?>: <span class="require">*</span></label>
                <textarea class="form-control" name="PREVIEW_TEXT" id="form-REVIEW-PREVIEW_TEXT" rows="5"><?=$arResult['DATA']['PREVIEW_TEXT'];?></textarea>
                <?=(!empty($arResult['ERROR']['PREVIEW_TEXT']) && !empty($_POST) ? '<span class="help-block">'.$arResult['ERROR']['PREVIEW_TEXT'].'</span>' : '');?>
            </div>

            <?if($arResult['USE_CAPTCHA']):?>
                <?
                $dynamicArea = new \Bitrix\Main\Page\FrameStatic("review_captcha");
                $dynamicArea->startDynamicArea();
                ?>
                <div class="form-group<?=(!empty($arResult['ERROR']['CAPTCHA']) && !empty($_POST) ? ' has-error' : '');?>">
                    <label for="form-REVIEW-CAPTCHA"><?=GetMessage('FORM_REV_FIELD_CAPTCHA')?>: <span class="require">*</span></label>

                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <div class="captcha-img">
                                <input type="hidden" name="captcha_sid" value="<?=$arResult["capCode"]?>">
                                <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["capCode"]?>" width="180" height="40" alt="CAPTCHA">
                                <a href="#" class="update-captcha-img"><i class="fa fa-refresh"></i> <?=GetMessage('FORM_REV_CAPTCHA_CHANGE_IMAGE')?></a>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
                            <div class="captcha-input">
                                <input class="form-control" id="form-REVIEW-CAPTCHA" type="text" name="captcha_word" size="30" maxlength="50" value="" />
                            </div>
                            <?=(!empty($arResult['ERROR']['CAPTCHA']) && !empty($_POST) ? '<span class="help-block">'.$arResult['ERROR']['CAPTCHA'].'</span>' : '');?>
                        </div>
                    </div>
                </div>
                <?
                $dynamicArea->finishDynamicArea();
                ?>
            <?endif?>

            <div class="form-footer">
                <div class="left">
                    <div class="required">
                        <span>*</span> <?=GetMessage('FORM_REV_REQUIRED_FIELDS')?>
                    </div>
                </div>
                <div class="right">
                    <button type="submit" class="btn btn-primary btn-submit" data-loading-text="<?=GetMessage('FORM_REV_SEND')?>">
                        <?=GetMessage('FORM_REV_ADD')?>
                    </button>
                </div>
            </div>
        </fieldset>

    </form>
<?endif;?>