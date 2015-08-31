<?xml version="1.0" encoding="iso-8859-1"?>
<area name="{$_module_name}" lang="{$_locale_id}">
{foreach from=$_expressions item=data}
    <variable name="{$data.variable_name}"><![CDATA[{$data.expression}]]></variable>
{/foreach}
</area>