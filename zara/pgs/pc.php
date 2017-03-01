<?php 
	$msg='';
	$id = (!empty($_REQUEST['id'])) ? intval($_REQUEST['id']) : false;
	$manutenzione=(empty($_REQUEST['id'])) ?  R::dispense('pc') : R::load('pc', intval($_REQUEST['id']));
	if (!empty($_REQUEST['sn'])) : 
		$manutenzione->sn=$_POST['sn'];
		$manutenzione->marche_id=$_POST['marche_id'];
		$manutenzione->hostname=floatval($_POST['hostname']);
		$manutenzione->modello=intval($_POST['modello']);
		//$manutenzione->dataintervento=date_create($_POST['dataintervento']);
		try {
			R::store($manutenzione);
			$msg='Dati salvati correttamente ('.json_encode($manutenzione).') ';
		} catch (RedBeanPHP\RedException\SQL $e) {
			$msg=$e->getMessage();
		}
	endif;	
	
	if (!empty($_REQUEST['del'])) : 
		$manutenzione=R::load('pc', intval($_REQUEST['del']));
		try{
			R::trash($manutenzione);
		} catch (RedBeanPHP\RedException\SQL $e) {
			$msg=$e->getMessage();
		}
	endif;
	
	$pc=R::findAll('pc', 'ORDER by id ASC LIMIT 999');
	$pc=R::findAll('pc');
	$new=!empty($_REQUEST['create']);
	
?>

<h1>
	<a href="index.php">
		<?=($id) ? ($new) ? 'Nuovo manutenzione' : 'manutenzione n. '.$id : 'pc';?>
	</a>
</h1>
<?php if ($id || $new) : ?>
		<form method="post" action="?p=pc">
			<?php if ($id) : ?>
				<input type="hidden" name="id" value="<?=$manutenzione->id?>" />
			<?php endif; ?>
			<label for="sn">
				Seriale
			</label>
			<input name="sn"  value="<?=$manutenzione->sn?>" autofocus required  />

			<!--label for="dataintervento">
				Data
			</label>
			<input name="dataintervento"  value="<?=date('Y-m-d',strtotime($manutenzione->dataintervento))?>" type="date" /-->
			
			<label for="marche_id">
				Marca
			</label>
			<select name="marche_id">
				<option>
				<?php foreach ($pc as $a) : ?>
					<option value="<?=$a->id?>" <?=($a->id==$id) ? 'selected' :'' ?> >
						<?=$a->id?>
					</option>
				<?php endforeach; ?>
			</select>
			<label for="modello">
				modello
			</label>			
			<input name="modello"  value="<?=$manutenzione->modello?>" type="text" />

			<label for="dataintervento">
				hostname
			</label>			
			<input name="hostname"  value="<?=$manutenzione->hostname?>" type="text" step="any" />			
			
			<button type="submit" tabindex="-1">
				Salva
			</button>
			
			<a href="?p=pc" >
				Elenco
			</a>			
			
			<a href="?p=pc&del=<?=$manutenzione['id']?>" tabindex="-1">
				Elimina
			</a>					
		</form>
<?php else : ?>
	<div class="tablecontainer">
		<table style="table-layout:fixed" class="table table-striped table-bordered responsive ">
			<colgroup>
				<col style="width:150px" />
			</colgroup>
			<thead>
				<tr>
					<th>PC</th>
					<th>Data e ora</th>
					<th>sn</th>
					<th>modello</th>
					<th>hostname</th>
					<th>INtervento</th>
					<th style="width:160px;text-align:center">Modifica</th>
					<th style="width:160px;text-align:center">Cancella</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($pc as $r) : ?>
				<tr>
					<td>
							<?=($r->marche_id) ? $r->marche->marca : ''?>
					</td>			
					<td>
						<?=date('d/m/Y',strtotime($r->dataintervento))?>
					</td>
					<td>
						<?=$r->sn?>
					</td>
					<td style="text-align:right" >
						<?=$r->modello?>
					</td>	
					<td style="text-align:right" >
						<?=$r->hostname?>
					</td>
					<td style="text-align:right" >
						<?=$r->hostname?>
					</td>
					
					<td style="text-align:center" >
						<a href="?p=pc&id=<?=$r['id']?>">
							Mod.
						</a>
					</td>
					<td style="text-align:center" >
						<a class="btn btn-sm btn-danger" href="?p=pc&del=<?=$r['id']?>" tabindex="-1">
							Cancella
						</a>
					</td>							
				</tr>		
			<?php endforeach; ?>
			</tbody>
		</table>
		<h4 class="msg">
			<?=$msg?>
		</h4>	
	</div>
<?php endif; ?>
<a href="?p=pc&create=1">Inserisci nuovo</a>
<script>
	var chg=function(e){
		console.log(e.name,e.value)
		document.forms.frm.elements[e.name].value=(e.value) ? e.value : null
	}	
</script>