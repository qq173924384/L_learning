$.extend({
    myControlInit: function(catalog_li, cart, control, modal, btn_del, btn_save) {
        var init = function(data) {
            // 控件栏拖拽
            $(catalog_li).draggable({
                helper: 'clone',
                revert: "invalid"
            });
            // 编辑栏接受拖拽以及排序
            $(cart).droppable({
                drop: function(event, ui) {
                    var $control = $(ui.draggable.find(control).prop("outerHTML"));
                    $control.find('*[id]').each(function() {
                        var id = $(this).attr('id'),
                            timestamp = new Date().getTime();
                        $(this).attr('id', id + timestamp);
                        $control.html($control.html().replace(new RegExp('"#' + id + '"', "g"), '"#' + id + timestamp + '"'));
                    })
                    $(this).append($control);
                }
            }).sortable({
                items: control,
            });
            var title = data.title,
                panel = data.panel,
                select = data.select,
                db_select = data.db_select,
                getValue = function(index, $control) {
                    var value = '';
                    switch (index) {
                        case 'carousel':
                            value = $control.find('div.carousel-inner').html();
                            break;
                        case 'text':
                        case 'html':
                            value = $.trim($control.html()).replace(/&nbsp;/g, ' ').replace(/<br>/g, '\n');
                            break;
                        default:
                            if (index.indexOf("class-") == 0) {
                                value = $control.attr("class");
                            } else if (index.indexOf("data-") == 0) {
                                value = $control.data(index.replace("data-", ""));
                            } else if (index.indexOf("has-") == 0) {} else {
                                value = $control.attr(index);
                            }
                    }
                    return value ? value : '';
                },
                buildForm = function(index, element, $control) {
                    var $parent = $('<div></div>').attr({
                            'class': 'form-group',
                            'data-control': $control.selector
                        }),
                        $label = $('<label></label>').html(title[index]),
                        value = getValue(index, $control),
                        $input = '';
                    // 生成控件表单
                    switch (element) {
                        case 'panel':
                            $.ajax(panel[index] + '?time=' + new Date().getTime(), {
                                async: false,
                                success: function(data) {
                                    $input = $(data);
                                }
                            });
                            $parent.addClass('myPanel').append($label).append($input);
                            $input.find('.control_editor>div').html(value);
                            return $parent;
                            break;
                        case 'number':
                        case 'text':
                            $input = $('<input/>').attr({
                                'class': 'form-control',
                                'type': element,
                                'name': index,
                                'value': value
                            });
                            break;
                        case 'textarea':
                            $input = $('<textarea></textarea>').attr({
                                'class': 'form-control',
                                'type': element,
                                'name': index,
                                'rows': '3',
                                'style': 'max-width:100%;'
                            }).html(value);
                            break;
                        case 'select':
                            $input = $('<select></select>').attr({
                                'class': 'form-control',
                                'name': index
                            });
                            for (var i in select[index]) {
                                var e = select[index][i],
                                    option = $('<option></option>').attr({
                                        'value': e
                                    }).html(i);
                                value.indexOf(e) >= 0 && option.attr('selected', "selected");
                                index.indexOf("class-") == 0 && option.addClass(e);
                                $input.append(option);
                            }
                            break;
                        case 'db_select':
                            $input = $('<select></select>').attr({
                                'class': 'form-control',
                                'name': index
                            });
                            $.ajax(db_select[index] + '?time=' + new Date().getTime(), {
                                async: false,
                                dataType: 'json',
                                success: function(r) {
                                    if (r.status == 200) {
                                        for (var i in r.data) {
                                            var e = r.data[i],
                                                option = $('<option></option>').attr({
                                                    'value': e
                                                }).html(i);
                                            (value + '').indexOf(e) >= 0 && option.attr('selected', "selected");
                                            index.indexOf("class-") == 0 && option.addClass(e);
                                            $input.append(option);
                                        }
                                    } else {
                                        console.log(r.msg)
                                    }
                                }
                            });
                            break;
                        default:
                            for (var i in element) {
                                var e = element[i];
                                $input += '<input name="' + index + '" type="hidden" value="' + e + '" />';
                            }
                            return $parent.append($input);
                    }
                    return $parent.append($label).append($input);
                },
                saveControl = function($form, $control, data_form) {
                    // 保存
                    $(btn_save).bind('click', function() {
                        $control.attr("class", '');
                        for (var name in data_form) {
                            var value = $form.find('[data-control="' + $control.selector + '"] [name="' + name + '"]').val();
                            switch (name) {
                                case 'carousel':
                                    var $carousel_inner = $('<div></div>').attr({
                                            'class': 'carousel-inner',
                                            'role': 'listbox'
                                        }),
                                        value = $form.find('.myPanel .control_editor .container');
                                    value.find('div.item').removeClass('active');
                                    value.find('div.item:first').addClass('active');
                                    $control.html('').append($carousel_inner.append(value.html()));
                                    break;
                                case 'text':
                                case 'html':
                                    $control.html($('<div>').text(value).html().replace(/\n/g, '<br/>').replace(/\s/g, '&nbsp;'));
                                    break;
                                case 'value':
                                    $control.val(value);
                                    break;
                                default:
                                    if (name.indexOf("class-") == 0) {
                                        $control.addClass(value);
                                    } else if (name.indexOf("data-") == 0) {
                                        $control.data(name.replace("data-", ""), value);
                                        $control.attr(name, value);
                                    } else if (name.indexOf("has-") == 0) {
                                        if (value) {
                                            switch (name) {
                                                case 'has-side':
                                                    var id = $control.attr('id'),
                                                        $span = $('<span>').attr({
                                                            'aria-hidden': 'true',
                                                            'class': 'glyphicon'
                                                        }),
                                                        $button = $('<a></a>').attr({
                                                            'class': 'carousel-control',
                                                            'href': '#' + id,
                                                            'role': 'button'
                                                        }),
                                                        $left_button = $button.clone().attr('data-slide', 'prev').addClass('left').append($span.clone().addClass('glyphicon-chevron-left')),
                                                        $right_button = $button.clone().attr('data-slide', 'next').addClass('right').append($span.clone().addClass('glyphicon-chevron-right'));
                                                    $control.append($left_button).append($right_button);
                                                    break;
                                                case 'has-bottom':
                                                    var id = $control.attr('id'),
                                                        $ol = $('<ol></ol>').addClass('carousel-indicators'),
                                                        length = $form.find('.myPanel .control_editor .container .item').length;
                                                    for (var i = 0; i < length; i++) {
                                                        var $li = $('<li></li>').attr({
                                                            'data-slide-to': i,
                                                            'data-target': '#' + id
                                                        })
                                                        if (i == 0) {
                                                            $li.addClass('active');
                                                        }
                                                        $ol.append($li);
                                                    }
                                                    $control.append($ol);
                                                    break;
                                                default:
                                            }
                                        }
                                    } else {
                                        $control.attr(name, value);
                                    }
                            }
                        }
                    })
                };
            // 控件点击编辑
            $(document).on('click', cart + ' ' + control + ',' + catalog_li + ' ' + control, function() {
                var $my_control = $(this),
                    $myModal = $(modal),
                    $form = $myModal.find('form');
                // 删除控件事件重新绑定
                $(btn_del).unbind().bind('click', function() {
                    swal({
                        title: "确定删除?",
                        text: "删除后将无法恢复!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "删除"
                    }, function() {
                        $my_control.hide("normal", function() {
                            $my_control.remove();
                        });
                        $myModal.modal('hide');
                    });
                });
                // 保存控件事件重新绑定
                $(btn_save).unbind();
                $form.html('');
                var type = $my_control.data('type'),
                    control_type = type.split('&');
                for (var node in control_type) {
                    var node_name = control_type[node],
                        data_form = data.control[node_name],
                        $control = node_name == 'DIV' ? $my_control : node_name.indexOf("DB") == 0 ? $my_control : $my_control.find(node_name);
                    // 读取控件格式
                    for (var index in data_form) {
                        var element = data_form[index];
                        $form.append(buildForm(index, element, $control));
                    }
                    saveControl($form, $control, data_form)
                }
                $(btn_save).bind('click', function() {
                    $my_control.addClass(control.replace('.', ' '));
                    switch (type) {
                        case "DBAC&DIV":
                            $('.myControl[data-type="DBAC&DIV"]').dbacLoad();
                            $.li_resize();
                        default:
                            break;
                    }
                    $myModal.modal('hide');
                });
                if (control.length > 0) {
                    $myModal.modal();
                }
            });
        };
        // $.getJSON('/js/myControl/control.json', init);
        $.getJSON('/js/myControl/control.json?time=' + new Date().getTime(), init);
    },
    li_resize: function() {
        $('.myControl[data-type="DBAC&DIV"] .panel .list-group li').each(function() {
            $(this).find('a').css('width', $(this).width() - $(this).find('span').width() - 6);
        })
    }
});
$.fn.extend({
    dbacLoad: function() {
        this.each(function() {
            var $this = $(this),
                target = $this.data('target');
            $.post($this.data('source'), {
                cate: $this.data('cate'),
                limit: $this.data('limit')
            }, function(r) {
                if (r.status == 200) {
                    $this.find('.panel-heading a').attr({
                        href: r.data.title_url,
                        target: target
                    });
                    $this.find('.panel-heading strong').html(r.data.title);
                    var html = '';
                    for (var i in r.data.list) {
                        var item = r.data.list[i];
                        html += '<li class="list-group-item">';
                        html += '<a href="' + item.url + '" target="' + target + '" title="' + item.title + '">';
                        html += item.title;
                        html += '</a>';
                        html += '<span class="pull-right">' + item.date + '</span>';
                        html += '</li>';
                    }
                    $this.find('.list-group').html(html);
                    $.li_resize();
                } else {
                    swal("失败!", r.info, "error");
                }
            }, 'json');
        });
        return this;
    }
});
$(window).ready(function() {
    if ($('#json_article').length > 0) {
        var article = $.parseJSON($('#json_article').html());
        $('#title').html('<h3>' + article.title + '</h3>');
        $('#content').html(article.content);
        $('#footer').html('发布时间：' + article.create_time + '&nbsp;&nbsp;&nbsp;修改时间：' + article.edit_time);
    }
    if ($('#json_breadcrumb').length > 0) {
        $('#breadcrumb').html($('#json_breadcrumb').html());
    }
    $('.myControl[data-type="DBAC&DIV"]').dbacLoad();
}).resize(function() {
    $.li_resize();
}).scroll(function() {});