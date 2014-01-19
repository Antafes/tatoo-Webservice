<!DOCTYPE html>
<html lang="{$languageCode}">
	<head>
		<title>{$translator->getTranslation('title')}</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width">
		{include_css}
		{include_js}
	</head>
	<body>
		<div id="head">
			{include file='menu.tpl'}
			<div id="languageSelect">
				<form method="get" action="index.php">
					<input type="hidden" name="page" value="{if $smarty.get.page}{$smarty.get.page}{else}Index{/if}" />
					<select name="language">
						{foreach from=$languages item='language'}
							<option value="{$language->getLanguageId()}"{if $language->getLanguageId() == $currentLanguage} selected="selected"{/if}>
								{$translator->getTranslation($language->getLanguage())}
							</option>
						{/foreach}
					</select>
				</form>
			</div>
			<div class="clear"></div>
		</div>