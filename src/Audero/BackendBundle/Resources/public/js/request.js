$('#table-javascript').bootstrapTable({
    method: 'get',
    url: '/admin/requests/data',
    cache: false,
    height: 400,
    striped: true,
    pagination: true,
    pageSize: 25,
    pageList: [10, 25, 50, 100, 200],
    search: true,
    showColumns: true,
    columns: [
    {
        field: 'id',
        title: 'Id',
        align: 'left',
        valign: 'bottom',
        sortable: true
    },
        {
        field: 'title',
        title: 'Title',
        align: 'left',
        valign: 'bottom',
        sortable: true
    },

    {
        field: 'slug',
        title: 'Slug',
        align: 'left',
        valign: 'bottom',
        sortable: true
    }]
});
