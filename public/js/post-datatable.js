console.log('>>post-datatable.js has loaded');
$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#posts-table')) {
        $('#posts-table').DataTable().destroy();
    }
    $('#posts-table').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: '/posts/data',
            dataSrc: function(json) {
                console.log('DataTables response:', json);
                return json.data;
            },
            error: function(xhr, error, thrown) {
                        console.error('DataTables AJAX error:', xhr, error, thrown);
                    }
        },
        pageLength: 5,
        lengthMenu: [[5, 10, 25], [5, 10, 25]],        
        columns: [
            { data: 'varIndex', name: 'varIndex', orderable: true, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'thumbnail', name: 'thumbnail', orderable: true, searchable: true,
                render: function (data) {
                    return `<img src="${data}" alt="thumbnail" style="max-width: 50px;">`;
                }
            },
            { data: 'description', name: 'description' },
            { data: 'publish_date', name: 'publish_date' },
            { data: 'status_label', name: 'status', orderable: true, searchable: true },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});