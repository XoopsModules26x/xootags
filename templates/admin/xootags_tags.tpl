<{include file="admin:system/admin_navigation.tpl"}>
<{include file="admin:system/admin_tips.tpl"}>
<{include file="admin:system/admin_buttons.tpl"}>

<{if $form}>
    <{$form}>
<{/if}>

<{if count($tags)}>
    <table class="outer">
        <thead>
        <tr>
            <th class="txtcenter width60"><{$smarty.const._AM_XOO_TAGS_TERM}></th>
            <th class="txtcenter"><{$smarty.const._AM_XOO_TAGS_COUNT}></th>
            <th class="txtcenter"><{$smarty.const._AM_XOO_TAGS_SHOW_HIDE}></th>
            <th class="txtcenter"><{$smarty.const._AM_XOO_TAGS_ACTION}></th>
        </tr>
        </thead>

        <{foreach from=$tags item=tag}>
            <tr class="<{cycle values="even,odd"}>">
                <td class="txtleft bold">
                    <a href="<{xoAppUrl '/modules/xootags/'}>tag.php?tag_id=<{$tag.tag_id}>" title="<{$tag.tag_term}>"><{$tag.tag_term}></a><{if !$smarty.foreach.foo.last}>,&nbsp;<{/if}>
                </td>

                <td class="txtcenter">
                    <{$tag.tag_count}>
                    <img class="xo-moduleadmin-image" src="<{$tag.xoosocialnetwork_image_link}>" alt="<{$tag.xoosocialnetwork_title}>">
                </td>

                <td class="txtcenter">
                    <{if ( $tag.tag_status )}>
                        <a href="tags.php?op=hide&amp;tag_id=<{$tag.tag_id}>" title="<{$smarty.const._AM_XOO_TAGS_SHOW_HIDE}>"><img src="<{xoImgUrl 'media/xoops/images/icons/16/on.png'}>" alt="<{$smarty.const._AM_XOO_TAGS_SHOW_HIDE}>"></a>
                    <{else}>
                        <a href="tags.php?op=view&amp;tag_id=<{$tag.tag_id}>" title="<{$smarty.const._AM_XOO_TAGS_SHOW_HIDE}>"><img src="<{xoImgUrl 'media/xoops/images/icons/16/off.png'}>" alt="<{$smarty.const._AM_XOO_TAGS_SHOW_HIDE}>"></a>
                    <{/if}>
                </td>
                <td class="txtcenter">
                    <!--
                    <a href="tags.php?op=edit&amp;tag_id=<{$tag.tag_id}>" title="<{$smarty.const._EDIT}>"><img src="<{xoImgUrl 'media/xoops/images/icons/16/edit.png'}>" alt="{$smarty.const._EDIT}>"></a>
-->
                    <a href="tags.php?op=del&amp;tag_id=<{$tag.tag_id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoImgUrl 'media/xoops/images/icons/16/delete.png'}>" alt="<{$smarty.const._DELETE}>"></a>
                </td>
            </tr>
        <{/foreach}>
    </table>
<{/if}>
