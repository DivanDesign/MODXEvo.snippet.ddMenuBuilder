<?php
/**
 * ddMenuBuilder
 * @version 1.13b (2018-10-17)
 * 
 * @desc Fresh, simple and flexible template-driven menu builder. Initially inspired by combination of the Wayfinder and Ditto advantages with significant code simplification.
 * 
 * @uses PHP >= 5.6.
 * @uses (MODX)EvolutionCMS >= 1.1 {@link https://github.com/evolution-cms/evolution }
 * @uses (MODX)EvolutionCMS.libraries.ddTools >= 0.24.1 {@link http://code.divandesign.biz/modx/ddtools }
 * 
 * Data provider parameters:
 * @param $provider {'parent'|'select'} — Name of the provider that will be used to fetch documents. Default: 'parent'.
 * @param $providerParams {stirng_json|string_queryFormated} — Parameters to be passed to the provider. The parameter must be set as JSON (https://en.wikipedia.org/wiki/JSON) or Query string (https://en.wikipedia.org/wiki/Query_string).
 * When $provider == 'parent' =>
 * @param $providerParams['parentIds'] {array|string_commaSepareted} — Parent IDs — the starting points for the menu. Specify '0' to start from the site root. Default: '0'.
 * @param $providerParams['parentIds'][i] {integer_documentID} — Parent ID. @required
 * @param $providerParams['depth'] {integer} —  The depth of documents to build the menu. Default: 1.
 * @example &providerParams=`{"parentId": 1, "depth": 2}`.
 * @example &providerParams=`parentId=1&depth=2`.
 * When $provider == 'select' =>
 * @param $providerParams['ids'] {array|string_commaSepareted} — Document IDs. @required
 * @param $providerParams['ids'][i] {integer_documentID} — Document ID. @required
 * @example &providerParams=`{"ids": [1, 2, 3]}`.
 * @example &providerParams=`ids=1,2,3`.
 * 
 * General parameters:
 * @param $sortDir {'ASC'|'DESC'} — The sorting direction (by “menuindex” field). Default: 'ASC'.
 * @param $showPublishedOnly {0|1} — Show only published documents. Default: 1.
 * @param $showInMenuOnly {0|1} — Show only documents visible in the menu. Default: 1.
 * 
 * Template parameters:
 * @param $templates {stirng_json|string_queryFormated} — Templates. All templates can be set as chunk name or code via “@CODE:” prefix. Placeholders available in all templates: [+id+], [+menutitle+] (will be equal to [+pagetitle+] if empty), [+pagetitle+], [+published+], [+isfolder+].
 * @param $templates['item'] {string_chunkName|string} — The menu item template. Default: '<li><a href="[~[+id+]~]" title="[+pagetitle+]">[+menutitle+]</a></li>'.
 * @param $templates['itemHere'] {string_chunkName|string} — The menu item template for the current document. Default: '<li class="active"><a href="[~[+id+]~]" title="[+pagetitle+]">[+menutitle+]</a></li>'.
 * @param $templates['itemActive'] {string_chunkName|string} — The menu item template for a document which is one of the parents to the current document when the current document doesn't displayed in the menu (e. g. excluded by the “depth” parameter). Default: $templates['itemHere'].
 * 
 * @param $templates['itemParent'] {string_chunkName|string} — The menu item template for documents which has a children displayed in menu. Default: '<li><a href="[~[+id+]~]" title="[+pagetitle+]">[+menutitle+]</a><ul>[+children+]</ul></li>';.
 * @param $templates['itemParentHere'] {string_chunkName|string} — The menu item template for the current document when it has children displayed in menu. Default: '<li class="active"><a href="[~[+id+]~]" title="[+pagetitle+]">[+menutitle+]</a><ul>[+children+]</ul></li>'.
 * @param $templates['itemParentActive'] {string_chunkName|string} — The menu item template for a document which has the current document as one of the children. Default: $templates['itemParentHere'].
 * 
 * @param $templates['itemParentUnpub'] {string_chunkName|string} — The menu item template for unpublished documents which has a children displayed in menu. Default: $templates['itemParent'].
 * @param $templates['itemParentUnpubActive'] {string_chunkName|string} — The menu item template for an unpublished document which has the current document as one of the children. Default: $templates['itemParentActive'].
 * 
 * @param $templates['outer'] {string_chunkName|string} — Wrapper template. Available placeholders: [+children+]. Default: '<ul>[+children+]</ul>'.
 * 
 * @param $placeholders {stirng_json|string_queryFormated} — Additional data as query string has to be passed into “templates['outer']”. The parameter must be set as JSON (https://en.wikipedia.org/wiki/JSON) or Query string (https://en.wikipedia.org/wiki/Query_string). Default: —.
 * @example &placeholders=`{"pladeholder1": "value1", "pagetitle", "My awesome pagetitle!"}`.
 * @example &placeholders=`pladeholder1=value1&pagetitle=My awesome pagetitle!`.
 * 
 * @link http://code.divandesign.biz/modx/ddmenubuilder/1.13b
 * 
 * @copyright 2009–2018 DivanDesign {@link http://www.DivanDesign.biz }
 */

//Include (MODX)EvolutionCMS.libraries.ddTools
require_once($modx->getConfig('base_path') . 'assets/libs/ddTools/modx.ddtools.class.php');

//Prepare template params
$templates = ddTools::encodedStringToArray($templates);

$ddMenuBuilder_params = new stdClass();

//Задаём шаблоны
$ddMenuBuilder_params->templates = [];

if (isset($templates['item'])){$ddMenuBuilder_params->templates['item'] = $modx->getTpl($templates['item']);}
if (isset($templates['itemHere'])){$ddMenuBuilder_params->templates['itemHere'] = $modx->getTpl($templates['itemHere']);}
if (isset($templates['itemActive'])){$ddMenuBuilder_params->templates['itemActive'] = $modx->getTpl($templates['itemActive']);}

if (isset($templates['itemParent'])){$ddMenuBuilder_params->templates['itemParent'] = $modx->getTpl($templates['itemParent']);}
if (isset($templates['itemParentHere'])){$ddMenuBuilder_params->templates['itemParentHere'] = $modx->getTpl($templates['itemParentHere']);}
if (isset($templates['itemParentActive'])){$ddMenuBuilder_params->templates['itemParentActive'] = $modx->getTpl($templates['itemParentActive']);}

if (isset($templates['itemParentUnpub'])){$ddMenuBuilder_params->templates['itemParentUnpub'] = $modx->getTpl($templates['itemParentUnpub']);}
if (isset($templates['itemParentUnpubActive'])){$ddMenuBuilder_params->templates['itemParentUnpubActive'] = $modx->getTpl($templates['itemParentUnpubActive']);}

if (empty($ddMenuBuilder_params->templates)){unset($ddMenuBuilder_params->templates);}

$templates['outer'] = (isset($templates['outer'])) ? $modx->getTpl($templates['outer']) : '<ul>[+children+]</ul>';

//Направление сортировки
if (isset($sortDir)){$ddMenuBuilder_params->sortDir = $sortDir;}
//По умолчанию будут только опубликованные документы
if (isset($showPublishedOnly)){$ddMenuBuilder_params->showPublishedOnly = $showPublishedOnly;}
//По умолчанию будут только документы, у которых стоит галочка «показывать в меню»
if (isset($showInMenuOnly)){$ddMenuBuilder_params->showInMenuOnly = $showInMenuOnly;}

$ddMenuBuilder = new ddMenuBuilder($ddMenuBuilder_params);

//Prepare provider params
$providerParams = ddTools::encodedStringToArray($providerParams);

//Генерируем меню
$result = $ddMenuBuilder->generate($ddMenuBuilder->prepareProviderParams([
	//Parent by default
	'provider' => isset($provider) ? $provider : 'parent',
	'providerParams' => $providerParams
]));

//Данные, которые необоходимо передать в шаблон
if (!empty($placeholders)){
	$placeholders = ddTools::encodedStringToArray($placeholders);
}else{
	$placeholders = [];
}

$placeholders['children'] = $result['outputString'];

return ddTools::parseText([
	'text' => $templates['outer'],
	'data' => $placeholders
]);
?>