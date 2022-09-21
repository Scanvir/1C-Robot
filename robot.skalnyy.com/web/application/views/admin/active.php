<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<canvas id="myChart" width="100%" height="50"></canvas>
<script>
const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        <?php
            $labels = [];
            foreach ($data['new'] as $week){
                array_push($labels, $week['week']);
            }
            foreach ($data['active'] as $week){
                if(!in_array($week['week'], $labels))
                    array_push($labels, $week['week']);
            }
            sort($labels);
            $label = ''; $ndata =''; $adata = '';
            foreach ($labels as $row){
                $label .= $row.',';
                
                $key = array_search($row, array_column($data['new'], 'week'));
                if($key > -1)
                    $ndata .= $data['new'][$key]['count'].',';
                else
                    $ndata .= '0,';
                    
                $key = array_search($row, array_column($data['active'], 'week'));

                if($key > -1)
                    $adata .= $data['active'][$key]['count'].',';    
                else
                    $adata .= '0,';
                
            }
        ?>
        labels: [<?php echo $label; ?>],
        datasets: [{
            label: '# нових гостей',
            data: [<?php echo $ndata; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
            ],
            borderWidth: 1
        },{
            label: '# активних гостей',
            data: [<?php echo $adata; ?>],
            backgroundColor: [
                'rgba(128, 0, 128, 0.2)',
            ],
            borderColor: [
                'rgba(128, 0, 128, 1)',
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>