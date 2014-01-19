{include file="header.tpl"}
<div id="login">
	{if $error}
		<div class="error">{$error}</div>
	{/if}
	<form method="post" action="index.php?page=Login">
		<table class="collapse">
			<tr>
				<td>{$translator->getTranslation('username')}:</td>
				<td>
					<input type="text" name="username" />
				</td>
			</tr>
			<tr>
				<td>{$translator->getTranslation('password')}:</td>
				<td>
					<input type="password" name="password" />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					{add_form_salt formName='login'}
					<input type="submit" value="{$translator->getTranslation('login')}" />
				</td>
			</tr>
		</table>
	</form>
</div>
{include file="footer.tpl"}