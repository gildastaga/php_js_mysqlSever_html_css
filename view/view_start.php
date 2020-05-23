<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <script src="lib/jquery-3.4.1.min.js" type="text/javascript"></script>
        <script src="lib/Chart.js" type="text/javascript"></script>
<!--        <script src="js/chart.js" type="text/javascript"></script>-->
        <script>
            $(function () {
                init();
                
                $("#number").change(function () {
                    console.log($("#number").val() + " " + $("#period").val())
                    $.post("user/starts",{numbre: $("#number").val(), periode: $("#period").val()}, function (data) {
                        var tab = jQuery.parseJSON(data);
                        getStats(tab);
                    });
                });
                
                $("#period").click(function () {
                    console.log($("#period").val())
                    $.post("user/starts",{numbre: $("#number").val(), periode: $("#period").val()}, function (data) {
                        var tab = jQuery.parseJSON(data);
                        getStats(tab);
                    });
                });
            });            
               
            
            function init() {
                $.get("user/starts", function (data) {
                    var tab = jQuery.parseJSON(data);
                    getStats(tab);
                });
            }

            function getStats(data) {
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(u => u.UserName),
                        datasets: [{
                                label: '# of Votes',
                                data: data.map(u => parseInt(u.activity)),

                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                                ],
                                borderWidth: 1
                            }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                        }
                    }
                });
            }

//            function recupSelection(src, dest)
//            {
//                var valeur = src.options[src.selectedIndex].value;
//                console.log(valeur);
//                if (valeur = '')
//                    return;
//
//                dest.value += src.options[src.selectedIndex].value + 'n';
//                src.selectedIndex = 0;
//            }
//            ;
        </script>

    </head>
    <body>
        <div class="bloc1">
            <div class="title"><a href="post/index"><img style="color: white;"src="lib/parsedown-1.7.3/back.png" width="30" height="20"  alt=""/></a>Stuck Overflow </div>
            <div>
                <form class="menu">
                    <?php if (!$user): ?>
                        <?php include('menu.html'); ?>
                    <?php else: ?>
                        <?php include('menus.html'); ?>
                    <?php endif; ?>
                </form>   
            </div>
        </div>
        <div class="main">
            <div class="star">
                <form id="nbrperide" method="post">
                    <p>period : the last</p>

                    <input id="number" min="0" value="1"  max="100" type="number" name="numb"  >

                    <select class="" name="test" id="period"  >
                        <option value="days" selected="selected">days</option>
                        <option value="week">week</option>
                        <option value="month">month</option>
                        <option value="year">year</option>
                    </select>

                </form>
            </div>

            <canvas id="myChart" width="200" height="200"></canvas>
        </div>

    </body>

</html>
