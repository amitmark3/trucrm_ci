<?php $this->load->view('partials/emails/header') ?>

    <p>Hi <?= $sm ?>,</p>
    <p>The following training is required soon:</p>
    <?php foreach ($users as $user) : ?>
    <div class="datagrid">
    	<table>
    		<tbody>
    			<tr>
    				<td width="33%">User:</td>
    				<td><?= $user['profile']['first_name'] ?> <?= $user['profile']['last_name'] ?></td>
    			</tr>
    			<tr class="alt">
    				<td>Course:</td>
    				<td><?= $user['course']['name'] ?></td>
    			</tr>
    			<tr>
    				<td>Department:</td>
    				<td><?= $user['department']['name'] ?></td>
    			</tr>
    			<tr class="alt">
    				<td>Date Due:</td>
    				<td><?= date('jS M Y', strtotime($user['date'])) ?></td>
    			</tr>
    		</tbody>
    	</table>
    </div><br>
	<?php endforeach; ?>
    <br>
    <p class="text-center"><a href="<?= site_url('login') ?>" class="btn" target="_blank">Click Here To Login</a></p>

<?php $this->load->view('partials/emails/footer') ?>