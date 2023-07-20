<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<style>
	.category-img{
		width:3em;
		height:3em;
		object-fit:cover;
		object-position:center center;
	}
</style>
<div class="card card-outline rounded-0 card-purple">
	<div class="card-header">
		<h3 class="card-title"> Список категорий </h3>
		<div class="card-tools">
			<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary" style="border-radius: 5px;"><span class="fas fa-plus-square"></span> Добавьте новую категорий </a>
		</div>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<div class="table-responsive">
				<table class="table table-hover table-striped table-bordered" id="list">
					<colgroup>
						<col width="5%">
						<col width="15%">
						<col width="30%">
						<col width="25%">
						<col width="15%">
						<col width="10%">
					</colgroup>
					<thead>
						<tr>
							<th> # ID: </th>
							<th> Дата создания: </th>
							<th> Название: </th>
							<th> Описание: </th>
							<th> Состояние: </th>
							<th> Действие: </th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$i = 1;
							$qry = $conn->query("SELECT * from `category_list` where delete_flag = 0 order by `name` asc ");
							while($row = $qry->fetch_assoc()):
						?>
							<tr>
								<td class="text-center"><?php echo $i++; ?></td>
								<td><?php echo date("Y-m-d H:i",strtotime($row['created_at'])) ?></td>
								<td class=""><?= $row['name'] ?></td>
								<td class=""><p class="m-0 text-truncate"><?= strip_tags(html_entity_decode($row['description'])) ?></p></td>
								<td class="text-center">
									<?php if($row['status'] == 1): ?>
										<span class="badge badge-success px-3 rounded-pill"> Активный </span>
									<?php else: ?>
										<span class="badge badge-danger px-3 rounded-pill"> Неактивный </span>
									<?php endif; ?>
								</td>
								<td align="center">
									<button type="button" class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">	Действие <span class="sr-only">Toggle Dropdown</span></button>
									<div class="dropdown-menu" role="menu">
										<a class="dropdown-item view-data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Посмотреть </a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item edit-data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Редактировать </a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Удалить </a>
									</div>
								</td>
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Вы уверены, что хотите удалить эту категорию навсегда?","delete_category",[$(this).attr('data-id')])
		})
		$('#create_new').click(function(){
			uni_modal("<i class='far fa-plus-square'></i> Добавить новую категорию ","categories/manage_category.php")
		})
		$('.edit-data').click(function(){
			uni_modal("<i class='fa fa-edit'></i> Добавить новую категорию ","categories/manage_category.php?id="+$(this).attr('data-id'))
		})
		$('.view-data').click(function(){
			uni_modal("<i class='fa fa-th-list'></i> Подробная информация о категории ","categories/view_category.php?id="+$(this).attr('data-id'))
		})
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [5] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
	})
	function delete_category($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_category",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>