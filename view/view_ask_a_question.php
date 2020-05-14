<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Stuck Overflow </title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <script>
            let Title,checkbox,Body;
            document.onreadystatechange = function () {
                if (document.readyState === 'complete') {
                    Title = document.getElementById("Title");
                    checkbox = document.getElementById("checkbox");
                    Body = document.getElementById("Body");
                }
            };
            function checkTitle(){
                let ok = true;
                errTitle.innerHTML = "";
                if(!(/^.{3,16}$/).test(Title.value)){
                    errTitle.innerHTML += "<p>Title length must be between 3 and 16.</p>";
                    ok = false;
                }
                if(Title.value.length > 0 && !(/^[a-zA-Z][a-zA-Z0-9]*$/).test(Title.value)){
                    errTitle.innerHTML += "<p>Title must start by a letter and must contain only letters and users.</p>";  
                    ok = false;
                }
                return ok;
            }
            
            function checkcheckbox(){
                let ok = true; 
                errcheckbox.innerHTML = "";
                if( TagName.length>4  ){
                    errcheckbox.innerHTML += "<p>checkbox  used 5 tag max</p>";  
                    ok = false;
                }
                return ok;
            }
            function checkBody(){
                let ok = true;
                errBody.innerHTML = "";
                if(!(/^.{3,16}$/).test(Body.value)){
                    errBody.innerHTML += "<p>Body length must be between 3 and 16.</p>";
                    ok = false;
                }
                if(Body.value.length === 0 && !(/^[a-zA-Z][a-zA-Z0-9]*$/).test(FullName.value)){
                    errBody.innerHTML += "<p>Body must start by a letter and must contain only letters and users.</p>";  
                    ok = false;
                }
                return ok;
            }
            
            function checkAll(){
                let ok = checkTitle();
                ok = checkcheckbox() && ok;
                ok = checkBody() && ok;
                return ok;
            }
        </script>
    </head>
    <body>
        <div class="bloc1">
            <div class="title"><a href="post/index"><img style="color: white;"src="lib/parsedown-1.7.3/back.png" width="30" height="20"  alt=""/></a> Stuck Overflow </div>
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
            <br><br>
            <form id="askform" action="post/Ak_a_question" method="post" >
                <table>
                    <tr>
                        <td>Title</td>
                        <td><input id="Title" name="Title" type="text" rows='1'  value="<?= $Title ?>" > </td>
                        <td class="errors" id="errTitle"></td>
                    </tr>
                    <tr>
                        <td> add 5 tag i: </td>
                        <td> 
                            <?php foreach ($tag as $row):?>
                                <input id="" type="checkbox" name="TagName[]" multiple="oui"  value="<?= $row->TagName  ?>"  > <?= $row->TagName ;?>
                            <?php endforeach; ?>
                        </td>
                        <td class="errors" id="errcheckbox"> </td>
                    </tr>
                     <tr>
                        <td>Body</td>
                        <td><textarea id="Body" name="Body" type="text" rows='4'  value="<?=$Body ?>" > <?= $Body ?></textarea></td>
                        <td class="errors" id="errBody"></td>
                    </tr>               
                </table><br><br>
                <input id="post" type="submit" value="publish your question">   
            </form>    
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
</html>
