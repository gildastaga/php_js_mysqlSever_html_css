<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Stuck Overflow </title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <script src="lib/jquery-3.4.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-validation-1.19.1/jquery.validate.min.js" type="text/javascript"></script>
        <script>
            $.validator.addMethod("regex", function (value, element, pattern) {
                if (pattern instanceof Array) {
                    for(p of pattern) {
                        if (!p.test(value))
                            return false;
                    }
                    return true;
                } else {
                    return pattern.test(value);
                }
            });
            $(function (){
                $('#askform').validate({ 
                    rules: {
                        Title: {
                            required: true
                        },
                        TagName:{
                            remote: {
                                url: 'post/TagName_available_service',
                                type: 'post',
                                data:  {
                                    TagName: function() {
                                        return $("#TagName").val();
                                    }
                                }
                            }
                        },
                        Body:{
                            required: true
                        }
                    } ,  
                    messages: {
                        Title: {
                            required: 'required Title'  
                        },
                        TagName:{
                            remote:'nombre de tag depasse '
                        },
                        Body: {
                            required: 'required Body'  
                        }
                  }
              }) ;     
                $("input:text:first").focus();    
            });
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
                    </tr><br>
                    <tr>
                        <td> add 5 tag i: </td><br>
                        <td> 
                            <?php foreach ($tag as $row):?>
                                <input id="TagName" type="checkbox" name="TagName[]" multiple="oui"  value="<?= $row->TagName  ?>"  > <?= $row->TagName ;?>
                            <?php endforeach; ?>
                        </td>
                        <td class="errors" id="errTagName"> </td>
                    </tr><br>
                    <tr>
                        <td>Body</td>
                        <td><textarea id="Body" name="Body" type="text" rows='4'   > <?= $Body ?></textarea></td>
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
