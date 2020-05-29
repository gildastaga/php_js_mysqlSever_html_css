<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Stuck Overflow </title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <script src="lib/jquery-3.4.1.min.js" type="text/javascript"></script>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.structure.min.css" rel="stylesheet" type="text/css"/>
        <script src="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.js" type="text/javascript"></script>
        <script>
            var Timestamp = 'datetime';
            var PostId = "<?= $posts->PostId ?>";
            var UserId = "<?= $user->UserId ?>";
            console.log(PostId);
            $(function () {
                $("#pagi").hide();
                $('#post'+'*').attr("disabled", true);
                $("#Body"+'*').on("input", function () {
                    $('#post').attr("disabled", $(this).val().length === 0);
                });

                $("#comment_form"+'*').hide();
                $("#enablecomment"+'*').click(function () {
                    $("#comment_form"+'*').toggle("fast", function () {
                        if ($("#comment_form"+'*').is(":visible")) {
                            $("#enablecomment"+'*').html("Click here to hide the new message form.");
                            $("#Body"+'*').focus();
                        } else {
                            $("#enablecomment"+'*').html("Click here to leave a message.");
                        }
                    });
                });

                tblMessages = $('#comment');
                tblMessages.html("<tr><td>Loading...</td></tr>");
                getMessages()
                postButton = $('#post');
                postButton.attr("type", "button");
                postButton.click(postMessage());

            });
            //save comment
            function postMessage() {
//                var newMsg = {UserId: UserId,
//                    PostId: PostId,
//                    Body: $("#Body").val(),
//                    datetime: Timestamp,
//                    id: -1
//                };
//                messages.push(newMsg);
                $("#comment_form").submit(function (e) {
                    e.preventDefault(); //empêcher une action par défaut
                    //var form_url = $(this).attr("action"); //récupérer l'URL du formulaire
                    var form_method = $(this).attr("method"); //récupérer la méthode GET/POST du formulaire
                    var form_data = $(this).serialize(); //Encoder les éléments du formulaire pour la soumission
                    
                    $.post('comment/add_comment_service/',{PostId,Body :$("#Body").val()},function (data) {
                        var tab = jQuery.parseJSON(data);
                        getMessages(); 
                    });
                });
            }   
            
            //recuper les commentaire 
            function getMessages(){ 
                $.post("comment/get_visible_comment_service/", {PostId}, function (data) {
                    var tab =jQuery.parseJSON(data);
                    tabComment(tab);
                });
            }
//            function sortMessages() {
//                messages.sort(function (a, b) {
//                    if (a[sortColumn] < b[sortColumn])
//                        return sortAscending ? -1 : 1;
//                    if (a[sortColumn] > b[sortColumn])
//                        return sortAscending ? 1 : -1;
//                    return 0;
//                });
//            }
//            function sort(field) {
//                if (field === sortColumn)
//                    sortAscending = !sortAscending;
//                else {
//                    sortColumn = field;
//                    sortAscending = true;
//                }
//                sortMessages();
//                displayTable();
//            }
    function tabComment(datas) {
        $("#comment").html("");
        var table = "";
            for(var index = 0; index < datas.comment.length; index++) {
                table += "<tr>";
                table += '&nbsp  ' + datas.comment[index].markdown; 
                table += '<br>&nbsp &nbsp asked <span>'+datas.comment[index].temp_ago +'</span>';
                table += '&nbsp by '+datas.comment[index].name+')('+ datas.comment[index].nbr_vote + 'vote(s) &nbsp, ' +datas.comment[index].count_Answer+' Answer (s)) &nbsp';
                table += '</tr><br><br>';
            }
        $("#comment").append(table);    
        }

        </script>
    </head>
    <body>
        <div class="bloc1">
            <div class="title"> <a href="post/index"><img src="lib/parsedown-1.7.3/back.png" width="30" height="20"  alt=""/></a>Stuck Overflow </div>
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
        <br>
        <div class="main">         
            <table id="message_list" class="message_list">

                <?php if ($user): ?>            
                    <?php echo $posts->Title; ?><br><br>
                    <tr>
                        Post by  <?php echo $user->get_user_by_UserId($posts->AuthorId)->UserName; ?>
                        <?php if ($user->UserId == $posts->AuthorId || $user->Role == "admin"): ?>
                        <a href="post/edit/<?php echo $posts->PostId; ?>"><img src="lib/parsedown-1.7.3/edit.png" width="30" height="20"  alt=""/></a>
                        <a href="post/delete_confirm/<?php echo $posts->PostId; ?>"><img src="lib/parsedown-1.7.3/delete.png" width="30" height="20"  alt=""/></a><br>
                    <?php endif; ?><br>
                    <?php foreach ($tag as $row): ?>
                        <?php if ($user->UserId == $posts->AuthorId || $user->Role == "admin"): ?>
                            <a href="tag/delete_tag/<?php echo $row->TagId; ?>/<?php echo $posts->PostId; ?>"><img src="lib/parsedown-1.7.3/croix.png" width="15" height="10"  alt=""/></a>
                        <?php endif; ?>    
                        <a href="post/by_tag/<?php echo $row->TagId; ?>"><?= $row->TagName . ' '; ?></a>
                    <?php endforeach; ?>
                    <?php if ($user->UserId == $posts->AuthorId || $user->Role == "admin"): ?>    
                        <form action="tag/asso_tag_post/<?php echo $posts->PostId; ?>" method="post">
                            <select name="TagId">
                                <?php foreach ($tags as $rows): ?>
                                    <option value=<?php echo $rows->TagId; ?> ><?php echo $rows->TagName; ?></option>
                                <?php endforeach; ?> 
                            </select>        
                            <input  type="image" img src="lib/parsedown-1.7.3/plus.png" width="30" height="20"  alt="">
                        </form>   
                    <?php endif; ?>
                    </tr> 
                <?php endif; ?><br><br>
                <tr>
                    <td>
                        <table> 
                            <tr>
                                <?php if ($user): ?>
                                    <td>
                                        <a href="vote/add_vote/<?php echo $posts->PostId; ?>/<?php echo TRUE; ?>"><img 
                                            <?php if (Vote::get_vote($posts->PostId, $user->UserId) && Vote::get_vote($posts->PostId, $user->UserId)->UpDown == 1): ?>                  
                                                    style=" -webkit-filter: hue-rotate(90deg);
                                                    filter: hue-rotate(90deg);"
                                                <?php else: ?>
                                                    style="-webkit-filter: grayscale(1);
                                                    filter: grayscale(1);"
                                                <?php endif; ?>
                                                src="lib/parsedown-1.7.3/vote1.png" width="30" height="20"  alt=""/></a><br>
                                        <?php echo Post::nbr_vote($posts->PostId); ?> score(s)<br>
                                        <a href="vote/add_vote/<?php echo $posts->PostId; ?>"><img
                                            <?php if (Vote::get_vote($posts->PostId, $user->UserId) && Vote::get_vote($posts->PostId, $user->UserId)->UpDown == -1): ?> 
                                                    style=" -webkit-filter: hue-rotate(90deg);
                                                    filter: hue-rotate(90deg);"
                                                <?php else: ?>
                                                    style="   -webkit-filter: grayscale(1);
                                                    filter: grayscale(1);"
                                                <?php endif; ?>
                                                src="lib/parsedown-1.7.3/vote2.png" width="30" height="20" alt=""/></a><br>
                                    </td>
                                <?php endif; ?>              
                            <h6> body post </h6>   
                            <td>
                                <h3>comment by post</h3><br><br>
                                <table id="comment_0">
                                <?php foreach ($comment as $values): ?>
                                    <table>
                                        <tr>
                                        <li> <?php echo $values->Body; ?></li>
                                        <br>&nbsp &nbsp asked <span><?php echo $values->temp_ago()[0]; ?></span> 
                                        &nbsp by <?php echo $values->name(); ?> &nbsp &nbsp
                                        <?php if ($user): ?>
                                            <?php if ($user->Role == "admin" || $values->UserId == $user->UserId): ?>
                                                <a href="comment/edit_comment/<?php echo $values->CommentId; ?>">
                                                    <input id="post" type="image" img src="lib/parsedown-1.7.3/edit.png" width="30" height="20"alt=""> </a>            
                                                <a href="comment/delete_comment/<?php echo $values->CommentId; ?>">
                                                    <img src="lib/parsedown-1.7.3/delete.png" width="30" height="20"  alt=""/></a><br>
                                            <?php endif; ?>
                                            <?php endif; ?><br> 
                                        </tr> </table>    
                                <?php endforeach; ?><br>
                                 </table>
                                <table id="comment">
                                    
                                </table>
                                <?php if ($user): ?>
                                    <!--                                    Add your Comment<br>-->
                                    <div id="enablecomment"> Add a Comment on the post.</div>
                                    <form id="comment_form" action="comment/add_comment/<?php echo $posts->PostId; ?>" method="post">                 
                                        <textarea id="Body" name="Body" rows='2'></textarea><br><br>
                                        <input id="post" type="submit" value="Comment">
                                    </form>
                                <?php endif; ?><br><br>

                            <li><?php echo $posts->markdown(); ?></li> <br>
                            </td>
                            </tr>
                        </table><br><br>

                        <h6> Post response's list</h6> <br><br>
                        <?php foreach ($listanswer as $reponse): ?>
                        
                            <table> 
                                <tr>
                                    <?php if ($user): ?>
                                        <td>
                                            <a href="vote/add_vote/<?php echo $reponse->PostId; ?>/<?php echo TRUE; ?>"><img 
                                                <?php if (Vote::get_vote($reponse->PostId, $user->UserId) && Vote::get_vote($reponse->PostId, $user->UserId)->UpDown == 1): ?>                   
                                                        style=" -webkit-filter: hue-rotate(90deg);
                                                        filter: hue-rotate(90deg);"
                                                    <?php else: ?>
                                                        style="-webkit-filter: grayscale(1);
                                                        filter: grayscale(1);"
                                                    <?php endif; ?>
                                                    src="lib/parsedown-1.7.3/vote1.png"  width="30" height="20"  alt=""/></a><br>
                                            <?php echo Post::nbr_vote($reponse->PostId); ?> score(s)<br>
                                            <a href="vote/add_vote/<?php echo $reponse->PostId; ?>"><img 
                                                <?php if (Vote::get_vote($reponse->PostId, $user->UserId) && Vote::get_vote($reponse->PostId, $user->UserId)->UpDown == -1): ?>                   
                                                        style=" -webkit-filter: hue-rotate(90deg);
                                                        filter: hue-rotate(90deg);"
                                                    <?php else: ?>
                                                        style="-webkit-filter: grayscale(1);
                                                        filter: grayscale(1);"
                                                    <?php endif; ?>  
                                                    accesskey=""src="lib/parsedown-1.7.3/vote2.png" width="30" height="20" alt=""/></a><br>
                                                <?php //if($posts->AuthorId ==$user->UserId): ?>
                                            <a href="post/accept_and_refuse_answer/<?php echo $reponse->PostId; ?>/<?php echo FALSE; ?>"><img
                                                    style="-webkit-filter: grayscale(1); filter: grayscale(1);"
                                                    src="lib/parsedown-1.7.3/refuser.png" width="30" height="20"  alt=""/></a>
                                            <a href="post/accept_and_refuse_answer/<?php echo $reponse->PostId; ?>/<?php echo TRUE; ?>"><img
                                                <?php if ($posts->AcceptedAnswerId == $reponse->PostId): ?>                   
                                                        style=" -webkit-filter: hue-rotate(90deg);
                                                        filter: hue-rotate(90deg);"
                                                    <?php else: ?>
                                                        style="-webkit-filter: grayscale(1);
                                                        filter: grayscale(1);"
                                                    <?php endif; ?>
                                                    src="lib/parsedown-1.7.3/accepte.png" width="30" height="20" alt=""/></a>
                                            <?php endif; ?>
                                    </td>
                                    <?php $reponsecomment = Comment::get_all_comment($reponse->PostId); ?>
                                    <td>
                                        <?php if (count($reponsecomment) != 0): ?>
                                            <h4> comment list</h4>
                                            <?php foreach ($reponsecomment as $values): ?>
                                                <div id="comment">
                                                    <li> <?php echo $values->Body; ?></li>
                                                    <br>&nbsp &nbsp asked <span><?php echo $values->temp_ago()[0]; ?></span> 
                                                    &nbsp by <?php echo $values->name(); ?> &nbsp &nbsp
                                                    <?php if ($user): ?>
                                                        <?php if ($user->Role == "admin" || $values->UserId == $user->UserId): ?>
                                                            <a href="comment/edit_comment/<?php echo $values->CommentId; ?>">
                                                                <input id="post" type="image" img src="lib/parsedown-1.7.3/edit.png" width="30" height="20"alt=""> </a>            
                                                            <a href="comment/delete_comment/<?php echo $values->CommentId; ?>">
                                                                <img src="lib/parsedown-1.7.3/delete.png" width="30" height="20"  alt=""/></a><br>
                                                        <?php endif; ?><br> 
                                                    <?php endif; ?>    
                                                </div>     
                                            <?php endforeach; ?><br> 
                                        <?php endif; ?>     
                                        <?php if ($user && count($listanswer) != 0): ?><br>
                                            &nbsp &nbsp<div id="enablecomment"> Add a Comment on the answer.</div><br><br>
                                            <form id="comment_form" action="comment/add_comment/<?php echo $reponse->PostId; ?>" method="post">                 
                                                <textarea id="Body" name="Body" rows='2'></textarea><br>
                                                <input id="post" type="submit" value="Comment">
                                            </form>

                                        <?php endif; ?><br><br>
                                <li><?php echo $reponse->markdown(); ?></li><br>
                                <?php if ($user && $user->UserId == $reponse->AuthorId): ?>
                                    <a href="post/edit/<?php echo $reponse->PostId; ?>"><img src="lib/parsedown-1.7.3/edit.png" width="30" height="20"  alt=""/></a>
                                    <a href="post/delete_confirm/<?php echo $reponse->PostId; ?>"><img src="lib/parsedown-1.7.3/delete.png" width="30" height="20"  alt=""/></a><br>
                                <?php endif; ?>
                                <p>asked <span><?php echo $reponse->temp_ago()[0]; ?></span>
                                    &nbsp by <?php echo $reponse->name(); ?> &nbsp by &nbsp</p>
                        </td>
                    </tr>
                </table>
            <?php endforeach; ?><br><br><br>
            <?php if ($user): ?>
                Add your Anwer<br>
                <form id="post_form" action="post/show/<?php echo $posts->PostId; ?>" method="post">                 
                    <textarea id="Body" name="Body" rows='8'></textarea><br><br>
                    <input id="post" type="submit" value="put your Answer">
                </form>
            <?php endif; ?><br><br>
            </td>
            </tr>
            </table>
            <?php if (count($errors) != 0): ?>
                <div class='errors'>
                    <br><br><p>Please correct the following error(s) :</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>    
    </body>
    <script>
        
    </script>
</html>
