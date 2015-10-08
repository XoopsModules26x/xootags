<{assign var=tags value=$block.tags}>
<{assign var=xootags_colors value=$block.colors}>

<script type="text/javascript" src="<{xoImgUrl modules/xootags/assets/js/jquery/jquery.cloud/jqcloud-1.0.2.js}>"></script>
<link rel="stylesheet" href="<{xoImgUrl modules/xootags/assets/js/jquery/jquery.cloud/jqcloud.css}>" type="text/css" media="screen"/>

<{include file='module:xootags/xootags_css.tpl' ags=$block.tags}>

<script type="text/javascript">
    var word_list = [
        <{foreach from=$tags item=tag name=foo}>
        {
            text: "<{$tag.tag_term_js}><{if $xootags_count}> (<{$tag.tag_count}>)<{/if}>",
            class: "tag<{$tag.tag_id}> <{cycle values="$xootags_colors"}>",
            weight: <{$tag.size}>,
            link: "<{xoAppUrl /modules/xootags/}>tag.php?tag_id=<{$tag.tag_id}>"
        },
        <{/foreach}>
    ];
    $(function () {
        $("#xootagsCloudBlock").jQCloud(word_list);
    });
</script>

<div id="xootagsCloudBlock"></div>
