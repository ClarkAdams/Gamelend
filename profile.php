<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) != true) {
	header ("Location: index.php");
}
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<meta http-equiv="Content-Language" content="sv" />
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<title>Profile - GameLend - A Gaming Solidarity Initiative</title>
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<!--[if lte IE 7]>
	<script src="js/IE8.js" type="text/javascript"></script><![endif]-->
	<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" media="all" href="css/ie6.css"/><![endif]-->
	<script type="text/javascript" src="js/javascript.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
	<script type="text/javascript">

		function addGameToUserLibrary(e) {
			var gameID = e.parent().parent().attr("id");
			$.ajax({
				dataType: "json",
				type : "POST",
				url : "addGameToUserLibrary.php",
				data : {
					gameID : gameID
				},
				success : function(data) {
					
					if (data.status=="success") {
						notification("A game was added to your Library");
						updateUserLibrarySection();
					} else if(data.status=="already registered") {
						notification("You have already registered that game to your library");
					} else{
						notification("Something went wrong");
					};
					
				}
			});
		};

		function collapsegameInfoDiv(e) {
			var $gameInfoDiv = e.parent().parent().parent().find(".gameInfoDiv");
			$gameInfoDiv.animate({
				height: 0
			}, {
				duration: 500,
				queue: false
			});
		}

		function populateGameInfoDiv(e, borrowed, holder) {
			$.ajax({
				dataType: "json",
				type: "POST",
				url: "getGameById.php",
				data: {
					id: e.parent().parent().attr("id")
				},
				success: function(result) {
					var $InfoDiv = e.parent().parent().parent().find(".gameInfoDiv");

					var $imgDiv = $("<div>", {class:'infoImageDiv'});

					$imgDiv.append("<img src='"+result.Data.Game.Images.boxart+"' />");
					$InfoDiv.append($imgDiv);

					var $dataDiv = $("<div>", {class:"gameInfoDataDiv"});
					$dataDiv.append("<h2>"+result.Data.Game.GameTitle+"</h2>");
					if (borrowed!="no user") {
						$dataDiv.append("<h3>Borrowed from "+borrowed+"</h3>");
					}
					if(holder!="no user"){
						$dataDiv.append("<h3>Lent to "+holder+"</h3>");
					}
					if (typeof result.Data.Game.Overview!="undefined") {
						var overview = result.Data.Game.Overview.substr(0, 300) + "\u2026";
					} else {
						var overview = "No overview for this game";
					};
					
					$dataDiv.append("<p>"+overview+"</p>");
					$dataDiv.append("<p>For more information about the game visit <a target='blank' href='http://thegamesdb.net/game/"+result.Data.Game.id+"/' >thegamesdb.net</a></p>");
					$dataDiv.append("<p class='gameSpecs'>"+result.Data.Game.Platform+" | Players: "+result.Data.Game.Players+" | Co-op: "+result.Data.Game.Coop+"</p>");
					$InfoDiv.append("<img onclick='collapsegameInfoDiv($(this))' src='art/collapse-80.png' class='collapseIcon' />");
					$InfoDiv.append($dataDiv);
				}
			});
		}

		function expand(e, borrowed, holder) {
			$(".gameInteractionBar").removeClass("activeGame");
			e.parent().addClass("activeGame");
			var $gameInfoDiv = e.parent().parent().parent().find(".gameInfoDiv");
			
			if ($gameInfoDiv.height()==0) {
				$(".gameInfoDiv").animate({
					height: 0
				}, {
					duration: 500,
					queue: false
				});

				$gameInfoDiv.empty();

				populateGameInfoDiv(e, borrowed, holder);

				$gameInfoDiv.animate({
					height: 250
				}, {
					duration: 500
				});
			} else if(false) {

			} else {
				$(".gameInfoDiv").animate({
					height: 0
				}, {
					duration: 500
				});
				$gameInfoDiv.animate({
					height: 0
				}, {
					duration: 400,
					done: function() {

						$gameInfoDiv.empty();
						populateGameInfoDiv(e, borrowed, holder);

						$gameInfoDiv.animate({
							height: 250
						}, {
							duration: 500
						});
					}
				});
			};	
		}

		function searchuser(searchstring){
			
			if(this.value !="") {
				$.ajax({
					dataType : "JSON",
					type : "POST",
					url : "searchUser.php",
					data : {
						searchstring : searchstring,
						platform : $("#userplatform option:selected").val(),
						city : $("#city option:selected").val()
					},
					success : addUserTosearchresultelement
				});
			}
		}
		function addUserTosearchresultelement(data) {
			if (data.length>0) {
				$("#usersearchresultelement").empty();
				$.each(data, function(key, userdata) {

					var $div = $("<div>", {id: userdata.id, class: "searchuser searchuserresult"});
					$div.css("background-color", randbgcolor());
					var $image = $("<figure>")
					if (userdata.img && userdata.img!="No image") {
						$image.append("<img src='art/userimages/"+userdata.img+"' alt='userthumb'>");
					} else{
						$image.append("<img src='art/head.png' alt='userthumb'>");
					};
					
					var $article = $("<article>", {class: "searchuserinfo"});
					$article.append("<header class='username'><h2>"+userdata.username+"</h2></header>");
					var $footer = $("<footer>", {class: "userdata"});
					$footer.append("<ol>");
					$footer.append("<li>Platform: "+userdata.platforms+"</li><li>Full name:"+userdata.firstname+" "+userdata.lastname+"</li><li>Lives: "+userdata.city+"</li><li>Email: "+userdata.email+"</li>");
					var $interactionElement = $("<div>", {class: "interaction-elements"});
					if (userdata.status==1) {
						$interactionElement.append("<img class='tooltip' src='art/requestpending-80.png' title='Friend request pending' />");
					} else if(userdata.status==2){
						$interactionElement.append("<img class='tooltip' src='art/friends-80.png' title='Friend' />");
					} else {
						$interactionElement.append("<img class='tooltip, befriend' src='art/requestfriendship-80.png' title='Friend request' class='befriend' />");
					};
					
					$article.append($footer);
					$div.append($image);
					$div.append($interactionElement);
					$div.append($article);
					$("#usersearchresultelement").append($div);
					$(".tooltip").tooltip();
					$("#"+userdata.id).on('click', '.befriend', function(e){
						var friendID = $(this).parent().parent().attr("id");

						$.ajax({
							dataType: "json",
							type : "POST",
							url : "requestFriendship.php",
							data : {
								friendID : friendID
							},
							success : function(data) {
								//alert(data.status);
								notification(data.status);
							}
						});
					});
					
				});
			} else{
				$("#usersearchresultelement").html("<h1 style='text-align: center;'>No search results</h1>");
			};
		}

		function updateUserLibrarySection() {
			//var $contentDiv = $("<div>", {class: 'searchResourceContainer'});
			$.ajax({
				dataType : "json",
				type : "POST",
				url : "getUserGamelibrary.php",
				success : function(results) {
					$("#userLibraryContainer").empty();

					var $contentSectionDiv = $("<div>", {class: 'contentSectionDiv'});
					var count=1;
					var amount = results.length;
						$.each(results, function(k, data) {
							
							$.ajax({
								dataType : "json",
								type : "POST",
								url : "getGameByid.php",
								data : {
									id : data.gameID
								},
								success : function(result) {
									--amount;

									var $game = $("<div>", {id: result.Data.Game.id, class: "game"});
									$game.css("background-color", randbgcolor());
									boxart = $.isArray(result.Data.Game.Images.boxart) ? result.Data.Game.Images.boxart[1] : result.Data.Game.Images.boxart;
									$game.append("<img src='"+boxart+"' />");

									$game.append("<div class='gameInteractionBar'><img onclick='removeGameFromeLibrary(\""+result.Data.Game.id+"\")' src='art/cross-80-yellow.png' /><img onclick='expand($(this), \"no user\", \"no user\");' src='art/info-80.png' /></div>");
									if (count==4 || amount==0) {
										var $gameInfoDiv = $("<div>", {class: 'gameInfoDiv'});
										$contentSectionDiv.append($game);
										$contentSectionDiv.append($gameInfoDiv);
										$("#userLibraryContainer").append($contentSectionDiv);
										$contentSectionDiv = $("<div>", {class: 'contentSectionDiv'});
										count=1;
									} else {
										++count;
										$contentSectionDiv.append($game);
									};
								}
							});
						});
					
				}
			});
		}

		function updateUserFriendsSection() {
			$.ajax({
				dataType : "json",
				type : "POST",
				url : "getFriends.php",
				success : function(results) {
					$("#userFriendsContainer").empty();
					$.each(results, function(k, data) {
						$("#friendlibrary").append("<option value='"+data.id+"'>"+data.username+"</option>")	
					});

					$.each(results, function(k, val) {
						$.ajax({
							dataType : "json",
							type : "POST",
							url : "getUserById.php",
							data : {
								userid : val['id']
							},
							success : addUserToPresentationSection
						});
					});
				} 
			});
		}

		function addUserToPresentationSection(data) {

			var $div = $("<div>", {id: data[0].id, class: "searchuser"});
			var $image = $("<figure>")
			if (data[0].img && data[0].img!="No image") {
				$image.append("<img src='art/userimages/"+data[0].img+"' alt='userthumb'>");
			} else{
				$image.append("<img src='art/head.png' alt='userthumb'>");
			};
			
			var $article = $("<article>", {class: "searchuserinfo"});
			$div.css("background-color", randbgcolor());

			if (data[0].gameIDArray[0]!=null) {
				var $contentDiv = $("<div>", {class: 'userResourceContainer'});
				var $contentSectionDiv = $("<div>", {class: 'contentSectionDiv'});
				// variables for sectioneing games withing presenations div by 4 a row
				var count=1;
				//	var for publishing last games of array
				var amount = data[0].gameIDArray.length;

				$.each(data[0].gameIDArray, function(k, val){

					$.ajax({
						dataType : "json",
						type : "POST",
						url : "getGameByid.php",
						data : {
							id : val["gameID"]
						},
						success : function(result) {
							// when amount reaches 0 all stacked games are printed
							--amount;

							var $game = $("<div>", {id: result.Data.Game.id, class: "game"});
							$game.css("background-color", randbgcolor());
							boxart = $.isArray(result.Data.Game.Images.boxart) ? result.Data.Game.Images.boxart[1] : result.Data.Game.Images.boxart;
							$game.append("<img src='"+boxart+"' />");

							$game.append("<div class='gameInteractionBar'><img onclick='requestlend($(this), \""+result.Data.Game.id+"\", \""+data[0].id+"\", \""+result.Data.Game.GameTitle+"\", \""+data[0].username+"\")' src='art/borrow-icon-yellow.png' title='Request to borrow game' /><img onclick='expand($(this), \"no user\", \"no user\");' src='art/info-80.png'  /></div>");
							if (count==4 || amount==0) {
								var $gameInfoDiv = $("<div>", {class: 'gameInfoDiv'});
								$contentSectionDiv.append($game);
								$contentSectionDiv.append($gameInfoDiv);
								$contentDiv.append($contentSectionDiv);
								$contentSectionDiv = $("<div>", {class: 'contentSectionDiv'});
								count=1;
							} else {
								++count;
								$contentSectionDiv.append($game);
							};
						},
						complete : function() {
							
						}
					});
				});

				$article.append("<img onclick='rotateToggle($(this))' class='showhidelibraryicon' src='art/arrow-left-80-yellow.png' />");
			} else{

			};

			$article.append("<header class='username'><h2>"+data[0].username+"</h2></header>");
			var $footer = $("<footer>", {class: "userdata"});

			$footer.append("<ol><li>Platform: "+data[0].platforms
				+"</li><li>Full name:"+data[0].firstname
				+" "+data[0].lastname+"</li><li>Lives: "+
				data[0].city+"</li></ol>");
			
			$article.append($footer);
			$div.append($image);
			$div.append($article);
			
			$div.append($contentDiv);
			$("#userFriendsContainer").append($div);
				
		}

		function updateUserBorrowSection() {
			$.ajax({
				dataType : "json",
				type : "POST",
				url : "getUserBorrowedGames.php",
				success : function(results) {
					$("#userBorrowedContainer").empty();

					var $contentSectionDiv = $("<div>", {class: 'contentSectionDiv'});
					var count=1;
					var amount = results.length;
					$.each(results, function(k, data){
						$.ajax({
							dataType : "json",
							type : "POST",
							url : "getLenderAndGameById.php",
							data : {
								id : data.gameID,
								lenderUsername : data.username
							},
							success : function(result) {
								--amount;

								var $game = $("<div>", {id: result.Data.Game.id, class: "game"});
								$game.css("background-color", randbgcolor());
								boxart = $.isArray(result.Data.Game.Images.boxart) ? result.Data.Game.Images.boxart[1] : result.Data.Game.Images.boxart;
								$game.append("<img src='"+boxart+"' />");

								$game.append("<div class='gameInteractionBar'><img onclick='expand($(this), \""+result.Data.Lender+"\", \"no user\");' src='art/info-80.png' /></div>");
								if (count==4 || amount==0) {
									var $gameInfoDiv = $("<div>", {class: 'gameInfoDiv'});
									$contentSectionDiv.append($game);
									$contentSectionDiv.append($gameInfoDiv);
									$("#userBorrowedContainer").append($contentSectionDiv);
									$contentSectionDiv = $("<div>", {class: 'contentSectionDiv'});
									count=1;
								} else {
									++count;
									$contentSectionDiv.append($game);
								};

							}
						});
					});
				}
			});
		}
			
		function updateUserLentSection() {
			$.ajax({
				dataType : "json",
				type : "POST",
				url : "getUserLentGames.php",
				success : function(results) {
					$("#userLentContainer").empty();

					var $contentSectionDiv = $("<div>", {class: 'contentSectionDiv'});
					var count=1;
					var amount = results.length;
					$.each(results, function(k, data){
						$.ajax({
							dataType : "json",
							type : "POST",
							url : "getHolderAndGameById.php",
							data : {
								id : data.gameID,
								holderUsername : data.username
							},
							success : function(result) {
								--amount;

								var $game = $("<div>", {id: data.id+"lend"+result.Data.Game.id, class: "game"});
								$game.css("background-color", randbgcolor());
								boxart = $.isArray(result.Data.Game.Images.boxart) ? result.Data.Game.Images.boxart[1] : result.Data.Game.Images.boxart;
								$game.append("<img src='"+boxart+"' />");

								$game.append("<div class='gameInteractionBar'><img onclick='removeLend($(this))' src='art/cross-80-yellow.png' /><img onclick='expand($(this), \"no user\", \""+result.Data.Holder+"\");' src='art/info-80.png' /></div>");
								if (count==4 || amount==0) {
									var $gameInfoDiv = $("<div>", {class: 'gameInfoDiv'});
									$contentSectionDiv.append($game);
									$contentSectionDiv.append($gameInfoDiv);
									$("#userLentContainer").append($contentSectionDiv);
									$contentSectionDiv = $("<div>", {"class": 'contentSectionDiv'});
									count=1;
								} else {
									++count;
									$contentSectionDiv.append($game);
								};
							}
						});
					});
				}
			});
		}
		
		function replaceImgUploadInsert() {
			$("#imageUpload").remove();
        	$('<input id="imageUpload" type="file" name="img" />').insertAfter('label[for="img"]');
        	$("#imageUpload").change(function (e) {
				if(this.disabled) return alert('File upload not supported!');
				var F = this.files;
				if(F && F[0]) for(var i=0; i<F.length; i++) readImage( F[i] );
			});
		}

		function readImage(file) {

		    var reader = new FileReader();
		    var image  = new Image();
		    var maximgsize = 2048*1024;

		    reader.readAsDataURL(file);  
		    reader.onload = function(_file) {
		        image.src    = _file.target.result;              // url.createObjectURL(file);
		        image.onload = function() {
		            var w = this.width,
		                h = this.height,
		                t = file.type,                           // ext only: // file.type.split('/')[1],
		                n = file.name,
		                s = file.size;
		                if (s>maximgsize) {
		                	$('#uploadPreview').html('<p>Image is to big. Max size allowed is 2MB</p>')
		                	replaceImgUploadInsert();
		                } else {
		            		$('#uploadPreview').html('<img src="'+ this.src +'">');    	
		                }
		            
		        };
		        //	if image not suported remove old element and replace with same without uloaded file
				//	javascript doesn't support manipulation of fileupload element
		        image.onerror= function() {
		        	replaceImgUploadInsert();
		        };      
		    };

		}
		
		function respondfriendrequest(data) {
			$.ajax({
				dataType : "json",
				type : "POST",
				url : "addFriendship.php",
				data : {
					response : $(data).attr("class"),
					userid : $(data).parent().attr("id")
				},
				success : function(response) {
					location.reload();
				}
			});

		}

		function respondlendrequest(data) {
			$.ajax({
				dataType : "json",
				type : "POST",
				url : "addLend.php",
				data : {
					response : $(data).attr("class"),
					gameIDfriendID : $(data).parent().attr("id")
				},
				success : function(response) {
					location.reload();
				}
			});
		}
		function addPlatform(select)
		{
		  var $ul = $("#platforms").prev('ul');
		  if ($ul.find('input[value=' + $("#platforms").val() + ']').length == 0){
		    $ul.append('<li onclick="$(this).remove();">' +
		      '<input type="hidden" name="platforms[]" value="' + 
		      $("#platforms").val() + '" /> ' +
		      $("#platforms").find(':selected').text() + '</li>');
		    }
		}

		$(function() {
			$("#dialog-confirm-removegame").dialog({
				autoOpen: false
			});
			$("#dialog-confirm-deletefriendship").dialog({
				autoOpen: false
			});
			$("#dialog-profileedit").dialog({
				autoOpen: false
			});
			$("#dialog-confirm-removeLend").dialog({
				autoOpen: false
			});

			$("#imageUpload").change(function (e) {
			    if(this.disabled) return alert('File upload not supported!');
			    var F = this.files;
			    if(F && F[0]) for(var i=0; i<F.length; i++) readImage( F[i] );
			});
			
			$('#removeimg').change(function(){
				if (this.checked) {
					$('#imageUpload').prop('disabled', true);
				} else {
					$('#imageUpload').prop('disabled', false);
				}
			});
			

			firstname = $( "#firstname" ),
			lastname = $( "#lastname" ),
			city = $( "#city" ),
			platforms = $( "#platforms" ),
		    allFields = $( [] ).add( name ).add( lastname ).add( city ).add( platforms ),
		    tips = $( ".validateTips" );
		    
			$( "#editUser" ).click(function() {
				$( "#dialog-profileedit" ).dialog( "open" );

				/*  opens up and populates the edit dialog with user info */
				$.ajax({
					dataType : "json",
					url : "getloginuser.php",
					success : function(resultdata) {
						var input = $( "#firstname" );
						input.val( resultdata.firstname );
						var input = $( "#lastname" );
						input.val( resultdata.lastname );
						
						//finds and selects city with cityID
						$("#updatecity").val(resultdata.city);

						//	Explodes string into array
						var platformsArray = new Array(resultdata.platforms.split("a"));
						// iterates over the array off platformID's and populates the platoform-div
						$(".platformselect").empty();
						$.each(platformsArray[0], function(k, data) {
							if (data!="") {
								var $ul = $("#platforms").prev('ul');
								$ul.append('<li onclick="$(this).remove();">' +
							    '<input type="hidden" name="platforms[]" value="' + 
							    data + '" /> ' +
							    $("#platforms > [value='"+data+"']").text() + '</li>');
						    };
					    });
					}
				});
		    });

			$( "#dialog-profileedit" ).dialog({
				autoOpen: false,
				minheight: 400,
				width: 400,
				draggable : false,
				modal: true,
				buttons: {
					"Update profile": function() {
						$('form#editform').submit();
					},
					Cancel: function() {
						$( this ).dialog( "close" );
					}
				},
				close: function() {
					allFields.val( "" ).removeClass( "ui-state-error" );
				}
			});
			
			function updateTips( t ) {
		      tips
		        .text( t )
		        .addClass( "ui-state-highlight" );
		      setTimeout(function() {
		        tips.removeClass( "ui-state-highlight", 1500 );
		      }, 500 );
		    }
		 
		    function checkLength( o, n, min, max ) {
		      if ( o.val().length > max || o.val().length < min ) {
		        o.addClass( "ui-state-error" );
		        updateTips( "Length of " + n + " must be between " +
		          min + " and " + max + "." );
		        return false;
		      } else {
		        return true;
		      }
		    }
		 
		    function checkRegexp( o, regexp, n ) {
		      if ( !( regexp.test( o.val() ) ) ) {
		        o.addClass( "ui-state-error" );
		        updateTips( n );
		        return false;
		      } else {
		        return true;
		      }
		    }
		});
	    
	    function deletFriendship(friendID) {
	    	$("#dialog-confirm-deletefriendship").dialog("open");
			$( "#dialog-confirm-deletefriendship" ).dialog({
		      resizable: false,
		      height:240,
		      width:400,
		      draggable : false,
		      modal: true,
		      buttons: {
		        "OK": function() {
		          $( this ).dialog( "close" );
					$.ajax({
						dataType : "json",
						type : "POST",
						url : "deleteFriendship.php",
						data : {
							friendID : friendID.parentNode.id
						},
						success : function(returndata) {
							$("#"+friendID.parentNode.id).remove();
						}
					});	
		        },
		        Cancel: function() {
		          $( this ).dialog( "close" );
		        }
		      }
		    });
	    }
	  
		function removeGameFromeLibrary(gameID) {
			$("#dialog-confirm-removegame").dialog("open");
			$( "#dialog-confirm-removegame" ).dialog({
		      resizable: false,
		      draggable : false,
		      height:240,
		      width:400,
		      modal: true,
		      buttons: {
		        "OK": function() {
		          $( this ).dialog( "close" );
					$.ajax({
						dataType : "json",
						type : "POST",
						url : "removeGameFromLibrary.php",
						data : {
							gameID : gameID
						},
						success : function(returndata) {
							updateUserLibrarySection();
							notification("A game was removed from your Library")
						}
					});	
		        },
		        Cancel: function() {
		          $( this ).dialog( "close" );
		        }
		      }
		    });
		}

		function removeLend(data) {

			$("#dialog-confirm-removeLend").dialog("open");
			$( "#dialog-confirm-removeLend" ).dialog({
		      resizable: false,
		      draggable : false,
		      height:240,
		      width:400,
		      modal: true,
		      buttons: {
		        "OK": function() {
		          $( this ).dialog( "close" );
					$.ajax({
						dataType : "json",
						type : "POST",
						url : "removeLend.php",
						data : {
							gameIDfriendID : data.parent().parent().attr("id")
						},
						success : function(returndata) {
							updateUserLentSection();
							notification("You have registered a game as returned")
						}
					});	
		        },
		        Cancel: function() {

		          $( this ).dialog( "close" );
		        }
		      }
		    });

		}
		
	</script>
</head>
<body>
	
<script type="text/javascript">
window.onload = function() {
	$(document).tooltip();
	
	

	$("#borrowsearchfield").keyup(function(e){
		if(e.keyCode == 13) {
			var searchstring = this.value;
			searchBorrowGames(searchstring);
		}
	});

	$("#dialog-requestlend").dialog({
		autoOpen: false
	});
	$("#dialog-requestlend-borrow").dialog({
		autoOpen: false
	});

	$(".showhideicon").click(function() {
		$(this).toggleClass("rotateRight");
		$(this).parent().find(".profileResourceContainer").slideToggle(500);
	});
	$(".addicon").click(function() {
		$(this).toggleClass("rotateLeft");
		$(this).parent().find(".profileSearchContainer").slideToggle(500);
	});

    $.ajax({
		dataType : "json",
		url : "getAllConsoles.php",
		success : function (result) {
			$.each(result, function(k, data) {
				$("#userplatform").append("<option value='"+data.id+"'>"+data.console+"</option>");
				$("#platform").append("<option value='"+data.id+"'>"+data.console+"</option>");
				$("#borrowPlatform").append("<option value='"+data.console+"'>"+data.console+"</option>");	
			});
		}
	});
	$.ajax({
		dataType : "json",
		url : "getGenres.php",
		success : function (result) {
			$.each(result, function(k, data) {
				$("#borrowGenre").append("<option value='"+data.genre+"'>"+data.genre+"</option>");
				$("#genre").append("<option value='"+data.genre+"'>"+data.genre+"</option>");	
			});
		}
	});

	$.ajax({
		dataType : "json",
		url : "getProfileSectionCounts.php",
		success : function (result) {
				$("#numberMessages").append(result.messagecount);
				$("#numberLent").append(result.lentcount);
				$("#numberFriends").append(result.friendcount);
				$("#numberBorrow").append(result.borrowcount);
				$("#numberLibrary").append(result.librarycount);
				
		}
	});

	$("#searchuserfield").keyup(function(e){
		searchuser(this.value);
	});

	// --- Searchfunctions --- //
	$("#searchgamefield").keyup(function(e){	
		if(e.keyCode == 13 && this.value !="") {
			var searchtype = "search";
			$.ajax({
				dataType : "JSON",
				type : "POST",
				url : "searchGame.php",
				data : {
					name : this.value,
					platform : $("#platform option:selected").text(),
					genre : $("#genre option:selected").text()
				},
				success : function(results){
					$("#gamesearchresultelement").empty();
					if (results.Data=="\n" || results.Data=="") {
						$("#gamesearchresultelement").html("<h1 style='text-align: center;'>No search results</h1>");
					} else{
						var $contentSectionDiv = $("<div>", {class: 'contentSectionDiv'});
						var count=1;
						var amount = results.Data.Game.length;
						$.each(results.Data.Game, function(key, gamedata){
							var $game = $("<div>", {id: gamedata.id, class: "game"});
							$game.css("background-color", randbgcolor());
							$game.html("<img style='width:50px; height:50px; margin-top:80px;' src='art/loader.gif' />");
							--amount;
							if (count==4 || amount==0) {
								var $gameInfoDiv = $("<div>", {class: 'gameInfoDiv'});
								$contentSectionDiv.append($game);
								$contentSectionDiv.append($gameInfoDiv);
								$("#gamesearchresultelement").append($contentSectionDiv);
								$contentSectionDiv = $("<div>", {class: 'contentSectionDiv'});
								count=1;
							} else {
								++count;
								$contentSectionDiv.append($game);
							};
						});
						if (true) {} else{};
						$.each(results.Data.Game, function(key, gamedata){
							$.ajax({
								dataType : "JSON",
								type : "POST",
								url : "getGameById.php",
								data : {
									id : gamedata.id
								},
								success : function(result) {
									boxart = $.isArray(result.Data.Game.Images.boxart) ? result.Data.Game.Images.boxart[1] : result.Data.Game.Images.boxart;
									
									$("#gamesearchresultelement").find("#"+result.Data.Game.id).html("<img src='"+boxart+"' />");
									$("#gamesearchresultelement").find("#"+result.Data.Game.id).append("<div class='gameInteractionBar'><img onclick='addGameToUserLibrary($(this))' src='art/plus-80-yellow.png' /><img onclick='expand($(this), \"no user\", \"no user\");' src='art/info-80.png' /></div>");

									if (typeof(result.Data.Game.Images.noimage)!="undefined" && result.Data.Game.Images.noimage!==null && result.Data.Game.Images.noimage!="") {
										$.ajax({
											type: "POST",
											url : "saveImage.php",
											data : {
												id : gamedata.id
											}, 
											success : function(e) {
												notification("Image saved!");
											}
										});
									};

								}	
							});
						});
					};
					
				}
			});
		}
	});

	// --- Profile games, friends, lent and borrow list functions --- //

	$.ajax({
		dataType : "json",
		url : "getRequests.php",
		success : function(resultdata) {
			
			$.each(resultdata["friendrequest"], function(k, data) {
				
				$.ajax({
					dataType : "json",
					type : "POST",
					url : "getUserById.php",
					data : {
						userid : data.requestid
					},
					success : function(result) {

						var $div = $("<div>", {id: "message"+result[0].id, class: "messageElement"});
						$div.css("background-color", randbgcolor());
						//$div.append("<div class='respondno' onclick='respondfriendrequest($(this))' style='cursor: pointer; width:100px; height:100px; float:right; background-color:red;'></div>");
						//$div.append("<div class='respondyes' onclick='respondfriendrequest($(this))' style='cursor: pointer; width:100px; height:100px; float:right; background-color:green;'></div>");
						$div.append("<div class='respondno' onclick='respondfriendrequest($(this))' ><img  src='art/cross-80-yellow.png' /></div>");
						$div.append("<div class='respondyes' onclick='respondfriendrequest($(this))' ><img  src='art/plus-80-yellow.png' /></div>");
						
						$div.append("<p>Friendship request from "+result[0].firstname+" "+result[0].lastname+" ("+result[0].username+")</p>");
						$div.append("<p>Lives in  "+result[0].city+"</p>");
						$div.append("<p>User platforms: "+result[0].platforms+"</p>");
						$("#userMessageContainer").append($div);

					}
				});
			});
			
			$.each(resultdata["lendrequest"], function(k, data) {
				$.ajax({
					dataType : "json",
					type : "POST",
					url : "getUserById.php",
					data : {
						userid : data.userborrowid
					},
					success : function(result) {
						var $div = $("<div>", {id: data.userborrowid+"lend"+data.gameID, class: "messageElement"});
						$div.css("background-color", randbgcolor());
						//$div.append("<div class='respondno' onclick='respondlendrequest($(this))' style='cursor: pointer; width:100px; height:100px; float:right; background-color:red;'></div>");
						//$div.append("<div class='respondyes' onclick='respondlendrequest($(this))' style='cursor: pointer; width:100px; height:100px; float:right; background-color:green;'></div>");
						$div.append("<div class='respondno' onclick='respondlendrequest($(this))'><img src='art/cross-80-yellow.png' /></div>");
						$div.append("<div class='respondyes' onclick='respondlendrequest($(this))'><img src='art/plus-80-yellow.png' /></div>");
						
						$div.append("<p>Lendrequest from "+result[0].firstname+" "+result[0].lastname+" ("+result[0].username+")</p>");
						$div.append("<p>Want's to borrow  "+data.name+" for "+data.platform+"</p>");
						$("#userMessageContainer").append($div);
					}
				});
			});
			
		}
	});

	$.ajax({
		dataType : "json",
		url : "getAllCities.php",
		success : function (result) {
			$.each(result, function(k, data) {
				$("#city").append("<option value='"+data.id+"'>"+data.name+"</option>");	
				$("#updatecity").append("<option value='"+data.id+"'>"+data.name+"</option>")	
			});
		}
	});

	$.ajax({
		dataType : "json",
		url : "getAllConsoles.php",
		success : function (result) {
			$.each(result, function(k, data) {
				$("#platforms").append("<option value='"+data.id+"'>"+data.console+"</option>")	
			});
		}
	});

	$.ajax({
		dataType: "json",
		type : "POST",
		url : "getloginuser.php",
		success : function(result) {
			if (result.img!="" && result.img!="No image") {
				$(".userimage").append("<img src='art/userimages/"+result.img+"'>");
			} else {
				$(".userimage").append("<img src='art/head.png'>");
			}
			$("#userdatatlist").append("<li>Username: "+result.username+"</li>");
			
			$("#userdatatlist").append("<li>Firstname: "+result.firstname+"</li>");
			$("#userdatatlist").append("<li>Lastname: "+result.lastname+"</li>");
			$("#userdatatlist").append("<li>Email: "+result.email+"</li>");
			$.ajax({
				dataType : "json",
				type : "POST",
				url : "getCityName.php",
				data : {
					cityid : result.city
				},
				success : function(data) {
					if (typeof(data[0])!=="undefined") {
						$("#userdatatlist").append("<li>Lives: "+data[0].cityname+"</li>");		
					} else{
						$("#userdatatlist").append("<li style='color:#C54654;'>Lives: No city selected</li>");
					};
					
				}
			});
			$.ajax({
				dataType : "json",
				type: "POST",
				url: "getUserPlatformlistByName.php",
				data : {
					platforms : result.platforms
				},
				success: function(data) {
					$("#userdatatlist").append("<li>Platforms: </li><li style='color:#C54654;'>"+data[0].platforms+"</li>");
				}
			});
		}
	});
	updateUserFriendsSection();
	updateUserLentSection();
	updateUserLibrarySection();
	updateUserBorrowSection();
	
	
}
	
</script>
	
	<header id="banner" class="body">
		<h1>GameLend <section>A Gaming Solidarity Initiative</section></h1>
		<h1>BETA <section>0.2</section></h1>
	</header>

	<section id="newsboard" class="body, profileSection">
		<img class='showhideicon' src='art/arrow-right-80-yellow.png' />
		<header class="profilebanners"><h2>News</h2></header>
		<div id="newsboardContainer" class="profileResourceContainer">
			<ol id="posts-list" class="hfeed">

				<li><article class="hentry">	
					<header>
						<h2 class="entry-title">BETA 0.2</h2>
					</header>

					<footer class="post-info">
						<abbr class="published"><!-- YYYYMMDDThh:mm:ss+ZZZZ -->
							26th February 2014
						</abbr>

						<address class="vcard author">
							By <a class="url fn" href="mailto: gamelendcom@gmail.com">Manfred Johansson</a>

						</address>
					</footer><!-- /.post-info -->

					<div class="entry-content">
						<h3>General info</h3>
						<p>I have now completely remade the GameLend structure and made a full fledged BETA version which contains all the functions that i feel will make both browsing and requesting game lending simplified. The result is a one-page-solution which i think is the better way of displaying the functions and resources since the idea behind GameLend is not to present a vast collection of redundant functions and resources, but a elementary community with the sole purpose of simplifying game sharing.</p>
						<p>All registered users up to date have all been deleted because of reconstruction and implementation of email verification. This forces everyone who have already registered to go through the registration process again. I do apologize for this but it is due to the need for testing of the new registration procedure.</p>
						<br /><p>Fixed bugs:</p>
						<br /><ul>
							<li>No query results from own games</li>
							<li>Edit profile element duplicates data if canceled and open</li>
							<li>During registration prompt if username, email already exists</li>
							<li>Not remove image if no image is selected in image upload element</li>
							<li>Registered username can be duplicate of existing</li>
							<li>Remove possibility of lending own games</li>
							<li>Account for minimum data from thegamesdb.net</li>
						</ul>
						<br />
						<p>Improvements:</p>
						<br /><ul>
							<li>Separate search and borrow querybox</li>
							<li>Prompt if trying to add same game to library</li>
							<li>During registration prompt if username, email already exists</li>
							<li>Remove profile image </li>
							<li>Preview profile image before upload</li>
							<li>Message element for user feedback, top of window</li>
							<li>Indication of friendship in the search function</li>
							<li>Only show login element before logged in</li>
							<li>Interaction symbols on resource elements</li>
							<li>Expanding and collapsing resource elements</li>
							<li>Show number of elements on all resource elements</li>
							<li>Cache all thegamesdb.net  queries</li>
							<li>Resize and cache all game images</li>
							<li>Load indicator while loading queries</li>
							<li>Indication if no search results</li>
							<li>Show from whom the game was borrowed and whom the game was lent to</li>
							<li>Own icons and graphics</li>
							<li>Email validation on register</li>
						</ul>
						<br/>
						<p>There are more bugfixes and improvements but these are the ones I remember and have written down during development.</p>
						<p>I hope you enjoy this version of GameLend and if you counter any bugs, lack of functions or any other problems please report them in the bottom right form.</p>
					</div><!-- /.entry-content -->
				</article></li>

				<li><article class="hentry">	
					<header>
						<h2 class="entry-title">Release of BETA 0.1.1</h2>
					</header>

					<footer class="post-info">
						<abbr class="published"><!-- YYYYMMDDThh:mm:ss+ZZZZ -->
							2th February 2014
						</abbr>

						<address class="vcard author">
							By <a class="url fn" href="mailto: gamelendcom@gmail.com">Manfred Johansson</a>

						</address>
					</footer><!-- /.post-info -->

					<div class="entry-content">
						<p>Ok, so about 80 hours of coding has resulted in GameLend BETA 0.1. A shell for you game lovers to share your libraries with one another and I would like to start by saying that it’s far from finished! There are some security issues and obviously the design lacks… basicly everything. I am releasing this BETA 0.1 to get responses on the “design”, structure, functions and so you can report all the bug’s I’m to lazy to find myself.</p>
						<p>So, please feel free to use this community to register yourself, your games and discover what treasures your friends game libraries hold. And don’t hesitate to fill out the bug report form on the bottom right with ANY type of thoughts on the site: Typo’s, bug’s, design flaws, missing functions etc.</p>
						<br /><p>The functions available at the moment:</p>
						<br /><ul>
							<li>Register</li>
							<li>Secure login</li>
							<li>Edit personal information</li>
							<li>Send friend request</li>
							<li>Respond to friend request</li>
							<li>Send lend request</li>
							<li>Respond to lend request</li>
							<li>Unfriend</li>
							<li>Register retuned game</li>
							<li>Add game to library</li>
							<li>Remove game from library</li>
							<li>Searching for games on the thegamesdb.net</li>
							<li>Searching friends game libraries</li>
							<li>Search users</li>
							<li>And report bug’s</li>
						</ul>
						<br />
						<p>PS There is no interaction design implemented. So you basicly have to do all the work yourselves.</p>
					</div><!-- /.entry-content -->
				</article></li>



			</ol><!-- /#posts-list -->
		</div>
	</section>
	
	<section id="profile" class="body, profileSection">
		<div id="profileactionpannel">
			<img src="art/cogg-80.png" id="editUser" class="profileactionicons" title="Change personal information" />
			<img src="art/logout-80-yellow.png" onclick="logoutUser()" id="logout" class="profileactionicons" title="Logout" />
		</div>
			
			<figure class="userimage">	
			</figure>
			<header class="profilebanners"><h2>User Profile </h2></header>
			<article id="user-info">
				
				<div class="userdatalist">
				<ul id="userdatatlist">
					
				</ul>
				</div>
				
			</article>
	</section>
	
		<section id="messageboard" class="body, profileSection">
			<img class='showhideicon' src='art/arrow-right-80-yellow.png' />
			<header class="profilebanners"><h2>Messages (<span id="numberMessages"></span>)</h2></header>
			<div id="userMessageContainer" class="profileResourceContainer">
			</div>
		</section>

		<section id="userFriends" class="body, profileSection">
			<img class='showhideicon' src='art/arrow-right-80-yellow.png' />
			<img class='addicon' src='art/plus-80-yellow.png' />
			<header class='profilebanners'><h2>Friends (<span id="numberFriends"></span>)</h2></header>
			<div id="searchFriendsContainer" class="profileSearchContainer">
				<section class="body, searchelement">
					<div class="searchTypeElement">
						
						<div class="searchform">
							<input type="text" placeholder="search users" id="searchuserfield" />
							<div class="styled-select">
							<select id="city" onchange="searchuser()">
								<option value="0">City</option>
								
							</select>
							</div>
							<div class="styled-select">
							<select id="userplatform" onchange="searchuser()">
								<option value="">Platform</option>
								
							</select>
							</div>
						</div>
					</div>
				</section>
				<section id="usersearchresultelement" class="body, searchelement">
				</section>
			</div>
			<div id="userFriendsContainer" class="profileResourceContainer">
			</div>
		</section>

		<section id="usergamelibrary" class="body, profileSection">
			<img class='showhideicon' src='art/arrow-right-80-yellow.png' />
			<img class='addicon' src='art/plus-80-yellow.png' />
			<header class="profilebanners"><h2>Game Library (<span id="numberLibrary"></span>)</h2></header>
			<div id="searchGamesContainer" class="profileSearchContainer">
				<section class="body, searchelement">
					<div class="searchTypeElement">
						
						<div class="searchform">
							<input type="text" placeholder="search games" id="searchgamefield">
							<div class="styled-select">
							<select id="platform">
								<option value="">Platform</option>
								
							</select>
							</div>
							<div class="styled-select">
							<select id="genre">
								<option value="">Genre</option>
							</select>
							</div>
							
							
						</div>
					</div>
				</section>
				<section id="gamesearchresultelement" class="body, searchelement">
				</section>
			</div>

			<div id="userLibraryContainer" class="profileResourceContainer">
			</div>
			
		</section>

		<section id="lentGames" class="body, profileSection">
			<img class='showhideicon' src='art/arrow-right-80-yellow.png' />
			<header class="profilebanners"><h2>Lent Games (<span id="numberLent"></span>)</h2></header>
			<div id="userLentContainer" class="profileResourceContainer">
			</div>
		</section>

		<section id="borrowedgames" class="body, profileSection">
			<img class='showhideicon' src='art/arrow-right-80-yellow.png' />
			<img class='addicon' src='art/plus-80-yellow.png' />
			<header class="profilebanners"><h2>Borrowed Games (<span id="numberBorrow"></span>)</h2></header>

			<div id="searchBorrowGamesContainer" class="profileSearchContainer">
				<section class="body, searchelement, contentelement">
					<div class="searchTypeElement">
						
						<div class="searchform">
							<input type="text" placeholder="search games" id="borrowsearchfield">
							<div class="styled-select">
							<select onchange="searchBorrowGames('')" id="friendlibrary">
								<option value="">Friend</option>
								
							</select>
							</div>
							<div class="styled-select">
							<select onchange="searchBorrowGames('')" id="borrowPlatform">
								<option value="">Platform</option>
								
							</select>
							</div>
							<div class="styled-select">
							<select onchange="searchBorrowGames('')" id="borrowGenre">
								<option value="">Genre</option>
							</select>
							</div>
							
						</div>
					</div>
				</section>
				<section id="searchresultelement" class="body, searchelement, contentelement, profileSection">
					
				</section>
			</div>

			<div id="userBorrowedContainer" class="profileResourceContainer">
			</div>
		</section>
		<div style="height:100px;"></div>
	
	<div id="dialog-confirm-removegame" title="Remove game from library?">
	  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>The game will be removed from your library</p>
	</div>
	<div id="dialog-confirm-removeLend" title="Remove game from library?">
	  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Have you regained your game?</p>
	</div>
	<div id="dialog-confirm-deletefriendship" title="Delete friendship?">
	  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you want to remove this user from your friendlist?</p>
	</div>
	<div id="dialog-profileedit" title="Edit Profile">
	  <p class="validateTips">All form fields are required.</p>
	 
	<form id="editform" method="post" enctype="multipart/form-data" action="updateUserinfo.php">
	  <fieldset>
	    <label for="firstname">Firstname</label>
	    <input type="text" name="firstname" id="firstname" class="text ui-widget-content ui-corner-all" value="" />
	    <label for="lastname">Lastname</label>
	    <input type="text" name="lastname" id="lastname" class="text ui-widget-content ui-corner-all" value="" />
	    <label for="platforms">Add/remove platforms</label>
	    <ul class="platformselect">	
		</ul>
		
		<select id="platforms" onchange="addPlatform(this);">
			<option value="">Select game consoles you own</option>
		</select><br />
		<label for="city">City</label><br/>
		<select id="updatecity" name="city">
			<option value="0">City</option>
		</select><br />
		<input type="checkbox" name="removeimg" id="removeimg" value="true">
		<label for="removeimg">Remove profile image</label><br/>
		<label for="img">Add/change profile image</label><br/>
		<input id="imageUpload" type="file" name="img" />
	  </fieldset>
	  </form>
	  <div id="uploadPreview"></div>
	</div>

	<div id="dialog-requestlend" title="Borrow game">
  		
	</div>

	<div id="dialog-requestlend-borrow" title="Borrow game">
	  <p class="validateTips">Select from whom to borrow</p>
	 
	  <form>
	  <fieldset>
		<select id="gameholders">
			
		</select>
	  </fieldset>
	  </form>
	</div>

<div id="formcontainer">
<div id="bugformelement">
	<h2><span>&#9652;</span> Feedback form</h2>
	<form id="bugform" method="post" action="api/bug.php" >
		<input type="hidden" name="url" value='<?php echo $_SERVER["REQUEST_URI"]; ?>'/>
		<input type="text" size="12" name="shortdescription" placeholder="short decription"><br />
		
		<textarea rows="5" cols="20" name="description" wrap="physical" placeholder="Details"></textarea><br />
		<input value="submit" name="submit">
	</form> 
</div>
</div>
<script type="text/javascript">
$('#bugformelement h2').click(function() {
	if ($("#bugformelement").height()==50) {
		$('#bugformelement').animate({height:'250px'});
		$('#bugformelement span').html('&#9660;');
	} else{
		$('#bugformelement').animate({height:'50px'});
		$('#bugformelement span').html('&#9650;');
	};
});
</script>
</body>

</html>