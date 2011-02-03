<table width="100%">
<? 
// Find out max pageviews it will be our MAX
$maxPageViews = 0;
$totalPageViews = 0;
$totalVisits = 0;
$totalTimeSpent = 0;
foreach ($data as $row) {
	$maxPageViews = $row->getMetric('ga:pageviews')->__toString() > $maxPageViews ? $row->getMetric('ga:pageviews')->__toString() : $maxPageViews;
	$totalPageViews += (int)$row->getMetric('ga:pageviews')->__toString();
	$totalVisits += (int)$row->getMetric('ga:visits')->__toString();
	$totalTimeSpent += (int)$row->getMetric('ga:timeOnSite')->__toString();
}

foreach ($data_last as $row) {
	$maxPageViews = $row->getMetric('ga:pageviews')->__toString() > $maxPageViews ? $row->getMetric('ga:pageviews')->__toString() : $maxPageViews;
	$totalPageViews += (int)$row->getMetric('ga:pageviews')->__toString();
	$totalVisits += (int)$row->getMetric('ga:visits')->__toString();
	$totalTimeSpent += (int)$row->getMetric('ga:timeOnSite')->__toString();
}
foreach ($data as $row) : ?>
<tr<?=in_array(date('N',mktime(0,0,0,date('m'),(string)$row->getValue('ga:day'),date('Y'))),array(6,7)) ? ' class="wk-d"' : ''?>>
	<td><?=$row->getValue('ga:day')?></td>
	<td width="100%">
	<table width="100%"">
		<tr>
			<td style="background-color:#E2E2E2" width="<?=(((int)$row->getMetric('ga:pageviews')->__toString())/$maxPageViews)*70?>%">
			<div style="background-color:#AAAAAA;width:<?=round(((int)$row->getMetric('ga:visits')->__toString()/(int)$row->getMetric('ga:pageviews')->__toString())*100)?>%">&nbsp;</div>
			</td>
			<td nowrap><?=$row->getMetric('ga:pageviews')?>/<?=$row->getMetric('ga:visits')?>, <?
	$seconds = $row->getMetric('ga:timeOnSite');	
	echo round(((int)$seconds->__toString())/3600);
	?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/view','hours');?>.</td>
		</tr>
	</table>
	</td>	
</tr>
<?php endforeach; 
foreach ($data_last as $row) : ?>
<tr<?=in_array(date('N',mktime(0,0,0,date('m')-1,(string)$row->getValue('ga:day'),date('Y'))),array(6,7)) ? ' class="wk-d"' : ''?>>
	<td><?=$row->getValue('ga:day')?></td>
	<td width="100%">
	<table width="100%"">
		<tr>
			<td style="background-color:#E2E2E2" width="<?=(((int)$row->getMetric('ga:pageviews')->__toString())/$maxPageViews)*70?>%">
			<div style="background-color:#AAAAAA;width:<?=round(((int)$row->getMetric('ga:visits')->__toString()/(int)$row->getMetric('ga:pageviews')->__toString())*100)?>%">&nbsp;</div>
			</td>
			<td nowrap><?=$row->getMetric('ga:pageviews')?>/<?=$row->getMetric('ga:visits')?>, <?
	$seconds = $row->getMetric('ga:timeOnSite');	
	echo round(((int)$seconds->__toString())/3600);
	?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/view','hours');?>.</td>
		</tr>
	</table>
	</td>	
</tr>
<?php endforeach; ?>
<tr>
	<td colspan="3"><strong><?=erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/view','Total pageviews');?> - <?=$totalPageViews;?>, <?=erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/view','Total visits');?> - <?=$totalVisits;?>, <?=erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/view','Time spent');?> - <?=round(((int)$totalTimeSpent)/3600);?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('statistic/view','hours');?>.</strong></td>
</tr>
</table>