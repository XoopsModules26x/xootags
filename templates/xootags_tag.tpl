<{include file='module:xootags/xootags_css.tpl'}>

<{if $moduletitle != ''}>
    <fieldset>
        <legend><{$moduletitle}></legend>

        <{foreach from=$subtitle item=title}>
            <{$title}>
            <br>
        <{/foreach}>
    </fieldset>
<{/if}>

<{if $welcome}>
    <div class="xootagsMsg">
        <{$welcome}>
    </div>
<{/if}>

<{$xoopaginate->display()}>
<{foreach from=$tags item=item}>
    <div class="item">
        <div class="itemHead">
        </div>

        <div>
            <div class="itemTitle">
                <a href="<{$item.link}>" title="<{$item.title}>"><{$item.title}></a>
            </div>

            <div class="itemInfo">
                <div class="itemPoster">
                    <{$smarty.const._XOO_TAGS_AUTHOR}> : <a href="<{xoAppUrl 'userinfo.php'}>?uid=<{$item.uid}>" title="<{$item.uid_name}>">
                        <{$item.uid_name}>
                    </a>
                </div>
                <div class="itemDate">
                    <{$smarty.const._XOO_TAGS_PUBLISHED}>: <span><{$item.date}></span>
                </div>

                <{if $item.tags}>
                    <{include file='module:xootags/xootags_bar.tpl' tags=$item.tags}>
                <{/if}>

                <div class="itemModules">
                    <{$smarty.const._XOO_TAGS_TOPICS}>:&nbsp;
                    <{foreach from=$item.modules item=module name=foo}>
                    <a href="<{xoAppUrl '/modules/'}><{$module.dirname}>/" title="<{$module.name}>"><i class="xootags-ico-<{$module.dirname}>"></i><{$module.name}>
                        </a><{if !$smarty.foreach.foo.last}>,&nbsp;<{/if}>
                    <{/foreach}>
                </div>
            </div>

            <div class="clear"></div>

            <{if $item.content}>
                <div class="itemBody">
                    <{$item.content}>
                </div>
            <{/if}>

        </div>
    </div>
<{/foreach}>
<{$xoopaginate->display()}>
