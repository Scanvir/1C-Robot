<?php
print('Оновлено: '.$data['count'].' записів');

print("<table class=\"table striped compact row-hover\"><thead><tr><th>Код</th><th>Код</th><th>Назва</th></tr></thead><tbody>");
foreach ($data['catalog'] as $row){
    print("<tr>
        <td>".$row['Code']."</td>
        <td>".$row['ParentCode']."</td>
        <td>".$row['Name']."</td>
    </tr>");
}
print("</tbody></table>");