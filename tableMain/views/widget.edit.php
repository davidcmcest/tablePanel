<?php declare(strict_types = 0);

/**
 * Top items widget form view.
 *
 * @var CView $this
 * @var array $data
 */

use Modules\TableModuleRME\Includes\CWidgetFieldColumnsListView;
use Modules\TableModuleRME\Includes\CWidgetFieldTableModuleItemGroupingView;

$form = new CWidgetFormView($data);

$groupids = array_key_exists('groupids', $data['fields'])
	? new CWidgetFieldMultiSelectGroupView($data['fields']['groupids'])
	: null;

$form
	->addField($groupids)
	->addField(array_key_exists('hostids', $data['fields'])
		? (new CWidgetFieldMultiSelectHostView($data['fields']['hostids']))
			->setFilterPreselect([
				'id' => $groupids->getId(),
				'accept' => CMultiSelect::FILTER_PRESELECT_ACCEPT_ID,
				'submit_as' => 'groupid'
			])
		: null
	)
	->addField(
		(new CWidgetFieldMultiSelectItemView($data['fields']['itemid']))
			->setPopupParameter('value_types', [ITEM_VALUE_TYPE_FLOAT, ITEM_VALUE_TYPE_UINT64])
	)
	->addField(
		(new CWidgetFieldRadioButtonListView($data['fields']['item_filter_type']))
			->setFieldHint(
				makeHelpIcon([
					_('Elige si deseas filtrar usando IDs de items o etiquetas del widget de filtrado'), BR(), BR(),
					_('Actualmente, solo el widget Navegador de items transmite etiquetas, pero solo si el Navegador de items está configurado con \'Agrupar por\' de \'Valor de etiqueta de item\'')
				])
			)
	)
	->addField(
		(new CWidgetFieldCheckBoxView($data['fields']['update_item_filter_only']))
			->setFieldHint(
				makeHelpIcon([
					_('Marcar esta casilla significa que este widget solo mostrará métricas cuando haya un filtro de items establecido'), BR(),
					_('Si el filtro de items es un widget, una selección de ese widget referido es la única forma en que este widget mostrará métricas')
				])
			)
	)
	->addField(array_key_exists('host_tags_evaltype', $data['fields'])
		? new CWidgetFieldRadioButtonListView($data['fields']['host_tags_evaltype'])
		: null
	)
	->addField(array_key_exists('host_tags', $data['fields'])
		? new CWidgetFieldTagsView($data['fields']['host_tags'])
		: null
	)
	->addField(
		(new CWidgetFieldRadioButtonListView($data['fields']['layout']))
			->setFieldHint(
				makeHelpIcon([
					_('Horizontal - Host en la primera columna. Valores por item/métricas en columnas subsiguientes'), BR(),
					_('Vertical - Nombre de item/métrica en la primera columna. Valores por host en columnas subsiguientes'), BR(),
					_('3 Columnas - Nombre de item/métrica en la primera columna. Host en la segunda columna. Valores por item/métricas en la tercera columna'), BR(),
					_('Columna por patrón - Cada patrón de item especificado recibe su propia columna')
				])
			)
	)
	->addField(
		(new CWidgetFieldTableModuleItemGroupingView($data['fields']['item_group_by']))
			->setFieldHint(
				makeHelpIcon([
					_('Las etiquetas elegidas se mostrarán en la primera columna de la tabla.'), BR(),
					_('Alternativamente, puedes agrupar las métricas por host, lo que omitirá la primera columna, '),
					_('especificando una agrupación de \'{HOST.HOST}\'')
				])
			)
			->addRowClass('field_item_group_by')
	)
	->addField(
		(new CWidgetFieldTextBoxView($data['fields']['grouping_delimiter']))
			->setFieldHint(
				makeHelpIcon([
					_('Permite personalizar el delimitador de agrupación de items.'), BR(),
					_('Por defecto, el delimitador es \' / \' si no se especifica nada aquí.')
				])
			)
			->setWidth(ZBX_TEXTAREA_SMALL_WIDTH)
			->addRowClass('field_grouping_delimiter')
	)
	->addField(
		new CWidgetFieldRadioButtonListView($data['fields']['problems'])
	)
	->addField(
		(new CWidgetFieldColumnsListView($data['fields']['columns']))->addClass(ZBX_STYLE_TABLE_FORMS_SEPARATOR)
	)
	->addField(
		(new CWidgetFieldRadioButtonListView($data['fields']['bar_gauge_layout']))
			->setFieldHint(
				makeHelpIcon([
					_('Elige cómo mostrar las barras de medición en la tabla. Seleccionar \'Columna\' mostrará proporciones dentro de cada columna, mientras que seleccionar \'Fila\' mostrará proporciones dentro de cada fila.')
				])
			)
	)
	->addField(
		(new CWidgetFieldRadioButtonListView($data['fields']['bar_gauge_tooltip']))
			->setFieldHint(
				makeHelpIcon([
					_('Al pasar el ratón sobre una barra de medición en la tabla, se mostrará la proporción de la celda de valor correspondiente como porcentaje.'), BR(),
					_('Por defecto, se muestra la proporción con respecto al valor máximo. Sin embargo, eligiendo \'Suma\' puedes mostrar la proporción de la celda de valor con respecto a la suma de todas las celdas de valor, o puedes no mostrar ningún tooltip.'), BR(),
					_('NOTA: La proporción mostrada usará la elección de \'Diseño de barra\' de arriba')
				])
			)
	)
	->addField(
		(new CWidgetFieldCheckBoxView($data['fields']['no_broadcast_hostid']))
			->setFieldHint(
				makeHelpIcon([
					_('Desactiva la capacidad de transmitir el ID de host a otros widgets cuando los hosts son visibles en la tabla')
				])
			)
			->addRowClass('field_no_broadcast_hostid')
	)
	->addField(array_key_exists('aggregate_all_hosts', $data['fields'])
		? (new CWidgetFieldCheckBoxView($data['fields']['aggregate_all_hosts']))
			->setFieldHint(
				makeHelpIcon([
					_('Marcar esta casilla agregará todos los valores, por la agrupación de items de arriba, a través de todos los hosts'), BR(), BR(),
					_('NOTA: Marcar esta casilla requiere que se establezca una \'Agregación de patrones de columna\' en \'Items\' '), BR(),
					_('en el popup de configuración bajo la sección \'Configuración avanzada\''), BR(), BR(),
					_('OTRA NOTA: al usar esto, las opciones de \'Ordenación de hosts\' de la sección de Configuración avanzada de abajo se ignoran')
				])
			)
			->addRowClass('field_aggregate_all_hosts')
		: null
	)
	->addField(
		(new CWidgetFieldCheckBoxView($data['fields']['show_grouping_only']))
			->setFieldHint(
				makeHelpIcon([
					_('Marcar esta casilla hará que solo se muestre la columna \'Agrupación de items\''), BR(),
					_('Esto es útil cuando quieres usar este widget como filtro para otros widgets en lugar de mostrar métricas.'), BR(),
					_('NOTA: Marcar esta casilla automáticamente marca \'Transmitir desde columna agrupada\' para cada patrón de item especificado')
				])
			)
			->addRowClass('field_show_grouping_only')
	)
	->addField(
		(new CWidgetFieldCheckBoxView($data['fields']['autoselect_first']))
			->setFieldHint(
				makeHelpIcon([
					_('Marcar esta casilla hará que la primera celda de valor y host se seleccione automáticamente')
				])
			)
	)
	->addField(
		(new CWidgetFieldRadioButtonListView($data['fields']['footer']))
			->setFieldHint(
				makeHelpIcon([
					_('Si se establece, se agregará una fila de pie al final de la tabla')
				])
			)
	)
	->addField(
		(new CWidgetFieldTextBoxView($data['fields']['item_header']))
			->setFieldHint(
				makeHelpIcon([
					_('Cambia el nombre del encabezado del valor por defecto \'Items\' a este valor cuando se usan todos los diseños excepto Horizontal')
				])
			)
	)
	->addField(
		(new CWidgetFieldTextBoxView($data['fields']['host_header']))
			->setFieldHint(
				makeHelpIcon([
					_('Cambia el nombre del encabezado del valor por defecto \'Host\' a este valor cuando se usan todos los diseños excepto Vertical')
				])
			)
	)
	->addField(
		(new CWidgetFieldTextBoxView($data['fields']['reset_row']))
			->setFieldHint(
				makeHelpIcon([
					_('Al escribir un valor en esta casilla, agregarás una fila de reinicio al widget con el valor que ingresaste.'), BR(),
					_('Una fila de reinicio se usa con los diseños \'Horizontal\', \'3 Columnas\' y \'Columna por patrón\'.'), BR(),
					_('Después de hacer clic en el valor de la fila de reinicio, los widgets conectados se reiniciarán a sus configuraciones base.')
				])
			)
	)
	->addField(
		(new CWidgetFieldTextAreaView($data['fields']['item_name_strip']))
			->setFieldHint(
				makeHelpIcon([
					_('Establece la etiqueta de fila (Vertical) o columna (Horizontal/3 Columnas) para el nombre de la métrica'), BR(),
					_('Macros soportadas:'),
					(new CList([
						'{HOST.*}',
						'{ITEM.*}',
						'{INVENTORY.*}',
						_('Macros de usuario'),
					]))->addClass(ZBX_STYLE_LIST_DASHED)
				])
			)
	)
	->addFieldset(
		(new CWidgetFormFieldsetCollapsibleView(_('Configuración avanzada')))
			->addFieldsGroup(
				(new CWidgetFieldsGroupView(_('Ordenación de hosts')))
					->addField(
						new CWidgetFieldRadioButtonListView($data['fields']['host_ordering_order_by'])
					)
					->addField(
						(new CWidgetFieldPatternSelectItemView($data['fields']['host_ordering_item']))
							->removeLabel()
							->addClass(CFormField::ZBX_STYLE_FORM_FIELD_OFFSET_1)
					)
					->addField(
						new CWidgetFieldRadioButtonListView($data['fields']['host_ordering_order'])
					)
					->addField(
						new CWidgetFieldIntegerBoxView($data['fields']['host_ordering_limit'])
					)
					->addRowClass('fields-group-host-ordering')
			)
			->addFieldsGroup(
				(new CWidgetFieldsGroupView(_('Ordenación de items')))
					->addField(
						new CWidgetFieldRadioButtonListView($data['fields']['item_ordering_order_by'])
					)
					->addField(
						(new CWidgetFieldPatternSelectHostView($data['fields']['item_ordering_host']))
							->removeLabel()
							->addClass(CFormField::ZBX_STYLE_FORM_FIELD_OFFSET_1)
					)
					->addField(
						new CWidgetFieldRadioButtonListView($data['fields']['item_ordering_order'])
					)
					->addField(
						(new CWidgetFieldIntegerBoxView($data['fields']['item_ordering_limit']))
							->setFieldHint(makeHelpIcon(_('El límite se aplica a cada "Patrón de item" por separado')))
					)
					->addRowClass('fields-group-item-ordering')
			)
			->addField(
				new CWidgetFieldRadioButtonListView($data['fields']['show_column_header'])
			)
	)
	->includeJsFile('widget.edit.js.php')
	->addJavaScript('widget_tablemodulerme_form.init('.json_encode([
		'templateid' => $data['templateid']
	], JSON_THROW_ON_ERROR).');')
	->show();
