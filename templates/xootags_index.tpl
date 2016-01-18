<{include file='module:xootags/xootags_css.tpl'}>

<{if $moduletitle != ''}>
    <fieldset>
        <legend><{$moduletitle}></legend>
    </fieldset>
<{/if}>

<{if $welcome}>
    <div class="xootagsMsg">
        <{$welcome}>
    </div>
<{/if}>

<{$xoopaginate->display()}>
<{if $xootags_main_mode == "cloud"}>
    <{include file='module:xootags/xootags_cloud.tpl'}>
<{elseif $xootags_main_mode == "blog"}>
    <{include file='module:xootags/xootags_blog.tpl'}>
<{/if}>
<{$xoopaginate->display()}>
