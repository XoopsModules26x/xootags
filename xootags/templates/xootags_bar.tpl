<div class="itemTags">
    <{$smarty.const._XOO_TAGS_TAGS}>:
    <{foreach from=$tags item=tag name=foo}>
        <a href="<{xoAppUrl /modules/xootags/}>tag.php?tag_id=<{$tag.tag_id}>" title="<{$tag.tag_term}>"><{$tag.tag_term}></a><{if !$smarty.foreach.foo.last}>,&nbsp;<{/if}>
    <{/foreach}>
</div>
