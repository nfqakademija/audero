function operateFormatter(value, row, index) {
    return [
    '<a class="like" href="javascript:void(0)" title="Like">',
    '<i class="glyphicon glyphicon-heart"></i>',
    '</a>',
    '<a class="edit ml10" href="javascript:void(0)" title="Edit">',
    '<i class="glyphicon glyphicon-edit"></i>',
    '</a>',
    '<a class="remove ml10" href="javascript:void(0)" title="Remove">',
    '<i class="glyphicon glyphicon-remove"></i>',
    '</a>'
    ].join('');
    }

window.operateEvents = {
    'click .like': function (e, value, row, index) {
    alert('You click like icon, row: ' + JSON.stringify(row));
    console.log(value, row, index);
    },
    'click .edit': function (e, value, row, index) {
    alert('You click edit icon, row: ' + JSON.stringify(row));
    console.log(value, row, index);
    },
    'click .remove': function (e, value, row, index) {
    alert('You click remove icon, row: ' + JSON.stringify(row));
    console.log(value, row, index);
    }
    };

$('#table-javascript').bootstrapTable({
    method: 'get',
    url: '/app_dev.php/admin/users/data',
    cache: false,
    height: 400,
    striped: true,
    pagination: true,
    pageSize: 25,
    pageList: [10, 25, 50, 100, 200],
    search: true,
    showColumns: true,
    showRefresh: true,
    clickToSelect: true,
    columns: [ {
        field: 'username',
        title: 'Username',
        align: 'left',
        valign: 'bottom',
        sortable: true
    },
        {
            field: 'email',
            title: 'Email',
            align: 'left',
            valign: 'bottom',
            sortable: true
        },
        {
            field: 'enabled',
            title: 'Enabled',
            align: 'left',
            valign: 'bottom',
            sortable: true
        },
        {
            field: 'lastLogin[date]',
            title: 'Last Login',
            align: 'left',
            valign: 'bottom',
            sortable: true
        },
        {
            field: 'roles',
            title: 'Role',
            align: 'left',
            valign: 'bottom',
            sortable: true
        },
        {
            field: 'operate',
            title: 'Item Operate',
            align: 'center',
            valign: 'middle',
            clickToSelect: false,
            formatter: operateFormatter,
            events: operateEvents
        }]
});
