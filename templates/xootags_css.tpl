<style type="text/css" media="screen,print">
    <{foreach from=$tags item=tag name=foo}>
    .tag <{$tag.tag_id}> {
        font-size: <{$tag.font}>%;
        line-height: auto;
    }

    <{foreach from=$tag.modules item=module}>
    .xootags-ico- <{$module.dirname}> {
        background-image: url('<{$module.image}>');
    }

    <{/foreach}>

    <{/foreach}>
</style>
