<{foreach from=$tags item=tag}>
    <div class="item">
        <div class="itemHead">
            <{if $xootags_qrcode.use_qrcode}>
                <div class="itemQRcode">
                    <a href="<{xoAppUrl /modules/xootags/}>tag.php?tag_id=<{$tag.tag_id}>"><img src="<{xoAppUrl modules/xootags/qrcode.php}>?url=<{xoAppUrl /modules/xootags/}>tag.php?tag_id=<{$tag.tag_id}>" alt="<{xoAppUrl /modules/xootags/}>tag.php?tag_id=<{$tag.tag_id}>"></a>
                </div>
            <{/if}>
            <div>
                <div class="itemTitle">
                    <a href="<{xoAppUrl /modules/xootags/}>tag.php?tag_id=<{$tag.tag_id}>" title="<{$tag.tag_term}>">
                    <span class="tag<{$tag.tag_id}> <{cycle values="$xootags_colors"}>">
                    <{$tag.tag_term}><{if $xootags_count}> (<{$tag.tag_count}>)<{/if}>
                    </span></a>
                </div>

                <div class="itemInfo">
                    <div class="itemModules">
                        <{$smarty.const._XOO_TAGS_TOPICS}>:&nbsp;
                        <{foreach from=$tag.modules item=module name=foo}>
                        <a href="<{xoAppUrl /modules/xootags/}>tag.php?tag_id=<{$tag.tag_id}>&module_id=<{$module.mid}>" title="<{$module.name}>"><i class="xootags-ico-<{$module.dirname}>"></i><{$module.name}>
                            </a><{if !$smarty.foreach.foo.last}>,&nbsp;<{/if}>
                        <{/foreach}>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
<{/foreach}>
