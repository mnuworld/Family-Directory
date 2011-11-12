// Set the default key press position and maximum
var keyPressPosition = 0;
var keyPressMax = 0;
        
// Perform any resizing or positioning after the document has been loaded
$(document).ready(function() {
    // Position the footer
    positionFooter();
    
    // Position the toolbox if it exists
    if($("#toolbox").length) {
        positionToolboxDropdown();
    }
    
    // If search result divs exists, position them
    if($(".search_results").length) {
        // Call moveResults on key up
        $(document).keyup(function(){moveResults(null);});

        // Position the results
        positionResults();
    }
    
    // If the show user table exists, show the edit features
    //if($("#show_user_table").length) {
        // Change the cursor to a pointer when over an editable field
        //$(".edit_data").css("cursor","pointer");
        
        // On mouse over, highlight the text
        //$(".edit_data").mouseover(editOnMouseOver);
        
        // On mouse out, un-highlight the text
        //$(".edit_data").mouseout(editOnMouseOut);
        
        // Set the onclick event for text items
        //$(".text").click(editTextOnClick);
    //}
    
    // Position ancestor tiles if they exist
    if($("#family_tree_container").length) {
        // Remove the footer
        $("#footer").hide();
        
        // Add the keyup event handler to move ancestor tiles
        $(document).keyup(function(){moveFamilyTreeDivs(null);});
        
        // Set the frame height and overflow
        $("#family_tree_container").css("height",browserHeight() - $("#header").height() - $("#footer").height() - $("#search_tab").height() - 15);
        $("#container").css("overflow","hidden");
        
        // Position ancestor/descendant tiles depending on what is shown
        if($(".anc").length) {
            positionAncestors(6);
        } else if($(".dec").length) {
            positionDescendants(6);
        }
    }
});

// Perform any resizing or position after the browser has been resized
$(window).resize(function() {
    // Position the toolbox
    positionToolboxDropdown();

    // Position the footer
    positionFooter();
    
    // If search result divs exists, position them
    if($(".search_results").length) {
        // Position the results
        positionResults();
    }
    
    // Position ancestor tiles if they exist
    if($("#family_tree_container").length) {
        // Remove the footer
        $("#footer").hide();
        
        // Set the frame height and overflow
        $("#family_tree_container").css("height",browserHeight() - $("#header").height() - $("#footer").height() - $("#search_tab").height() - 15);
        $("#container").css("overflow","hidden");
        
        // Position ancestor/descendant tiles depending on what is shown
        if($(".anc").length) {
            positionAncestors($("#level").val());
        } else if($(".dec").length) {
            positionDescendants($("#level").val());
        }
    }
});

function toggleSearchForm(show)
{
    // Show/Hide the search bar and move the search tab down/up
    if(show) {
        $("#search_form").slideDown("slow",function() {
            $("#search_tab").html("<a href='#' onclick='toggleSearchForm(0); return false;'>Close</a>");
            positionFooter();
        });
    } else {
        $("#search_form").slideUp("slow",function() {
            $("#search_tab").html("<a href='#' onclick='toggleSearchForm(1); return false;'>Open</a>");
            positionFooter();
        });
    }
}

function highlightSelection(div,highlight)
{
    // Highlight the user tiles
    if(highlight) {
        div.style.backgroundColor = "#CFD9FC";
        div.style.cursor = "pointer";
    } else {
        div.style.backgroundColor = "#FFFFFF";
    }
}

function positionResults()
{
    var width = browserWidth();
    
    // Resize the content container because the search result containers are positioned absolute
    containerHeight = $(".search_results").height() + $(".dir_arrow").height() + 25;
    $("#content_container").css("height",containerHeight);
    
    // Change the overflow of the container to prevent horizontal scrolling
    $("#container").css("overflow-x","hidden");

    // Loop through each child and set their position
    var divOffset = (width - 400) / 2;
    var child = $("#content_container").children();
    for(i = 0; i < $("#content_container").children().length; i++) {
        child.css("left",divOffset);
        divOffset += 410;
        keyPressMax++;
        child = child.next();
    }
    
    // Call to moveResults to hide the arrows on initial load if required
    moveResults(null);
}

function moveResults(button)
{
    // Get the keypress value
    var keyPress = (window.event) ? event.keyCode : null;
    
    // If the value of button is not null, then use it
    keyPress = button ? button : keyPress;

    // Move the results divs based on the key pressed
    switch(keyPress) {
        case 37:
            if(keyPressPosition > 0) {
                $(".search_results").animate({left:"+=410"});
                keyPressPosition--;         
            }
        break;

        case 39:
            if(keyPressPosition < keyPressMax  - 1) {
                $(".search_results").animate({left:"-=410"});
                keyPressPosition++;
            }
        break;
    }
    
    // Hide the arrow buttons when direction is not available
    if(keyPressPosition == 0) {
        $("#left_button").css("visibility","hidden");
    } else {
        $("#left_button").css("visibility","visible");
    }
    if(keyPressPosition == keyPressMax - 1) {
        $("#right_button").css("visibility","hidden");
    } else {
        $("#right_button").css("visibility","visible");
    }
}

function positionToolboxDropdown()
{
    // Position the toolbox
    $("#toolbox_dropdown").css("left",$("#toolbox").offset().left + 1);
    $("#toolbox_dropdown").css("top",-12);
    $("#toolbox_dropdown").css("width",$("#toolbox").width() + 18);
    
    // Add the event listeners
    $("#toolbox").mouseover(toolboxMouseOver);
    $("#toolbox").mouseout(toolboxMouseOut);        
    $("#toolbox").click(toolboxClick);
}

function toolboxMouseOver()
{
    // Highlight the toolbox on mouse over and show the down arrow
    $("#toolbox").css("backgroundColor","#114477");
    $("#toolbox img").css("visibility","visible");
}

function toolboxMouseOut()
{
    // Un-highlight the toolbox on mouse out and hide the down arrow
    $("#toolbox").css("backgroundColor","#003366");
    $("#toolbox img").css("visibility","hidden");
}

function toolboxClick()
{
    // Show the drop down when the toolbox is clicked
    $("#toolbox_dropdown").show(1,
        function() {
            $(document).click(documentClick);
            $("#toolbox").unbind("mouseout");
        }
    );
}

function documentClick()
{
    // Hide the drop down anytime the document is clicked
    $("#toolbox_dropdown").hide();
    $(document).unbind("click");
    toolboxMouseOut();
    $("#toolbox").mouseout(toolboxMouseOut);
}

function showMore(show)
{
    var table = $("#show_user_table tr");
    var row = table.next();
    
    // Loop through each table row and show the ones that are hidden
    for(var i = 0; i < table.length; i++) {
        row = row.next();
        if(show) {
            $(".show_more").css("display","");
            $("#show_more").html('<a href="#" onclick="showMore(0); return false">+ Show Less</a>');
        } else {
            $(".show_more").css("display","none");
            $("#show_more").html('<a href="#" onclick="showMore(1); return false">+ Show More</a>');
        }
    }
}

function editOnMouseOver()
{
    $(this).css("border-bottom","2px solid blue");
}

function editOnMouseOut()
{
    $(this).css("border-bottom","0px");
}

function editTextOnClick()
{
    // Get the id and text of the field being edited
    var id = $(this).attr("id");
    var text = $(this).html();
    
    // If the field is empty, remove the click to edit text
    if(text == "click to edit") {
        text = "";
    }        
    
    // Remove the event handlers for the click and mouse over events
    $(this).unbind("click");
    $(this).unbind("mouseover");
    
    // Replace the html of the field with a text field
    $(this).html("<input type='text' class='edit_input' value='"+text+"' id='"+id+"'>");
    
    // Remove the border set by the mouse over event
    $(this).css("border-bottom","0px");
    
    // Put the focus on the text field
    $(this).children().focus();
    
    // Set event handlers to update the database on blur or when enter is pressed
    $(this).children().blur(submitTextOnBlur);
    $(this).children().keydown(submitTextOnBlur);
}
        
function submitTextOnBlur(e)
{
    // Check the type of the event fired to see if it is either a blur, enter key, or tab key
    if((e.type == "blur") || ((e.type == "keydown") && ((e.keyCode == 9) || (e.keyCode == 13)))) {
        // Get the id of the subject
        var id = $("#hidden_user_id").val();
        
        // Get the text of the field and encode the text for uri
        var value = $(this).val();
        value = escape(value);
        
        // Get the parent and the name of the field
        var parent = $(this).parent();
        var field = $(this).attr("id");
        field = field.substring(field.indexOf("_") + 1);
        
        //$(parent).load("/ajax/updateuser.php?f="+field+"&v="+value+"&id="+id+"&t="+Math.random(),function() {
            // If the field was erased, set the the text to click to edit
            if(value == "") {
                value = "click to edit";
            }
            
            // Update the html with the new value and replace the event handlers that were removed by the click handle
            $(parent).html(unescape(value));
            $(parent).click(editTextOnClick);
            $(parent).mouseover(editOnMouseOver);
        //});
    }
}

function changeGeneration(gen)
{
    if($(".anc").length) {
        positionAncestors(gen);
    } else if($(".dec").length) {
        positionDescendants(gen);
    }
}

function positionAncestors(gen)
{
    // Hide all the divs so that we can show them generatin by generation during a generation change
    $(".family_tree_tile_container").hide();
    $(".family_tree_vertical_line").hide();
    $(".family_tree_horizontal_line").hide();
    $(".gen_s").hide();
    
    // Show the generations starting with the first up to the gen'th generation
    // Must be called from 1 to gen because current generation tile positions are generated from the previous generation position
    positionAncestorsGenerations(1,gen);
}

function positionAncestorsGenerations(generationCount,generation)
{
    heightArray = new Array();
    var height = $("#content_container").height();
    
    // Height and width of each user tile
    var tileWidth = $(".family_tree_tile_container").width();
    var tileHeight = $(".family_tree_tile_container").height()
    
    // Get the height offset of the top-most user in the generation which is equal to the location of the child in the previous generation
    var startHeight = generationCount > 2 ? $("#anc_"+(generationCount-1)+"_1").css("top").slice(0,-2) : (height - tileHeight) / 2;

    // Set the vertical and horizontal spacing parameters for each generation
    var xSpacing = 20;
    var ySpacing = generationCount == 1 ? 0 : Math.pow(2,(generation - generationCount + 1)) / 3;
    
    // Variable to hold the amount of pixels to move the subject user when showing siblings
    var moveHeight;

    // Show the generation and indent right based on the current generation
    $(".gen_"+generationCount).show();
    $(".gen_"+generationCount).css("left",(generationCount-1)*(tileWidth+xSpacing)+5+"px");  
    
    // Loop through each user tile of the generation. Start at 1 to help with div indexes
    for(var personCount = 1; personCount < Math.pow(2,generationCount-1) + 1; personCount++) {
        // The height of the current person is the starting height +/- the vertical spacing
        heightArray[personCount] = startHeight-(tileHeight*ySpacing)*Math.pow(-1,(personCount-1)%2);
        
        // Move the user tile down
        $("#anc_"+generationCount+"_"+personCount).css("top",heightArray[personCount]+"px");
        
        // If the person count is a multiple of 2, it is the wife and we need to position the tile relative to the husband
        if(personCount % 2 == 0 && personCount != Math.pow(2,generationCount-1)) {
            // Get the height of the husband
            startHeight = $("#anc_"+(generationCount-1)+"_"+(personCount/2+1)).css("top").slice(0,-2);
            
            // Update the vertical spacing of the wife relative to the husband
            ySpacing = Math.pow(2,(generation - generationCount + 1)) / 3;
        }
    }
    
    // If the generation is greater than 1, we need to position the brackets
    if(generationCount > 1) {
        for(var i = 1; i < Math.pow(2,generationCount-1) + 1; i+=2) {
            $("#v_"+generationCount+"_"+(i-Math.floor(i*0.5))).css("height",heightArray[i+1]-heightArray[i]-tileHeight-1+"px");
            $("#v_"+generationCount+"_"+(i-Math.floor(i*0.5))).css("top",heightArray[i]+tileHeight+"px");
            $("#v_"+generationCount+"_"+(i-Math.floor(i*0.5))).css("left",((generationCount-1)*(tileWidth+xSpacing))+5+50+"px");
            $("#h_"+generationCount+"_"+(i-Math.floor(i*0.5))).css("width","69px");
            $("#h_"+generationCount+"_"+(i-Math.floor(i*0.5))).css("top",(heightArray[i+1]+heightArray[i]+tileHeight)/2+"px");
            $("#h_"+generationCount+"_"+(i-Math.floor(i*0.5))).css("left",((generationCount-2)*(tileWidth+xSpacing))+tileWidth+6+"px");
        }
    }
    
    // If the generation count is 1, then we show the siblings if required
    if($("#siblings").val() == 1 && generationCount == 1) {
        // Vertically align the siblings
        $(".gen_s").css("left",(generationCount-1)*(tileWidth+xSpacing)+5+"px");
        
        // Get the number of siblings plus the subject
        var siblingCount = $(".gen_s").length;
        
        // Loop through sibling times for each sibling to position them
        for(var n = 2; n < siblingCount + 2; n++) {
                $("#anc_s_"+n).css("top",startHeight+(n-1)*tileHeight+"px");
                moveHeight = (n-1)*tileHeight/2;
        }
        
        // Move up the subject user and the siblings by moveHeight pixels
        $(".gen_1").animate({top:"-="+moveHeight+"px"},0);
        $(".gen_s").animate({top:"-="+moveHeight+"px"},0);
        
        // Show the siblings
        $(".gen_s").show();
    }
    
    // If the current generation is still less than the total number of generations, re-call this method for the next generation
    if(generationCount < generation) {
        positionAncestorsGenerations(generationCount+1,generation);
    }  
}

function positionDescendants(generation)
{
    // Hide all the divs so that we can show them generatin by generation during a generation change
    $(".family_tree_tile_container").hide();
    $(".dec").css("left","0px");
    //$(".family_tree_vertical_line").hide();
    //$(".family_tree_horizontal_line").hide();
    
    var tileWidth = $(".family_tree_tile_container").width();
    var tileHeight = $(".family_tree_tile_container").height();
    var width = browserWidth();
    var startWidth = (width - tileWidth) / 2;
    var startHeight = browserHeight() - $("#content_container").height();
    var xSpacing = 20;
    var ySpacing = 50;
    var parentXLocation;
    var childXLocation;
    var divId;
    var firstIndex;
    var secondIndex;
    var parentId;
    var userId;
    var spouseId;
    var siblingCount = 0;
    var currentSibling = 1;
    var divLocations = new Array();
    var xDifference;
    var classes;
    var currentDiv;
    var xCurrent;
    var xDifference;
    
    // Loop through generations up until the gen'th generation or when you run out of people
    for(var generationCount = 1; generationCount <= generation && $(".gen_"+generationCount).length; generationCount++) {
        // Position the generations vertically
        $(".gen_"+generationCount).css("top",startHeight+((generationCount-1)*(ySpacing+tileHeight))+"px");
        
        // Loop through each user in the generation and position them relative to their parents
        $(".gen_"+generationCount).each(function(index) {
            // Get the id of the div
            divId = $(this).attr("id");
            
            // Get the indexes of all '_' in the id name
            firstIndex = divId.indexOf("_");
            secondIndex = divId.lastIndexOf("_");
            
            // Get the current user's id
            userId = $(this).attr("id").slice(firstIndex+1,secondIndex);
            
            // Get the user's parent's id
            parentId = $(this).attr("id").slice(secondIndex+1);
            
            // Space the first generation different than the others
            if(parentId == 0) {
                // Move the div and push the div location to the array
                $(this).css("left",startWidth+"px");
                divLocations.push([generationCount,$(this).attr("id"),startWidth,parentId,userId]);
            } else {
                // Get the parent's spouse's id
                classes = $("div[id^='dec_"+parentId+"']").attr("class");
                spaceIndex = classes.lastIndexOf(" ");
                spouseId = parseInt(classes.substr(spaceIndex+11));
                
                // Get the location of the parent div
                parentXLocation = parseInt($("div[id^='dec_"+parentId+"']").css("left").slice(0,-2)) + (tileWidth / 2);

                // Get the number of siblings the current user has left to position
                if(siblingCount == 0) {
                    siblingCount = $(".parent_id_"+parentId).length;
                }
                
                // Align the fist sibling based on the parent position and remaing siblings based on previous sibling position
                if(currentSibling == 1) {
                    childXLocation = parentXLocation - ((siblingCount*tileWidth) / 2) - (xSpacing / 2) * (siblingCount - 1);
                } else {
                    childXLocation += xSpacing + tileWidth;
                }
                
                $(this).css("left",childXLocation+"px");
                divLocations.push([generationCount,$(this).attr("id"),childXLocation,parentId,userId]);
                
                // If the current sibling is the last one, reset the family flags otherwise increment the current sibling
                if(currentSibling == siblingCount) {
                    siblingCount = 0;
                    currentSibling = 1;              
                } else {
                    currentSibling++;
                }
                
            }
        });
        
        // Loop through the locations and compare each user in a generation to test for tile overlap
        for(var i = 0; i < divLocations.length - 1; i++) {
            // If the generation is the same and the first tile is overlapped by the second, move every tile to the right over
            if(divLocations[i][0] == divLocations[i+1][0] && divLocations[i][2] + 280 > divLocations[i+1][2]) {
                // Get the number of pixels to shift all tiles to the right
                xDifference = divLocations[i][2] + 280 - divLocations[i+1][2];
                
                // Start at the first node and push everything to the right over by xDifference
                currentDiv = $("#"+divLocations[i][1]).next();
                for(var j = i + 1; currentDiv.hasClass("dec"); j++) {
                    // Loop through the divLocations array to update the x locations of each node that has been moved
                    // Can not do this using the loop index because the tree traversal is in a different order than the array
                    for(var k = 0; k < divLocations.length; k++) {
                        if(divLocations[k][1] == currentDiv.attr("id")){
                            divLocations[k][2] += xDifference;
                        }
                    }
                    
                    // Get the current x position
                    xCurrent = parseInt(currentDiv.css("left").slice(0,-2));
                    
                    // Move the tile over
                    currentDiv.css("left",xCurrent+xDifference+"px");
                    
                    // Move to the next tile
                    currentDiv = currentDiv.next();
                }
            }
        }
        
        // Show the generation
        $(".gen_"+generationCount).show();
    }        
}

function moveFamilyTreeDivs(button)
{
    // Get the keypress value
    var keyPress = (window.event) ? event.keyCode : null;
    
    // If the value of button is not null, then use it instead of keypress
    keyPress = button ? button : keyPress;

    // On a keypress, move the tiles in the right direction
    switch(keyPress)
    {
        case 37:
            $(".family_tree_tile_container").animate({left: "+=300"});
            $(".family_tree_vertical_line").animate({left: "+=300"});
            $(".family_tree_horizontal_line").animate({left: "+=300"});
            break;
        case 38:
            $(".family_tree_tile_container").animate({top: "+=150"});
            $(".family_tree_vertical_line").animate({top: "+=150"});
            $(".family_tree_horizontal_line").animate({top: "+=150"});
            break;
        case 39:
            $(".family_tree_tile_container").animate({left: "-=300"});
            $(".family_tree_vertical_line").animate({left: "-=300"});
            $(".family_tree_horizontal_line").animate({left: "-=300"});
            break;
        case 40:
            $(".family_tree_tile_container").animate({top: "-=150"});
            $(".family_tree_vertical_line").animate({top: "-=150"});
            $(".family_tree_horizontal_line").animate({top: "-=150"});
            break;
    }
}

function showSiblings(show)
{
    // Show the siblings
    if(show == 1) {
        $(".sibling").show();
    } else {
        $(".sibling").hide();
        var height = browserHeight();
        
        var startHeight = (height - $(".family_tree_tile_container").height()) / 2;
        $("#anc_1").css("top",startHeight+"px");
    }
}

function hideUsers(tile,gender,id)
{
    // Close the users box
    $("#change_" + tile).hide(500);
    $("#change_" + tile).html("");
    $("#" + tile + "_x_box").css("visibility","hidden");
    
    // Change the onclick events
    $("#" + tile).attr("onclick","changeUserTile('" + tile + "'," + gender + "," + id + ")");
    $("#" + tile + "_x_box").attr("onclick","hideUsers('" + tile + "'," + gender + "," + id + ")");
}

function changeUserTile(tile,gender,id)
{
    // Show the loader
    $("#" + tile + "_x_box img").attr("src","images/loader_white_small.gif");
    $("#" + tile + "_x_box").css("visibility","visible");

    // Make the ajax call
    $("#change_" + tile).load("ajax/change/?t="+tile+"&g="+gender+"&i="+id,function() {
        // Show the div
        $("#change_" + tile).show(500);
        
        // Show the x box
        $("#" + tile + "_x_box img").attr("src","images/x_box.png");
        
        // Change the onclick event
        $("#" + tile).attr("onclick","hideUsers('" + tile + "'," + gender + "," + id + ")");
    });
}

function selectUser(object,tile,id,gender)
{
    // Hide the users box and change the tile div to the selected user
    hideUsers(tile);
    $("#hidden_" + tile + "_id").val(id)
    $("#" + tile).html($(object).html());
    $("#" + tile).attr("onclick","changeUserTile('" + tile + "'," + gender + "," + id + ")");
    $("#" + tile + "_x_box").attr("onclick","hideUsers('" + tile + "'," + gender + "," + id + ")");
}

function checkForm()
{    
    // Validate the email
    validateEmail = checkEmail($("#email").val());

    // Check the required fields
    if($("#first_name").val() == "") {
        alert("Please enter a first name.");
        return;
    } else if($("#last_name").val() == "") {
        alert("Please enter a last name.");
        return;
    } else if($("#email").val() != "" && !validateEmail) {
        alert("Please enter a valid email address.");
        return;
    } else if($("#education_add").css("display") != "none" && $("#education_add").val() == "") {
        alert("Please enter a new education.");
        return;
    } else if($("#profession_add").css("display") != "none" && $("#profession_add").val() == "") {
        alert("Please enter a new profession.");
        return;
    } else if($("#city_add").css("display") != "none" && $("#city_add").val() == "") {
        alert("Please enter a new city.");
        return;
    } else if($("#state_add").css("display") != "none" && $("#state_add").val() == "") {
        alert("Please enter a new state.");
        return;
    } else if($("#country_add").css("display") != "none" && $("#country_add").val() == "") {
        alert("Please enter a new country.");
        return;
    } else if($("#marital_status").val() == "blank") {
        alert("Select a marital status.");
        return;
    } else {
        // Show the loader
        $("#save_loader").css("visibility","visible");
        
        // Set the submit flag and submit the form
        $("#hidden_submit_form").val("1");
        $("#user_form").submit();
    }
}

function checkAddField(form,field)
{
    // Show/Hide the text field when adding a new pre-defined entry
    if(form.elements[field].value == "add") {
        form.elements[field+"_add"].style.display = "";
    } else {
        form.elements[field+"_add"].style.display = "none";
    }
}

function checkPasswordChangeForm(form)
{
    // Check the required fields for the change password form
    if(form.password_new_1.value != form.password_new_2.value) {
        alert("New Passwords Do Not Match");
    } else if(form.password_current && form.password_current.value == "") {
        alert("Please enter your current password");
    } else {
        form.hidden_password_change_submit.value = "1";
        form.submit();
    }
}

function checkEmail(email) {
    // Valid email using the regex
    var expressionCheck = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    if(expressionCheck.test(email) == false) {
        return false;
    } else {
        return true;
    }
}

function showLogs(view,id)
{
    // Make the ajax call
    $("#log_records_container").load("ajax/logs/?v="+view+"&i="+id,function() {
        // Position the footer after a load
        positionFooter();
    });
}

function positionFooter()
{
    // Get all of the heights required
    var windowHeight = browserHeight();
    var containerHeight = $("#container").height();
    var headerHeight = 42;
    var footerHeight = $("#footer").height();
    var footerTop;
    
    // Calculate the number of pixels from the top to move the footer
    if(containerHeight+headerHeight > (windowHeight - footerHeight)) {
        footerTop = containerHeight+headerHeight;
    } else {
        footerTop = windowHeight - footerHeight - footerHeight - 10;
    }
    
    // Position the footer
    $("#footer").css("top",footerTop);
}

function browserHeight()
{
    var height;

    // Get the height of the viewport
    if (typeof window.innerWidth != 'undefined') {
        height = window.innerHeight;
    } else if (typeof document.documentElement != 'undefined' && typeof document.documentElement.clientWidth != 'undefined' && document.documentElement.clientWidth != 0) {
        height = document.documentElement.clientHeight;
    } else {
        height = document.getElementsByTagName('body')[0].clientHeight;
    }

    return height;  
}

function browserWidth()
{
    var width;
    
    // Get with width of the viewport
    if (typeof window.innerWidth != 'undefined') {
        width = window.innerWidth;
    } else if (typeof document.documentElement != 'undefined' && typeof document.documentElement.clientWidth != 'undefined' && document.documentElement.clientWidth != 0) {
        width = document.documentElement.clientWidth;
    } else {
        width = document.getElementsByTagName('body')[0].clientWidth;
    }
    
    return width;
}

/**
 * Function to display the contents of an object
 */
function inspect(obj, maxLevels, level)
{
  var str = '', type, msg;

    // Start Input Validations
    // Don't touch, we start iterating at level zero
    if(level == null)  level = 0;

    // At least you want to show the first level
    if(maxLevels == null) maxLevels = 1;
    if(maxLevels < 1)     
        return '<font color="red">Error: Levels number must be > 0</font>';

    // We start with a non null object
    if(obj == null)
    return '<font color="red">Error: Object <b>NULL</b></font>';
    // End Input Validations

    // Each Iteration must be indented
    str += '<ul>';

    // Start iterations for all objects in obj
    for(property in obj)
    {
      try
      {
          // Show "property" and "type property"
          type =  typeof(obj[property]);
          str += '<li>(' + type + ') ' + property + 
                 ( (obj[property]==null)?(': <b>null</b>'):('')) + '</li>';

          // We keep iterating if this property is an Object, non null
          // and we are inside the required number of levels
          if((type == 'object') && (obj[property] != null) && (level+1 < maxLevels))
          str += inspect(obj[property], maxLevels, level+1);
      }
      catch(err)
      {
        // Is there some properties in obj we can't access? Print it red.
        if(typeof(err) == 'string') msg = err;
        else if(err.message)        msg = err.message;
        else if(err.description)    msg = err.description;
        else                        msg = 'Unknown';

        str += '<li><font color="red">(Error) ' + property + ': ' + msg +'</font></li>';
      }
    }

      // Close indent
      str += '</ul>';

    return str;
}