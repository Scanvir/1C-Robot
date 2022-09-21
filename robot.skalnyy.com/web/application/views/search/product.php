<div class="row">
<?php
    $photo = Product::getPhoto($data['product']['Code'])['url'];
?>
<div class="cell-sm-full cell-md-5 cell-lg-6">
    <p class="text-center display1"><?php echo $data['product']['Name']; ?></p>
    <div class="d-flex flex-justify-center"><img width="40%" src="<?php echo $photo; ?>"></div>
</div>
<div class="cell-sm-full cell-md-7 cell-lg-6 p-5">

<table class="table subcompact striped row-hover table-border cell-border mt-4" >
    <thead><tr><th>Аптека</th><th>Ціна</th><th>Резерв</th><th>Режим роботи</th></tr></thead><tbody>
    <?php 
        $rests = $data['result'];
        //print_r($rests);
        $key = 0;
        foreach ($rests as $rest):
            $branch = Branch::getBranchByCode($rest['Branch']);
            if ($branch['Reserve'] == 1)
                $reserve = '<a href="/cart/addToCart/?branch='.$branch['Code'].'&product='.$data['product']['Code'].'">У кошик</a>';
            else
                $reserve = '';
            
            if ($rest['minPrice']==$rest['maxPrice'])
                $price = "".number_format($rest['maxPrice'], 2, '.', ' ');
            else
                $price = number_format($rest['minPrice'], 2, '.', ' ') . "-" . number_format($rest['maxPrice'], 2, '.', ' ');
            
            
            print("<tr>
            <td>" . $branch['Name'] . "</td>
            <td class=\"text-center\"><p>" . $price . "</p></td>
            <td class=\"text-center\">".$reserve."</td>
            <td class=\"text-center\">" . $branch['WorkTime'] . "</td></tr>");
        ?>
    <?php endforeach; ?>
</tbody>
</table>
</div>
<div class="m-5" id="instruction"></div>
<script>
window.onload = function(){  
    console.log("Product = <?php echo $data['product']['Code']; ?>");
    
    var httpRequest = false;
    if (window.XMLHttpRequest) { // Mozilla, Safari, ...
        httpRequest = new XMLHttpRequest();
        if (httpRequest.overrideMimeType) {
            httpRequest.overrideMimeType('text/html');
        }
    } else if (window.ActiveXObject) { // IE
        try {
            httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}
        }
    }
    
    if (!httpRequest) {
        alert('Не вийшло :( Неможливо створити екземпляр класа XMLHTTP');
        return false;
    }
    
    httpRequest.onreadystatechange = function() { alertContents(httpRequest); };

    httpRequest.open('GET', 'https://www.bilaromashka.com.ua/search/instruction/?Code=<?php echo $data['product']['Code']; ?>', true);
    httpRequest.send(null);
}

function alertContents(httpRequest) {
    if (httpRequest.readyState == 4) {
        if (httpRequest.status == 200) {
            document.getElementById('instruction').innerHTML += httpRequest.responseText;
        } else {
            console.log('С запросом возникла проблема.');
        }
    }
}
</script>


