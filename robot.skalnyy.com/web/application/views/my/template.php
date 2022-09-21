<?php include 'application/views/layouts/header.php'; ?>
<?php include 'application/views/layouts/menu.php'; ?>
<?php 
    print('<div class="mt-16 mb-20">
    <div class="row">
        <div class="cell-sm-12 cell-md-3">
        ');
    
    include 'application/views/my/my_sidebar.php';
    
    print('         </div>
        <div class="cell-sm-12 cell-md-9">');
    
    
    if ($data['view'] == 'cart')
        include 'application/views/my/cart.php';
    else if ($data['view'] == 'reserve')
        include 'application/views/my/reserve.php';
    else if ($data['view'] == 'profile')
        include 'application/views/my/profile.php';
    else
        include 'application/views/my/index.php';
    print ('
        </div>
    </div>
</div>
');
    
include 'application/views/layouts/footer.php'; ?>