<div id="<?=$fileID?>" class="progressWrapper float-break">
	<div class="progressContainer" id="progressContainer<?=$fileID?>">
			<a style="visibility: visible;" href="#" class="progressCancel" id="cancelLink<?=$fileID?>"> </a>
			
			<div class="left-progorescolumng">
				<div class="progressName">
					<?=htmlspecialchars($fileName)?>
				</div>
				<div class="progressBarStatus" id="progresStatus<?=$fileID?>">
					<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/fileuploadcontainer','Waiting')?>...
				</div>
				<div class="progressBarInProgress" id="progressBarInProgress<?=$fileID?>">
					
				</div>
			</div>		
			
			<div class="right-progresbar">
				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/fileuploadcontainer','Enter new album name or choose public album')?></div>				
				<input type="text" id="PhotoTitle<?=$fileID?>" value="" class="inputfield" />
				<select id="AlbumIDToUploadArchive<?=$fileID?>">
					<option value="">--Choose--</option>
		 		  <?php foreach ($items = erLhcoreClassModelGalleryAlbum::getAlbumsByCategory(array('filter' => array('public' => 1),'offset' => 0, 'limit' => 100)) as $album) : ?>
		 		     <option value="<?=$album->aid?>"><?=$album->title?></option>
		 		  <?php endforeach;?> 		  
		 		</select>
 		  
				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/fileuploadcontainer','Keywords')?></div>	
				<input type="text" id="PhotoKeyword<?=$fileID?>" value="" class="inputfield" />	
				
				<div class="progressName"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/fileuploadcontainer','description')?></div>			
				<textarea class="default-textarea" id="PhotoDescription<?=$fileID?>"></textarea>						
			</div>
			
			
	</div>
</div>
