<table class="table table-striped">
    <thead>
        <tr>
            <th>Title</th>
            <th>URL</th>
            <th>Name</th>
            <th>Created Time</th>
            <th>Operation</th>
        </tr>
    </thead>
    <tbody>
<?php
    foreach ($rows as $row) {
        $editUrl = assembleUrl(array(
            'section'    => 'admin',
            'controller' => 'business',
            'action'     => 'edit',
            'id'         => $row->id,
        ));
        $deleteUrl = assembleUrl(array(
            'section'    => 'admin',
            'controller' => 'business',
            'action'     => 'delete',
            'id'         => $row->id,
        ));
        $activeUrl = assembleUrl(array(
            'section'    => 'admin',
            'controller' => 'business',
            'action'     => 'active',
            'id'         => $row->id,
            'status'     => $row->active ? 0 : 1,
        ));
        $viewUrl   = assembleUrl(array(
            'section'    => 'admin',
            'controller' => 'business',
            'action'     => 'view',
            'id'         => $row->id,
        ));
?>
        <tr>
            <td><a href="<?php echo $viewUrl; ?>"><?php echo htmlspecialchars($row->title); ?></a></td>
            <td><?php echo htmlspecialchars($row->url); ?></td>
            <td><?php echo htmlspecialchars($row->name); ?></td>
            <td><?php echo date('Y-m-d H:i:s', $row->time_created); ?></td>
            <td>
                <a href="<?php echo $editUrl; ?>">Edit</a>
                | <a href="<?php echo $activeUrl; ?>"><?php echo $row->active ? 'Deactivate' : 'Active'; ?></a>
                <?php if (!$row->in_use) { ?>
                | <a href="<?php echo $deleteUrl; ?>">Delete</a>
                <?php } ?>
            </td>
        </tr>
<?php } ?>
    </tbody>
</table>

<div><?php echo $this->pagination->create_links(); ?></div>