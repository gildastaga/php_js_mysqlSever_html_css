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
              //  $.('# of Votes').hide();
                init();
                //pour recuperer les nombres de weeck,month,year
                $("#number").change(function () {console.log($("#number").val(),$("#period").val());
                    let chart=myChart;
                    $.post("user/starts", {numbre: $("#number").val(), periode: $("#period").val()}, function (data) {
                        var tab = jQuery.parseJSON(data);
                        removeData(chart);
                        for(var i = 0; i < tab.length; ++i) {
                            addData(chart, tab[i].UserName, tab[i].activity);
                        }console.log(tab);
                        getStats(tab);
                        
                    });
                });
               //pour recuperer  weeck,month,year
                $("#period").click(function () {console.log($("#number").val(),$("#period").val());
                    let chart=myChart;
                    $.post("user/starts", {numbre: $("#number").val(), periode: $("#period").val()}, function (data) {
                        var tab = jQuery.parseJSON(data);
                        removeData(chart);
                        for(var i = 0; i < tab.length; ++i) {
                            addData(chart, tab[i].UserName, tab[i].activity);
                        }console.log(tab);
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
            // suprimmer les enciens donne du graphe 
            function removeData(chart) {
                for(var i = 0 ; i < chart.data.labels.length ; i++) {
                    chart.data.labels.pop();
                    chart.data.datasets.forEach((dataset) => {
                        dataset.data.pop();
                    });
                }
                
                chart.update();
            }
            //ajouter des nouvelles donnes
            function addData(chart, label, data) {
                chart.data.labels.push(label);
                chart.data.datasets.forEach((dataset) => {
                    dataset.data.push(data);
                });
                chart.update();
            }
            var myChart;
            function getStats(data) {
                var ctx = document.getElementById('myChart').getContext('2d');
                 myChart = new Chart(ctx, {
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
                        },
                        onClick: function (evt, array) {
                            donnee = array[0];
                            if (typeof donnee == 'undefined')
                                return;
                            let UserName = this.data.labels[donnee._index];
                            let nbre = this.data.datasets[0].data[donnee._index];
                            actbyuser(UserName);
                        },
                        events:['click']
                    }
                });
            }
            //recuper les activite d'un user sur une  dure precise 
            function actbyuser(UserName){
                $.post("user/getActivityByUser", {numbre: $("#number").val(), periode: $("#period").val(),UserName :UserName}, function (data) {
                    var tab =jQuery.parseJSON(data);
                    displayTable(tab,UserName);
                    });
            }
            //dessiner le tableau   des activites d'un user
            function displayTable(tab,UserName){
                tblMessages = $('#message_list');
                tblMessages.html("<tr><td>Loading...</td></tr>");
                var u=UserName;
                var html ="<p>Detailed Activity for "+u+"</p>";
                    html +="<tr><th id='moment' onclick='sort(\"moment\");'>moment</th>" ;
                    html +="<th id='type' onclick='sort(\"type\");'>type</th>";
                    html +="<th id='Title' onclick='sort(\"Title\")';>Title</th></tr>";
                    for (var m of tab) {
                        html += "<tr>";
                        html += "<td>" + m.moment + "</td>";
                        html += "<td>"+m.type+"</td>";
                        html += "<td>" + m.Title + "</td>";
                        html += "</tr>";
                    }
                    tblMessages.html(html);
            }

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
            <div style=" width:40%; height:40%;margin-left: 25%;">
                <canvas id="myChart" width="200" height="200" ></canvas>
            </div><br><br><br>
            <div  class="message_list" style=" width:15%; height:20%;margin-left: 25%;">
                <table id="message_list">
                    
                </table>
            </div>
        </div>
    </body>
</html>
