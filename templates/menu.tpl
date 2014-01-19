<div id="menu">
	{if $smarty.session.userId}
		<a class="button" href="index.php?page=Index">{$translator->getTranslation('index')}</a>
		{if $isAdmin}
			<a class="button" href="index.php?page=Admin">{$translator->getTranslation('admin')}</a>
		{/if}
	{/if}
	{if $smarty.session.userId}
		<a class="button" href="index.php?page=Logout">{$translator->getTranslation('logout')}</a>
	{/if}
	<div class="clear"></div>
</div>