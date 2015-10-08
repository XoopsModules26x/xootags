<div class="bold">
    <{$smarty.const._AM_XOO_TAGS_COUNT_TAGS}>: <span class="red"><{$count_tag}></span>
</div>
<div class="bold">
    <{$smarty.const._AM_XOO_TAGS_COUNT_ITEM}>: <span class="red"><{$count_item}></span>
</div>

<hr>
<{foreach from=$count_module item=module}>
    <div class="bold">
        <a href="tags.php?module_id=<{$module.mid}>"><{$module.name}></a>: <span class="red"><{$module.count}></span>
    </div>
<{/foreach}>
