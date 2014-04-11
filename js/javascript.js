function logoutUser() {
  $.ajax({
    url : "includes/logout.php",
    success : function() {
      location.reload();
    }
  });

}

function searchBorrowGames(searchstring) {
  var $contentDiv = $("<div>", {class: 'searchResourceContainer'});
  $.ajax({
    dataType : "JSON",
    type : "POST",
    url : "searchUserGames.php",
    data : {
      searchstring : searchstring,
      friendFilter : $("#friendlibrary option:selected").val(),
      platformFilter : $("#borrowPlatform option:selected").text(),
      genreFilter : $("#borrowGenre option:selected").text()
    },
    success : function(result){
      if (result.length>0) {
        $("#searchresultelement").empty();
        
        var $contentSectionDiv = $("<div>", {class: 'contentSectionDiv'});
        var count=1;
        var amount = result.length;
        $.each(result, function(key, gamedata){
          
          var $game = $("<div>", {id: gamedata, class: "game"});
          $game.css("background-color", randbgcolor());
          $game.html("<img style='width:50px; height:50px; margin-top:80px;' src='art/loader.gif' />");
          --amount;

          if (count==4 || amount==0) {
            var $gameInfoDiv = $("<div>", {class: 'gameInfoDiv'});
            $contentSectionDiv.append($game);
            $contentSectionDiv.append($gameInfoDiv);
            $("#searchresultelement").append($contentSectionDiv);
            $contentSectionDiv = $("<div>", {class: 'contentSectionDiv'});
            count=1;
          } else {
            ++count;
            $contentSectionDiv.append($game);
          };
        });

        $.each(result, function(k, data) {

          $.ajax({
            dataType : "JSON",
            type : "POST",
            url : "getGameById.php",
            data : {
              id : data
            },
            success : function(result) {
              boxart = $.isArray(result.Data.Game.Images.boxart) ? result.Data.Game.Images.boxart[1] : result.Data.Game.Images.boxart;              
              $("#searchresultelement").find("#"+result.Data.Game.id).html("<img src='"+boxart+"' />");
              $("#searchresultelement").find("#"+result.Data.Game.id).append("<div class='gameInteractionBar'><img onclick='borrowrequestlend($(this), \""+result.Data.Game.id+"\", \""+data[0].id+"\", \""+result.Data.Game.GameTitle+"\", \""+data[0].username+"\")' src='art/borrow-icon-yellow.png' /><img onclick='expand($(this), \"no user\", \"no user\");' src='art/info-80.png' /></div>");
            }
          });
        });
      } else{
        $("#searchresultelement").html("<h1 style='text-align: center;'>No search results</h1>");
      };
      
      //$("#searchresultelement").append($contentDiv);
    }
  });

} 

function requestlend(object, gameID, userID, gameName, userName) {
  //console.log(gameID+userID+gameName+userName);
  //var userID = $(object).parent().parent().parent().parent().parent().find(".username").val();
  //var gameID = $(object).parent().parent().attr("id");

  $("#dialog-requestlend").html('<p class="validateTips">Send lendrequest for <b>'+gameName+'</b> from <b>'+userName+'</b>?</p>');
  
  $("#dialog-requestlend").dialog("open");
  $( "#dialog-requestlend" ).dialog({
      resizable: false,
      height:240,
      width:400,
      draggable : false,
      modal: true,
      buttons: {
        "OK": function() {
            $( this ).dialog( "close" );
            $.ajax({
        dataType: "json",
        type : "POST",
        url : "requestLend.php",
        data : {
          gameID : gameID,
          friendID : userID
        },
        success : function(data) {
          if (data.status=="success") {
            notification("Request has been sent to user");
            updateUserLibrarySection();
          } else if(data.status=="Lendrequest already sent") {
            notification("Lendrequest already sent");
          } else{
            notification("Something went wrong");
          };
          //alert (data.status);
        }
      });
      
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });
}

function borrowrequestlend(object) {
  var gameID = $(object).parent().parent().attr("id");
  $.ajax({
    dataType : "json",
    type : "POST",
    url: "getGameHolders.php",
    data : {
      gameID : gameID
    },
    success : function(resultdata) {
      $("#gameholders").empty();
      $.each(resultdata, function(k, data) {
        $("#gameholders").append("<option value='"+data[0]["id"]+"'>"+data[0]["firstname"]+" "+data[0]["lastname"]+" ("+data[0]["username"]+")</option>");  
      });
      
    }
  });
  $("#dialog-requestlend-borrow").dialog("open");
  $( "#dialog-requestlend-borrow" ).dialog({
      resizable: false,
      height:240,
      width:400,
      draggable : false,
      modal: true,
      buttons: {
        "OK": function() {
            $( this ).dialog( "close" );
            $.ajax({
        dataType: "json",
        type : "POST",
        url : "requestLend.php",
        data : {
          gameID : gameID,
          friendID : $("#gameholders").find(':selected').val()
        },
        success : function(data) {
          if (data.status=="success") {
            notification("Request has been sent to user");
            updateUserLibrarySection();
          } else if(data.status=="Lendrequest already sent") {
            notification("Lendrequest already sent");
          } else{
            notification("Something went wrong");
          };
         // alert (data.status);
        }
      });
      
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });
}

function randbgcolor() {
  var colors = new Array("#ffb11b", "#66c69b", "#3fb3c6", "#fa6a3e", "#bf8ec3", "#64d862", "#8ba3b2");
  var num = Math.floor(Math.random() * 6);
  return colors[num];
}

function rotateToggle(e){
  e.toggleClass("rotateLeft");
  e.parent().parent().find(".userResourceContainer").slideToggle(500);
}

function notification(message){
  if ($("#notificationElement").length!=0) {
    $("#notificationElement").remove();
  };

  var $notificationElement = $("<div>", {id:"notificationElement"});
  $notificationElement.html("<h2>"+message+"</h2>")
  $("body").append($notificationElement);
  
  $("#notificationElement").slideDown(800).delay(3000).slideUp(500);
}
// Call this function *after* the page is completely loaded!
function resize_boxart(maxht, maxwt, minht, minwt) {
  var imgs = document.getElementsByClassName('boxart');

  var resize_image = function(img, newht, newwt) {
    img.height = newht;
    img.width  = newwt;
  };
  
  for (var i = 0; i < imgs.length; i++) {
    var img = imgs[i];
    if (img.height > maxht || img.width > maxwt) {
      // Use Ratios to constraint proportions.
      var old_ratio = img.height / img.width;
      var min_ratio = minht / minwt;
      // If it can scale perfectly.
      if (old_ratio === min_ratio) {
        resize_image(img, minht, minwt);
      } 
      else {
        var newdim = [img.height, img.width];
        newdim[0] = minht;  // Sort out the height first
        // ratio = ht / wt => wt = ht / ratio.
        newdim[1] = newdim[0] / old_ratio;
        // Do we still have to sort out the width?
        if (newdim[1] > maxwt) {
          newdim[1] = minwt;
          newdim[0] = newdim[1] * old_ratio;
        }
        resize_image(img, newdim[0], newdim[1]);
      }
    }
  }
}


var MAX_HEIGHT = 180;
function render(src, elementID){
  var canvas = document.getElementById(elementID);
  var ctx = canvas.getContext("2d");

  img = new Image();
  img.onload = function () {

    canvas.height = canvas.width * (img.height / img.width);

    /// step 1
    var oc = document.createElement('canvas'),
    octx = oc.getContext('2d');

    oc.width = img.width * 0.5;
    oc.height = img.height * 0.5;
    octx.drawImage(img, 0, 0, oc.width, oc.height);

    /// step 2
    octx.drawImage(oc, 0, 0, oc.width * 0.5, oc.height * 0.5);

    ctx.drawImage(oc, 0, 0, oc.width * 0.5, oc.height * 0.5,
    0, 0, canvas.width, canvas.height);
  }
  img.src = src;
}