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
?>

<?if ($arParams["VK_USE"] == "Y" || $arParams["FB_USE"] == "Y"):?>

	<?
		if (!empty($arResult['ERRORS'])) {
			ShowError(implode('<br>', $arResult['ERRORS']));
			return;
		}

		/*$arJSParams = array(
			'serviceList' => array(

			),
			'settings' => array(

			)
		);

		if ($arParams["FB_USE"] == "Y") {
			$arJSParams['serviceList']['facebook'] = true;
			$arJSParams['settings']['facebook'] = array(
				'parentContID' => 'fb_comments_tab' . $arParams['TYPE'],
				'contID' => 'bx-cat-soc-comments-fb_'.$arResult['ELEMENT']['ID'].$arParams['TYPE'],
				'facebookPath' => '//connect.facebook.net/'.(strtolower(LANGUAGE_ID)."_".strtoupper(LANGUAGE_ID)).'/all.js#xfbml=1'
			);
		}*/
	?>

	<br />
	<div role="tabpanel">

		<ul class="nav nav-tabs" id="catalog-comments-tab<?=$arParams['TYPE']?>" role="tablist">

            <?if ($arParams["VK_USE"] == "Y"):?>
                <li class="active">
                    <a href="#vk_comments_tab<?=$arParams['TYPE']?>" aria-controls="vk_comments<?=$arParams['TYPE']?>" role="tab" data-toggle="tab">
                        <i class="fa fa-vk"></i> <?=GetMessage("IBLOCK_CSC_TAB_VK")?>
                    </a>
                </li>
            <?endif?>

            <?if ($arParams["FB_USE"] == "Y"):?>
                <li>
                    <a href="#fb_comments_tab<?=$arParams['TYPE']?>" aria-controls="fb_comments<?=$arParams['TYPE']?>" role="tab" data-toggle="tab">
                        <i class="fa fa-facebook"></i> Facebook
                    </a>
                </li>
            <?endif?>

		</ul>

		<div class="tab-content">

			<?if ($arParams["VK_USE"] == "Y"):?>
				<div role="tabpanel" class="tab-pane active" id="vk_comments_tab<?=$arParams['TYPE']?>">

					<?$frame = $this->createFrame()->begin();?>
					<?='
				<div id="vk_comments'.$arParams['TYPE'].'"></div>
				<script type="text/javascript">
					BX.ready(BX.defer(function(){
						if (!!window.VK)
						{
							VK.init({
								apiId: "'.(isset($arParams["VK_API_ID"]) && strlen($arParams["VK_API_ID"]) > 0 ? $arParams["VK_API_ID"] : "API_ID").'",
								onlyWidgets: true
							});

							VK.Widgets.Comments(
								"vk_comments'.$arParams['TYPE'].'",
								{
									pageUrl: "'.$arResult["URL_TO_COMMENT"].'",'.
					(isset($arParams["COMMENTS_COUNT"]) ? "limit: ".$arParams["COMMENTS_COUNT"]."," : "").
					'attach: false
								}
							);
						}
					}));
				</script>'?>

					<?$frame->end();?>
				</div>
			<?endif?>

            <?if ($arParams["FB_USE"] == "Y"):?>
                <div role="tabpanel" class="tab-pane" id="fb_comments_tab<?=$arParams['TYPE']?>">

                    <?$frame = $this->createFrame()->begin();?>
                    <?='<div class="fb-comments" id="fb-comments-'.$arParams['TYPE'].'" data-href="'.$arResult["URL_TO_COMMENT"].'"'.
                    (isset($arParams["FB_COLORSCHEME"]) ? ' data-colorscheme="'.$arParams["FB_COLORSCHEME"].'"' : '').
                    (isset($arParams["COMMENTS_COUNT"]) ? ' data-numposts="'.$arParams["COMMENTS_COUNT"].'"' : '').
                    (isset($arParams["FB_ORDER_BY"]) ? ' data-order-by="'.$arParams["FB_ORDER_BY"].'"' : '').
                    ' data-width="100%"></div>'?>

                    <?='
                        <div id="fb-root"></div>
                        <script>(function(d, s, id) {
                          var js, fjs = d.getElementsByTagName(s)[0];
                          if (d.getElementById(id)) return;
                          js = d.createElement(s); js.id = id;
                          js.src = "//connect.facebook.net/'.(strtolower(LANGUAGE_ID)."_".strtoupper(LANGUAGE_ID)).'/sdk.js#xfbml=1&version=v2.0";
                          fjs.parentNode.insertBefore(js, fjs);
                        }(document, \'script\', \'facebook-jssdk\'));</script>
                    '?>

                    <?$frame->end();?>
                </div>
            <?endif?>

		</div>

	</div>

<?endif?>