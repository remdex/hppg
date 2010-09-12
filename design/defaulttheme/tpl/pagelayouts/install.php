<!DOCTYPE html>

<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_head.tpl.php'));?>


<div id="container" class="no-left-column no-right-column">

	<div id="bodcont" class="float-break">			
		<div id="middcont">
			<div id="mainartcont">
			 <div style="padding:2px">
			<?					
			     echo $Result['content'];		
			?>			
			</div>
			</div>
		</div>		
	</div>
	
	
<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_footer.tpl.php'));?>

	
</div>

