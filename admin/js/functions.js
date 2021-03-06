function checkChildren(element) {
    var sectionId = element.value;
    var childCheckboxes = $('.sonOf' + sectionId);
    var length = childCheckboxes.length;
    for (var i = 0; i < length; i++) {
        if(childCheckboxes[i].checked != element.checked){
            $('#'+childCheckboxes[i].id).trigger('click');
        }
    }
} 

function loginAdmin() {
    $.ajax({
        type: "POST",
        async: false,
        url: base_url + "admin/ajax/loginAdmin.php",
        data: {
            user : $('#username').val(),
            pass : $('#password').val()
//            "username=" + escape($('#username').val()) + '&password=' + escape($('#password').val())
        },
        success: function(msg) {
//            alert (msg);
            if (msg != 'ok') {
                if (msg == 1) {
                    $('#usernamediv').css("border-left", "3px solid #ED1C24");
                    $('#username').value = "";
                    $('#username').focus();
                    msg = '<strong>Error!</strong><p>Ha olvidado introducir el nombre de usuario. Por favor vuelva a intentarlo.</p>';
                } else if (msg == 2) {
                    $('#passwordiv').css("border-left", "3px solid #ED1C24");
                    $('#password').value = '';
                    $('#password').focus();
                    msg = '<strong>Error!</strong><p>Ha olvidado introducir la contraseÃ±a. Por favor vuelva a intentarlo.</p>';
                } else if (msg == 3) {
                    $('#usernamediv').css("border-left", "3px solid #ED1C24");
                    $('#username').value = '';
                    $('#username').focus();
                    msg = '<strong>Error!</strong><p>Login incorrecto.</p>';
                } else if (msg == 4) {
                    $('#usernamediv').css("border-left", "3px solid #ED1C24");
                    $('#username').value = '';
                    $('#username').focus();
                    msg = '<strong>Error!</strong><p>Faltan usuario y contraseña.</p>';
                }
                $('.span12').css('display', 'block');
                $('.alert').html(msg);
            } else {
                $('.span12').css('display', 'none');
                location.href = base_url + "admin/list.php?t=secciones";
            }
        }
    });
}


function deleteImg(thefield, table, id) {
    var field = thefield;
    if(id == 'undefined'){
        var id = $('#itemid').val();
    }
    if(table == 'undefined'){
        var table = $('#table').val(); 
    }   
    $.ajax({
        type: "POST",
        url: base_url+"admin/ajax/deleteImg.php",
        data: {
            i: id, 
            t: table,
            f: field
        }
    }).done(function(msg) {
        //alert(msg);
        location.reload();
    });
}
function deleteFile(thefield, table, id) {
    var field = thefield;
    if(id == 'undefined'){
        var id = $('#itemid').val();
    }
    if(table == 'undefined'){
        var table = $('#table').val(); 
    }
    //alert(base_url+"admin/ajax/deleteImg.php?t=" + table + "&f="+field+"&i=" + id);
    $.ajax({
        type: "GET",
        url: base_url+"admin/ajax/deleteFile.php?t=" + table + "&f="+field+"&i=" + id,
        data: {
            id: id, 
            table: table
        }
    }).done(function(msg) {
//        alert(msg);
    location.reload();
    });
}

function validateContent(input) {
    if(input.value.length==0) {
        input.focus();
        return false;
    }
    return true;
}

function showAttributes(selected){
    $.ajax({
        url: base_url + "admin/ajax/showAttributes.php",
        async: false,
        type: 'POST',
        data: {
            attributeId: selected
        },
        success: function(msg) {
            $('#couponAttributeValueFk').html(msg);
            //$('#couponAttributeValueFk').trigger("liszt:updated");
            setTimeout(function(){
                $('#couponAttributeValueFk').trigger("liszt:updated")
            },90);
        }
        
    });
}

function saveForm(hrefTo,action) {
//    alert(hrefTo);
    var data = new FormData($('form')[0]);
    $.ajax({
        url: base_url + "admin/ajax/saveForm.php",
        type: 'POST',
        contentType: false,
        async: false,
        processData: false,
        cache: false,
        data: data,
        success: function(msg) {
//            alert(msg);
            if (msg > 0) {
                if (hrefTo == undefined) {
                    location.href = "";
//                    alert("dd")
                } else {
                    if(action == "continue"){
                        location.href = hrefTo+msg;
//                        alert("aa")
                    } else {
//                        alert("ss");
                        location.href = hrefTo;
                    }
                }
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert('Error '+xhr.status+': '+thrownError);
        }
        
    });
}

function progressHandlingFunction(e){
    if(e.lengthComputable){
        $('progress').attr({
            value:e.loaded,
            max:e.total
        });
    }
}

function deleteRow(id, table) {   
    $('#table').val(table);
    $('#myModal').modal('show');
    $('#delID').val(id);
    $('#table').val(table);
}

function alertCancelItem(id, table, hrefTo) {
    
    $('#itemModal').modal('show');
    $('#delID').val(id);
    $('#table').val(table);
    $('#hrefTo').val(hrefTo);
}

function deleteLastAdded() {
    var id = $('#delID').val();
    var table = $('#table').val();
    var hrefTo = $('#hrefTo').val();
    $.ajax({
        url: base_url + "admin/ajax/deleteItem.php",
        type: "POST",
        async: false,
        success: function(msg) {
//            alert(msg);
            location.href = hrefTo;
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert('Error '+xhr.status+': '+thrownError);
            
        },
        data: {
            id: id,
            table: table
        }
    /*}).done(function(msg) {
         alert(msg);
         //location.reload();*/
    });
}
function deleteItem() {
    var id = $('#delID').val();
    var table = $('#table').val();
    $.ajax({
        url: base_url + "admin/ajax/deleteItem.php",
        type: "POST",
        async: false,
        success: function(msg) {
//            alert(msg);
            location.reload();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert('Error '+xhr.status+': '+thrownError);
        },
        data: {
            id: id,
            table: table
        }
    /*}).done(function(msg) {
         alert(msg);
         //location.reload();*/
    });
}

function hideButtons(row) {
    $('#confirm_' + row).css("display", "none");
    $('#cancel_' + row).css("display", "none");
    $('#delete_' + row).attr("enabled", "enabled");
}

function deleteInnerRow(id, table, row, count) {
    $('.hidden-button').css("display", "none");  
    $('#confirm_' + row).css("display", "block");
    $('#cancel_' + row).css("display", "block");
    $('#delete_' + row).attr("disabled", "disabled");
    $('#delRow').val(row);
    $('#delID').val(id);
    $('#deltable').val(table);
    $('#count').val(count);
}

function deleteInnerItem() {
    var id = $('#delID').val();
    var table = $('#deltable').val();
    var row = $('#count').val();
    $.ajax({
        url: base_url + "admin/ajax/deleteItem.php",
        type: "POST",
        async: false,
        data: {
            id: id,
            table: table,
            row: row
        },
        success: function(msg) {
            //alert(msg);
            $('#' + table + '_' + row).fadeOut("slow", function() {
                $('#' + table + '_' + row).remove();
            });
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert('Error '+xhr.status+': '+thrownError);
        }
    });
}
function createInnerItem(parent, parentid, table, count, langcount, row) {
    //alert(parent + '-' + parentid + '-' + table + '-' + count + '-' + langcount);
    $.ajax({
        url: base_url + "admin/ajax/createInnerItem.php",
        type: "POST",
        async: false,
        data: {
            parent: parent,
            parentid: parentid,
            table: table,
            count: count,
            langcount: langcount,
            row: row
        },
        success: function(msg) {
            //alert(msg);
            $('#insert_' + table).before($(msg).fadeIn("slow"));
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert('Error '+xhr.status+': '+thrownError);
        }
    });
}

function updateButton(parent, parentid, table, count, langcount,row) {
    $('#insert_' + table + ' button').attr('onclick', 'createInnerItem(\'' + parent + '\',\'' + parentid + '\',\'' + table + '\',\'' + count + '\',\'' + langcount + '\',\'' + row + '\')');
}

function addNew(value, parenttable,table, elementid) {
    var array = elementid.split("_");
    if (array[1] === undefined){
        //alert('id undefinded');    
        array[1] = -1;
    }
    //alert(array[1]);
    if (value == -1) {
        var count = array[1];
        $.ajax({
            url: base_url + "admin/ajax/insertInnerItem.php",
            type: "POST",
            async: false,
            data: {
                table: table,
                parent: parenttable,
                count: count
            },
            success: function(msg) {
                $('#' + elementid + '_chzn').after($(msg).fadeIn("slow"));
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert('Error '+xhr.status+': '+thrownError);
            }
        });
    } else {
        $('#'+parenttable+'_'+array[1]).fadeOut("slow", function() {
            $('#'+parenttable+'_'+array[1]).remove();
        });
    }
}

function getFormFields(id, table, row, newform,langcount) {
    deleteInnerRow(id, table, row);
    deleteInnerItem();
    setTimeout(function() {
        $.ajax({
            url: base_url + "admin/ajax/insertInnerForm.php",
            type: "POST",
            async: false,
            data: {
                id: newform,
                langcount: langcount,
                section: id
            },
            success: function(msg) {
                //alert(msg);
                //$('#'+table+'_'+row).after($(msg).fadeIn("slow"));
                $('#insertAferThis').after($(msg).fadeIn("slow"));
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert('Error '+xhr.status+': '+thrownError);
            }
        });
    }, 700);

//document.getElementById('section_form_values_formfields').innerHTML='';
}

function logout(){
//    alert("dfdf");
    $.ajax({
            url: base_url + "admin/ajax/logout.php",
            async: false,
            success: function(msg) {
                location.href="http://ous.proyectosclientes.es/admin";
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert('Error '+xhr.status+': '+thrownError);
            }
        });
}
function addImg(cont){
    var acont = cont + 2;   
        
    var ocont = "addImg("+acont+");";
    $.ajax({
            url: base_url + "admin/ajax/addImg.php",
            type: "POST",
            data: {
                id: cont
            },
            async: false,
            success: function(msg) {
                $("#formButt").before(msg);
                $("#addbtn").attr("onclick",ocont);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert('Error '+xhr.status+': '+thrownError);
            }
        });
}
function addImg1(cont){
    var acont = cont + 2;   
        
    var ocont = "addImg1("+acont+");";
    $.ajax({
            url: base_url + "admin/ajax/addImg1.php",
            type: "POST",
            data: {
                id: cont
            },
            async: false,
            success: function(msg) {
                $("#formButt").before(msg);
                $("#addbtn").attr("onclick",ocont);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert('Error '+xhr.status+': '+thrownError);
            }
        });
}
function delImg(id,cont){
    if(id == -1){
        $("#banner_"+cont).remove();
    }else{
        $.ajax({
                url: base_url + "admin/ajax/delImg.php",
                type: "POST",
                data: {
                    id: id,
                    cont: cont
                },
                async: false,
                success: function(msg) {
//                      alert(msg);
                      location.reload();
    //                $("#contform").html(msg);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert('Error '+xhr.status+': '+thrownError);
                }
            });
    }
}
function delImg1(id,cont){
    if(id == -1){
        $("#banner_"+cont).remove();
    }else{
        $.ajax({
                url: base_url + "admin/ajax/delImg1.php",
                type: "POST",
                data: {
                    id: id,
                    cont: cont
                },
                async: false,
                success: function(msg) {
//                      alert(msg);
                      location.reload();
    //                $("#contform").html(msg);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert('Error '+xhr.status+': '+thrownError);
                }
            });
    }
}

