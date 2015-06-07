/*
 * 
 * Jonathan Gamble
 */

$(document).ready(function(){
    
    var opts = {
        serverFile: 'dynamic.php',
        tableID: 'myTable',
        deleteRowClass: 'deleteRow',
        editRowClass: 'editRow',
        addRowID: 'addRow',
        fieldIDs: ['position', 'term', 'definition', 'delete', 'edit'],
        formID: 'myForm',
        lastField: 'submitField',
        debug: 0
    };    
    myTable = new ajaxTable(opts);
    
    /*$(function() {
        $("input[type=submit], input[type=button], a, button").button();
    });
    
   $(":submit, :button, :reset").button();
    
    $('.ui-dialog').on('click', function() {
    $('.ui-dialog').removeClass('ui-state-focus');
    $(this).addClass('ui-state-focus');
    });
    (function ($) {

        $.fn.styleTable = function (options) {
            var defaults = {
                css: 'ui-styled-table'
            };
            options = $.extend(defaults, options);

            return this.each(function () {
                $this = $(this);
                $this.addClass(options.css);

                $this.on('mouseover mouseout', 'tbody tr', function (event) {
                    $(this).children().toggleClass("ui-state-hover", event.type == 'mouseover');
                });

                $this.find("th").addClass("ui-state-default");
                $this.find("td").addClass("ui-widget-content");
                $this.find("tr:last-child").addClass("last-child");
            });
        };

        $("table").styleTable();

    })(jQuery);

    // http://stackoverflow.com/questions/14744940/how-to-style-focus-on-jquery-ui-dialog-widget
    $(document).on('click', function(e) {
        var $target = $(e.target);
        if(!$target.hasClass('ui-dialog') && $target.parents().hasClass('ui-dialog')) {
            $('.ui-dialog').removeClass('ui-state-focus');
        }
    });

    $("tbody tr:odd").css('background-color', '#ccc');
*/

});
/*
 * ajaxTable Object
 */
function ajaxTable(opts) {
    
    init();   
    /*
     * init() - Add click and enter events
     */        
    function init() {
        // nothing is open for editing
        $.openEdit = 0;
        // add row event
        $('#' + opts.addRowID).click(add);

        // add enter event
        $('.submitField').keypress(function (e) {
          if (e.which == 13) {
            add(e);
            // focus back to first input
            $('form').find('input[type=text],textarea,select').filter(':visible:first').focus();
            return false;
          }
        });
        // delete row event
        $('.' + opts.deleteRowClass).click(del);
        
        // edit row event
        $('.' + opts.editRowClass).click(edit);
        
        // sortable event
        $('#' + opts.tableID +' tbody').sortable({
            helper: function(e, ui) {
                // fix problem with sortable
                // http://tinyurl.com/qg6zcz2
                ui.children().each(function() {
                    $(this).width($(this).width());
                });
                return ui;
            },
            update: function() {
                // update count
                var i = 0;
                $('#' + opts.tableID + ' tbody tr td:first-child').each(function() {
                    $(this).html(++i);
                });
                // post to server
                $.ajax({
                    type: 'POST',
                    data: {
                        action: 'sort',
                        items: $(this).sortable('toArray')
                    },
                    url: opts.serverFile,
                    error: ajaxErr,
                    dataType: 'json',
                    success: function(results) {
                        // debug
                        if (opts.debug) alert(JSON.stringify(results));
                    }
                });
            }
        }).disableSelection();
        
    }
    /*
     * add() - Click add button event
     */
    function add(e) {
        
        var data = $('#' + opts.formID).serialize();
        
        // get count
        var pos = $('table#' + opts.tableID + ' tbody tr:last td').html() || 0;
        data += '&action=add&position=' + (parseInt(pos) + 1);
        // post to server
        $.ajax({
            type: 'POST',
            data: data,
            url: opts.serverFile,
            error: ajaxErr,
            success: addSuccess,
            dataType: 'json'
        });
        // prevent submit
        e.preventDefault();
    }
    /*
     * addSuccess() - if added to server succesfully
     */
    function addSuccess(results) {
        // debug
        if (opts.debug) alert(JSON.stringify(results));
        
        // add fields in fieldIDs order
        var td = '';
        for (var d in opts.fieldIDs) {
            td += "<td>" + results.data[opts.fieldIDs[d]] + "</td>";
        }     
        // append number | fields | delete
        $('#' + opts.tableID).children('tbody').append(
            $('<tr>').attr('id', results['id']).append(td)
        );

        // clear form values
        $('#' + opts.formID)[0].reset();
        
        // delete row event
        $('#' + results['id'] + ' .' + opts.deleteRowClass).click(del);
        
        // edit row event    
        $('#' + results['id'] + ' .' + opts.editRowClass).click(edit);
        
        
    }
        /*
     * edit() - Click edit button event
     */
    function edit(e) {
        if ($(this).text() == 'save') {
            $.openEdit--;
            if ($.openEdit === 0) {
                // enable sortable
                $('#' + opts.tableID +' tbody').disableSelection();
                $('#' + opts.tableID +' tbody').sortable("enable");
            }
            // remove input
            var ignore = [1,4,5];
            var i = 0;
            var data = {};
            $(this).closest('tr').children('td').each(function() {
                i++;
                if (i !== 1 && i !==4 && i !==5) {
                    var val = $(this).children('input').val();
                    $(this).text(val);
                    data[opts.fieldIDs[i-1]] = val;
                }
            });
            // post to server
            $.ajax({
                type: 'POST',
                data: {
                    action: 'update',
                    id: $(this).closest('tr').attr('id'),
                    fields: data
                },
                url: opts.serverFile,
                error: ajaxErr,
                success: function(results) {
                    // debug
                    if (opts.debug) alert(JSON.stringify(results));
                },
                dataType: 'json'
            });
            // change save button to edit
            $(this).text("edit");
        }
        else {
            $.openEdit++;
            // disable sortable
            $('#' + opts.tableID +' tbody').enableSelection();
            $('#' + opts.tableID +' tbody').sortable("disable");
            // replace elements with input tag
            var ignore = [1,4,5];
            var i = 0;
            $(this).closest('tr').children('td').each(function() {
                i++;
                if (i !== 1 && i !==4 && i !==5) {
                    var t = '<input class="enterInput" type="text" value="';
                    t += $(this).text();
                    t += '">';             
                    $(this).html(t);
                }
            });
            // enter event
            var id = $(this).closest('tr').attr('id');
            $('tr[id=' + id + '] .enterInput').keypress(function (e) {
                if (e.which == 13) {
                  $('tr[id=' + id + '] .editRow').trigger("click");
                  e.preventDefault();
                }
            });
            
            // change edit button to add
            $(this).text("save");
        }        
        // prevent submit
        e.preventDefault();
    }
    /*
     * del() - Click delete button event
     */
    function del(e) {
        // post to server
        $.ajax({
            type: 'POST',
            data: {
                action: 'del',
                id: $(this).closest('tr').attr('id')
            },
            error: ajaxErr,
            url: opts.serverFile,
            success: delSuccess,
            dataType: 'json'
        });
        // prevent submit
        e.preventDefault();
    }
    /*
     * delSuccess() - Deleted successfully
     */
    function delSuccess(results) {
        // debug
        if (opts.debug) alert(JSON.stringify(results));
        
        // get id        
        var id = '#' + results.data['id'];
        // fade out
        $(id).fadeOut('fast', function() {
            // update count
            $(id + ' ~ tr > td:first-child').each(function() {
                $(this).html(parseInt($(this).html()) - 1);
            });
            // remove element
            $(this).remove();
        });
    }
    /*
     * ajaxErr() - Debugging
     */
    function ajaxErr(xhr, status, error) {
        // debug
        if(opts.debug) alert(xhr.responseText);
    }   
}
