<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Stuck Overflow </title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
         <div class="title">
             <form class="menus">Stuck Overflow</form>  
             <?php include('menu.html'); ?>
        </div>
         <div class="menus">
             <form class="menus">
                <a href="post/newest">Newest</a>
                <a href="vote/active"> Active</a>
                <a href="post/unanswered">Unanswered</a>
                <a href="vote/votes">Vote</a>
            </form> 
             <form class="recherche">
                 <input id="idsearch" type="search" name="" aria-label="search ">
                 <button>search</button>
             </form>
        </div>
    </body>
</html>
