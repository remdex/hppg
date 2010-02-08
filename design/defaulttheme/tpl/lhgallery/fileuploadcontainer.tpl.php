<? if ($supported == true) : ?>

<div id="<?=$fileID?>" class="progressWrapper float-break">
	<div class="progressContainer" id="progressContainer<?=$fileID?>">
			<a style="visibility: visible;" href="#" class="progressCancel" id="cancelLink<?=$fileID?>"> </a>
			
			<div class="left-progorescolumng">
				<div class="progressName">
					<?=htmlspecialchars($fileName)?>
				</div>
				<div class="progressBarStatus" id="progresStatus<?=$fileID?>">
					<?=erTranslationClassLhTranslation::getInstance()->getTranslation('fc/fileuploadcontainer','Waiting')?>...
				</div>
				<div class="progressBarInProgress" id="progressBarInProgress<?=$fileID?>">
					
				</div>
			</div>		
			
			<div class="right-progresbar">
				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('fc/fileuploadcontainer','Photo title')?></div>				
				<input type="text" id="PhotoTitle<?=$fileID?>" value="" class="inputfield" />

				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('fc/fileuploadcontainer','Photo keywords')?></div>	
				<input type="text" id="PhotoKeyword<?=$fileID?>" value="" class="inputfield" />	
				
				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('fc/fileuploadcontainer','Caption')?></div>			
				<textarea class="default-textarea" id="PhotoDescription<?=$fileID?>"></textarea>						
			</div>
			
			
	</div>
</div>
<? else : ?>

<div id="<?=$fileID?>" class="progressWrapper float-break">
	<div class="progressContainer" id="progressContainer<?=$fileID?>">						
			<div class="left-progorescolumng">
				<div class="progressName">
					<?=$fileName?>
				</div>
				<div class="progressBarStatus" id="progresStatus<?=$fileID?>">
					<?=erTranslationClassLhTranslation::getInstance()->getTranslation('fc/fileuploadcontainer','Waiting')?>...
				</div>
				<div class="progressBarInProgress" id="progressBarInProgress<?=$fileID?>">
					
				</div>
			</div>
			
			<div class="right-progresbar">
				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('fc/fileuploadcontainer','Sutch file already exists')?>...</div>				
			</div>
	</div>
</div>

<? endif; ?>