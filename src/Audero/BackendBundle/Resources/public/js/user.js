$('#table-javascript').bootstrapTable({
    method: 'get',
    url: '/admin/users/data',
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
