<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','Cache status');?></legend>



<table class="lentele" cellpadding="0" cellspacing="0">
    <tr>
        <th>Name</th>
        <th>Version</th>
        <th>Additional info</th>
    </tr>
    <tr>
        <td>Last hits cache version</td>
        <td><?=date('Y-m-d H:i:s',$last_hits_version)?> (<?=$last_hits_version?>)</td>
        <td>Expires every 10 minutes</td>
    </tr>
    <tr>
        <td>Most popular version</td>
        <td><?=date('Y-m-d H:i:s',$most_popular_version)?> (<?=$most_popular_version?>)</td>
        <td>Expires every 25 minutes</td>
    </tr>
    <tr>
        <td>Popular recent version</td>
        <td><?=date('Y-m-d H:i:s',$popularrecent_version)?> (<?=$popularrecent_version?>)</td>
        <td>Expires every 10 minutes</td>
    </tr>
    <tr>
        <td>Top rated version</td>
        <td><?=$top_rated?></td>
        <td></td>
    </tr>
    <tr>
        <td>Last commented</td>
        <td><?=$last_commented?></td>
        <td></td>
    </tr>            
</table>


</fieldset>