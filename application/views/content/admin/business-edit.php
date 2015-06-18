<div class="page-title">
    <h2><?php echo htmlspecialchars($title); ?></h2>
</div>

<?php if (validation_errors()) { ?>
<div class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <?php echo validation_errors(); ?>
</div>
<?php } ?>

<?php
    $url = assembleUrl(array(
        'section'    => 'admin',
        'controller' => 'business',
        'action'     => 'edit',
        'id'         => $rows['id'],
    ));
?>

<form action="" method="post" class="form-horizontal">
    <div class="form-group <?php echo form_error('title') ? 'has-error' : ''; ?>">
        <label for="title" class="col-sm-3 control-label">Title</label>
        <div class="col-sm-5">
            <input type="input" name="title" class="form-control" value="<?php echo set_value('title') ?: $rows['title']; ?>">
        </div>
        <div class="col-sm-4">
            <?php echo form_error('title'); ?>
        </div>
    </div>
    
    <div class="form-group">
        <label for="name" class="col-sm-3 control-label">Name</label>
        <div class="col-sm-5">
            <input type="input" name="name" class="form-control" disabled="disabled" value="<?php echo $rows['name']; ?>">
        </div>
        <div class="col-sm-4"></div>
    </div>
    
    <?php if (!$rows['active']) { ?>
    <div class="form-group <?php echo form_error('url') ? 'has-error' : ''; ?>">
        <label for="url" class="col-sm-3 control-label">URL</label>
        <div class="col-sm-5">
            <input type="input" name="url" class="form-control" value="<?php echo set_value('url') ?: $rows['url']; ?>">
        </div>
        <div class="col-sm-4">
            <?php echo form_error('url'); ?>
        </div>
    </div>
    <?php } ?>
    
    <input type="hidden" name="csrf" value="<?php echo $csrf; ?>">

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="submit" name="submit" class="btn btn-primary" value="Submit">
        </div>
    </div>
</form>
