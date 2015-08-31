<div class="centered">
    <h2>{$lang_forum.strForumActiveUsers}</h2>
    <p class="page_list">
    {$forum_userlinks}
    </p>
    <table class="content_table">
        <tr>
            <th>{$lang_forum.strForumActiveUsersName}</th>
            <th>{$lang_forum.strForumActiveUsersMessageCount}</th>
        </tr>
        {foreach from=$forum_userpages item=data}
        <tr class="{cycle values="tr_odd,tr_twin"} row">
            <td>{$data.name}</td>
            <td>{$data.message_count}</td>
        </tr>
        {foreachelse}
        <tr>
            <td colspan="2">Nincsenek hozzászólások</td>
        </tr>
        {/foreach}
    </table>
</div>