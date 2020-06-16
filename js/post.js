$(function() {
    $("#ongle-php").hide();
    $("#postList-php").hide();
    $("#post").hide();
    $("#pagi").hide();
    getPost(); 
  });

var posts;
       
function getPost() { 
   
    posts = $("#postList-ajax");
    $.get("post/indexJson", function(data) {
        datas = jQuery.parseJSON(data);
        index(datas);
    });
  
    $("#search").keyup(function() {;
        $.post("post/searchJson", {terme: $("#search").val()}, function (data){
            datas = jQuery.parseJSON(data);
            index(datas);
        });
    });

    }
function prev(action,page){
    if(page>=0){
        $.post("post/"+action, {page: page}, function(data){
            datas=jQuery.parseJSON(data);
            index(datas);
        });
    }
}  
function next(action,page){
    $.post("post/"+action, {page: page} , function(data){ 
        datas=jQuery.parseJSON(data); 
        index(datas);
    });
}
function page_i(action,page){
    $.post("post/"+action, {page: page}, function(data){
        datas=jQuery.parseJSON(data);
        index(datas);
    });
}
function newest() {
  $.get("post/newestJson", function(data) {
      datas = jQuery.parseJSON(data);
      index(datas);
  });
}

function byTag() {
  $.get("post/bytagJson", function(data) {
      datas = jQuery.parseJSON(data);
      index(datas);
  }) ; 
}

function vote() {
  $.get("vote/indexJson", function(data) {
      datas = jQuery.parseJSON(data);
      index(datas);
  });  
}

function unanswered() {
  $.get("post/unansweredJson", function(data) {
      datas = jQuery.parseJSON(data);
      index(datas);
  }) ;
}

function active() {
  $.get("post/activeJson", function(data) {
      datas = jQuery.parseJSON(data);
      index(datas);
  }) ; 
}

function byTags(TagId){
    $.get("post/bytagJson/"+TagId, function(data){
        datas=jQuery.parseJSON(data);
        index(datas);
    });
}
function index(datas) {
    posts.html("");
    var table = "";
        table += '<div class="menus">';
        table += '<form class="menus">';
        table += '<span onclick = "newest()" id="newest" >Newest  &nbsp</span> ';
        table += '<span  onclick = "active()" id="active" >  Active&nbsp</span>';
        table += '<span  onclick = "unanswered()" id="unanswerd" > Unanswered &nbsp </span>';
        table += '<span  onclick = "vote()" id="vote" > Vote &nbsp </span>';
        table += '<span  onclick = "byTag()" id="bytag" > by tag &nbsp </span>';
        table += '</form>';
        table += '</div>';
//        table += '<div>';
//        table += '<form class="recherche" id="recherche" method="post" >';
//        table += '<input id="search" type="search" name="search"   aria-label="search ">';
//        table += '<input id="save" style="display:none;" type="submit" value="search">';
//        table += '</form>';
//        table += '</div>';
        table += ' <br><br><br><br>';
        table += '<div class="main" >';
        table += '<div  class="message_list" id="postList-php" >';
        table += '<table id="message_list" class="message_list">';
        for(var index = 0; index < datas.posts.length; index++) {
            table += "<tr>";
            table += '<li><a href="post/show/' + datas.posts[index].PostId +'">' + datas.posts[index].Title + '</a></li>';
            table += '&nbsp  ' + datas.posts[index].markdown; 
            table += '<br>&nbsp &nbsp asked <span>'+datas.posts[index].temp+'</span>';
            table += '&nbsp by '+datas.posts[index].name+')('+ datas.posts[index].nbr_vote + 'vote(s) &nbsp, ' +datas.posts[index].count_Answer+' Answer (s)) &nbsp';
            for(var i = 0; i < datas.posts[index].tags.length; i++) {
                table += '<span  onclick = "byTags('+datas.posts[index].tags[i].TagId +')" id="bytag" > '+ datas.posts[index].tags[i].TagName + '</span>';                
            }
            table += '</tr><br><br>';
        }
        table += '</table>';
        table += '</div>';
        table +='</div';
        table +='<div>';
        table+='<div class="pagination">';
        table+='<li  style="page-item ('+datas.currentPage+'>1) ?'+'"-webkit-filter:blur(90deg);'+
                                                        'filter: blur(90deg);"'+' :'+
                                                        '"pointer-events:none;'+
                                                        '-webkit-filter: grayscale(1);'+
                                                        'filter: grayscale(1);" ">';
            if(datas.currentPage>1){ ;
                table+='<span id="prev" style="page-link ml-auto" onclick="prev(`'+ datas.action+'`,'+(datas.currentPage-1)+')"; >prev &laquo</span>' ;
            }    
        table+='</li>';
        for(var page_i=1; page_i<=datas.nbr ;page_i++){
            table+='<li  style="page-item ('+datas.currentPage+'=='+page_i+' ) ?'+'"-webkit-filter:blur(90deg);'+
                                                        'filter: blur(90deg);"'+' :'+
                                                        '"pointer-events:none;'+
                                                        '-webkit-filter: grayscale(1);'+
                                                        'filter: grayscale(1);" ">';
            table+='<span style="page-link ml-auto" onclick="page_i(`'+ datas.action+'`,'+page_i+')"; id="page_i">'+page_i+'</span>' ;
            table+='</li>';
        }
        table+='<li  style="page-item ('+datas.currentPage+'<='+datas.nbr+') ?'+'"-webkit-filter:blur(90deg);'+
                                                        'filter: blur(90deg);"'+' :'+
                                                        '"pointer-events:none;'+
                                                        '-webkit-filter: grayscale(1);'+
                                                        'filter: grayscale(1);" ">';
        if(datas.currentPage <datas.nbr){
            table+='<span  id="next" style="page-link ml-auto" onclick="next(`'+ datas.action+'`,'+(datas.currentPage+1)+')"; > &raquo; next</span>' ;
        }
        table+='</li>';
        table +='</div>';
        table +='</div>';
        
    posts.append(table);  
}  
  