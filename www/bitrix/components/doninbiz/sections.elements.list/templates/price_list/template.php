<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<div class="price-list" role="tabpanel">

    <?if( ! empty($arResult['SECTIONS'])):?>

        <ul class="nav nav-tabs light" role="tablist">
            <?$is = 0;foreach($arResult['SECTIONS'] as $aSection):?>
                <?
                $this->AddEditAction($aSection['ID'], $aSection['EDIT_LINK'], CIBlock::GetArrayByID($aSection["IBLOCK_ID"], "SECTION_EDIT"));
                $this->AddDeleteAction($aSection['ID'], $aSection['DELETE_LINK'], CIBlock::GetArrayByID($aSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));
                ?>
                <li role="presentation" id="<?=$this->GetEditAreaId($aSection['ID']);?>"<?if(++$is == 1):?> class="active"<?endif?>>
                    <a href="#tab-<?=$aSection['ID']?>" role="tab" data-toggle="tab">
                        <?=$aSection['NAME']?>
                    </a>
                </li>
            <?endforeach;?>
        </ul>
    <?endif?>

    <?if( ! empty($arResult['SECTIONS'])):?>

        <div class="tab-content light">
            <?$is = 0;foreach($arResult['SECTIONS'] as $aSection):?>

                <div id="tab-<?=$aSection['ID']?>" class="tab-pane<?if(++$is == 1):?> active<?endif?>" role="tabpanel">

                    <?if( ! empty($aSection['DESCRIPTION'])):?>
                        <div class="section-text">
                            <?=$aSection['DESCRIPTION']?>
                        </div>
                        <br />
                    <?endif?>

                    <?if ( ! empty($aSection['ITEMS'])):?>

                        <!--<div class="table-responsive">-->

                            <table class="table table-striped table-hover table-bordered footable">

                                <thead>
                                    <tr>
                                        <th data-toggle="true"><?=GetMessage('CT_PL_NAME')?></th>
                                        <?foreach($aSection['PRICE_PROPERTIES'] as $aPriceProperties):?>
                                            <?
                                            $sTdHide = '';
                                            if ($aPriceProperties['XML_ID'] != 'PRICE') {
                                                $sTdHide = ' data-hide="phone"';
                                            }
                                            $sTdSort = '';
                                            if ($aPriceProperties['XML_ID'] == 'PRICE') {
                                                $sTdSort = ' data-type="numeric" data-value="'.$aPriceProperties['VALUE'].'"';
                                            }
                                            ?>
                                            <th<?=$sTdHide.$sTdSort?>>
                                                <?=$aPriceProperties['VALUE']?>
                                            </th>
                                        <?endforeach?>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?foreach($aSection['ITEMS'] as $aItem):?>
                                        <?
                                            $this->AddEditAction($aItem['ID'], $aItem['EDIT_LINK'], CIBlock::GetArrayByID($aItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                                            $this->AddDeleteAction($aItem['ID'], $aItem['DELETE_LINK'], CIBlock::GetArrayByID($aItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                                        ?>

                                        <tr id="<?=$this->GetEditAreaId($aItem['ID']);?>">
                                            <td><?=$aItem['NAME']?></td>

                                            <?foreach($aSection['PRICE_PROPERTIES'] as $aPriceProperties):?>
                                                <td class="text-center">
                                                    <?
                                                        $sVal = $aItem['PROPERTY_'.$aPriceProperties['XML_ID'].'_VALUE'];

                                                        if ($aPriceProperties['XML_ID'] == 'PRICE') {
                                                            if (empty($sVal)) {
                                                                echo '<a href="'.SITE_DIR.'order/question.php" class="get-question-form has-tooltip a-question" title="'.GetMessage('CT_PL_SPECIFY_COST').'" data-name="'.$aItem['NAME'].'"><i class="fa fa-envelope-o"></i></a>';
                                                            } else {
                                                                echo $arParams['PRICE_PREFIX'] . $sVal . htmlspecialchars_decode($arParams['PRICE_SUFFIX']);
                                                            }
                                                        } else {
                                                            echo $sVal;
                                                        }
                                                    ?>
                                                </td>
                                            <?endforeach?>
                                        </tr>

                                    <?endforeach?>

                                </tbody>

                            </table>

                        <!--</div>-->

                    <?endif?>

                </div>


            <?endforeach?>
        </div>

    <?endif?>

</div>