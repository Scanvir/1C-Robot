<div class="row">
    <div class="col-10 offset-1">
        <p class="text-center remark success">Оберіть необхідний товар та дізнаєтесь наявність та ціни в аптеках</p>
    </div>
</div>
<div class="row ">
<?php
    if(array_key_exists('result', $data)){ ?>
    
<div class="col-12">
    <ul id="paintings"
        data-role="list"
        data-sort-class="painting-price"
        data-sort-dir="desc"
        data-cls-list="unstyled-list row flex-justify-center"
        data-cls-list-item="cell-fs-6 cell-sm-5 cell-md-3 cell-lg-3 cell-xl-2"
        data-show-search="false">
    <?php
        $latestProducts = $data['result'];
        foreach ($latestProducts as $product): 
            $branch = Product::getProductByCode($product['Code']);
            $photo = Product::getPhoto($product['Code'])['url'];
    ?>
        <li>
            <div class="border bd-indigo p-1 h-100">
                <figure class="text-center">
                            <a href="/search/product/?code=<?php echo $branch['Code'];?>"><?php echo $branch['Name']; ?></a>
                </figure>
                <div class="text-center">
                    <a href="/search/product/?code=<?php echo $branch['Code'];?>">
                        <img width="50%" src="<?php echo $photo; ?>">
                    </a>
                    <figcaption class="painting-author text-bold"><?php echo $branch['Producer']; ?></figcaption>
                </div>
            </div>
        </li>
    <?php 
        endforeach;
    }
?>
    </ul>
</div>