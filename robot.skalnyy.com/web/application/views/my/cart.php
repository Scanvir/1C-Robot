<h3>Кошик</h3>
<ul id="paintings"
    data-role="list"
    data-cls-list="unstyled-list row flex-justify-center mt-4"
    data-cls-list-item="cell-sm-6 cell-md-6"
>

<?php
    if($data['view'] == 'cart')
        foreach ($data['cart'] as $key1 => $mainRow){
            $branch = Branch::GetBranchByCode($key1);
            print('<li>
        <figure class="text-center">
            <div>
                <h4>'.$branch['Name'].'</h4>
            </div>
            <div>
            <table class="table table-border">');
            foreach ($mainRow as $key2 => $row){
                $product = Product::getProductByCode($key2);
                $rest = Product::getProductRest($key2, $key1);
                
                $price = $rest['price'];
                $qty = (float)$row;
                $sum = $qty * $price;
                
                //print_r($rest);
                
                $cel = floor($qty);
                $del = (float)$product['Delivery'];
                
                
print('<tr><td>'.$product['Name'].'</td><td><div class="place-right">
<div class="text-right">Цілих упаковок
<nobr><button class="button info mini" onClick="addWhole(\''.$key1.'\', \''.$key2.'\', '.$del.', '.$price.')">+</button><nobr id="whole_'.$key1.'_'.$key2.'"> '.$cel.' </nobr>
<button class="button info mini" onClick="subWhole(\''.$key1.'\', \''.$key2.'\', '.$del.', '.$price.')">-</button>
</div></nobr>');
if ($product['Delivery'] > 0) 
print('<div class="text-right">1/'.(1/$del).' упаковки
<nobr><button class="button info mini" onClick="addFract(\''.$key1.'\', \''.$key2.'\', '.$del.', '.$price.')">+</button><nobr id="fract_'.$key1.'_'.$key2.'"> '.($qty - floor($qty)) / $del.' </nobr>
<button class="button info mini" onClick="subFract(\''.$key1.'\', \''.$key2.'\', '.$del.', '.$price.')">-</button></nobr>
</div>');
print('<div class="text-right"><strong id="sum_'.$key1.'_'.$key2.'">Сума: '.$sum.'</strong></div>
</td></tr>');
            }
            
            print('</table>
            <button class="button alert" onclick="window.location.href=\'/cart/clearCart/?branch='.$key1.'\'">Видалити</button>
            <button class="button success" onclick="window.location.href=\'/cart/reserveCart/?branch='.$key1.'\'" >Підтвердити</button>
            </div>
        </figure>
    </li>');
        }
?>
</ul>
<p class="remark info">Карта Власний рахунок надає можливість отримати знижку в замовленні, будь ласка вкажіть номер карти Власного рахунку у своєму <a href="/my/profile">профілі</a></p>
<p class="remark success">Після підтвердження резерва, товар буде очікувати вас на аптеці протягом 24 годин</p>
<p class="remark success">Забрати його можна вказавши номер резерва який ви отримаєте після підтвердження</p>
<p class="remark success">Також ви можете продовжити пошук та резервувати декілька товарів одним замовленням</p>
<p class="text-center">&nbsp;</p>
<script>

function updateRest(data, key1, key2, del, price) {
    console.log(data);
    sum = (price * data).toFixed(2);
    whole = Math.trunc(data);
    fract = Math.round((data - whole) / del);
    document.getElementById("sum_" + key1 + "_" + key2).innerHTML = 'Сума: ' + sum;
    document.getElementById("whole_" + key1 + "_" + key2).innerHTML = ' ' + whole;
    document.getElementById("fract_" + key1 + "_" + key2).innerHTML = ' ' + fract;
}
async function addWhole(key1, key2, del, price) {
    const source = await fetch(`https://www.bilaromashka.com.ua/cart/plusCart/?branch=${key1}&product=${key2}`);
    const data = await source.json();
    updateRest(data, key1, key2, del, price);
}
async function subWhole(key1, key2, del, price) {
    const source = await fetch(`https://www.bilaromashka.com.ua/cart/minusCart/?branch=${key1}&product=${key2}`);
    const data = await source.json();
    updateRest(data, key1, key2, del, price);
}
async function addFract(key1, key2, del, price) {
    const source = await fetch(`https://www.bilaromashka.com.ua/cart/plusCartdel/?branch=${key1}&product=${key2}`);
    const data = await source.json();
    updateRest(data, key1, key2, del, price);
}
async function subFract(key1, key2, del, price) {
    const source = await fetch(`https://www.bilaromashka.com.ua/cart/minusCartdel/?branch=${key1}&product=${key2}`);
    const data = await source.json();
    updateRest(data, key1, key2, del, price);
}
</script>