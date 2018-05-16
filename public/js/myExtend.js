$.extend({
    mySwal: function(r) {
        if (r.status == 200) {
            swal({
                title: "成功！",
                text: r.info,
                type: "success"
            }, function() {
                location.reload();
            });
        } else {
            swal("失败!", r.info, "error");
            if (r.status == 401) {
                location.reload();
            }
        }
    },
    myDelAlert: function(callback) {
        swal({
            title: "确定删除?",
            text: "删除后将无法恢复!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "删除",
            closeOnConfirm: false
        }, callback);
    },
    myPost: function(url, data) {
        $.post(url, data, function(r) {
            $.mySwal(r);
        }, 'json');
    }
});