{* HA NEM A TARTALOMHOZ VAN BEEPITVE *}
{if empty($divFormCnt)}
    {assign var=divFormCnt value=$form_id}
{/if}
{* HA NEM A TARTALOMHOZ VAN BEEPITVE VEGE *}

{if !$form_success_msg && !$form_back_link}
    <div id="forms_{$divFormCnt}">
    <form {$forms.$divFormCnt.form_id.attributes}>

    {$forms.$divFormCnt.form_id.hidden}
    {foreach item=sec key=i from=$forms.$divFormCnt.form_id.sections}
        <h1>{$forms.$divFormCnt.title}</h1>
        <p>{$forms.$divFormCnt.lead}</p><div class="lezerkard"></div>
        {foreach item=element from=$sec.elements}
        	{if $element.type eq "submit"}
        		{if not $form.frozen}
        			{if not $form.frozen}
        				<br /><center>{$element.html}</center>
        			{/if}
        		{/if}
        	{elseif $element.type != "reset"}
        		<div>
        			<strong>{$element.label}</strong>{if $element.required}<span style="color: #f00; font-size: 0.8em;">*</span>{/if}<br />
        			{if $element.error}<span style="color: #f00;">{$element.error}</span><br />{/if}
        			{if $element.type eq "group"}
        				{foreach key=gkey item=gitem from=$element.elements}
        					{$gitem.label}
        					{$gitem.html}{if $gitem.required}<font color="red">*</font>{/if}
        					{if $element.separator}{cycle values=$element.separator}{/if}
        				{/foreach}
        			{else}
        				{$element.html}
        			{/if}
        		</div>
        	{/if}
        {/foreach}
    {/foreach}

    {if $forms.$divFormCnt.form_id.requirednote and not $forms.$divFormCnt.form_id.frozen}
    	<br /><br />{$forms.$divFormCnt.form_id.requirednote}
    {/if}

    </form>
    </div>
{else}
    <p>
      {$form_success_msg|nl2br}
      <br /><a style="font-size: 1em;" href="index.php?{$form_back_link}" title="{$locale.index_forms.field_back}">{$locale.index_forms.field_back} ...</a>
    </p>
{/if}