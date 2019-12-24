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
         <div class="title">Stuck Overflow  
            <form class="menu">
                <a href="post/question">Questions</a>
               <a href="user/login">Log In</a>
               <a href="user/signup">Sign Up</a>

             <?php if (strlen($user->UserName) == 0): ?>
                <?php include('menu.html'); ?>
             <?php else: ?>
                 <?php include('menus.html'); ?>
             <?php endif; ?>
        </div>      
        <div class="main"> 
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
             <form id="post_form" action="post/index?param1=<?= $Title->UserName ?>" method="post">
                question :<br>
                <textarea id="Body" name="Body" rows='3'></textarea><br>
<!--               <input id="private" name="private" type="checkbox">Private message<br>-->
                <input id="post" type="submit" value="Post">
            </form>
           afficharge dsequestion !!!!!
             <?php foreach($post as $message): ?>
                    <?php if(( ($message->author == $user || $message->Title == $user)) ): ?>
                        <tr>
                            <td><?= $message->Timestamp ?></td>
                            <td><a href='user/UserName/<?= $message->author->UserName?>'><?= $message->author->UserName ?></a></td>
                            <td><?= $message->Body ?></td>
<!--                            <td><input type='checkbox' disabled <?= ($message->private ? ' checked' : '') ?>></td>-->
                            <td>
                                <?php if($user == $message->author || $user == $message->Title): ?>
                                    <form class='link' action='post/index?action=delete' method='post' >
                                    	<input type='text' name='param' value='<?= $message->postId ?>' hidden>
                                    	<input type='submit' value='erase'>
                                    </form>   
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif;?>
                <?php endforeach; ?>   
        </div>
    </body>
</html>
