<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bootstrap Table Checkbox Select All and Cancel</title>
    <link href="//cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<h2>Bootstrap Table Checkbox Select All and Cancel</h2>
<table class="table table-striped">
    <tr>
        <th class="active">
            <input type="checkbox" class="select-all checkbox" name="select-all" />
        </th>
        <th class="success">Name</th>
        <th class="warning">Gender</th>
        <th class="danger">Age</th>
    </tr>
    <tr>
        <td class="active">
            <input type="checkbox" class="select-item checkbox" name="select-item" value="1000" />
        </td>
        <td class="success">sudhi</td>
        <td class="warning">boy</td>
        <td class="danger">20</td>
    </tr>
    <tr>
        <td class="active">
            <input type="checkbox" class="select-item checkbox" name="select-item" value="1001" />
        </td>
        <td class="success">kiran</td>
        <td class="warning">boy</td>
        <td class="danger">21</td>
    </tr>
    <tr>
        <td class="active">
            <input type="checkbox" class="select-item checkbox" name="select-item" value="1002" />
        </td>
        <td class="success">Prasanna</td>
        <td class="warning">boy</td>
        <td class="danger">22</td>
    </tr>
    <tr>
        <td class="active">
            <input type="checkbox" class="select-item checkbox" name="select-item" value="1003" />
        </td>
        <td class="success">shruthi </td>
        <td class="warning">girl</td>
        <td class="danger">23</td>
    </tr>
</table>
<button id="select-all" class="btn button-default">SelectAll/Cancel</button>

<iframe src="https://bbbootstrap.com/snippets/embed/bootstrap-5-table-search-and-checkboxes-10209122" frameborder="0"></iframe>
</body>
<script src="//cdn.bootcss.com/jquery/2.2.1/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script>
    $(function(){
        //button select all or cancel
        $("#select-all").click(function () {
            var all = $("input.select-all")[0];
            all.checked = !all.checked
            var checked = all.checked;
            $("input.select-item").each(function (index,item) {
                item.checked = checked;
            });
        });
        //column checkbox select all or cancel
        $("input.select-all").click(function () {
            var checked = this.checked;
            $("input.select-item").each(function (index,item) {
                item.checked = checked;
            });
        });
        //check selected items
        $("input.select-item").click(function () {
            var checked = this.checked;
            var all = $("input.select-all")[0];
            var total = $("input.select-item").length;
            var len = $("input.select-item:checked:checked").length;
            all.checked = len===total;
        });
        
    });
     
        
</script>
</html>