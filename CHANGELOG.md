# ddMenuBuilder changelog
## 1.11 (2016-11-25)
* \* Attention! PHP >= 5.4 is required.
* \* Attention! MODXEvo.library.ddTools >= 0.16.1 is required.
* \+ Added an ability to pass ids of the selected documents to output.
* \* Short array syntax is used because it's more convenient.
* \* ddMenuBuilder Class:
	* \* Unpublished docs will be used if needed.
	* \* ddMenuBuilder->generate:
		* \* Now takes custom “where” clauses instead of parent id.
		* \* Refactoring parameters style.
* \* Other minor changes.

## 1.10 (2016-09-12)
* \+ Added an ability to pass additional data into a “tpls_outer” template (see the “placeholders” parameter).
* \+ Added support of “@CODE:” keyword prefix in the snippet templates.
* \* Attention! Snippet now requires MODXEvo >= 1.1.

## 1.9 (2015-12-28)
* \* Внимание! Используется «modx.ddTools» 0.15.
* \* ddMenuBuilder snippet:
	* \* Вместо прямого обращения к полю «$modx->config» используется метод «$modx->getConfig».
	* \* Следующие параметры были переименованы (старые имена поддерживаются, но не рекомендуются к использованию):
		* \* «tplRow» → «tpls_item».
		* \* «tplHere» → «tpls_itemHere».
		* \* «tplActive» → «tpls_itemActive».
		* \* «tplParentRow» → «tpls_itemParent».
		* \* «tplParentHere» → «tpls_itemParentHere».
		* \* «tplParentActive» → «tpls_itemParentActive».
		* \* «tplUnpubParentRow» → «tpls_itemParentUnpub».
		* \* «tplUnpubParentActive» → «tpls_itemParentUnpubActive».
		* \* «tplWrap» → «tpls_outer».
	* \* Параметр «tpls_itemParentHere» по умолчанию равен `<li class="active"><a href="[~[+id+]~]" title="[+pagetitle+]">[+menutitle+]</a><ul>[+children+]</ul></li>` (значение по умолчанию больше не зависит от параметра «tpls_itemParent»). Решение неоднозначное, подумать.
* \* ddMenuBuilder class обновлён до 2.0:
	* \* Теперь это обычный объект, поля и методы не статические.
	* \* Публичный только метод «generate», остальные поля и методы приватные.
	* \- Удалено поле «$table», вместо него используется «ddTools::$tables['site_content']».
	* \* Поле «ddMenuBuilder->id» переименовано в «ddMenuBuilder->hereDocId».
	* \+ Добавлены значения по умолчанию для полей «sortDir» и «where».
	* \+ Значения шаблонов по умолчанию хранятся в поле «ddMenuBuilder->templates».
	* \* Переименованы шаблоны.
	* \+ Добавлен конструктор.
	* \* Обработка параметров «showPublishedOnly», «showInMenuOnly» и формирование SQL-условия вынесены из сниппета в конструктор класса «ddMenuBuilder».
	* \* Обработка значений шаблонов по умолчанию вынесена из сниппета в конструктор класса «ddMenuBuilder».
	* \* Подключение библиотеки «modx.ddTools» вынесено в конструктор.
	* \* Вместо прямого обращения к полю «$modx->config» используется метод «$modx->getConfig».
	* \* Файл «assets/snippets/ddMenuBuilder/ddmenubuilder.class.php» переименован в «assets/snippets/ddMenuBuilder/ddMenuBuilder.class.php».
	* \* Внимание! Используется «modx.ddTools» 1.0.15.

## 1.8 (2015-02-05)
* \* ddMenuBuilder snippet:
	* \* Плэйсхолдер «[+wrapper+]» во всех шаблонах заменён на «[+children+]».
* \* ddMenuBuilder class:
	* \+ Добавлен метод «ddMenuBuilder::getOutputTemplate».
	* \* Метод «ddMenuBuilder::generate»:
		* \* Переменная «$children» должна быть определена.
		* \* Для проверки наличия дочерних документов используется «empty» вместо простого логического значения (т.к. пустой массив также означает отсутствие детей).
		* \* Рефакторинг:
			* \* Один «return» вместо нескольких.
			* \* Переменная «$tpl» объявляется в любом случае.
			* \* Элемент массива «str» объявляется в самом начале, таким образом, он всегда существует.
			* \* Код определения шаблона для вывода документа вынесен в отдельный метод.
			* \* Обработка пустого «menutitle» документа делается только если документ будет выводиться.
			* \* Определение «активности» текущего документа объеденено в одно условие и перенесено после парсинга.
		* \* Парсинг текущего пункта меню делается только если шаблон определён (если не определён, значит выводить не надо).
		* \* Всегда возвращает массив.
		* \* Поля результирующего массива переименованы:
			* \* «act» → «hasActive».
			* \* «str» → «outputString».
		* \* В массиве документа поле «wrapper» переименовано в «children».
		* \* В результирующем массиве в любом случае будут поля «hasActive» и «outputString».
		* \* Переменная «$doc» в любом случае будет содержать поле «children» с массивом дочерних документов, в случае если их нет или не нужно выводить, «$doc['children']['outputString']» будет равняться пустой строке.
		* \* Определение шаблона и прочие операции, связанные с выводом, производятся только если в этом есть смысл.
		* \* Удалена переменная «$sql».
* \* Удалены устаревшие комментарии, исправлено оформление кода и прочие незначительные изменения.

## 1.7 (2012-10-17)
* \+ «Первая» версия.

<style>ul{list-style:none;}</style>