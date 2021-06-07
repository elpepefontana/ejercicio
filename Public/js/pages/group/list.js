/* global homePath */

var json_data;
var files;

function getVars(url){
    var formData = new FormData();
    var split;
    $.each(url.split("&"), function(key, value) {
        split = value.split("=");
        formData.append(split[0], decodeURIComponent(split[1].replace(/\+/g, " ")));
    });
    return formData;
}

$(document).delegate('#img','change', prepareUpload);

function prepareUpload(event){
    files = event.target.files;
}

$(document).ready(function () {
    $('#tbl_groups').jtable({
        title: 'Usuarios',
        create: true,
        paging: true,
        pageSize: 10, 
        sorting: true,     
        jtStartIndex: 0,     
        jtPageSize: 10, 
        jtSorting: 'last_name',
        defaultSorting: 'last_name',
        selecting: true, 
        multiselect: true, 
        selectingCheckboxes: true,
        selectOnRowClick: false,
        actions: {
            listAction: function (postData, jtParams) {
                return $.Deferred(function ($dfd) {
                $.ajax({
                    url: homePath + '/Group/Search/limit=' + jtParams.jtStartIndex + ',' + jtParams.jtPageSize + '&sort=' + jtParams.jtSorting,
                    type: 'POST',
                    dataType: 'json',
                    data: postData,
                        success: function (data) {
                            $dfd.resolve(data);
                        },
                        error: function () {
                            $dfd.reject();
                        }
                    });
                });
            },
            createAction: homePath + '/Group/Create/',
            updateAction: homePath + '/Group/Change/',
            deleteAction: homePath + '/Group/Erase/'
        },
        fields: { 
            id: {key: true,list: false},
            name: {title: 'Nombre', width: '10%',inputClass : 'validate[required]'}
        },
        formCreated: function (event, data) {
            data.form.validationEngine();
        },
        formSubmitting: function (event, data) {
            return data.form.validationEngine('validate');
        },
        formClosed: function (event, data) {
            data.form.validationEngine('hide');
            data.form.validationEngine('detach');
        },
        selectionChanged: function () {
            var $selectedRows = $('#tbl_groups').jtable('selectedRows'),records = [];
            $selectedRows.each(function () {
                var record = $(this).data('record');
                records.push(record);
            });
            json_data = JSON.stringify(records);
        }
        
        
    });
    
    
    $('#tbl_groups').jtable('load');
    
    
    $('#search').click((e) => {
        e.preventDefault();

        if ($('#field').val() === 'undefined' || $('#value').val() === 'undefined') {
            return;
        }

        var data = {};
        data[$('#field').val()] = $('#value').val();
        
        $('#tbl_groups').jtable('load', data);
    });

    $('#clean').click(function (e) {
        e.preventDefault();
        
        $('#field').val('');
        $('#value').val('');
        
        $('#tbl_groups').jtable('load', {});
    });

    $('#search').click();

});



function myJsonPost(path, params) {
    method = "POST"; 
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "Record");
    hiddenField.setAttribute("value", params);
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();
} 