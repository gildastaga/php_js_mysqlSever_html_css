$(function() {
    $("#ongle-php").hide();
    $("#postList-php").hide();
    getPost();
    
});

var posts;
function getPost() {
    
    posts = $("#postList-ajax");
    $.get("post/indexJson", function(data) {
        datas = jQuery.parseJSON(data);
        index(datas);
    });
}

function newest() {
  $.get("post/newestJson", function(data) { alert('ok')
      datas = jQuery.parseJSON(data);
        console.log(datas);
      index(datas);
  });
}

function bytag() {
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
        table += '<div>';
        table += '<form class="recherche" id="recherche" action="post/fiter" method="post" method="get">';
        table += '<input id="search" type="search" name="search"   aria-label="search ">';
        table += '<div class="result" id="result" ></div>';
        table += '<input id="post" type="submit" value="search">';
        table += '</form>';
        table += '</div>';
        table += ' <br><br><br><br>';
        table += '<div class="main" >';
        table += '<div  class="message_list" id="postList-php" >';
        table += '<table id="message_list" class="message_list">';
        for(var index = 0; index < datas.posts.length; index++) {
            table += "<tr>";
            table += '<li><a href="post/show/' + datas.posts[index].PostId +'">' + datas.posts[index].Title + '</a></li>';
            table += '&nbsp  ' + datas.posts[index].markdown; 
            table += '<br>&nbsp &nbsp asked <span>'+datas.posts[index].temp_ago +'</span>';
            table += '&nbsp by '+datas.posts[index].name+')('+ datas.posts[index].nbr_vote + 'vote(s) &nbsp, ' +datas.posts[index].count_Answer+' Answer (s)) &nbsp';
            for(var i = 0; i < datas.posts[index].tags.length; i++) {
                table += '<a href="post/by_tag/'+datas.posts[index].tags[i].TagId +'">'+ datas.posts[index].tags[i].TagName + '</a>&nbsp';
            }
            table += '</tr><br><br>';
        }
        table += '</table>';
        table += '</div>';
        table +='</div';
        
    posts.append(table);
}
  