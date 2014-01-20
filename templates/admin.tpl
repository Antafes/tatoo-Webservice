{include file="header.tpl"}
<div id="admin">
	{if $error}
		<div class="error">{$error}</div>
	{/if}
	{if $message}
		<div class="message">{$message}</div>
	{/if}
	{if !$smarty.get.action || $smarty.get.action == 'list'}
		<table class="collapse userList">
			<thead>
				<tr>
					<th>{$translator->getTranslation('userId')}</th>
					<th>{$translator->getTranslation('username')}</th>
					<th>{$translator->getTranslation('admin')}</th>
					<th>{$translator->getTranslation('options')}</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$userList item='user'}
					<tr class="{cycle values='odd,even'}">
						<td>{$user->getUserId()}</td>
						<td>{$user->getName()}</td>
						<td class="centered">
							{if $user->getAdmin() && $user->getUserId() != $smarty.session.userId}
								<a href="index.php?page=Admin&amp;removeAdmin={$user->getUserId()}">
									{$translator->getTranslation('removeAdmin')}
								</a>
							{elseif !$user->getAdmin()}
								<a href="index.php?page=Admin&amp;setAdmin={$user->getUserId()}">
									{$translator->getTranslation('setAdmin')}
								</a>
							{/if}
						</td>
						<td>
							{if $user->getUserId() != $smarty.session.userId}
								<a href="index.php?page=Admin&amp;delete={$user->getUserId()}">
									{$translator->getTranslation('delete')}
								</a>
							{/if}
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
		<a class="button" href="index.php?page=Admin&amp;action=create">{$translator->getTranslation('createUser')}</a>
	{else}
		<form method="post" action="index.php?page=Admin&amp;action=create">
			<table class="collapse createUser">
				<tbody>
					<tr>
						<td>
							<label for="usernameText">{$translator->getTranslation('username')}</label>
						</td>
						<td>
							<input id="usernameText" type="text" name="username" value="{$smarty.post.username}" />
						</td>
					</tr>
					<tr>
						<td>
							<label for="emailText">{$translator->getTranslation('email')}</label>
						</td>
						<td>
							<input id="emailText" type="text" name="email" value="{$smarty.post.email}" />
						</td>
					</tr>
					<tr>
						<td>
							<label for="languageSelect">{$translator->getTranslation('language')}</label>
						</td>
						<td>
							<select id="languageSelect" name="language">
								{foreach from=$languages item='language'}
									<option value="{$language->getLanguageId()}">{$translator->getTranslation($language->getLanguage())}</option>
								{/foreach}
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label for="adminCheckbox">{$translator->getTranslation('admin')}</label>
						</td>
						<td>
							<input id="adminCheckbox" type="checkbox" name="admin" value="1"{if $smarty.post.admin} checked="checked"{/if} />
						</td>
					</tr>
					<tr>
						<td class="buttons" colspan="2">
							{add_form_salt formName='createUser'}
							<input type="submit" value="{$translator->getTranslation('createUser')}" />
							<a class="button" href="index.php?page=Admin">{$translator->getTranslation('cancel')}</a>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	{/if}
</div>
{include file="footer.tpl"}