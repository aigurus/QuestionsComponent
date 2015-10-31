$(document).ready(function() {
    var totalcommid = 31;
    
    $(".option-reply").live("click", function() {        
        var currtxt = $(this).text();
        var curroptlink = $(this);
        var cid = $(this).parent().parent().parent().attr("id");
        var poundcid = "#link-"+cid;
        
        var htmlform = '<form id="commsubform"><label>Add Comment:</label> <textarea id="thecomment" class="commtxtfield"></textarea> <br /><br /> <a class="savecommbtn" id="link-'+cid+'">Save Comment</a></form>';
        
        // toggle between show/hide comment form views
        if(currtxt == "Reply") {
            $(this).parent().next(".showhidecommform").html(htmlform);
            $(this).text("Hide form");
        }
        
        if(currtxt != "Reply") {
            curroptlink.parent().next(".showhidecommform").html("&nbsp;");
            curroptlink.text("Reply");
        }
        
        // begin translating our comment
        $(poundcid).live("click", function() {
            var newcomtxt = $('textarea#thecomment').val();
            var targetcontainer = "#"+cid;
            
            var newcommhtml = '<div class="comment-thread"> <div class="comment-block" id="c'+totalcommid+'"> <a href="#" class="author-avatar"><img src="components/com_questions/media/images/avatar2.png" alt="anonymous" /></a> <p class="comment-top-meta"> <strong>anonymous</strong> <span>posted 1 sec ago</span></p> <div class="comment-content"> <p class="the-comment-post">' + newcomtxt + '</p> <p class="comment-reply-options"><a class="option-reply">Reply</a></p> <div class="showhidecommform">&nbsp;</div> </div> </div> <div class="thread-replies"></div> </div>';
            
            $(targetcontainer).next(".thread-replies").hide().append(newcommhtml).fadeIn();
            
            curroptlink.trigger("click");
        });
        
        totalcommid++;
        
        return false;
    });
});


