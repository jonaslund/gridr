jQuery(function($) { 
var reqURL = "http://gridr.org/";
//var reqURL = "http://localhost:8888/";

if(document.getElementById("gridfixed")) {
  $(window).scrollTop(37);   
}

if(Modernizr.input.placeholder) {
  $("html").addClass("placeholder");
}
    //Add-Search
    $("#search").submit(function() { 
      var thisQuery = $("#video").val();
      var videoID = youtube_parser(thisQuery);
      $("#results").empty();
      
      $("#ytloader").show();
      
      if(videoID === "QRY") {              
        $.ajax({ 
          type: "POST",
          url: reqURL + "/xhr/?do=searchYT",
          data: {query: thisQuery },
          success: function(data) {
            if(data) {            
                var obj = $.parseJSON(data);
                  $.each(obj, function(i, object) {
                    var imgSRC = object.imgSRC;
                    var videoID = object.videoID;                
                    var videoTitle = object.title;

                    $("<img />", {
                      class: "videoThumb",
                      src: imgSRC,
                      "data-id": videoID,
                      "data-title": videoTitle        
                    }).appendTo($("#results"));
                  });
              }
            $("#ytloader").hide();
            $("#video").val("");
            var moreLink = "<a href='#' data-qry='"+thisQuery+"' data-offset='2' class='loadMore'>Load More</a>";
            $(moreLink).appendTo($("#results"));
          }
        });
      
      } else {
        addVideo(videoID);
        $("#ytloader").hide();
        $("#video").val("");
      }
      return false;
    });
    
    $(".loadMore").live("click", function() { 
      $("#results").html("");
      $("#ytloader").show();
      var thisQuery = $(this).attr("data-qry"),
          thisOffset = $(this).attr("data-offset");
      
      $.ajax({ 
        type: "POST",
        url: reqURL + "/xhr/?do=searchYT",
        data: {query: thisQuery, offset: thisOffset },
        success: function(data) {
          if(data) {            
              var obj = $.parseJSON(data);
                $.each(obj, function(i, object) {
                  var imgSRC = object.imgSRC;
                  var videoID = object.videoID;                
                  var videoTitle = object.title;

                  $("<img />", {
                    class: "videoThumb",
                    src: imgSRC,
                    "data-id": videoID,
                    "data-title": videoTitle        
                  }).appendTo($("#results"));
                });
            }
          $("#ytloader").hide();
          $("#video").val("");
          var moreLink = "<a href='#' data-qry='"+thisQuery+"' data-offset='"+(thisOffset+1)+"' class='loadMore'>Load More</a>";
          $(moreLink).appendTo($("#results"));
        }
      });
      
      return false;
    });
    
    //add video
    $(".videoThumb").live("click",function() { 
      var videoID = $(this).attr("data-id");      
      addVideo(videoID);
      startSort();
    });
  
    $(".videoThumb").live("mouseenter", function() { 
      var thisTitle = $(this).attr("data-title");
      var titleHolder = $(".yttitle");
      
      $(titleHolder).text(thisTitle).show();
    });

    $(".videoThumb").live("mouseleave", function() { 
      var titleHolder = $(".yttitle");      
      $(titleHolder).hide();
    });

    
    $(".delete-video").live("click",function() { 
      var gridID = $(".grid-edit").attr("data-id");
      var videoCont = $(this).parents(".youtube-edit");
      var rowID = $(videoCont).attr("data-id");
      $(videoCont).remove();
      
      var amtVideos = parseInt($("#gridEdit").find(".youtube-edit").size());
      $("#gridEdit").attr("class", " ").addClass("g"+amtVideos);      
      return false;
    });
    
    //login//signup
    $(".login").click(function() { 
      var loginForm = $("<div />").load(reqURL + "/forms/login #layer");
      $(loginForm).appendTo("body");
      return false;
    });

    $(".forgot").live("click", function() { 
      var loginForm = $("<div />").load(reqURL + "/forms/forgot #layer");      
      $("#layer").remove();
      $(loginForm).appendTo("body");
      return false;
    });
    
    $("#deleteGrid").click(function() { 
      var gridID = $(".grid-edit").attr("data-id");

      var yesdelete = confirm('Are You Sure You Wanna Delete This Grid Forever?');
      if(yesdelete) {
       $.ajax({
        type: "POST",
        url: reqURL + "/xhr/?do=deleteGrid",
        data: {gridID: gridID},
        success: function(data) {
          if(data) {
            window.location.href = data;
          }
        }
       });      
      }
      
      return false;    
    });
    
    $("#login").live("submit", function() { 
      var pass = $("#password").val(),
          email = $("#email").val();
          error = $(this).find(".error");
          
      $.ajax({
        type: "POST",
        url: reqURL + "/xhr/?do=login",
        data: {password: pass, email: email},
        success: function(data) {          
          if(data === "WRONG") {
            $(".error").html("Wrong Combo").show();
          } else {
            window.location.reload();
          }
        }
      });
      
      return false;      
    });
    
    $("#layer").live("click", function(e) { 
      var target = $(e.target).attr("id");
      if(target === "layer") {
        $(this).parent("div").remove();
      }
    });

    $(".signup").click(function() { 
      var signup = $("<div />").load(reqURL + "/forms/signup #layer");
      $(signup).appendTo("body");
    });

    $("#signup").live("submit", function() { 
      var pass = $("#password").val(),
          email = $("#email").val(),
          error = $(this).find(".error");
          
      $.ajax({
        type: "POST",
        url: reqURL + "/xhr/?do=signup",
        data: {password: pass, email: email},
        success: function(data) {
          if(data != "success") {
            $(".error").html(data).show();
          } else {
            window.location.reload();
          }
        }
      });
            
      return false;
    });
    
    $("#forgot").live("submit", function() { 
      var email = $("#email").val(),
          error = $(this).find(".error");
          
      $.ajax({
        type: "POST",
        url: reqURL + "/xhr/?do=passreset",
        data: {email: email},
        success: function(data) {
          if(data === "Email Sent, check your email for instructions..!") {
            $(".error").html(data).insertAfter("#forgot").show();
            $("#forgot").remove();

          } else {
            $(".error").html(data).show();
          }
        }
      });
            
      return false;
    });

    $(".toggle-handle").click(function() { 
      $(this).next(".toggle").show();
      $(this).hide();
      return false;    
    });
    
    
    $("#editGrid").click(function() { 
      var gridID = $(".grid-edit").attr("data-id");
      var gridTitle = $("#title").val();

      var selectedVideos = [];
      $("#gridEdit").find("img").each(function() { 
        selectedVideos.push($(this).attr("data-src")) 
      });
      
      if(selectedVideos.length > 0 ) {    
        $.ajax({
            type: "POST",
            url: reqURL + "/xhr/?do=editGrid",
            data: {gridID: gridID, gridTitle: gridTitle, gridVideos: selectedVideos},
            success: function(data) {
             if(data === "success") {
               window.location.reload();
             }
            }
          });      
      } else {
        alert("Grid Can't Be Empty");
      }
            
      return false;  
    });
    
    $("#newGrid").click(function() { 
      var gridTitle = $("#title").val();
      var selectedVideos = [];
      $("#gridEdit").find("img").each(function() { 
        selectedVideos.push($(this).attr("data-src"))             
      });
      
      if(selectedVideos.length > 0) {   
        $.ajax({
            type: "POST",
            url: reqURL + "/xhr/?do=newGrid",
            data: {gridTitle: gridTitle, gridVideos: selectedVideos},
            success: function(data) {
              if(data) {
                window.location.href = data;
              }
            }
          });      
      } else {
        alert("Grid Can't Be Empty");
        return false;  
      }      
    });
    
    $("#settingsForm").submit(function() { 
      var email = $("#email").val(),
          password = $("#password").val(),
          username = $("#username").val();
          message = $(this).find(".error");
          
          
        $.ajax({ 
          type: "POST",
          url: reqURL + "/xhr/?do=settings",
          data: {email: email, password: password, username: username },
          success: function(data) {
            if(data) {
              $(message).html(data);              
            } else {
              $(message).html("Saved!");
            }
           }
        });             
    
      return false;
    });
    
    $("#previewGrid").live("click", function() { 
      var selectedVideos = [];

      var preview = "<div><div id='layer' class='bglayer'><div id='gridfixed' class='layer' style='width: 960px !important; height: 720px !important;'></div></div></div>";
      $(preview).appendTo("body");
      
      var amtVideos = parseInt($("#gridEdit").find(".youtube-edit").size());
      $("#gridfixed").addClass("g"+amtVideos);

      $("#gridEdit").find(".youtube-edit").each(function() {         
        var thisVideoSrc = $(this).attr("data-video");
        addVideoIframe(thisVideoSrc);
      });



      return false;
    });


//initate sort
startSort();
});

function addVideo(videoID) {
  var gridID = $(".grid-edit").attr("data-id");
  var videoID = videoID;
  var amtVideos = parseInt($("#gridEdit").find(".youtube-edit").size());
  
  if(amtVideos == 16) {
    alert("Sorry, maximum 16 videos, try removing one and add it again");
  } else {
  
    // var newVideo = '<div class="youtube-edit" data-video=' + videoID + '">' 
    //              + '<iframe width="420" height="315" src="http://www.youtube.com/embed/' + videoID + '" data-src="' + videoID+ '" frameborder="0" allowfullscreen></iframe>'
    //              + '<div class="edit-tools">'
    //              + '<a class="delete-video" href="#">&times;</a>'
    //              + '</div>'
    //              + '</div>'
    //              ;

    var newVideo = '<div class="youtube-edit" data-video=' + videoID + '">' 
                 + '<img width="420" height="315" class="drag-video" src="http://i.ytimg.com/vi/' + videoID + '/0.jpg" data-src="' + videoID+ '" />'
                 + '<div class="edit-tools">'
                 + '<a class="delete-video" href="#">&times;</a>'
                 + '</div>'
                 + '</div>'
                 ;

    
      $(newVideo).prependTo($("#gridEdit"));

      var newAmt = amtVideos+1;
      $("#gridEdit").attr("class", " ").addClass("g"+newAmt);
  }  
}

function addVideoIframe(videoID) {
  var gridID = $(".grid-edit").attr("data-id");
  var videoID = videoID;
  
  // var newVideo = '<div class="youtube-edit" data-video=' + videoID + '">' 
  //              + '<iframe width="420" height="315" src="http://www.youtube.com/embed/' + videoID + '" data-src="' + videoID+ '" frameborder="0" allowfullscreen></iframe>'
  //              + '</div>';

  var newVideo = '<iframe width="420" height="315" src="http://www.youtube.com/embed/' + videoID + '" data-src="' + videoID+ '" frameborder="0" allowfullscreen></iframe>';

  $(newVideo).appendTo($("#gridfixed"));
}  

function startSort() {
 $("#gridEdit").sortable({
      handle: '.drag-video'   
 });     
}

function youtube_parser(url){
    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\??v?=?))([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match&&match[7].length==11){
        return match[7];
    } else {
        return("QRY");
    }
}

