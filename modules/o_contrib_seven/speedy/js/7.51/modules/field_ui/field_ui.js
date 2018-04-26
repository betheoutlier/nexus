(function($){Drupal.behaviors.fieldUIFieldOverview={attach:function(context,settings){$("table#field-overview",context).once("field-overview",function(){Drupal.fieldUIFieldOverview.attachUpdateSelects(this,settings)})}};Drupal.fieldUIFieldOverview={attachUpdateSelects:function(table,settings){var widgetTypes=settings.fieldWidgetTypes;var fields=settings.fields;$(".widget-type-select",table).each(function(){this.initialValue=this.options[0].text});$(".field-type-select",table).each(function(){this.targetSelect=$(".widget-type-select",$(this).closest("tr"));$(this).bind("change keyup",function(){var selectedFieldType=this.options[this.selectedIndex].value;var options=selectedFieldType in widgetTypes?widgetTypes[selectedFieldType]:[];this.targetSelect.fieldUIPopulateOptions(options)});$(this).trigger("change",false)});$(".field-select",table).each(function(){this.targetSelect=$(".widget-type-select",$(this).closest("tr"));this.targetTextfield=$(".label-textfield",$(this).closest("tr"));this.targetTextfield.data("field_ui_edited",false).bind("keyup",function(e){$(this).data("field_ui_edited",$(this).val()!="")});$(this).bind("change keyup",function(e,updateText){var updateText=typeof updateText=="undefined"?true:updateText;var selectedField=this.options[this.selectedIndex].value;var selectedFieldType=selectedField in fields?fields[selectedField].type:null;var selectedFieldWidget=selectedField in fields?fields[selectedField].widget:null;var options=selectedFieldType&&selectedFieldType in widgetTypes?widgetTypes[selectedFieldType]:[];this.targetSelect.fieldUIPopulateOptions(options,selectedFieldWidget);if(updateText&&!this.targetTextfield.data("field_ui_edited")){this.targetTextfield.val(selectedField in fields?fields[selectedField].label:"")}});$(this).trigger("change",false)})}};jQuery.fn.fieldUIPopulateOptions=function(options,selected){return this.each(function(){var disabled=false;if(options.length==0){options=[this.initialValue];disabled=true}var previousSelectedText=this.options[this.selectedIndex].text;var html="";jQuery.each(options,function(value,text){var is_selected=typeof selected!="undefined"&&value==selected||typeof selected=="undefined"&&text==previousSelectedText;html+='<option value="'+value+'"'+(is_selected?' selected="selected"':"")+">"+text+"</option>"});$(this).html(html).attr("disabled",disabled?"disabled":false)})};Drupal.behaviors.fieldUIDisplayOverview={attach:function(context,settings){$("table#field-display-overview",context).once("field-display-overview",function(){Drupal.fieldUIOverview.attach(this,settings.fieldUIRowsData,Drupal.fieldUIDisplayOverview)})}};Drupal.fieldUIOverview={attach:function(table,rowsData,rowHandlers){var tableDrag=Drupal.tableDrag[table.id];tableDrag.onDrop=this.onDrop;tableDrag.row.prototype.onSwap=this.onSwap;$("tr.draggable",table).each(function(){var row=this;if(row.id in rowsData){var data=rowsData[row.id];data.tableDrag=tableDrag;var rowHandler=new rowHandlers[data.rowHandler](row,data);$(row).data("fieldUIRowHandler",rowHandler)}})},onChange:function(){var $trigger=$(this);var row=$trigger.closest("tr").get(0);var rowHandler=$(row).data("fieldUIRowHandler");var refreshRows={};refreshRows[rowHandler.name]=$trigger.get(0);var region=rowHandler.getRegion();if(region!=rowHandler.region){$("select.field-parent",row).val("");$.extend(refreshRows,rowHandler.regionChange(region));rowHandler.region=region}Drupal.fieldUIOverview.AJAXRefreshRows(refreshRows)},onDrop:function(){var dragObject=this;var row=dragObject.rowObject.element;var rowHandler=$(row).data("fieldUIRowHandler");if(typeof rowHandler!=="undefined"){var regionRow=$(row).prevAll("tr.region-message").get(0);var region=regionRow.className.replace(/([^ ]+[ ]+)*region-([^ ]+)-message([ ]+[^ ]+)*/,"$2");if(region!=rowHandler.region){refreshRows=rowHandler.regionChange(region);rowHandler.region=region;Drupal.fieldUIOverview.AJAXRefreshRows(refreshRows)}}},onSwap:function(draggedRow){var rowObject=this;$("tr.region-message",rowObject.table).each(function(){if($(this).prev("tr").get(0)==rowObject.group[rowObject.group.length-1]){if(rowObject.method!="keyboard"||rowObject.direction=="down"){rowObject.swap("after",this)}}if($(this).next("tr").is(":not(.draggable)")||$(this).next("tr").length==0){$(this).removeClass("region-populated").addClass("region-empty")}else if($(this).is(".region-empty")){$(this).removeClass("region-empty").addClass("region-populated")}})},AJAXRefreshRows:function(rows){var rowNames=[];var ajaxElements=[];$.each(rows,function(rowName,ajaxElement){rowNames.push(rowName);ajaxElements.push(ajaxElement)});if(rowNames.length){var $throbber=$('<div class="ajax-progress ajax-progress-throbber"><div class="throbber">&nbsp;</div></div>');$(ajaxElements).addClass("progress-disabled").after($throbber);$("input[name=refresh_rows]").val(rowNames.join(" "));$("input#edit-refresh").mousedown();$(ajaxElements).attr("disabled",true)}}};Drupal.fieldUIDisplayOverview={};Drupal.fieldUIDisplayOverview.field=function(row,data){this.row=row;this.name=data.name;this.region=data.region;this.tableDrag=data.tableDrag;this.$formatSelect=$("select.field-formatter-type",row);this.$formatSelect.change(Drupal.fieldUIOverview.onChange);return this};Drupal.fieldUIDisplayOverview.field.prototype={getRegion:function(){return this.$formatSelect.val()=="hidden"?"hidden":"visible"},regionChange:function(region){var currentValue=this.$formatSelect.val();switch(region){case"visible":if(currentValue=="hidden"){var value=typeof this.defaultFormatter!=="undefined"?this.defaultFormatter:this.$formatSelect.find("option").val()}break;default:var value="hidden";break}if(value!=undefined){this.$formatSelect.val(value)}var refreshRows={};refreshRows[this.name]=this.$formatSelect.get(0);return refreshRows}}})(jQuery);
