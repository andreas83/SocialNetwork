<?php
include("header.php");

?>

<h1><?php echo $modelName; ?> </h1>

<div class="row">
    <div class="col-sm-4">
        <form class="form-horizontal" action="/backend/<?php echo $modelName; ?>/list/"  enctype="multipart/form-data"  method="post">
        <div class="row">
            <div class="col-sm-8">
            <input type="text"  class="form-control" placeholder="<?php echo _("Search"); ?>" value="<?php echo (isset($term) ? $term : ""); ?>" name="term">
            </div>
            <div class="col-sm-4">
            <input type="submit" class="btn btn-success" value="<?php echo _("Search"); ?>" >
            </div>
        </div>
        </form>
    </div>
        
    <div class="col-sm-8 text-right">

    <a href="/backend/<?php echo $modelName; ?>/create/" class="btn btn-primary">
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create <?php echo $modelName; ?></a>
    </div>
</div>
    <table class="table table-hover">
        <thead>
        <tr>
            <?php
            
            foreach ($configuration->visible as $properties )
            {
                echo "<th>".$properties."</th>";
            }
            ?>
            <th  class="text-right"><?php echo _("Action"); ?></th>
        </tr>
        </thead>
        <?php foreach($result as $model)
        { ?>
            <tr>
                
            <?php
            foreach ($configuration->visible as $properties)
            {
                echo "<td>".$model->$properties."</td>";
            }
            ?>
                <td  class="text-right">
                    <a href="/backend/<?php echo $modelName; ?>/edit/<?php echo $model->{$model->getPrimary()}; ?>/" class="btn btn-success"><span class="glyphicon  glyphicon-pencil" aria-hidden="true"></span> <?php echo _("Edit"); ?></a>
                    <a href="/backend/<?php echo $modelName; ?>/delete/<?php echo $model->{$model->getPrimary()}; ?>/" class="btn btn-warning confirm"><span class="glyphicon  glyphicon-trash" aria-hidden="true"></span> <?php echo _("Delete"); ?></a>
                </td>
            </tr>
        <?php
        }?>
    </table>

    <nav>
        <ul class="pagination">
            <li>
                <a href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php
            foreach(range(1, $pages) as $page)
            {
                echo '<li><a href="/backend/'.$modelName.'/list/page/'.$page.'/'.(isset($term) ? "?term=".$term : "").'">'.$page.'</a></li>';
            }
            ?>
            <li>
                <a href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="modal fade" id="confirm">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-modelName"><?php echo _("Attention"); ?></h4>
                </div>
                <div class="modal-body">
                    <p><?php echo _("Are you sure"); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _("Abort"); ?></button>
                    <button type="button" id="delete" class="btn btn-danger"><?php echo _("Delete"); ?></button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php
include("footer.php");
?>