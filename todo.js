$(function(){
	"use strict";
    $("#new-todo").focus();

	// update
	$("#todos").on("click", ".update_todo", function(){
		// get id
		var id = $(this).parents("li").data("id");
		// ajax
		$.post("_ajax.php",{
			id: id,
			mode: "update",
			token: $("#token").val()
		}, function(res){
			if (res.state === "1"){
				$("#todo_" + id).find(".todo_title").addClass("done");
			} else {
				$("#todo_" + id).find(".todo_title").removeClass("done");
			}
		});
	});

	// create
	$("#new_todo_form").on("submit", function () {
		// get title
		var title = $("#new-todo").val();
		if(title != "") {
			$.post("_ajax.php", {
				title: title,
				mode: "create",
				token: $("#token").val()
			}, function (res) {
			//	add <li>
				var $li = $("#todo-temple").clone();
				$li
					.attr("id", "todo_" + res.id)
					.data("id", res.id)
					.find(".todo_title").text(title);
				$("#todos").prepend($li.fadeIn());
				$("#new-todo").val('').focus();
			});
        }
		//画面遷移しないため
		return false;
	});

    // delete
    $("#todos").on("click", ".delete-todo", function () {
        if(confirm("are you sure?")) {
            var id = $(this).parents("li").data("id");
            $.post("_ajax.php", {
                id: id,
                mode: "delete",
                token: $("#token").val()
            }, function () {
                $("#todo_" + id).fadeOut(800);
            });
        }
    });
});