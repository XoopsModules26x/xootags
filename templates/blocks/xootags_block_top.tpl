<link rel="stylesheet" href="<{xoImgUrl 'modules/xootags/assets/js/jquery/jquery.cloud/jqcloud.css'}>" type="text/css" media="screen"/>
<{assign var=tags value=$block.tags}>
<{assign var=xootags_colors value=$block.colors}>

<{foreach from=$tags item=tag name=foo}>
    <span style="line-height:<{$block.lineheight}>%;">
        <a href="<{xoAppUrl '/modules/xootags/'}>tag.php?tag_id=<{$tag.tag_id}>" title="<{$tag.tag_term}>"><span class="tag<{$tag.tag_id}> <{cycle values="$xootags_colors"}>" style="font-size:<{$tag.font}>%;"><{$tag.tag_term}></span></a>
        <{if !$smarty.foreach.foo.last}>,&nbsp;<{/if}>
    </span>
<{/foreach}>
