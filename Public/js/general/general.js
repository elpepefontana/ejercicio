/* global homePath, Metro */

// oculta un objeto en pantalla
function hide(objectName){
    if(document.getElementById(objectName).style.display === 'inline' || document.getElementById(objectName).style.display === 'block')
        document.getElementById(objectName).style.display = 'none';
}
  
// muestra un objeto en pantalla  
function show(objectName){
    if(document.getElementById(objectName).style.display === 'none' || document.getElementById(objectName).style.display === '')
        document.getElementById(objectName).style.display = 'inline';
}

// muestra un objeto en pantalla en bloque
function showBlock(objectName){
    if(document.getElementById(objectName).style.display === 'none' || document.getElementById(objectName).style.display === '')
        document.getElementById(objectName).style.display = 'block';
}

//valida si un objeto está vacio
function isEmpty(obj) {
    if (typeof obj === 'object') {
        return $.isEmptyObject(obj);
    }
    if (obj === '') {
        return true;
    }
    if (obj === 'undefined') {
        return true;
    }
    if (obj === null) {
        return true;
    }
    return false;
}

function ucfirst(str, force){
    var str = force ? str.toLowerCase() : str;
    return str.replace(
        /(\b)([a-zA-Z])/,
        function(firstLetter){
            return   firstLetter.toUpperCase();
        }
    );
}

function toCamelCase(value, first = false) {
    if (isEmpty(value)) {
        return '';
    }
    if (value.indexOf('_') === -1) {
        return ucfirst(value);
    }
    var arr = value.split('_');
    var out = '';
    $.each(arr, function (key, str){
        if (isEmpty(out)) {
            out += first ? ucfirst(str) : str;
        } else {
            out += ucfirst(str);
        }
    });
    return out;
}

function drawInfoBox(title, content, type, event){
    var html_content = "<h3>" + title + "</h3><p>" + content + "</p>";
    return Metro.infobox.create(
        html_content,       
        type,
        {
            onOpen: function(){
                var ib = $(this).data("infobox"); 
                setTimeout(
                    function(){
                        ib.close();
                    },
                    3000
                ); 
            }      
        }
    );
}

function closeModal(modalName, objectRemove = '') {
    $('#' + modalName + 'Modal').hide();
    
    var hidden = modalName.replace(/child_/gi, '');
    
    if (modalName.indexOf('child_') >= 0 && searchElementInDom('hid_' + hidden)) {
        removeElement('hid_' + hidden);
    }
    
    if (modalName.indexOf('child_') >= 0 && searchElementInDom('hid_' + hidden + '_father')) {
        removeElement('hid_' + hidden + '_father');
    }
    
    if (modalName.indexOf('child_') >= 0 && searchElementInDom('hid_' + hidden + '_id')) {
        removeElement('hid_' + hidden + '_id');
    }
    
    if (modalName.indexOf('child_') >= 0 && searchElementInDom('hid_' + hidden + '_color')) {
        removeElement('hid_' + hidden + '_color');
    }
    
    if (modalName.indexOf('child_') === -1 && searchElementInDom('hid_' + hidden + '_fields')) {
        removeElement('hid_' + hidden + '_fields');
    }
    
    if (objectRemove !== '') {
        removeElement(objectRemove);
    }
}

function setURL(objectName, action) {
    var obj = toCamelCase(objectName, true);
    return 'http://localhost' + homePath + '/' + obj + '/' + action;
}

function ajaxHandlerData(objectName, method, data) {
    return {
        object: objectName,
        method: method,
        dataToSend: data
    };
}

function searchColors(objectName, objectContext){
    var url = setURL('ajax_handler', 'List');
    
    var send = {'name': 'menu_color'};
    
    var ajaxData = ajaxHandlerData('combo', 'searchCombo', send);
    
    $.post(url, ajaxData).then(
        function(response) {
            var colors = JSON.parse(response);
            createHiddenData(objectName, objectContext, colors.Records);
        },
        function(xhr){
            console.log(xhr.status, xhr.responseText);
        }
    );
}

function randomColors(colors) {
    return colors[Math.floor(Math.random() * colors.length)];
}

// AJAX GENERAL FUNCTIONS


function sendMassiveUploadData(dataToSend) {
    var url = homePath + '/Gallery/levelChartTypeDecide/';
    $.post(url, dataToSend).then(
        function(response){
            if (response === '') {
                return;
            }
            drawInfoBox(
                'Resultado:', 
                response.split('|')[0] + ' registros ingresados.<br />' + response.split('|')[1] + ' registros con error.',
                'info', 
                ''
            );
            setTimeout( function(){ window.location.replace(homePath + '/TableGen/');}, 5000 );
        },
        function(xhr){
            console.log(xhr.status, xhr.responseText);
        }
    );
}

function searchTranslationFields(homePath, object, idObject) {
    var url = homePath + '/CodeGen/searchTranslationFields';
    
    var send = {};
    send.object = object;
    send.id_object = idObject;
    
    $.post(url, send).then(
        function (response) {
            $('#translateModalContent').html(response);
            $('#translate').show();
        },
        function(xhr){
            console.log(xhr.status, xhr.responseText);
        }
    );
}

function createSpecificTranslation(homePath, object, idObject, fieldName, languageCode, translateValue) {
    var url = homePath + '/CodeGen/createSpecificTranslation/';
    
    var send = {};
    send.tableName = object;
    send.id_object = idObject;
    send.name      = fieldName;
    send.value     = $('#' + fieldName + '_' + languageCode).val();
    send.language_code = languageCode;
    
    if(send.value !== '') {
        $.post(url, send).then(
            function(response){
                var msg = 'Traducción al "' + languageCode + '" de "' + fieldName + '" realizada con exito.';
                if (response !== 'OK') {
                    msg = 'Error en traducción al "' + languageCode + '" de "' + fieldName + '".';
                }
                drawInfoBox('Resultado', msg,'info', '');
            },
            function(xhr){
                console.log(xhr.status, xhr.responseText);
            }
        );
    } else
        drawInfoBox('Error', 'Debecompletar el campo "' + fieldName + '" de la traduccion a "' + languageCode + '".','alert', '');
}

// DYNAMIC MODAL

function drawModalDomStyle(modalData, modalContext) {
    modalData.modalWidth = modalData.modalWidth !== '' ? "width: " + modalData.modalWidth + "%;" : '';
    
    var modal = createElement('div', {
        id: modalData.modalName + 'Modal', 
        class: 'Modal', 
        style : "z-index: {" + modalData.zIndex + "});"
    });
    addElement(modal, modalContext);
    
    var titleClass = createElement('div', {
        id: modalData.modalName + 'Content',
        class: 'Modal-content',
        style: modalData.modalWidth
    });
    addElement(titleClass, modal);
    
    setModalTitleClass(modalData, titleClass);
    
    setModalContent(modalData, titleClass);
}

function setModalTitleClass(modalData, modalTitleClassContext) {
    var modalTitleClass = createElement('div', {
        class: modalData.modalTitleClass, 
        style: 'height: 45px;font-size: 20px; font-weight: bold;'
    });
    addElement(modalTitleClass, modalTitleClassContext);
    
    setModalTitleElements(modalData, modalTitleClass);
    
    setModalCloseElements(modalData, modalTitleClass);
}

function setModalTitleElements(modalData, modalTitleContext) {
    
    var modalTitle = createElement('div', {
        'id': modalData.modalName + 'ModalTitle',
        'class': 'px-3 py-2 mb-3',
        'style': 'float: left;width: 90%;'
    });
    addElement(modalTitle, modalTitleContext);
    
    var title = createElement('p', {});
    title.text(modalData.modalTitle);
    addElement(title, modalTitle);
}

function setModalCloseElements(modalData, modalCloseContext) {
    var closeDiv = createElement('div', {'class': 'px-3', 'style': 'float: left;width: 10%;'});
    addElement(closeDiv, modalCloseContext);
    
    var closeSpan = createElement('span', {
        'id': modalData.modalName + 'Close',
        'class': 'ModalClose',
        'onclick': "closeModal('" + modalData.modalName + "', '" +  modalData.modalCloseObject + "');"
    });
    closeSpan.text('x');
    addElement(closeSpan, closeDiv);
}

function setModalContent(modalData, modalContentContext){
    var modalContent = createElement('div', {
        id: modalData.modalName + 'ModalContent',
        class: modalData.modalContentClass + ' py-3'
    });
    addElement(modalContent, modalContentContext);
}

function ajaxPostCall2(objectName, method, send) {
    var url = setURL('ajax_handler', 'List');
    
    var toSend = {object: objectName, method: method, dataToSend: send};
    
    return $.post(url, toSend);
}

function ajaxJqueryPostCall(objectName, method, send) {
    var toSend = {object: objectName, method: method, dataToSend: send};
    $.ajax({
        method: "POST",
        url: setURL('ajax_handler', 'List'),
        data: toSend, 
        dataType: 'json', 
        contentType: 'application/json;',
        success: function(response){
            console.log(response);
            var formData = JSON.parse(response);
            if ($.isEmptyObject(formData)) {
                return;
            }
            
            createForm(formData, context, action);
            
            createHiddenData(objectName + '_fields', 'hiddenData', formData.fields);
        },
        error: function(xhr){
            console.log(xhr.status, xhr.statusText, xhr.responseText);
        }
    });
}

function ajaxPostCall(objectName, method, send) {
    var toSend = JSON.stringify({object: objectName, method: method, dataToSend: send});
    return $.ajax({
        url: setURL('ajax_handler', 'List'),
        method: 'POST',
        data: toSend, 
        dataType: 'json',
        contentType: "application/json; charset=utf-8;",
        cache: false
    });
}
