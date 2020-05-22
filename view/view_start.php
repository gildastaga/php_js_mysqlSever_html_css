<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <script src="lib/jquery-3.4.1.min.js" type="text/javascript"></script>
        <script src="lib/Chart.js" type="text/javascript"></script>
        <script src="js/chart.js" type="text/javascript"></script>
        <script></script>

</head>
<body>
    <div class="bloc1">
        <div class="title"><a href="post/index"><img style="color: white;"src="lib/parsedown-1.7.3/back.png" width="30" height="20"  alt=""/></a>Stuck Overflow </div>
    </div>
    <div class="main">
        <div>
            <form>
                <p>period : the last</p>
                <input id="number" type="number">
                <select class="" id="period">
                    <option value="" selected="select">choisir la periode</option>
                    <option value="days">days</option>
                    <option value="week">week</option>
                    <option value="month">month</option>
                    <option value="year">year</option>
                </select>
                    
            </form>
        </div>
        
        <canvas id="myChart" width="400" height="400"></canvas>
    </div>
    
</body>

</html>
