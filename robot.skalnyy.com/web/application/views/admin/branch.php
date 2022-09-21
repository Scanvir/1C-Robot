<?php
print('Оновлено: '.$data['count'].' записів');

print("<table class=\"table striped compact row-hover\"><thead><tr><th>Код</th><th>Аптека</th><th>Населений пункт</th><th>Розклад</th><th>Резерв</th></tr></thead><tbody>");
foreach ($data['branch'] as $row){
    $checked = '';
    if ($row['Reserve'] == 1) {
        $checked = 'checked';
    }
    print("<tr>
        <td>".$row['Code']."</td>
        <td>".$row['Name']."</td>
        <td>".$row['Position']."</td>
        <td>".$row['WorkTime']."</td>
        <td><input type=\"checkbox\" data-role=\"checkbox\" ".$checked." onclick='reserve(this, \"".$row['Code']."\");'></td>
    </tr>");
}
print("</tbody></table>");
?>
<script>
function reserve(cb, code) {
    console.log("Branch = " + code);
    console.log("Clicked, new value = " + cb.checked);
    
    httpRequest = new XMLHttpRequest();
    httpRequest.overrideMimeType('text/xml');

    httpRequest.open('GET', 'https://www.bilaromashka.com.ua/admin/branchReserve/?branch=' + code + '&reserve=' + cb.checked, true);
    httpRequest.setRequestHeader('Cache-Control', 'no-cache');
    httpRequest.send(null);
}
</script>