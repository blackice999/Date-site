<?php
    session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function listContent(content, location) {

                $("." + location).css("visibility", "hidden");

                //If the title is pressed, toggle the visibility for the list content
                $("." + location + "_title").click(function() {

                    //If the box is hidden, show it
                    if($("." + location).css('visibility') == 'hidden') {
                        $("." + location).css('visibility', 'visible');
                    } else {
                        $("." + location).css("visibility", "hidden");
                    }

                    var text;
                    var user;

                    if(location == 'new_message') {

                        $("#post_message").on('submit', function (e) {
                            e.preventDefault();
                            text = $("#message").val();
                            user = $("#user").val();

                            $.get("web_service.php?cmd=" + content, {message: text, user: user}, function (message) {
                                var json_response = jQuery.parseJSON(message);
                                $.each(json_response, function(key, value) {
//                                    alert(value);
                                });
                            });
                        });
                    }

                    $.get("web_service.php?cmd=" + content, function (message) {

                        var json_response = jQuery.parseJSON(message);

                        var listContent = "<ul class=" + location + "_list>";

                        $.each(json_response, function(key, value) {

                            //For the messages list, there is a class that styles it separately
                            //so check the type of the location
                            if(location == 'friends') {
                                listContent += "<li>" + value.first_name + " " +  value.last_name + "</li>";
                            } else if(location == 'messages') {
                                listContent += "<li class='message'>" + value.message + "</li>";
                                    listContent += "<span class='send_date'>" + value.send_date + "</span>";
                            }
                        });

                        //Use the .send_date selector with the on() method
                        //as the content is dynamically received
//                        $('.messages').on({
//                            mouseenter:  function() {
//                            //Selects current '.send_date'
//                            $(this).css('display', 'none');
//                        },
//
//                            mouseleave: function() {
//                                $(this).css('display', 'inline');
//                            }
//                        }, '.send_date');

                        listContent += "</ul>";

                        if(location != 'new_message') {

                            $("." + location).html(listContent);
                        }
                    });
                });
            }

            listContent('listfriends', 'friends');
            listContent('listmessages', 'messages');
            listContent("postmessage", 'new_message');
        });
    </script>
</head>
<body>
        <?php
            include "menu.php";

            if(isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true):
        ?>
            <div class="list_container">
                <div class="list_item">
                    <h3 class="friends_title"> List friends</h3>
                    <div class="user_lists friends"></div>
                </div>

                <div class="list_item">
                    <h3 class="messages_title"> List messages</h3>
                    <div class="user_lists messages"></div>
                </div>

                <div class="list_item">
                    <h3 class="new_message_title"> Post a new message</h3>
                    <div class="user_lists new_message">
                        <form id="post_message" method="get">
                            <label>
                                Message:
                                <input type="text" name="message" id="message">
                            </label>
                            <label>
                                User:
                                <input type="text" name="user" id="user">
                            </label>
                            <input type="submit" name="submit">
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
</body>
</html>