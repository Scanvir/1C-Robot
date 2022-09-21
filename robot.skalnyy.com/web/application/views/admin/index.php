
                <div class="cell-8">
                            
                <?php 
                    if ($data){
                        if (array_key_exists('view', $data))
                            include 'application/views/admin/'.$data['view'].'.php';
                        else
                            include 'application/views/admin/bred.php';
                    }
                ?>
                </div>
            </div>
        </div>
    </div>
    </section>
</div>