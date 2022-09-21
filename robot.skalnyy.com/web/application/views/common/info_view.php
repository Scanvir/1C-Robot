    <style>
        .center {
            width: 90%;
            top: 20%;
            margin: auto;
            font-weight: 200!important;
            font-size: 2rem;
            text-align: center;

        }
        .red {
            color: red;
        }
        body {
            height: 100%;
        }
    </style>
</head>
<body class="h-vh-100">
    <?php
        if($data) {
            if (array_key_exists('error', $data)) {
                echo '<div class="center red">';
                echo $data['error'];
            } else if (array_key_exists('info', $data)) {
                echo '<div class="center">';
                echo $data['info'];
            } else {
                print_r($data['array']);
            }
        }
    ?><br>
    <button onclick="document.location='/'" class="button secondary outline rounded large">&nbsp;&nbsp;&nbsp;На головну&nbsp;&nbsp;&nbsp;</button>
    </div>