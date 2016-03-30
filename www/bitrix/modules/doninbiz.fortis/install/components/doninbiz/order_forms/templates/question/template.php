<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
//var_dump($_POST);
//var_dump($arResult);
//var_dump($arParams);
//var_dump($_SERVER);

$this->setFrameMode(true);

$sAction = SITE_DIR . 'order/question.php';

$iHideModal = ( strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' );
$iHideModal = !empty($_GET['hideModal']) ? true : $iHideModal;

$sGoal = "yaCounter{$arParams['YA_COUNTER']}.reachGoal('{$arParams['YA_GOAL']}'); return true;";

$sSubmitGoal = $sClickGoal = null;

if ($arResult['IS_GOAL']) {
    if (empty($iHideModal)) {
        $sClickGoal = " onclick=\"{$sGoal}\"";
    } else {
        $sSubmitGoal = " onsubmit=\"{$sGoal}\"";
    }
}

?>

<?if(empty($iHideModal)): ?>
<div class="modal fade modal-order-form" id="orderQuestionModal" tabindex="-1" role="dialog" aria-labelledby="orderQuestionFormLabel" aria-hidden="true" data-uri="<?=$sAction?>">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
<?endif;?>
                <div class="m-outer-form" id="orderQuestion">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                    <div class="m-header">
                        <?if($arParams['FORM_NAME']):?>
                            <h3 id="orderQuestionFormLabel"><?=$arParams['FORM_NAME']?></h3>
                        <?endif;?>
                        <?if($arParams['FORM_TEXT'] && empty($arResult['SUCCESS'])):?>
                            <p class="text">
                                <?=$arParams['FORM_TEXT']?>
                            </p>
                        <?endif;?>
                    </div>

                    <div class="m-content">
                        <?if( ! empty($arResult['SUCCESS'])):?>
                            <p class="alert alert-success"><?=$arParams['SUCCESS_TEXT']?></p>
                        <?else:?>

                            <?if(!empty($arResult['ERROR']['GLOBAL'])):?>
                                <p class="alert alert-danger"><?=$arResult['ERROR']['GLOBAL']?></p>
                            <?endif?>

                            <form method="post" action="<?=$sAction?>" id="orderQuestionForm"<?=$sSubmitGoal?>>
                                <?=bitrix_sessid_post()?>
                                <input type="hidden" name="add_new_order" value="1" />
                                <input type="hidden" name="type" value="question" />
                                <?if ($_REQUEST['product_disabled']):?>
                                    <input type="hidden" name="product_disabled" value="1" />
                                <?endif?>

                                <div class="form-group<?=(!empty($arResult['ERROR']['NAME']) && !empty($_POST) ? ' has-error' : '');?>">
                                    <label for="form-QUESTION-NAME"><?=GetMessage('FORM_QUESTION_FIELD_NAME')?>: <span class="require">*</span></label>
                                    <input type="text" class="form-control" id="form-QUESTION-NAME" name="NAME" value="<?=$arResult['DATA']['NAME'];?>" />
                                    <?=(!empty($arResult['ERROR']['NAME']) && !empty($_POST) ? '<span class="help-block">'.$arResult['ERROR']['NAME'].'</span>' : '');?>
                                </div>

                                <div class="form-group<?=(!empty($arResult['ERROR']['MOBILE']) && !empty($_POST) ? ' has-error' : '');?>">
                                    <label for="form-QUESTION-MOBILE"><?=GetMessage('FORM_QUESTION_FIELD_MOBILE')?>: <span class="require">*</span></label>
                                    <span class="phone pull-right"></span>
                                    <input type="text" class="form-control" id="form-QUESTION-MOBILE" name="MOBILE" value="<?=$arResult['DATA']['MOBILE'];?>" />
                                    <input type="hidden" name="MOBILE_COUNTRY" value="<?=$arResult['DATA']['MOBILE_COUNTRY'];?>" />
                                    <?=(!empty($arResult['ERROR']['MOBILE']) && !empty($_POST) ? '<span class="help-block">'.$arResult['ERROR']['MOBILE'].'</span>' : '');?>
                                </div>

                                <div class="form-group<?=(!empty($arResult['ERROR']['EMAIL']) && !empty($_POST) ? ' has-error' : '');?>">
                                    <label for="form-QUESTION-EMAIL"><?=GetMessage('FORM_QUESTION_FIELD_EMAIL')?>:</label>
                                    <input type="text" class="form-control" id="form-QUESTION-EMAIL" name="EMAIL" value="<?=$arResult['DATA']['EMAIL'];?>" />
                                    <?=(!empty($arResult['ERROR']['EMAIL']) && !empty($_POST) ? '<span class="help-block">'.$arResult['ERROR']['EMAIL'].'</span>' : '');?>
                                </div>

                                <div class="form-group<?=(!empty($arResult['ERROR']['PRODUCT']) && !empty($_POST) ? ' has-error' : '');?>">
                                    <label for="form-QUESTION-PRODUCT"><?=GetMessage('FORM_QUESTION_FIELD_PRODUCT')?>:</label>
                                    <input type="text" class="form-control" id="form-QUESTION-PRODUCT" name="PRODUCT" value="<?=$arResult['DATA']['PRODUCT'];?>"<?if(!empty($_REQUEST['product_disabled'])):?> disabled="disabled"<?endif?> />
                                    <?=(!empty($arResult['ERROR']['PRODUCT']) && !empty($_POST) ? '<span class="help-block">'.$arResult['ERROR']['PRODUCT'].'</span>' : '');?>
                                </div>

                                <div class="form-group<?=(!empty($arResult['ERROR']['COMMENT']) && !empty($_POST) ? ' has-error' : '');?>">
                                    <label for="form-QUESTION-COMMENT"><?=GetMessage('FORM_QUESTION_FIELD_COMMENT')?>:</label>
                                    <textarea name="COMMENT" class="form-control" id="form-QUESTION-COMMENT"><?=$arResult['DATA']['COMMENT'];?></textarea>
                                    <?=(!empty($arResult['ERROR']['COMMENT']) && !empty($_POST) ? '<span class="help-block">'.$arResult['ERROR']['COMMENT'].'</span>' : '');?>
                                </div>

                                <?if($arResult['USE_CAPTCHA']):?>
                                    <?
                                    $dynamicArea = new \Bitrix\Main\Page\FrameStatic("modal_captcha_question");
                                    $dynamicArea->startDynamicArea();
                                    ?>
                                    <div class="form-group<?=(!empty($arResult['ERROR']['CAPTCHA']) && !empty($_POST) ? ' has-error' : '');?>">
                                        <label for="form-QUESTION-CAPTCHA"><?=GetMessage('FORM_QUESTION_FIELD_CAPTCHA')?>: <span class="require">*</span></label>
                                        <div class="captcha-img">
                                            <input type="hidden" name="captcha_sid" value="<?=$arResult["capCode"]?>">
                                            <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["capCode"]?>" width="180" height="40" alt="CAPTCHA">
                                            <a href="#" class="update-captcha-img"><i class="fa fa-refresh"></i> <?=GetMessage('FORM_QUESTION_CAPTCHA_CHANGE_IMAGE')?></a>
                                        </div>
                                        <div class="captcha-input">
                                            <input class="form-control" id="form-QUESTION-CAPTCHA" type="text" name="captcha_word" size="30" maxlength="50" value="" />
                                        </div>
                                        <?=(!empty($arResult['ERROR']['CAPTCHA']) && !empty($_POST) ? '<span class="help-block">'.$arResult['ERROR']['CAPTCHA'].'</span>' : '');?>
                                    </div>
                                    <?
                                    $dynamicArea->finishDynamicArea();
                                    ?>
                                <?endif?>

                                <div class="m-footer">
                                    <div class="left">
                                        <div class="required">
                                            <span>*</span> <?=GetMessage('FORM_QUESTION_REQUIRED_FIELDS')?>
                                        </div>
                                    </div>
                                    <div class="right">
                                        <button type="submit" class="btn btn-primary btn-lg btn-submit" data-loading-text="<?=GetMessage('FORM_QUESTION_SEND')?>"<?=$sClickGoal?>>
                                            <?=$arParams['SUBMIT_TEXT']?>
                                        </button>
                                    </div>
                                </div>

                            </form>
                        <?endif;?>
                    </div>

                    <div class="circle-outer">
                        <div class="circle-inner">
                            <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                        </div>
                    </div>

                </div>

<?if(empty($iHideModal)): ?>
            </div>

        </div>
    </div>
</div>
<?endif;?>