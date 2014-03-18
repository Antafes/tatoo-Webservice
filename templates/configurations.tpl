{include file="header.tpl"}
<div id="configurations">
	{if $error}
		<div class="error">{$error}</div>
		<br /><br />
	{/if}
	{if $message}
		<div class="message">{$message}</div>
	{/if}
	{if !$smarty.get.action || $smarty.get.action == 'list'}
		<table class="collapse configurationList">
			<thead>
				<tr>
					<th>{$translator->getTranslation('configuration')}</th>
					<th>{$translator->getTranslation('value')}</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$configurations item='configuration'}
					<tr class="{cycle values='odd,even'}">
						<td>{$configuration->getKey()}</td>
						<td>{$configuration->getValue()}</td>
						<td>
							<a href="index.php?page=Configurations&amp;action=create&amp;edit={$configuration->getId()}">{$translator->getTranslation('edit')}</a>
							<a href="index.php?page=Configurations&amp;delete={$configuration->getId()}">{$translator->getTranslation('delete')}</a>
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
		<a class="button" href="index.php?page=Configurations&amp;action=create">{$translator->getTranslation('createConfiguration')}</a>
	{else}
		<form method="post" action="index.php?page=Configurations">
			<table>
				<tbody>
					<tr>
						<td>{$translator->getTranslation('configuration')}:</td>
						<td>
							<input type="text" name="configuration" value="{$configuration->getKey()}" />
						</td>
					</tr>
					<tr>
						<td>{$translator->getTranslation('value')}:</td>
						<td>
							<input type="text" name="configValue" value="{$configuration->getValue()}" />
						</td>
					</tr>
					<tr>
						<td class="buttons" colspan="2">
							<input type="hidden" name="id" value="{$configuration->getId()}" />
							{if $configuration->getId() != 'new'}
								{add_form_salt formName='updateConfiguration'}
								<input type="submit" value="{$translator->getTranslation('updateConfiguration')}" />
							{else}
								{add_form_salt formName='createConfiguration'}
								<input type="submit" value="{$translator->getTranslation('createConfiguration')}" />
							{/if}
							<a class="button" href="index.php?page=Configurations">{$translator->getTranslation('cancel')}</a>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	{/if}
</div>
{include file="footer.tpl"}