$(document).ready(function() {
    $("input.select-on-check-all").hide();
    $("#plus-minus").click(function() {

        $("#toggle-pane-pr-item").slideToggle('slow', function() {
            if ($('#toggle-pane-pr-item').is(':hidden')) {
                console.log('hidden');
                $('#plus-minus').removeClass('fa-minus');
                $('#plus-minus').addClass('fa-plus');
            } else {
                console.log('show');
                $('#plus-minus').removeClass('fa-plus');
                $('#plus-minus').addClass('fa-minus');
            }

        });
        //$("p").remove();
    });
    /*
    $(".editable-col-qty").on("keypress keyup blur", function(event) {
        //this.value = this.value.replace(/[^0-9\.]/g,'');
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });*/

    $('body').on('change', '#purchaserequest-section_id', function() {
        var selectSection = true;
        var year = new Date($('#purchaserequest-purchase_request_date').val());
        //$("#purchaserequest-project_id").prop("readonly", true);
        if ($(this).val() == '') {
            $('#purchaserequest-project_id').attr("disabled", true);
            $('#hidden_section_id').val('');
        } else {
            $('#purchaserequest-project_id').attr("disabled", false);
            $('#hidden_section_id').val($(this).val());
        }
        $.pjax.reload({
            async: true,
            type: "POST",
            container: "#pr-item-grid",
            url: '/procurement/purchaserequest2/create',
            data: {
                section: $(this).val(),
                year: year.getFullYear(),
                selectSection: selectSection
            },
        }).done(function() {
            $("input.select-on-check-all").hide();
            $.pjax.reload({
                async: true,
                type: "POST",
                container: "#selected-item-grid",
                url: '/procurement/purchaserequest2/create',
                data: {
                    section: $("#purchaserequest-section_id").val(),
                    reloadsectionitems: true,
                    //selectSection: false
                },
            });
        });
    });
    $('body').on('change', '#purchaserequest-project_id', function() {
        var selectProject = true;
        var year = new Date($('#purchaserequest-purchase_request_date').val());
        //$("#purchaserequest-project_id").prop("readonly", true);
        if ($(this).val() == '') {
            $('#purchaserequest-section_id').attr("disabled", false);
            //$('#purchaserequest-section_id').val('');
        } else {
            $('#purchaserequest-section_id').attr("disabled", true);
        }
        $.pjax.reload({
            async: true,
            type: "POST",
            container: "#pr-item-grid",
            url: '/procurement/purchaserequest2/create',
            data: {
                selectProject: selectProject,
                project: $(this).val(),
                year: year.getFullYear()
            }
        }).done(function() {
            $("input.select-on-check-all").hide();
            var selectProject = true;
            $.pjax.reload({
                async: true,
                type: "POST",
                container: "#selected-item-grid",
                url: '/procurement/purchaserequest2/create',
                data: {
                    project: $("#purchaserequest-project_id").val(),
                    reloadprojectitems: true,
                },
            });
        });
    });
    $('body').on('click', '.buttonRemoveItem', function() {
        var removeitem = true;
        $.pjax.reload({
            async: true,
            type: "POST",
            container: "#selected-item-grid",
            url: '/procurement/purchaserequest2/create',
            data: {
                //section: $("#purchaserequest-section_id").val(),
                tmppritems_id: $(this).val(),
                removeitem: true,
                section: $("#purchaserequest-section_id").val(),
            },
        }).done(function() {
            //console.log('success');
            $.pjax.reload({
                async: true,
                type: "POST",
                container: "#pr-item-grid",
                url: '/procurement/purchaserequest2/create',
                data: {
                    section: $("#purchaserequest-section_id").val(),
                    reloadremoveditems: true,
                },
            });
        });
    });
    //----show Additems modal------
    $('body').on('click', '#btnAdditems', function() {
        //alert('hello clarrise!!!');
        $('div.modal-additems-class').removeClass('modal-additems-hide');
        $('div.modal-additems-class').addClass('modal-additems-show');
        $('div.modal-additems-content').removeClass('animate__fadeOut');
        $('div.modal-additems-content').addClass('animate__fadeIn');
    });
    //----close Additems modal------
    $('body').on('click', 'span.close', function() {
        $('div.modal-additems-content').removeClass('animate__fadeIn');
        $('div.modal-additems-content').addClass('animate__fadeOut');
        //element.addEventListener('animationend', () => {
        $('div.modal-additems-class').removeClass('modal-additems-show');
        $('div.modal-additems-class').addClass('modal-additems-hide');
        //});
    });

    /******************Prevent from submmitting multiple times******************/
    $('#create_pr_form').submit(function(e) {
        e.preventDefault;
        if (e.result == true) {
            $('#btn-submit-pr').prop('disabled', true);
            //$('#create_pr_form').submit();
        }
    })

    /**************************Update spectification***************************/
    $('#btnUpdatespecs').click(function(){
        var item_id = $(this).val();
        $.ajax({
            type:'POST',
            url: '/procurement/purchaserequest2/updatespecs',
            data:{
                item_id: item_id ,
                specs: $('#txtitemdesc').val()
            },
            success: function(data){
                CKEDITOR.instances.txtitemdesc.setData($('#txtitemdesc').val());
                $('div.modal-itemspecification-content').removeClass('animate__fadeIn');
                $('div.modal-itemspecification-content').addClass('animate__fadeOut');
                $('div#specs-' + item_id).html($('#txtitemdesc').val());
                //element.addEventListener('animationend', () => {
                $('div.modal-itemspecification-class').removeClass('modal-itemspecification-show');
                $('div.modal-itemspecification-class').addClass('modal-itemspecification-hide');
            }
        });
    });    
});

function onCheck(item_id, checked) {
    var checkItem = true;
    $.pjax.reload({
        async: true,
        type: "POST",
        container: "#selected-item-grid",
        url: '/procurement/purchaserequest2/create',
        data: {
            itemId: item_id,
            checked: checked,
            checkItem: checkItem,
            section: $("#purchaserequest-section_id").val()
        }
    })
}

function onQty(item_id, value, cost) {
    $.ajax({
        async: true,
        type: "POST",
        url: '/procurement/purchaserequest2/updateqty',
        data: {
            item_id: item_id,
            qty: value
        },
        success: function(data) {
            //console.log('success');
            $("div.total-" + item_id).html((value * cost).toFixed(2))
        }
    });
}

function showtextbox(item_id) {
    $(':input.txtqty').not('[id=' + 'txtqty_' + item_id + ']').hide();
    $('#txtqty_' + item_id).show();
    $('#txtqty_' + item_id).select();
    $('a.qtylink').not('[id=' + 'qtylink_' + item_id + ']').show();
    $('#qtylink_' + item_id).hide();
    $(':input#txtqty_' + item_id).focusout(function() {
        $(this).hide();
        $('#qtylink_' + item_id).show();
        $('#qtylink_' + item_id).html($(this).val());
    });
}

function showmodalitemspecification(item_id, value) {
    $('#detail-item-desc').html(value);
    $('#btnUpdatespecs').val(item_id);
    //$("body .cke_editable p").html('sdgdsfgsdfgfdgdfsg');
    //CKEDITOR.replace(specs);
    CKEDITOR.instances.txtitemdesc.setData($('div#specs-' + item_id).html());
    $('div.modal-itemspecification-class').removeClass('modal-itemspecification-hide');
    $('div.modal-itemspecification-class').addClass('modal-itemspecification-show');
    $('div.modal-itemspecification-content').removeClass('animate__fadeOut');
    $('div.modal-itemspecification-content').addClass('animate__fadeIn');
}
$('body').on('click', 'span#close-spec', function() {
    $('div.modal-itemspecification-content').removeClass('animate__fadeIn');
    $('div.modal-itemspecification-content').addClass('animate__fadeOut');
    //element.addEventListener('animationend', () => {
    $('div.modal-itemspecification-class').removeClass('modal-itemspecification-show');
    $('div.modal-itemspecification-class').addClass('modal-itemspecification-hide');
    //});
});