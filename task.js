let sort = "username";
let order = "desc";

$(document).ready(function(){
    showAll(0);
});

let _URL = window.URL;
$("#picture").on("change", function(e){{
    let file, img;
    if ((file = this.files[0])) {
        let regexp = new RegExp("(.*?)\.(jpg|png|gif)$");

        if (regexp.test(file.name.toLowerCase())){
            img = new Image();
            img.src = _URL.createObjectURL(file);
            img.onload = function () {
                console.log(parseInt(this.width) > 320 || parseInt(this.height) > 240);
                if (parseInt(this.width) > 320 || parseInt(this.height) > 240) {
                    $("#picture").val('');
                    $("label[for='validatedCustomFile']").html('Выберете файл...');
                    img.destroy();
                }
                //alert("Width:" + this.width + "   Height: " + this.height);//this will give you image width and height and you can easily validate here....
            };
        }
        else $(this).val('');
    }
    if ($(this).val() != '') $("label[for='validatedCustomFile']").html(file.name);
}});

$("#login form").submit(function(){
    event.preventDefault();
    $.post("/task/login", $(this).serialize(), function(data){
        if (data == '1') {
            showAll(0)
            $("#login").hide();
            $("#logout").show();
        }
    })
});

function logout() {
    $.post("/task/logout", {}, function (data) {
        if (data == '1') {
            showAll(0)
            $("#logout").hide();
            $("#login").show();
        }
    })
}

function renderCard(params, admin){
    $("#task_div .close").click();

    let done = 'Не выполнена';
    let checked = '';
    if (params[5] == '1') {
        done = 'Выполнена';
        checked = ' checked';
    }

    let card = "<div class='card mr-auto ml-auto mb-2' id='card_" + params[0] + "'>" +
        "<div class='card-header'><img class='card-img-top' src='" + params[4] + "'></div>" +
        "<div class='card-body'>" +
        "<h5 class='card-title'>" + params[1] + " (" + params[2] + ")</h5>" +
        "<p class='card-text'>" + params[3] + "</p>" +
        "</div>" +
        (admin == '1' ? "<div class='card-footer'><input type='checkbox' id='done_" + params[0] + "' onclick='setDone(" + params[0] + ")' class='done'" + checked + "> Выполнено <button type='button' class='btn btn-primary' onclick='editTask(" + params[0] + ")'>Редактировать</button></div>" : "<div class='card-footer'>" + done + "</div>") +
        "</div>";
    $("#tasks").append(card);
}

function editTask(id){
    $.post("/task/get", {id:id}, function (data) {
        let task = JSON.parse(data);
        console.log("edit");
        console.log(task[0][1]);
        $("#task_div_button").click();
        $("input[name='id']").val(task[0][0]);
        $("#validationUsername").val(task[0][1]);
        $("#validationEmail").val(task[0][2]);
        $("#text").val(task[0][3]);
        if (task[0][4].trim() != '') {
            $("#img img").attr('src', task[0][4]);
            $("#img").show();
        }
    });
}

function setDone(id){
    let done = '0';
    if ($("#done_"+id).is(":checked") == true) done = 1;
    $.post("/task/done", {id:id, done:done}, function(data){

    })
}

$("#task_div form").submit(function() {
    event.preventDefault();
    $.ajax({
        type: 'POST',
        url: "/task/save",
        enctype: 'multipart/form-data',
        data: new FormData( this ),
        processData: false,
        contentType: false,
        success: function (data) {
            showAll(0, sort, order);
        },
        error: function(data){
            console.log(data);
        }
    });
});

function reset(){
    $("#task_div input[type='text'], #task_div input[type='file'], #task_div textarea").each(function(){
        $(this).val('');
    });
    $("label[for='validatedCustomFile']").html('Выберете файл...');
    $("#img, #preview").hide();
}

function showAll(page, sort = 'username', order = 'ask'){
    $("#tasks").html('');
    console.log("[" + sort + "]");
    $.post("/task/showAll", {page:page, sort:sort, order:order}, function (tasks) {
        let params = JSON.parse(tasks);
        //console.log(params['admin']);
        for(let i in params) {
            if (i == 'admin' || i == 'pages') continue;
            renderCard(params[i], params['admin']);
        }

        $(".sortbtn").removeClass('btn-success').addClass('btn-primary');
        $(".sortbtn[data-sort!='" + sort + "']").attr('data-order', 'asc');
        $(".sortbtn i").removeClass('fa-arrow-alt-circle-up').addClass('fa-arrow-alt-circle-down');

        let arrow = 'down';
        if (order == 'desc') arrow = 'up';
        $("button[data-sort='" + sort + "']").removeClass('btn-primary').addClass('btn-success');
        $("button[data-sort='" + sort + "'] i").removeClass('fa-arrow-alt-circle-up fa-arrow-alt-circle-down').addClass('fa-arrow-alt-circle-' + arrow);

        $(".pagination").html('');
        if (params['pages'] > 1) {
            for(let i = 0; i < params['pages']; i++){
                $(".pagination").append("<li class='page-item'><a class='page-link' href='#' onclick='showAll(" + (i * 3) + ", \"" + sort + "\", \"" + order + "\")'>" + (i+1) + "</a></li>");
            }
        }

        if (params['admin'] == '1'){
            $("#login").hide();
            $("#logout").show();
        }
    });
}

$(".sortbtn").click(function(){
    sort = this.dataset.sort;
    order = this.dataset.order;
    showAll(0, sort, order);
    if (order == 'asc') this.dataset.order = 'desc'
    else this.dataset.order = 'asc';
});

function previewTask(){
    $.ajax({
        type: 'POST',
        url: "/task/preview",
        enctype: 'multipart/form-data',
        data: new FormData( document.getElementsByTagName('form')[1] ),
        processData: false,
        contentType: false,
        success: function (data) {
            let params = JSON.parse(data);

            let card = "<div class='card mr-auto ml-auto mb-2'>" +
                "<div class='card-header'><img class='card-img-top' src='" + params['pic'] + "'></div>" +
                "<div class='card-body'>" +
                "<h5 class='card-title'>" + params['username'] + " (" + params['email'] + ")</h5>" +
                "<p class='card-text'>" + params['text'] + "</p>" +
                "</div>" +
                (params['admin'] == '1' ? "<div class='card-footer'><input type='checkbox'> Выполнено <button type='button' class='btn btn-primary'>Редактировать</button></div>" : "<div class='card-footer'>Не выполнено</div>") +
                "</div>";

            $("#preview").html(card).show();
        },
        error: function(data){
            console.log(data);
        }
    });
}
