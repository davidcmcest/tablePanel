<?php


/**
 * @var CView $this
 * @var array $data
 */

use Modules\TableModuleRME\Includes\CWidgetFieldColumnsList;
// Note: CWidgetFieldSparkline removed for Zabbix 7.0 compatibility (only available in 7.2+)

$form = (new CForm())
	->setId('tablemodulerme_column_edit_form')
	->setName('tablemodulerme_column')
	->addStyle('display: none;')
	->addVar('action', $data['action'])
	->addVar('update', 1);

// Enable form submitting on Enter.
$form->addItem((new CSubmitButton())->addClass(ZBX_STYLE_FORM_SUBMIT_HIDDEN));

$form_grid = new CFormGrid();

if (array_key_exists('edit', $data)) {
	$form->addVar('edit', 1);
}

// Set column title
$form_grid->addItem([
	(new CLabel([
		_('Título de columna'),
		makeHelpIcon(_('Solo se usa cuando \'Diseño\' está configurado como \'Columna por patrón\''))
	]))->addClass('js-column-title'),
	(new CFormField(
		(new CTextBox('column_title', $data['column_title'], false))
			->setWidth(ZBX_TEXTAREA_STANDARD_WIDTH)
	))->addClass('js-column-title')
]);

// Set if you want to broadcast the itemids in the grouped column cell
$form_grid->addItem([
	(new CLabel([
		_('Transmitir desde columna agrupada'),
		makeHelpIcon([
			_('Marcar esta casilla significa que el ID de item se transmitirá a los widgets que escuchan al hacer clic en la celda de la columna con el valor de agrupación'), BR(),
			_('Esto es útil cuando tienes múltiples columnas y quieres transmitir múltiples métricas para graficarlas simultáneamente')
		])
	]))->addClass('js-broadcast-in-group-cell'),
	(new CFormField(
		(new CCheckBox('broadcast_in_group_row'))->setChecked($data['broadcast_in_group_row'])
	))->addClass('js-broadcast-in-group-cell')
]);

// Item patterns
$item_items_field_view = (new CWidgetFieldPatternSelectItemView($data['item_items_field']))
	->setFormName('tablemodulerme_column');

$key_tip = makeHelpIcon([
	_('Si conoces el patrón de clave de item, puedes especificarlo en lugar del patrón de nombre de item escribiendo: "key=<CLAVE_ITEM>" para cada patrón que desees.'), BR(), BR(),
	_('Los comodines aún son soportados para patrones de clave de item.'), BR(),
	_('La razón para usar patrones de clave de item aquí es que es más rápido debido a la indexación.'), BR(), BR(),
	_('Para encontrar la clave que necesitas, ve a la página de Últimos datos, busca tus patrones de item y luego marca la casilla "Mostrar detalles". La clave de cada item se mostrará debajo del nombre del item en la columna "Nombre"')
]);

foreach ($item_items_field_view->getViewCollection() as ['label' => $label, 'view' => $view, 'class' => $class]) {
	$label->addItem($key_tip);
	$form_grid->addItem([
		$label,
		(new CFormField($view))->addClass($class)
	]);
}

$form_grid
	->addItem($item_items_field_view->getTemplates())
	->addItem(new CScriptTag([
		$item_items_field_view->getJavaScript()
	]));

// Item tags.
$form_grid->addItem([
	new CLabel(_('Etiquetas de item')),
	new CFormField(
		(new CRadioButtonList('item_tags_evaltype', (int) $data['item_tags_evaltype']))
			->addValue(_('Y/O'), TAG_EVAL_TYPE_AND_OR)
			->addValue(_('O'), TAG_EVAL_TYPE_OR)
			->setModern()
	)
]);

$tags_view = (new CWidgetFieldTagsView($data['item_tags_field']))->setFormName('tablemodulerme_column');

foreach ($tags_view->getViewCollection() as ['label' => $label, 'view' => $view, 'class' => $class]) {
	$form_grid->addItem([
		$label,
		(new CFormField($view))->addClass($class)
	]);
}
$form_grid
	->addItem($tags_view->getTemplates())
	->addItem(new CScriptTag([
		$tags_view->getJavaScript()
	]));

// Base color.
$form_grid->addItem([
	new CLabel(_('Color base'), 'lbl_base_color'),
	new CFormField(
		new CColor('base_color', $data['base_color'])
	)
]);

// Font color.
$form_grid->addItem([
	new CLabel(_('Color de fuente'), 'lbl_font_color'),
	new CFormField(
		new CColor('font_color', $data['font_color'])
	)
]);

// Display value as.
$form_grid->addItem([
	new CLabel(_('Mostrar valor como'), 'display_value_as'),
	new CFormField(
		(new CRadioButtonList('display_value_as', (int) $data['display_value_as']))
			->addValue(_('Numérico'), CWidgetFieldColumnsList::DISPLAY_VALUE_AS_NUMERIC)
			->addValue(_('Texto'), CWidgetFieldColumnsList::DISPLAY_VALUE_AS_TEXT)
			->addValue(_('URL'), CWidgetFieldColumnsList::DISPLAY_VALUE_AS_URL)
			->setModern()
	)
]);

// Display.
$form_grid->addItem([
	(new CLabel(_('Visualización'), 'display'))->addClass('js-display-row'),
	(new CFormField(
		(new CRadioButtonList('display', (int) $data['display']))
			->addValue(_('Como es'), CWidgetFieldColumnsList::DISPLAY_AS_IS)
			->addValue(_('Barra'), CWidgetFieldColumnsList::DISPLAY_BAR)
			->addValue(_('Indicadores'), CWidgetFieldColumnsList::DISPLAY_INDICATORS)
			// Sparkline option removed for Zabbix 7.0 compatibility (only available in 7.2+)
			// ->addValue(_('Sparkline'), CWidgetFieldColumnsList::DISPLAY_SPARKLINE)
			->setModern()
	))->addClass('js-display-row')
]);

// Sparkline - Disabled for Zabbix 7.0 compatibility (only available in 7.2+)
// $sparkline = (new CWidgetFieldSparklineView(
// 	(new CWidgetFieldSparkline('sparkline', _('Sparkline')))
// 		->setInType(CWidgetsData::DATA_TYPE_TIME_PERIOD)
// 		->acceptDashboard()
// 		->acceptWidget()
// 		->setValue($data['sparkline'])
// ))->setFormName($form->getName());

// $form_grid->addItem([
// 	$sparkline->getLabel()->addClass('js-sparkline-row'),
// 	$sparkline->getView()->addClass('js-sparkline-row')
// ]);

// Min.
$form_grid->addItem([
	(new CLabel(_('Mín'), 'min'))->addClass('js-min-max-row'),
	(new CFormField(
		(new CTextBox('min', $data['min']))
			->setWidth(ZBX_TEXTAREA_FILTER_SMALL_WIDTH)
			->setAttribute('placeholder', _('calculado'))
	))->addClass('js-min-max-row')
]);

// Max.
$form_grid->addItem([
	(new CLabel(_('Máx'), 'max'))->addClass('js-min-max-row'),
	(new CFormField(
		(new CTextBox('max', $data['max']))
			->setWidth(ZBX_TEXTAREA_FILTER_SMALL_WIDTH)
			->setAttribute('placeholder', _('calculado'))
	))->addClass('js-min-max-row')
]);

// Thresholds.
$thresholds = (new CDiv([
	(new CTable())
		->setId('thresholds_table')
		->addClass(ZBX_STYLE_TABLE_FORMS)
		->setHeader(['', _('Umbral'), (new CColHeader(''))->setWidth('100%')])
		->setFooter(new CRow(
			(new CCol(
				(new CButtonLink(_('Agregar')))->addClass('element-table-add')
			))->setColSpan(3)
		)),
	(new CTemplateTag('thresholds-row-tmpl'))
		->addItem((new CRow([
			new CColor('thresholds[#{rowNum}][color]', '#{color}'),
			(new CTextBox('thresholds[#{rowNum}][threshold]', '#{threshold}', false))
				->setWidth(ZBX_TEXTAREA_TINY_WIDTH)
				->setAriaRequired(),
			(new CButton('thresholds[#{rowNum}][remove]', _('Eliminar')))
				->addClass(ZBX_STYLE_BTN_LINK)
				->addClass('element-table-remove')
		]))->addClass('form_row'))
	]))
	->addClass(ZBX_STYLE_TABLE_FORMS_SEPARATOR)
	->setWidth(ZBX_TEXTAREA_STANDARD_WIDTH);

$form_grid->addItem([
	(new CLabel(_('Umbrales'), 'thresholds_table'))->addClass('js-thresholds-row'),
	(new CFormField($thresholds))->addClass('js-thresholds-row')
]);

// Decimal places.
$form_grid->addItem([
	(new CLabel(_('Lugares decimales'), 'decimal_places'))->addClass('js-decimals-row'),
	(new CFormField(
		(new CNumericBox('decimal_places', $data['decimal_places'], 2))->setWidth(ZBX_TEXTAREA_NUMERIC_STANDARD_WIDTH)
	))->addClass('js-decimals-row')
]);

// Highlights.
$highlights = (new CDiv([
	(new CTable())
		->setId('highlights_table')
		->addClass(ZBX_STYLE_TABLE_FORMS)
		->setHeader(['', _('Expresión regular'), (new CColHeader(''))->setWidth('100%')])
		->setFooter(new CRow(
			(new CCol(
				(new CButtonLink(_('Agregar')))->addClass('element-table-add')
			))->setColSpan(3)
		)),
	(new CTemplateTag('highlights-row-tmpl'))
		->addItem((new CRow([
			new CColor('highlights[#{rowNum}][color]', '#{color}'),
			(new CTextBox('highlights[#{rowNum}][pattern]', '#{pattern}', false))
				->setWidth(ZBX_TEXTAREA_MEDIUM_WIDTH)
				->setAriaRequired(),
			(new CButton('highlights[#{rowNum}][remove]', _('Eliminar')))
				->addClass(ZBX_STYLE_BTN_LINK)
				->addClass('element-table-remove')
		]))->addClass('form_row'))
	]))
	->addClass(ZBX_STYLE_TABLE_FORMS_SEPARATOR)
	->setWidth(ZBX_TEXTAREA_STANDARD_WIDTH);

$form_grid->addItem([
	(new CLabel(_('Resaltados'), 'highlights_table'))->addClass('js-highlights-row'),
	(new CFormField($highlights))->addClass('js-highlights-row')
]);

$form_grid->addItem([
	(new CLabel(_('Modo de visualización de URL'), 'url_display_mode'))->addClass('js-url-display-mode'),
	(new CFormField(
		(new CRadioButtonList('url_display_mode', (int) $data['url_display_mode']))
			->addValue(_('Como es'), CWidgetFieldColumnsList::URL_DISPLAY_AS_IS)
			->addValue(_('Personalizado'), CWidgetFieldColumnsList::URL_DISPLAY_CUSTOM)
			->setModern()
	))->addClass('js-url-display-mode')
]);

$form_grid->addItem([
	(new CLabel([
		_('Sobrescribir visualización de URL'),
		makeHelpIcon([
			_('Personaliza el texto de visualización de la URL'), BR(), BR(),
			_('En lugar de mostrar la URL sin formato, puedes establecer un texto arbitrario para mostrar. La URL se codificará en el texto que ingreses en este cuadro de texto.'), BR(), BR(),
			_('También puedes mezclar macros con texto. Macros soportadas:'),
			(new CList([
				'{HOST.*}',
				'{ITEM.*}',
				'{INVENTORY.*}',
				_('Macros de usuario'),
			]))->addClass(ZBX_STYLE_LIST_DASHED)
		])
	]))->addClass('js-url-display-override'),
	(new CFormField(
		(new CTextBox('url_display_override', $data['url_display_override'], false))
			->setWidth(ZBX_TEXTAREA_STANDARD_WIDTH)
			->setAttribute('placeholder', _('Establecer texto de visualización personalizado'))
	))->addClass('js-url-display-override')
]);

$form_grid->addItem([
	(new CLabel([
		_('Personalización de URL'),
		makeHelpIcon([
			_('Personaliza la URL completa'), BR(), BR(),
			_('En lugar de mostrar el valor de métrica como una URL, puedes aprovechar los hosts e items de los resultados devueltos para crear una URL completamente personalizada. También puedes simplemente ingresar cualquier URL válida (p.ej. https://www.zabbix.com)'), BR(), BR(),
			_('También puedes mezclar macros con texto. Macros soportadas:'),
			(new CList([
				'{HOST.*}',
				'{ITEM.*}',
				'{INVENTORY.*}',
				_('Macros de usuario'),
			]))->addClass(ZBX_STYLE_LIST_DASHED)
		])
	]))->addClass('js-url-custom-override'),
	(new CFormField(
		(new CTextBox('url_custom_override', $data['url_custom_override'], false))
			->setWidth(ZBX_TEXTAREA_STANDARD_WIDTH)
			->setAttribute('placeholder', _('Crear una URL completamente personalizada'))
	))->addClass('js-url-custom-override')
]);

$form_grid->addItem([
	(new CLabel([
		_('Abrir URL en nueva pestaña'),
		makeHelpIcon([
			_('Marca esta casilla para abrir la URL en una nueva pestaña del navegador, de lo contrario el enlace se abrirá en la misma pestaña')
		])
	]))->addClass('js-url-open-in'),
	(new CFormField(
		(new CCheckBox('url_open_in'))->setChecked($data['url_open_in'])
	))->addClass('js-url-open-in')
]);

// Advanced configuration.
$advanced_configuration = new CWidgetFormFieldsetCollapsibleView(_('Configuración avanzada'));

// Column aggregation function.
$advanced_configuration->addItem([
	(new CLabel([
		_('Agregación de patrones de columna'),
		makeHelpIcon([
			_('Elige una función para agregar todos los patrones de item de esta columna para cada host.')
		])
	]))->addClass('js-column-agg-row'),
	(new CFormField(
		(new CSelect('column_agg_method'))
			->setId('column_agg_method')
			->setValue($data['column_agg_method'])
			->addOptions(CSelect::createOptionsFromArray([
				AGGREGATE_NONE => CItemHelper::getAggregateFunctionName(AGGREGATE_NONE),
				AGGREGATE_MIN => CItemHelper::getAggregateFunctionName(AGGREGATE_MIN),
				AGGREGATE_MAX => CItemHelper::getAggregateFunctionName(AGGREGATE_MAX),
				AGGREGATE_AVG => CItemHelper::getAggregateFunctionName(AGGREGATE_AVG),
				AGGREGATE_COUNT => CItemHelper::getAggregateFunctionName(AGGREGATE_COUNT),
				AGGREGATE_SUM => CItemHelper::getAggregateFunctionName(AGGREGATE_SUM)
			]))
			->setFocusableElementId('column_patterns_aggregation')
	))->addClass('js-column-agg-row')
]);

// Aggregation function.
$advanced_configuration->addItem([
	new CLabel(_('Función de agregación'), 'column_aggregate_function'),
	new CFormField(
		(new CSelect('aggregate_function'))
			->setId('aggregate_function')
			->setValue($data['aggregate_function'])
			->addOptions(CSelect::createOptionsFromArray([
				AGGREGATE_NONE => CItemHelper::getAggregateFunctionName(AGGREGATE_NONE),
				AGGREGATE_MIN => CItemHelper::getAggregateFunctionName(AGGREGATE_MIN),
				AGGREGATE_MAX => CItemHelper::getAggregateFunctionName(AGGREGATE_MAX),
				AGGREGATE_AVG => CItemHelper::getAggregateFunctionName(AGGREGATE_AVG),
				AGGREGATE_COUNT => CItemHelper::getAggregateFunctionName(AGGREGATE_COUNT),
				AGGREGATE_SUM => CItemHelper::getAggregateFunctionName(AGGREGATE_SUM),
				AGGREGATE_FIRST => CItemHelper::getAggregateFunctionName(AGGREGATE_FIRST),
				AGGREGATE_LAST => CItemHelper::getAggregateFunctionName(AGGREGATE_LAST)
			]))
			->setFocusableElementId('column_aggregate_function')
	)
]);

// Time period.
$time_period_field_view = (new CWidgetFieldTimePeriodView($data['time_period_field']))
	->setDateFormat(ZBX_FULL_DATE_TIME)
	->setFromPlaceholder(_('YYYY-MM-DD hh:mm:ss'))
	->setToPlaceholder(_('YYYY-MM-DD hh:mm:ss'))
	->setFormName('tablemodulerme_column')
	->addClass('js-time-period');

foreach ($time_period_field_view->getViewCollection() as ['label' => $label, 'view' => $view, 'class' => $class]) {
	$advanced_configuration->addItem([
		$label,
		(new CFormField($view))->addClass($class)
	]);
}

$advanced_configuration->addItem(new CScriptTag([
	'document.forms.tablemodulerme_column.fields = {};',
	$time_period_field_view->getJavaScript()
]));

// History data.
$advanced_configuration
	->addItem([
		(new CLabel(_('Datos de historial'), 'history'))->addClass('js-history-row'),
		(new CFormField(
			(new CRadioButtonList('history', (int) $data['history']))
				->addValue(_('Auto'), CWidgetFieldColumnsList::HISTORY_DATA_AUTO)
				->addValue(_('Historial'), CWidgetFieldColumnsList::HISTORY_DATA_HISTORY)
				->addValue(_('Tendencias'), CWidgetFieldColumnsList::HISTORY_DATA_TRENDS)
				->setModern()
		))->addClass('js-history-row')
	]);

// Footer Override
$advanced_configuration
	->addItem([
		(new CLabel(_('Sobrescribir pie'), 'override_footer'))->addClass('js-override-footer'),
		(new CFormField(
			(new CRadioButtonList('override_footer', (int) $data['override_footer']))
				->addValue(_('Sin sobrescribir'), CWidgetFieldColumnsList::FOOTER_DONT_OVERRIDE)
				->addValue(_('Ninguno'), CWidgetFieldColumnsList::FOOTER_SHOW_NONE)
				->addValue(_('Suma'), CWidgetFieldColumnsList::FOOTER_SHOW_SUM)
				->addValue(_('Promedio'), CWidgetFieldColumnsList::FOOTER_SHOW_AVERAGE)
				->setModern()
		))->addClass('js-override-footer')
	]);

// Whether to include itemids in table cells when using Column patterns aggregations
$advanced_configuration
	->addItem([
		(new CLabel([
			_('Incluir IDs de item en celda'),
			makeHelpIcon(_('Al usar \'Agregación de patrones de columna\' incluir todos los IDs de item para transmitir a otros widgets'))
		]))->addClass('js-include-itemids'),
		(new CFormField(
			(new CCheckBox('include_itemids'))->setChecked($data['include_itemids'])
		))->addClass('js-include-itemids')
	]);

$form_grid->addItem($advanced_configuration);

$form
	->addItem($form_grid)
	->addItem(
		(new CScriptTag('
			tablemodulerme_column_edit_form.init('.json_encode([
				'form_id' => $form->getId(),
				'thresholds' => $data['thresholds'],
				'highlights' => $data['highlights'],
				'colors' => $data['color_palette']
			], JSON_THROW_ON_ERROR).');
		'))->setOnDocumentReady()
	);

$output = [
	'header' => array_key_exists('edit', $data) ? _('Actualizar columna') : _('Nueva columna'),
	// Sparkline JS removed for Zabbix 7.0 compatibility
	'script_inline' => $this->readJsFile('column.edit.js.php', null, ''),
	'body' => $form->toString(),
	'buttons' => [
		[
			'title' => array_key_exists('edit', $data) ? _('Actualizar') : _('Agregar'),
			'keepOpen' => true,
			'isSubmit' => true,
			'action' => 'tablemodulerme_column_edit_form.submit();'
		]
	]
];

if ($data['user']['debug_mode'] == GROUP_DEBUG_MODE_ENABLED) {
	CProfiler::getInstance()->stop();
	$output['debug'] = CProfiler::getInstance()->make()->toString();
}

echo json_encode($output, JSON_THROW_ON_ERROR);
