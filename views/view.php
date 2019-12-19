<?php

use delikatesnsk\treedropdown\DropdownTreeListAsset;


/**
 * @var $this \yii\web\View
 * @var $widget \delikatesnsk\treedropdown\DropdownTreeWidget
 * @var $htmlData string
 */
DropdownTreeListAsset::register($this);
$widget = $this->context;
$attribute = $widget->attribute;
$ajaxIds = array_diff(is_array($widget->model->$attribute) ? $widget->model->$attribute : [$widget->model->$attribute], \yii\helpers\ArrayHelper::getColumn($widget->items, 'id'));
?>

    <div class="dropdown-tree-container"<?= ( isset( $widget->id ) && $widget->id != "" ? "id=\"".$widget->id."\"" : ""); ?>>
        <?= ( is_string( $widget->label ) ? "<label>".$widget->label."</label>" : "") ?>
        <div class="form-control tree-input">
            <div class="icon">
                <span class="input-clear fa fa-times-circle hide"></span>
                <span class="caret"></span>
            </div>
            <?php
                if ( $widget->multiSelect ) {

                    ?><ul><li class="empty">&nbsp;</li></ul><?php
                } else {
                    ?><span>&nbsp;</span><?php
                }
            ?>
        </div>
        <div class="tree-dropdown dropdown-menu" role="menu">
            <?php
            if ( is_array( $widget->searchPanel ) && isset( $widget->searchPanel['visible'] ) && $widget->searchPanel['visible'] ) {
                ?>
                <div class="tree-header">
                    <div class="row">
                        <div class="col-sm-6">
                            <span class="header-text"><?= ( isset( $widget->searchPanel['label'] ) ? $widget->searchPanel['label'] : '' ); ?></span>
                        </div>
                        <div class="col-sm-6">
                            <span class="search-clear">&times;</span>
                            <input class="form-control search-input" name="search-input" type="text" placeholder="<?= ( isset( $widget->searchPanel['placeholder'] ) ? $widget->searchPanel['placeholder'] : '' ); ?>" />
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="tree">
                <?php
                    if ( isset( $widget->items ) && is_array( $widget->items ) ) {
                        if ( is_array( $widget->rootNode ) && isset( $widget->rootNode['visible'] ) && $widget->rootNode['visible'] ) {
                        ?>
                            <ul>
                                <li class="parent">
                                    <div class="root node">
                                        <i class="fa fa-<?= ( count( $widget->items ) > 0 ? 'minus' : 'plus' ); ?>-square-o"></i>
                                        <span><?= ( isset( $widget->rootNode['label'] ) ? $widget->rootNode['label'] : '' ); ?></span>
                                    </div>
                        <?php
                        }

                        echo $htmlData;

                        if ( is_array( $widget->rootNode ) && isset( $widget->rootNode['visible'] ) && $widget->rootNode['visible'] ) {
                        ?>
                                </li>
                            </ul>
                        <?php
                        }
                    }
                ?>
            </div>
        </div>
        <?php
        if ( $widget->form !== null && ( $widget->form instanceof \yii\widgets\ActiveForm ) &&
            $widget->model !== null && ( $widget->model instanceof \yii\base\Model ) && $attribute != '') {
            echo $widget->form->field($widget->model, $attribute . (is_array($widget->model->$attribute) ? "[]" : ""))->hiddenInput()->label(false);
        }
        ?>
    </div>

<?php
$id = ( isset( $widget->id ) && $widget->id != "" ? "#".$widget->id : ".dropdown-tree-container");
$js = "
$(document).ready(function() {
    var widgetId = '$id';
    function addItem(id, name) {
        if ($(widgetId + ' .tree-input ul').length > 0) {
            $(widgetId + ' .tree-input ul li.empty').remove();
            $(widgetId + ' .tree-input ul').append('<li data-id='+id+'>'+name+'</li>');
        } else {
            $(widgetId + ' .tree-input > span').html(name);
        }
        
        if ( $(widgetId + ' > .form-group input[type=hidden]').length > 0) {
            if ( $(widgetId + ' > .form-group input[type=hidden]:eq(0)').val() != '' && $(widgetId + ' > .form-group input[data-id=' + id + ']').length == 0) {
                $(widgetId + ' > .form-group input[type=hidden]:eq(0)').clone().appendTo(widgetId + ' > .form-group');
                $(widgetId + ' > .form-group input[type=hidden]:last-child').val(id);
                $(widgetId + ' > .form-group input[type=hidden]:last-child').attr( 'data-id', id);
            } else {
                $(widgetId + ' > .form-group input[type=hidden]:eq(0)').val(id);
                $(widgetId + ' > .form-group input[type=hidden]:eq(0)').attr('data-id', id);
            }
        }
        $(widgetId + ' .tree-input .icon .input-clear').removeClass('hide');
    }
    function removeItem(id) {
        $(widgetId + ' .tree-input ul li[data-id='+id+']').remove();
        if ( $(widgetId + ' .tree-input ul li').length == 0 ) {
            $(widgetId + ' .tree-input ul').append('<li class=\"empty\">&nbsp;</li>');
            $(widgetId + ' .tree-input .icon .input-clear').addClass('hide');
        }
        if ( $(widgetId + ' > .form-group input[type=hidden]').length > 1 ) {
            $(widgetId + ' > .form-group input[data-id='+id+']').remove();
        } else {
            $(widgetId + ' > .form-group input[type=hidden]:eq(0)').val('');
        }
    }
        
    $(widgetId + ' .tree ul li .node i.fa-plus-square-o').each(function(){
        $(this).parent().parent().children('ul').children('li').hide();
    });
    $(widgetId + ' .tree-input').on('click', function(){
        if ( !$(this).children('.icon').children('.caret').hasClass('up') ) {
            $(this).children('.icon').children('.caret').addClass('up');
            $(this).parent().children('.tree-dropdown').addClass('open');
        } else {
            $(this).children('.icon').children('.caret').removeClass('up');
            $(this).parent().children('.tree-dropdown').removeClass('open');
        }
    });
    $(widgetId + ' .tree-header .search-clear').on('click', function(){
        $(widgetId + ' input[name=search-input]').val('');
        $(widgetId + ' .tree ul li.hide').removeClass('hide');
    });
    $(widgetId + ' .tree-header input[name=search-input]').on('keyup', function(e,v){
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode.toString() != 8) {
            var text = $(this).val()".( !is_array( $widget->searchPanel ) || !isset( $widget->searchPanel['searchCaseSensivity'] ) || !$widget->searchPanel['searchCaseSensivity']  ? ".toUpperCase()" : "" ).";
            $(widgetId + ' .tree ul li .node:not(.root) span').each(function(){
                var spanText = $(this).text()".( !is_array( $widget->searchPanel ) || !isset( $widget->searchPanel['searchCaseSensivity'] ) || !$widget->searchPanel['searchCaseSensivity']  ? ".toUpperCase()" : "" ).";
                if ( spanText.indexOf( text ) == -1 ) {
                    $(this).parent().parent().addClass('hide');
                }
            });
         } else {
            $(widgetId + ' .tree ul li.hide').removeClass('hide');
         }   
    });
    $(widgetId + ' .tree-header input[name=search-input]').on('paste', function(){
        $(widgetId + ' .tree ul li .node:not(.root) span:not(:contains( \$this.val() ))').parent().parent().addClass('hide');
    });
    $(widgetId + ' .tree-input .icon .input-clear').on('click', function(e){
        e.stopPropagation();
        if ( $(widgetId + ' .tree-input > ul').length != 0 ) {
            while ( $(widgetId + ' > .form-group input[type=hidden]').length > 1 ) {
                 $(widgetId + ' > .form-group input[type=hidden]:last-child').remove();
            }
            $(widgetId + ' .tree-input > ul li').remove();
            $(widgetId + ' .tree-input > ul').append('<li class=\"empty\">&nbsp;</li>');
            $(widgetId + ' .tree .node .fa-check-square-o').removeClass('fa-check-square-o').addClass('fa-square-o');
        } else {
            $(widgetId + ' .tree-input > span').html('&nbsp;');
        }
        $(widgetId + ' .tree ul li .node:not(.root) > span.selected').removeClass('selected');
        $(widgetId + ' > .form-group input[type=hidden]:eq(0)').val('');
        $(widgetId + ' .tree-input .icon .input-clear').addClass('hide');
    });
    $('{$widget->form->id}').on('click', widgetId + ' .tree ul li .node i',function(){
        var id = $(this).next('span').attr('data-id');
        var name = $(this).next('span').html();
        if ( $(this).hasClass('fa-minus-square-o') ) {";
//Collapse Node Event Ajax Request
if ( $widget->ajax !== null && is_array( $widget->ajax ) && isset( $widget->ajax['onNodeCollapse'] ) && is_array( $widget->ajax['onNodeCollapse'] ) && isset( $widget->ajax['onNodeCollapse']['url'] ) && trim( $widget->ajax['onNodeCollapse']['url'] ) != '' ) {
    $ajaxData = '';
    $nodeIdParamName = 'id';
    if ( isset( $widget->ajax['onNodeCollapse']['params'] ) && is_array(  $widget->ajax['onNodeCollapse']['params'] ) && count(  $widget->ajax['onNodeCollapse']['params'] ) > 0 ) {
        foreach( $widget->ajax['onNodeCollapse']['params'] as $key => $value ) {
            if ( $value !== '%nodeId' ) {
                $ajaxData .= ( $ajaxData != '' ? '&' : '' ).$key.'='.$value;
            } else {
                $nodeIdParamName = $key;
            }
        }
    }
    $url = $widget->ajax['onNodeCollapse']['url'];
    $method = mb_convert_case( ( isset( $widget->ajax['onNodeCollapse']['method'] ) &&  $widget->ajax['onNodeCollapse']['method'] != '' &&
    ( mb_convert_case(  $widget->ajax['onNodeCollapse']['method'], MB_CASE_LOWER ) == 'get' || mb_convert_case(  $widget->ajax['onNodeCollapse']['method'], MB_CASE_LOWER ) == 'post' )
        ? $widget->ajax['onNodeCollapse']['method']
        : 'post'
    ), MB_CASE_UPPER);
    $js .= "
            $.ajax({
                type: '".$method."',
                url: '".$url."',
                data: '".( $ajaxData != "" ? $ajaxData."&" : "" ).$nodeIdParamName."='+( typeof( $(this).parent().children('span').attr('data-id') ) != 'undefined' ? $(this).parent().children('span').attr('data-id') : '' ),
                async: true
            });
    ";

}
$js .= "
            $(this).parent().parent().children('ul').children('li').hide();
            $(this).removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
   }";

$js .= "else if ( $(this).hasClass('fa-plus-square-o') ) {";
//Expand Node Event Ajax Request
if ( $widget->ajax !== null && is_array( $widget->ajax ) && isset( $widget->ajax['onNodeExpand'] ) && is_array( $widget->ajax['onNodeExpand'] ) && isset( $widget->ajax['onNodeExpand']['url'] ) && trim( $widget->ajax['onNodeExpand']['url'] ) != '' ) {
    if ( isset( $widget->ajax['onNodeExpand']['params'] ) && is_array(  $widget->ajax['onNodeExpand']['params'] ) && count(  $widget->ajax['onNodeExpand']['params'] ) > 0 ) {
        $ajaxData = '';
        $nodeIdParamName = 'id';
        foreach( $widget->ajax['onNodeExpand']['params'] as $key => $value ) {
            if ( $value !== '%nodeId' ) {
                $ajaxData .= ( $ajaxData != '' ? '&' : '' ).$key.'='.$value;
            } else {
                $nodeIdParamName = $key;
            }
        }
    }
    $url = $widget->ajax['onNodeExpand']['url'];
    $method = mb_convert_case( ( isset( $widget->ajax['onNodeExpand']['method'] ) &&  $widget->ajax['onNodeExpand']['method'] != '' &&
               ( mb_convert_case(  $widget->ajax['onNodeExpand']['method'], MB_CASE_LOWER ) == 'get' || mb_convert_case(  $widget->ajax['onNodeExpand']['method'], MB_CASE_LOWER ) == 'post' )
                ? $widget->ajax['onNodeExpand']['method']
                : 'post'
              ), MB_CASE_UPPER);
    $js .= "
        var \$node = $(this);
        if ( \$node.parent().parent().children('ul').length == 0 ) {
            \$node.removeClass('fa-plus-square-o').addClass('fa-spinner');
            $.ajax({
                type: '".$method."',
                url: '".$url."',
                data: '".( $ajaxData != "" ? $ajaxData."&" : "" ).$nodeIdParamName."='+( typeof( $(this).parent().children('span').attr('data-id') ) != 'undefined' ? $(this).parent().children('span').attr('data-id') : '' ),
                error: function(req, text, error) { \$node.remove(); },
                success: function (data) {
                    if ( !$.isArray( data ) && data.length > 0 ) {
                        data = $.parseJSON( data );
                    }
                    if ( $.isArray( data ) && data.length > 0 ) {
                        var \$str = '';
                        for (var i = 0; i < data.length; i++ ) {
                           if ( i == 0 ) {
                              \$str = '<ul'+( \$node.parent().parent().parent().children('li').length > 0 && \$node.parent().parent().index() == \$node.parent().parent().parent().children('li').length - 1 ? ' class=\"last-node\"' : '' )+'>';
                           }
                           \$str += '    <li>'+
                                       '       <div class=\"node\">'+
                                       (typeof( data[i].items ) != 'undefined' ? '<i class=\"fa fa-plus-square-o\"></i>' : '') +
                                       ". ( $widget->multiSelect ? "'           <i class=\"fa fa-square-o\"></i>'+" : "" )."
                                       '           <span data-id=\"'+data[i].id+'\">'+data[i].label+'</span>'+
                                       '       </div>'+               
                                       '    </li>';           
                            if ( i == data.length - 1  ) {
                                \$str += '</ul>';
                            }
                        }
                        \$node.parent().parent().addClass('parent');
                        \$node.parent().parent().append( \$str );
                        \$node.parent().parent().children('ul').children('li').each(function() {
                                var \$span = $(this).find('span');
                                if (\$(widgetId + ' > .form-group input[data-id=' + \$span.data('id') + ']').length > 0) {
                                    \$(this).find('.node .fa-square-o').removeClass('fa-square-o').addClass('fa-check-square-o');
                                    \$span.addClass('selected');
                                }
                            });
                        \$node.parent().parent().children('ul').children('li').show();
                        \$node.removeClass('fa-spinner').addClass('fa-minus-square-o');
                    } else { \$node.remove();  }                
                },
                datatype: 'json',
                async: true
            });
        } else {
            $(this).parent().parent().children('ul').children('li').show();
            $(this).removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
        }
     } ";

} else {
    $js .= "
            $(this).parent().parent().children('ul').children('li').show();
            $(this).removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
        } ";
}
if ( $widget->multiSelect ) {
    $js .= "else if ( $(this).hasClass('fa-check-square-o') ) {
            $(this).removeClass('fa-check-square-o').addClass('fa-square-o');
            $(this).next('span').removeClass('selected');
            removeItem(id);
        } else if ( $(this).hasClass('fa-square-o') ) {
            $(this).removeClass('fa-square-o').addClass('fa-check-square-o');
            $(this).next('span').addClass('selected');
            addItem(id, name);    
        }
    });    
";
} else {
    $js .= "});    
    $('body').on('click', widgetId + ' .tree ul li .node:not(.root) > span', function(){
        $(widgetId + ' > .form-group input[type=hidden]:eq(0)').val( $(this).attr('data-id') );
        $(widgetId + ' .tree-input > span').html( $(this).html() );
        $(widgetId + ' .tree ul li .node:not(.root) > span').removeClass('selected');
        $(this).addClass('selected');
        $(widgetId + ' .tree-input .icon .input-clear').removeClass('hide');
        $(widgetId + ' .tree-input').click();
        
    });
    ";
}
if ( $widget->expand ) {
    $js .= "$(widgetId + ' .tree-input').click();
    ";
}
if ( $widget->form !== null && ( $widget->form instanceof \yii\widgets\ActiveForm ) ) {
    $js .= "$('#" . $widget->form->id . "').on('beforeValidate', function(){
        if ( $(widgetId + ' .tree-dropdown').hasClass('open') ) { 
            $(widgetId + ' .tree-input').click();
        };
    });
    $('#" . $widget->form->id . "').on('afterValidate', function(event, messages){
        //var attributes = $(this).data().yiiActiveForm.attributes;
        //var settings = $(this).data().yiiActiveForm.settings;
        if ( $(widgetId + ' > .form-group .help-block').html() != '' ) {
            $(widgetId + ' > label').addClass('has-error');
            $(widgetId + ' .tree-input').addClass('has-error');
        } else { 
            $(widgetId + ' > label').removeClass('has-error').addClass('has-success');
            $(widgetId + ' .tree-input').removeClass('has-error').addClass('has-success'); 
        }
    });
    ";
}
if ( $widget->form !== null && ( $widget->form instanceof \yii\widgets\ActiveForm ) &&
    $widget->model !== null && ( $widget->model instanceof \yii\base\Model ) && $attribute != '') {

    $attributes = ( is_array( $widget->model->$attribute ) ? $widget->model->$attribute : [ $widget->model->$attribute ] );

    if ( is_array( $attributes ) && count( $attributes ) > 0 ) {
        foreach ( $attributes as $attribute ) {
            if ( $attribute !== null ) {
                if ( $widget->multiSelect ) {
                    $js .= "$(widgetId + ' .tree ul li .node span[data-id=\"".$attribute."\"]').prev('.fa-square-o').click();\n";
                } else {
                    $js .= "$(widgetId + ' .tree ul li .node span[data-id=\"".$attribute."\"]').click();\n";
                }
            }
        }
        foreach ($ajaxIds as $ajaxId) {
            if ($ajaxId !== null) {
                $js .= "addItem($ajaxId, '{$widget->model->getItemLabel($ajaxId)}');";
            }
        }
        if ( !$widget->expand ) {
            $js .= "if ( $(widgetId + ' .tree-dropdown').hasClass('open') ) { $(widgetId + ' .tree-input').click(); };";
        }
    }
}

$js .= "});";
$this->registerJs($js );
?>