<h3>Мої замовлення</h3>
<table class="table subcompact row-hover">
    <tbody>
<?php
    foreach ($data['reserve'] as $key1 => $reserve) {
        $branch = Branch::GetBranchByCode($reserve['branch']);
        $cansel = ' (<a href="/cart/canselReserve/?id='.$reserve['id'].'">Скасувати</a>)';
        if ($reserve['status_id'] == 4) {
            $cansel = '';
            $color = ' class="bg-green"';
        } else if ($reserve['status_id'] == 3) {
            $cansel = '';
            $color = ' class="bg-red"';
        } else if ($reserve['status_id'] == 2)
            $color = ' class="bg-lightYellow"';
        else if ($reserve['status_id'] == 1)
            $color = ' class="bg-lightBlue"';
        else
            $color = '';
        
        print_r('<tr'.$color.'>
        <td>'.$branch['Name'].'</td>
        <td>F-'.$reserve['id'].$cansel.'</td>
        <td><table class="table">
            <tbody>');
        $allSum = 0;
        foreach ($reserve['tab'] as $key2 => $tab) {
            $product = Product::GetProductByCode($tab['product']);
            $price = $tab['priceApprove'];
            $sum = $tab['qtyApprove'] * $price;
            
            $allSum += $sum;
            print('<tr>
                <td>'.$product['Name'].'</td>
                <td>'.(float)$tab['qty'].'</td>
                <td>'.((float)$tab['qtyApprove'] == 0 ? '' : (float)$tab['qtyApprove']).'</td>
                <td>'.($price == 0 ? '' : 'x '.$price).'</td>
                <td>'.($sum == 0 ? '' : '= '.$sum).'</td>
            </tr>');
        }
            
        print_r('</tbody>
        </table></td>
        <td>'.($allSum == 0 ? '': $allSum).'</td>
        <td>'.$reserve['status'].'</td>
    </tr>');
        if ($reserve['description'] != ''){
            print('<tr><td colspan=5 '.$color.'><p class="text-center"><strong>'.$reserve['description'].'</strong></p></td></tr>');
        }
    }
?>