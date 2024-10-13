<?
//подключаем пролог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//устанавливаем заголовок страницы
$APPLICATION->SetTitle("AJAX");

   CJSCore::Init(array('ajax')); // Инициализация компонента `ajax` в ядре Bitrix.
   $sidAjax = 'testAjax'; // Устанавка заголовка страницы

if(isset($_REQUEST['ajax_form']) && $_REQUEST['ajax_form'] == $sidAjax){ //Проверка наличия компонента ajax в ядре Bitrix
   $GLOBALS['APPLICATION']->RestartBuffer(); //Очистка буфера вывода перед выводом данных в формате JSON
   echo CUtil::PhpToJSObject(array( //Вывод объекта в формате JSON для AJAX ответа
            'RESULT' => 'HELLO',
            'ERROR' => ''
   ));
   die(); //Немедленно  заверщение скрипта
}

?>
//HTML-разметка для блока с формой и процессом загрузки
<div class="group">
   <div id="block"></div >
   <div id="process">wait ... </div >
</div>
<script>
   window.BXDEBUG = true; // Установка переменной окружения для отладки
function DEMOLoad(){ //Определение функции для загрузки данных
   BX.hide(BX("block")); //Скрытие блока с данными
   BX.show(BX("process")); //Отображение процесса загрузки
   BX.ajax.loadJSON( //Отправка AJAX-запроса на сервер.
      '<?=$APPLICATION->GetCurPage()?>?ajax_form=<?=$sidAjax?>',
      DEMOResponse
   );
}
function DEMOResponse (data){ //Определение функции для обработки ответа.
   BX.debug('AJAX-DEMOResponse ', data); // Вывод сообщения в консоль разработчика.
   BX("block").innerHTML = data.RESULT; // Установка содержимого блока с данными.
   BX.show(BX("block")); // Отображение блока с данными
   BX.hide(BX("process")); // Скрытие процесса загрузки

   BX.onCustomEvent( // Подписка на событие обновления данных.
      BX(BX("block")), //Получаем элемент с ID "block", который был создан в HTML-разметке
      'DEMOUpdate'
   );
}

BX.ready(function(){ // Функция, которая вызывается после полной загрузки DOM.
   /*
   BX.addCustomEvent(BX("block"), 'DEMOUpdate', function(){
      window.location.href = window.location.href; // Добавление события для обновления ссылки.
   });
   */
   BX.hide(BX("block")); // Скрытие блока с данными
   BX.hide(BX("process")); // Скрытие процесса загрузки
   
    BX.bindDelegate( // Обработка кликов по элементам с классом `.css_ajax`.
      document.body, 'click', {className: 'css_ajax' },
      function(e){
         if(!e)
            e = window.event;
         
         DEMOLoad();
         return BX.PreventDefault(e);
      }
   );
   
});

</script>
<div class="css_ajax">click Me</div>
<?
//подключаем эпилог ядра bitrix
//подключение файла `footer.php`, который содержит выход из системы.
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?> 
