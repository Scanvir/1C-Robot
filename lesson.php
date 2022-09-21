		<h1><?php echo $data[0]['Name']?></h1>
		<table class="table">
		<tbody>
		<tr>
			<td>Викладач</td>
			<td><?php echo $data[0]['Teacher']?></td>
		</tr>
		<tr>
			<td>Посилання на урок</td>
			<td><a href="<?php echo $data[0]['zoom']?>" target="_blank">Натисніть щоб відкрити урок</a></td>
		</tr>
		<tr>
			<td>Zoom ID</td>
			<td><?php echo $data[0]['zoomId']?></td>
		</tr>
		<tr>
			<td>Zoom password</td>
			<td><?php echo $data[0]['zoomPass']?></td>
		</tr>
		</tbody>
	</table>
	
	Повернутись до <a href="/home"><b>розкладу занять</b></a><br>
	
	<?php
	    if(array_key_exists('docs', $data[0])){ 
	        $docs = $data[0]['docs'];
	        foreach ($docs as $doc): ?>
	        
<br><button type="button" id="view-pdf-btn" onclick="previewFile()" style="cursor: pointer; padding: 10px; font-family: 'Arial'; font-size: 15px; color: #ededed; border-radius: 5px; border: 1px #404040; background-color:#1473e6;" disabled><?php echo $doc[1]; ?></button>
<script src="https://documentcloud.adobe.com/view-sdk/main.js"></script>
<script>
const viewerConfig = {
    embedMode: "LIGHT_BOX"
};

document.addEventListener("adobe_dc_view_sdk.ready", function () {
    document.getElementById("view-pdf-btn").disabled = false;
});

function previewFile()
{
    var adobeDCView = new AdobeDC.View({
        clientId: "e38ce3a38bc140d6a0404bef45f072f8"
    });

    adobeDCView.previewFile({
        content: {
            location: {
                url: "<?php echo $doc[0]; ?>",
            },
        },
        metaData: {
            fileName: "Bodea Brochure.pdf"
        }
    }, viewerConfig);
};
</script>
    <?php endforeach; } ?>