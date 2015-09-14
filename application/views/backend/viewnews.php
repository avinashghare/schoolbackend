<div id="page-title">
    <a class="btn btn-primary btn-labeled fa fa-plus margined pull-right" href="<?php echo site_url("site/createnews"); ?>">Create</a>
    <h1 class="page-header text-overflow">news Details </h1>
</div>
<div id="page-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel drawchintantable">
                <?php $this->chintantable->createsearch("news List");?>
                <div class="fixed-table-container">
                    <div class="fixed-table-body">
                        <table class="table table-hover" id="" cellpadding="0" cellspacing="0">
                            <thead>
                                <tr>
                                    <th data-field="id">ID</th>
                                    <th data-field="title">Title</th>
                                    <th data-field="description">Description</th>
                                    <th data-field="startdate">Start Date</th>
                                    <th data-field="enddate">End Date</th>
                                    <th data-field="status">Status</th>
                                    <th data-field="Action">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="fixed-table-pagination" style="display: block;">
                        <div class="pull-left pagination-detail">
                            <?php $this->chintantable->createpagination();?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function drawtable(resultrow) {
            return "<tr><td>" + resultrow.id + "</td><td>" + resultrow.title + "</td><td>" + resultrow.description + "</td><td>" + resultrow.startdate + "</td><td>" + resultrow.enddate + "</td><td>" + resultrow.status + "</td><td><a class='btn btn-primary btn-xs' href='<?php echo site_url('site/editnews?id=');?>" + resultrow.id + "'><i class='icon-pencil'></i></a><a class='btn btn-danger btn-xs' onclick=return confirm(\"Are you sure you want to delete?\") href='<?php echo site_url('site/deletenews?id='); ?>" + resultrow.id + "'><i class='icon-trash '></i></a></td></tr>";
        }
        generatejquery("<?php echo $base_url;?>");
    </script>
</div>
</div>
