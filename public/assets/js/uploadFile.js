jQuery(document).ready(function ($) {
    var maxFileSize = 2 * 1024 * 1024; // (байт) Максимальный размер файла (2мб)
    var queue = {};
    var form = $('form#addTovar');
    var formEdit = $('form#editTovar');
    /*Создаем переменную для подсчета колличества файлов*/
    var countFiles = 0;
    var uniqImgages = [];
    var errorsImg = 'false';
    var errorBox;
    var imgToDeleteFromDatabase = [];
    $(document).on('change', '#fileMulti', function () {
        /*Скрытие сообщений об ошибках*/
        $('#error-warning, #error-danger, #error-success').hide();

        var files = this.files;
        /*Добавляем к переменной колличество файлов*/
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            if ( !file.type.match(/image\/(jpeg|jpg|png|gif)/) ) {
                errorBox = '#error-danger';
                $(errorBox+' span').text('Фотография должна быть в формате jpg, jpeg, png или gif.');
                errorsImg = 'true';
                errorsConditions(errorsImg,errorBox);
                continue;
            }
            if ( file.size > maxFileSize ) {
                errorBox = '#error-danger';
                $(errorBox+' span').text('Размер фотографии не должен превышать 2 Мб.');
                errorsImg = 'true';
                errorsConditions(errorsImg,errorBox);
                continue;
            }
            /*тестирование*/
            if($.inArray(file.name,uniqImgages)+1) {
                errorBox = '#error-warning';
                $(errorBox + ' span').html('Вы уже выбрали эту картинку!<br/> Картинка не продублируется.');
                errorsImg = 'true';
                errorsConditions(errorsImg,errorBox);
                continue;
            }
            if(countFiles<8) {
                uniqImgages.push(file.name);
                preview(files[i], countFiles);
                countFiles++;
            }
            else {
                errorBox = '#error-warning';
                $(errorBox + ' span').html('Вы загрузили максимальное колличество картинок.<br/> Чтобы заменить картинки, удалите ненужные.');
                errorsImg = 'true';
                errorsConditions(errorsImg,errorBox);
                break;
            }
            errorsImg = 'false';
            errorsConditions(errorsImg,errorBox);
        }
        this.value = '';
        return [countFiles,file];
    });
    // Создание превью
    function preview(file, countFiles) {
        var reader = new FileReader();
        reader.onload = (function(theFile, countFiles) {
            return function(e) {
                // Render thumbnail.
                var labelFile= $('.label-file');
                var span = document.createElement('span');
                span.innerHTML = ['<img class="img-thumbnail" src="', e.target.result,
                    '" title="Нажмите, чтобы сделать картинку титульной"/>'].join('');
                /*Создаем массив data с идентификатором id, которому присваиваем соответствующее значение
                * Это необходимо для того, чтобы можно было удалить ненужную картинку из массива
                * */
                /*Если удалить атрибут for у label, то label не сможет выбирать картинки.
                * Это необходимо делать для того, чтобы label стал неактивным когда
                * картинка выбрана. При удалении картинки label удаляется и
                * появляется новый вконце нормальный label*/
                labelFile.eq(countFiles).data('id', file.name).attr('for','');
                labelFile[countFiles].insertBefore(span, null);
                queue[file.name] = file;
                chooseTitul(labelFile,countFiles,file);
            };
        })(file, countFiles);
        // Read in the image file as a data URL.
        reader.readAsDataURL(file);
    }
    /*Функция начальной инициализации выбора титулки*/
    function chooseTitul(labelFile,countFiles,file) {
       /*Первичная инициализация*/
        labelFile.eq(countFiles).addClass('choosen');
        labelFile.eq(countFiles).parent().children().append('<p class="push-pin"><img title="Нажмите, чтобы сделать картинку титульной" src="/assets/images/approved-signal-grey.svg" alt=""></p>');
        labelFile.eq(countFiles).parent().append('<p class="delete-link" title="Удалить"><img src="/assets/images/icon.svg" alt=""></p>');
        firstImgSelect(file);

    }
    function firstImgSelect(file) {
        if(!($('.label-file').hasClass('active-titul'))) {
            var titulFirst;
            $('.label-file').eq(0).addClass('active-titul');
            $('.label-file').eq(0).find('.push-pin img').attr({'src':'/assets/images/approved-signal-green.svg','title':'Это титульная картинка'});
            $('.label-file').eq(0).find('.img-thumbnail').attr('title','Это титульная картинка');

            for(titulFirst in queue){
                break;
            }
            $('.label-file').eq(0).append('<input id="titul" type="hidden" name="titul" value="'+titulFirst+'">');

        }
    }
    // Удаление фотографий
    $(document).on('click', '.delete-link',function (event) {
        var item = $(this).parents('.images-col').find(".label-file"),
            id = item.data('id');
        uniqImgages.splice(uniqImgages.indexOf(queue[id].name), 1);
        delete queue[id];
        item.parent().remove();
        $(".images-col:last").after('<div class="col-sm-3 col-6 images-col"><input type="file" id="fileMulti" multiple=""><label class="label-file label-odd" for="fileMulti"></label></div>');
        countFiles--;
        firstImgSelect();
    });
    // Выбор титульной фотографии
    $(document).on('click', '.choosen',function (event) {
        $(".choosen").not(this).removeClass('active-titul');
        $(".choosen").not(this).parent().find('.push-pin img').attr({'src':'/assets/images/approved-signal-grey.svg','title':'Нажмите, чтобы сделать картинку титульной'});
        $(".choosen").not(this).parent().find('.img-thumbnail').attr('title','Нажмите, чтобы сделать картинку титульной');
        $(".choosen").not(this).find('#titul').remove();
        if(!($(this).hasClass('active-titul'))) {
            $(this).addClass('active-titul');
            $(this).parent().find('.push-pin img').attr({'src':'/assets/images/approved-signal-green.svg','title':'Это титульная картинка'});
            $(this).parent().find('.img-thumbnail').attr('title','Это титульная картинка');
            /*$(this).append('<input id="titul" type="hidden" name="titul" value="'+uniqImgages.indexOf(queue[$(this).data('id')].name)+'">');*/
            $(this).append('<input id="titul" type="hidden" name="titul" value="'+queue[$(this).data('id')].name+'">');
        }
    });
    /*обработка ошибок*/
    function errorsConditions(errorsImg, errorBox) {
        if(!errorBox == 0) {
            if(errorsImg == 'true') {
                $(errorBox).show();
            }
            else {
                $(errorBox).hide();
            }
        }

    }

    $(".deleteImageFromDatabase").on('click',function (event) {
        imgToDeleteFromDatabase.push(event.target.getAttribute('data-img'));
        $(this).parent().hide('slow');
        $('#imgToDeleteFromDatabase').val(imgToDeleteFromDatabase);

        /*console.log(imgToDeleteFromDatabase);*/

    });
    ajax(form);
    ajax(formEdit);

    function ajax(form) {
        form.on('submit', function (event) {
            var formData = new FormData(this);
            for (var id in queue) {
                formData.append('images[]', queue[id]);
            }
            $.ajax({
                url: window.location.pathname,
                type: 'POST',
                data: formData,
                async: true,
                success: function (res) {
                    /* console.log(res);*/
                    $("#error").html(res);

                },
                error: function (res) {
                    errorBox = '#error-danger';
                    $(errorBox + ' span').html('Ошибка отправки объявления. <br/> Свяжитесь с администратором');
                    errorsImg = 'true';
                    errorsConditions(errorsImg, errorBox);
                },
                cache: false,
                contentType: false,
                processData: false
            });
            return false;
        });
    }
});