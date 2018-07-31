<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <title></title>
</head>
<body>
<div class="container">
    <div class="row mb-3" id="login">
        <form class="form-inline">
            <div class="input-group">
                <label class="sr-only" for="inlineFormInputLogin">Логин</label>
                <input type="text" class="form-control input-group" id="inlineFormInputLogin" name="login" placeholder="Логин">

                <label class="sr-only" for="inputPassword">Пароль</label>
                <input type="password" id="inputPassword" class="form-control" aria-describedby="passwordHelpBlock" name="pass" placeholder="Пароль">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Авторизоваться</button>
                </div>
            </div>
        </form>
    </div>
    <div class="row mb-3" style="display: none" id="logout">
        <button type="submit" class="btn btn-primary" onclick="logout()">Выйти</button>
    </div>
    <div class="row mb-3">
        <button id="task_div_button" type="button" class="btn btn-primary" data-toggle="modal" data-target="#task_div" onclick="reset();">Добавить задачу</button>
    </div>
    <div class="mb-3">
        <label>Сортировка</label>
        <button class="btn btn-success sortbtn" data-order="desc" data-sort="username">
            Имя пользователя <i class="far fa-arrow-alt-circle-down"></i>
        </button>
        <button class="btn btn-primary sortbtn" data-order="asc" data-sort="email">
            E-mail <i class="far fa-arrow-alt-circle-down"></i>
        </button>
        <button class="btn btn-primary sortbtn" data-order="asc" data-sort="done">
            Статус <i class="far fa-arrow-alt-circle-down"></i>
        </button>
    </div>
    <div class="row"><ul class="pagination"></ul></div>
    <div id="tasks" class="d-flex justify-content-between align-content-between flex-wrap"></div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" id="task_div">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Задача</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" name="id" value="0">
                    <div class="mb-3">
                        <label for="validationUsername">Имя пользователя</label>
                        <input type="text" class="form-control" id="validationUsername" name="username" placeholder="Имя пользователя" required>
                        <div class="invalid-tooltip">
                            Напишите имя пользователя.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputEmail1">E-mail</label>
                        <input type="email" class="form-control" id="validationEmail" name="email" aria-describedby="emailHelp" placeholder="E-mail" required>
                    </div>

                    <div class="mb-3">
                        <label for="text">Текст задачи</label>
                        <textarea class="form-control" id="text" name="text" rows="3" required></textarea>
                    </div>

                    <div class="mb-3 custom-file">
                        <input type="file" class="custom-file-input" id="picture" name="picture">
                        <label class="custom-file-label" for="validatedCustomFile">Выберете файл...</label>
                        <div class="invalid-feedback">Файл должен быть </div>
                    </div>
                    <div id="img" class="mb-3" style="display: none"><img></div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        <button type="button" class="btn btn-info" onclick="previewTask()">Предварительный просмотр</button>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="display: none" id="preview">

            </div>
        </div>
    </div>
</div>
<?php
?>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="/task/task.js"></script>
</body>





