<?php
                        
                        if (array_key_exists('search', $data)) 
                            include 'application/views/admin/search.php';
                        if (array_key_exists('noActiveUsers', $data)) 
                            print_r($data['noActiveUsers']);
                        if (array_key_exists('count', $data)) 
                            print('Оновлено: '.$data['count'].' записів');
                        if (array_key_exists('users', $data)){
                        } else if (array_key_exists('catalog', $data)){
                            
                        } else if (array_key_exists('branch', $data)){
                            ?>

                <?php   } else if (array_key_exists('instr', $data)){
                            print_r($data['instr']);